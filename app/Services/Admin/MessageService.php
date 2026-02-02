<?php

namespace App\Services\Admin;

use App\Models\Message;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class MessageService
{
    public function getMessages(array $filters = []): LengthAwarePaginator
    {
        $query = Message::with(['unit', 'sender', 'receiver']);

        // Filter berdasarkan user role
        $user = Auth::user();
        if ($user && $user->hasRole('admin')) {
            // Admin bisa lihat semua pesan
        } else {
            // Unit hanya bisa lihat pesan terkait mereka
            $query->where(function ($q) use ($user) {
                $q->where('penerima_tipe', 'unit')
                  ->where('penerima_id', $user->id)
                  ->orWhere('pengirim_tipe', 'unit')
                  ->where('pengirim_id', $user->id);
            });
        }

        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                  ->orWhere('pesan', 'LIKE', "%{$search}%");
            });
        }

        // Status filter
        if (!empty($filters['status'])) {
            $statusFilters = explode(',', $filters['status']);
            $query->whereIn('status', $statusFilters);
        }

        // Kategori filter
        if (!empty($filters['kategori'])) {
            $kategoriFilters = explode(',', $filters['kategori']);
            $query->whereIn('kategori', $kategoriFilters);
        }

        // Prioritas filter
        if (!empty($filters['prioritas'])) {
            $prioritasFilters = explode(',', $filters['prioritas']);
            $query->whereIn('prioritas', $prioritasFilters);
        }

        // Unit filter
        if (!empty($filters['unit_id'])) {
            $query->where('unit_id', $filters['unit_id']);
        }

        // Pengirim/Receiver filter
        if (!empty($filters['pengirim_tipe'])) {
            $query->where('pengirim_tipe', $filters['pengirim_tipe']);
        }

        if (!empty($filters['penerima_tipe'])) {
            $query->where('penerima_tipe', $filters['penerima_tipe']);
        }

        // Perlu tindakan filter
        if (isset($filters['perlu_tindakan'])) {
            $query->where('perlu_tindakan', $filters['perlu_tindakan']);
        }

        // Sorting
        $sort = $filters['sort'] ?? 'created_at';
        $order = $filters['order'] ?? 'desc';
        $query->orderBy($sort, $order);

        $perPage = $filters['per_page'] ?? 20;

        return $query->paginate($perPage);
    }

    public function createMessage(array $data): Message
    {
        // Auto-set pengirim jika belum ada
        if (!isset($data['pengirim_tipe'])) {
            $user = Auth::user();
            $data['pengirim_tipe'] = 'admin';
            $data['pengirim_id'] = $user->id;
        }

        // Jika kategori status_request, tambahkan data tindakan
        if ($data['kategori'] === 'status_request' && !empty($data['unit_id'])) {
            $unit = Unit::find($data['unit_id']);
            if ($unit) {
                $data['data_tindakan'] = [
                    'unit_id' => $unit->id,
                    'unit_name' => $unit->nama_unit,
                    'current_status' => $unit->status,
                    'requested_status' => $this->extractRequestedStatus($data['pesan'])
                ];
            }
        }

        return Message::create($data);
    }

    public function getMessageDetail(string $id): Message
    {
        $message = Message::with(['unit', 'sender', 'receiver'])->findOrFail($id);

        // Mark as read jika belum dibaca
        if ($message->isUnread()) {
            $message->markAsRead();
        }

        return $message;
    }

    public function updateMessage(string $id, array $data): Message
    {
        $message = Message::findOrFail($id);

        // Jika update status ke 'ditanggapi' atau 'selesai'
        if (isset($data['status']) && in_array($data['status'], ['ditanggapi', 'selesai'])) {
            $data['tindakan_diambil_pada'] = now();
        }

        $message->update($data);

        // Jika ada tindakan dari kategori status_request, update unit status
        if ($message->kategori === 'status_request' && 
            $message->tipe_tindakan === 'update_unit_status' && 
            !empty($message->data_tindakan['requested_status']) &&
            $message->unit_id) {
            
            $unit = Unit::find($message->unit_id);
            if ($unit) {
                $unit->update(['status' => $message->data_tindakan['requested_status']]);
                
                // Buat pesan notifikasi ke unit
                $this->createStatusUpdateNotification($message, $unit);
            }
        }

        return $message->fresh();
    }

    public function deleteMessage(string $id): void
    {
        $message = Message::findOrFail($id);
        $message->delete();
    }

    public function sendReply(string $originalMessageId, array $data): Message
    {
        $original = Message::findOrFail($originalMessageId);

        $replyData = [
            'pengirim_tipe' => 'admin',
            'pengirim_id' => Auth::id(),
            'penerima_tipe' => $original->pengirim_tipe,
            'penerima_id' => $original->pengirim_id,
            'unit_id' => $original->unit_id,
            'judul' => 'Re: ' . $original->judul,
            'pesan' => $data['pesan'],
            'kategori' => $original->kategori,
            'prioritas' => $data['prioritas'] ?? $original->prioritas,
            'status' => 'terkirim',
        ];

        // Update original message status
        $original->markAsResponded();

        return $this->createMessage($replyData);
    }

    public function getUnreadCount(): int
    {
        return Message::where('penerima_tipe', 'admin')
                     ->unread()
                     ->count();
    }

    public function getActionRequiredCount(): int
    {
        return Message::requiresAction()->count();
    }

    private function extractRequestedStatus(string $pesan): string
    {
        $keywords = [
            'open' => ['buka', 'open', 'operasional', 'normal'],
            'full' => ['penuh', 'full', 'kapasitas maks', 'tidak bisa terima'],
            'maintenance' => ['maintenance', 'perbaikan', 'servis', 'rusak'],
            'closed' => ['tutup', 'closed', 'libur', 'tidak beroperasi']
        ];

        $pesan = strtolower($pesan);

        foreach ($keywords as $status => $words) {
            foreach ($words as $word) {
                if (str_contains($pesan, $word)) {
                    return $status;
                }
            }
        }

        return 'open'; // default
    }

    private function createStatusUpdateNotification(Message $message, Unit $unit): void
    {
        $notificationData = [
            'pengirim_tipe' => 'admin',
            'pengirim_id' => Auth::id(),
            'penerima_tipe' => 'unit',
            'penerima_id' => $unit->id,
            'unit_id' => $unit->id,
            'judul' => 'Status Unit Diperbarui',
            'pesan' => "Status unit {$unit->nama_unit} telah diubah menjadi " . 
                     ucfirst($unit->status) . " berdasarkan request Anda.",
            'kategori' => 'announcement',
            'prioritas' => 'biasa',
            'status' => 'terkirim',
        ];

        $this->createMessage($notificationData);
    }
}