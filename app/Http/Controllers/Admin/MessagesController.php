<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Message\StoreMessageRequest;
use App\Http\Requests\Admin\Message\UpdateMessageRequest;
use App\Models\Message;
use App\Models\Unit;
use App\Models\User;
use App\Services\Admin\MessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $tab = $request->get('tab', 'inbox');

        $query = Message::with(['unit', 'sender', 'receiver']);

        switch ($tab) {
            case 'inbox':
                $query->where('penerima_tipe', 'admin')
                    ->where('penerima_id', $user->id);
                break;
            case 'sent':
                $query->where('pengirim_tipe', 'admin')
                    ->where('pengirim_id', $user->id);
                break;
            case 'action':
                $query->where('perlu_tindakan', true)
                    ->whereNull('tindakan_diambil_pada');
                break;
            case 'unit':
                $query->where('pengirim_tipe', 'unit');
                break;
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                    ->orWhere('pesan', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $kategoriFilters = explode(',', $request->kategori);
            $query->whereIn('kategori', $kategoriFilters);
        }

        if ($request->filled('prioritas')) {
            $prioritasFilters = explode(',', $request->prioritas);
            $query->whereIn('prioritas', $prioritasFilters);
        }

        if ($request->filled('status')) {
            $statusFilters = explode(',', $request->status);
            $query->whereIn('status', $statusFilters);
        }

        if ($request->filled('unit')) {
            $unitFilters = explode(',', $request->unit);
            $query->whereIn('unit_id', $unitFilters);
        }

        $sort = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');
        $query->orderBy($sort, $order);

        $messages = $query->paginate($request->input('per_page', 20))->withQueryString();
        $units = Unit::active()->get();

        $unreadCount = Message::where('penerima_tipe', 'admin')
            ->where('penerima_id', $user->id)
            ->whereIn('status', ['terkirim', 'diterima'])
            ->count();

        $actionCount = Message::where('perlu_tindakan', true)
            ->whereNull('tindakan_diambil_pada')
            ->count();

        $kategoriOptions = [
            'technical' => 'Technical Issues',
            'status_request' => 'Status Request',
            'rating_feedback' => 'Rating Feedback',
            'maintenance' => 'Maintenance',
            'announcement' => 'Announcement',
            'instruction' => 'Instruction',
            'question' => 'Question',
            'emergency' => 'Emergency'
        ];

        $prioritasOptions = [
            'biasa' => 'Normal',
            'penting' => 'Important',
            'sangat_penting' => 'Urgent'
        ];

        return view('admin.messages.index', compact(
            'messages',
            'units',
            'unreadCount',
            'actionCount',
            'kategoriOptions',
            'prioritasOptions',
            'tab'
        ));
    }

    public function create()
    {
        $units = Unit::active()->get();
        $users = User::role('unit')->get();

        $kategoriOptions = [
            'technical' => 'Technical Issues',
            'status_request' => 'Status Request',
            'rating_feedback' => 'Rating Feedback',
            'maintenance' => 'Maintenance',
            'announcement' => 'Announcement',
            'instruction' => 'Instruction',
            'question' => 'Question',
            'emergency' => 'Emergency'
        ];

        $prioritasOptions = [
            'biasa' => 'Normal',
            'penting' => 'Important',
            'sangat_penting' => 'Urgent'
        ];

        return view('admin.messages.create', compact(
            'units',
            'users',
            'kategoriOptions',
            'prioritasOptions'
        ));
    }

    public function store(StoreMessageRequest $request)
    {
        try {
            $message = $this->messageService->createMessage($request->validated());
            return redirect()->route('admin.messages.show', $message->id)
                ->with('success', 'Message sent successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to send message: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $message = $this->messageService->getMessageDetail($id);

        $relatedMessages = Message::where('unit_id', $message->unit_id)
            ->where(function ($q) use ($message) {
                $q->where('pengirim_id', $message->pengirim_id)
                    ->orWhere('penerima_id', $message->pengirim_id)
                    ->orWhere('pengirim_id', $message->penerima_id)
                    ->orWhere('penerima_id', $message->penerima_id);
            })
            ->where('id', '!=', $message->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.messages.show', compact('message', 'relatedMessages'));
    }

    public function edit($id)
    {
        $message = Message::findOrFail($id);
        $units = Unit::active()->get();
        $users = User::role('unit')->get();

        $kategoriOptions = [
            'technical' => 'Technical Issues',
            'status_request' => 'Status Request',
            'rating_feedback' => 'Rating Feedback',
            'maintenance' => 'Maintenance',
            'announcement' => 'Announcement',
            'instruction' => 'Instruction',
            'question' => 'Question',
            'emergency' => 'Emergency'
        ];

        $prioritasOptions = [
            'biasa' => 'Normal',
            'penting' => 'Important',
            'sangat_penting' => 'Urgent'
        ];

        return view('admin.messages.edit', compact(
            'message',
            'units',
            'users',
            'kategoriOptions',
            'prioritasOptions'
        ));
    }

    public function update(UpdateMessageRequest $request, $id)
    {
        try {
            $message = $this->messageService->updateMessage($id, $request->validated());
            return redirect()->route('admin.messages.show', $message->id)
                ->with('success', 'Message updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update message: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->messageService->deleteMessage($id);
            return redirect()->route('admin.messages.index')
                ->with('success', 'Message deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete message: ' . $e->getMessage());
        }
    }

    public function markAsRead($id)
    {
        try {
            $message = Message::findOrFail($id);
            $message->status = 'dibaca';
            $message->dibaca_pada = now();
            $message->save();

            return response()->json(['success' => true, 'message' => 'Message marked as read']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to mark message: ' . $e->getMessage()], 500);
        }
    }

    public function markAsResponded($id)
    {
        try {
            $message = Message::findOrFail($id);
            $message->status = 'ditanggapi';
            $message->save();

            return response()->json(['success' => true, 'message' => 'Message marked as responded']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to mark message: ' . $e->getMessage()], 500);
        }
    }

    public function takeAction(Request $request, $id)
    {
        try {
            $request->validate(['action' => 'required|string', 'note' => 'nullable|string']);
            $message = Message::findOrFail($id);

            $updateData = [
                'tindakan_diambil_pada' => now(),
                'status' => 'selesai',
                'data_tindakan' => array_merge((array) $message->data_tindakan, [
                    'action_taken' => $request->action,
                    'action_note' => $request->note,
                    'action_by' => auth()->id(),
                    'action_at' => now()->toDateTimeString()
                ])
            ];

            $message->update($updateData);
            return response()->json(['success' => true, 'message' => 'Action recorded successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to record action: ' . $e->getMessage()], 500);
        }
    }

    public function sendReply(Request $request, $id)
    {
        try {
            $request->validate(['pesan' => 'required|string', 'prioritas' => 'required|in:biasa,penting,sangat_penting']);
            $reply = $this->messageService->sendReply($id, $request->all());
            return response()->json(['success' => true, 'message' => 'Reply sent successfully', 'data' => $reply]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send reply: ' . $e->getMessage()], 500);
        }
    }

    public function getStats()
    {
        $user = Auth::user();
        $unreadCount = Message::where('penerima_tipe', 'admin')
            ->where('penerima_id', $user->id)
            ->whereIn('status', ['terkirim', 'diterima'])
            ->count();

        $actionCount = Message::where('perlu_tindakan', true)
            ->whereNull('tindakan_diambil_pada')
            ->count();

        return response()->json(['success' => true, 'data' => ['unread' => $unreadCount, 'action_required' => $actionCount]]);
    }
}