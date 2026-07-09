<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\Employee\EmployeeUnitAssignment;
use App\Models\Conversation\Message;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MessageService
{
    public function getConversation(EmployeeUnitAssignment $assignment, array $filters = []): LengthAwarePaginator
    {
        $query = Message::where('employee_unit_assignment_id', $assignment->id)
            ->with('sender');

        if (!empty($filters['search'])) {
            $query->where('message', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'read') {
                $query->where('is_read', true);
            } elseif ($filters['status'] === 'unread') {
                $query->where('is_read', false);
            }
        }

        if (!empty($filters['sender'])) {
            $query->where('sender_type', $filters['sender']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (isset($filters['has_attachment'])) {
            if ($filters['has_attachment']) {
                $query->whereNotNull('attachment')->where('attachment', '!=', '');
            } else {
                $query->where(function ($q) {
                    $q->whereNull('attachment')->orWhere('attachment', '');
                });
            }
        }

        return $query->latest()->paginate($filters['per_page'] ?? 20);
    }

    public function store(EmployeeUnitAssignment $assignment, array $data, Authenticatable $sender): Message
    {
        return DB::transaction(function () use ($assignment, $data, $sender) {
            $attachmentPath = null;
            
            if (isset($data['attachment']) && $data['attachment']) {
                if (is_object($data['attachment']) && method_exists($data['attachment'], 'store')) {
                    $attachmentPath = $data['attachment']->store('messages/attachments', 'public');
                } else {
                    $attachmentPath = $data['attachment'];
                }
            }

            $message = Message::create([
                'employee_unit_assignment_id' => $assignment->id,
                'sender_type' => get_class($sender),
                'sender_id' => $sender->getAuthIdentifier(),
                'message' => $data['message'] ?? null,
                'attachment' => $attachmentPath,
                'message_type' => $data['message_type'] ?? 'text',
                'is_read' => false,
            ]);

            $this->clearCache($assignment->id);

            return $message->load('sender');
        });
    }

    public function update(Message $message, array $data, Authenticatable $user): Message
    {
        if ($message->sender_id !== $user->getAuthIdentifier() || $message->sender_type !== get_class($user)) {
            throw ValidationException::withMessages(['message' => 'You can only edit your own messages.']);
        }

        if ($message->is_read) {
            throw ValidationException::withMessages(['message' => 'Only unread messages can be edited.']);
        }

        return DB::transaction(function () use ($message, $data) {
            $updateData = [];
            
            if (isset($data['message'])) {
                $updateData['message'] = $data['message'];
            }
            
            if (isset($data['attachment'])) {
                if (is_object($data['attachment']) && method_exists($data['attachment'], 'store')) {
                    if ($message->attachment && Storage::disk('public')->exists($message->attachment)) {
                        Storage::disk('public')->delete($message->attachment);
                    }
                    $updateData['attachment'] = $data['attachment']->store('messages/attachments', 'public');
                } else {
                    $updateData['attachment'] = $data['attachment'];
                }
            }
            
            $updateData['edited_at'] = now();

            $message->update($updateData);
            $this->clearCache($message->employee_unit_assignment_id);

            return $message->fresh('sender');
        });
    }

    public function delete(Message $message): bool
    {
        return DB::transaction(function () use ($message) {
            $message->delete();
            $this->clearCache($message->employee_unit_assignment_id);
            return true;
        });
    }

    public function markAsRead(Message $message): Message
    {
        return DB::transaction(function () use ($message) {
            $message->update(['is_read' => true, 'read_at' => now()]);
            $this->clearCache($message->employee_unit_assignment_id);
            return $message;
        });
    }

    public function markAllAsRead(EmployeeUnitAssignment $assignment, Authenticatable $user): bool
    {
        return DB::transaction(function () use ($assignment, $user) {
            Message::where('employee_unit_assignment_id', $assignment->id)
                ->where('is_read', false)
                ->where('sender_type', '!=', get_class($user))
                ->update(['is_read' => true, 'read_at' => now()]);
            
            $this->clearCache($assignment->id);
            return true;
        });
    }

    public function downloadAttachment(Message $message): BinaryFileResponse
    {
        if (!$message->attachment) {
            throw ValidationException::withMessages(['attachment' => 'No attachment found.']);
        }

        if (!Storage::disk('public')->exists($message->attachment)) {
            throw ValidationException::withMessages(['attachment' => 'Attachment file not found on server.']);
        }

        $path = Storage::disk('public')->path($message->attachment);
        $filename = basename($path);

        return response()->download($path, $filename);
    }

    public function getUnreadCount(EmployeeUnitAssignment $assignment, Authenticatable $user): int
    {
        $cacheKey = "assignment_{$assignment->id}_unread_count_" . class_basename($user) . "_{$user->getAuthIdentifier()}";
        
        return Cache::tags(['messages', "assignment_{$assignment->id}"])->remember($cacheKey, 60, function () use ($assignment, $user) {
            return Message::where('employee_unit_assignment_id', $assignment->id)
                ->where('is_read', false)
                ->where('sender_type', '!=', get_class($user))
                ->count();
        });
    }

    public function getLatestMessages(EmployeeUnitAssignment $assignment, int $limit = 10)
    {
        return Message::where('employee_unit_assignment_id', $assignment->id)
            ->with('sender')
            ->latest()
            ->limit($limit)
            ->get();
    }

    protected function clearCache(int $assignmentId): void
    {
        Cache::tags(['messages', "assignment_{$assignmentId}"])->flush();
    }
}