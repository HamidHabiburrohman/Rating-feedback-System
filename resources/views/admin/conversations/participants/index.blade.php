{{-- resources/views/admin/conversations/participants/index.blade.php --}}
@include('admin.conversations.participants.table', [
    'participants' => $participants ?? collect(),
    'conversation' => $conversation
])

@include('admin.conversations.participants.create-modal', ['conversation' => $conversation])
@include('admin.conversations.participants.edit-modal', ['conversation' => $conversation])