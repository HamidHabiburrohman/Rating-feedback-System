@extends('layouts.admin.app')
@section('title', 'Conversations')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/admin/css/conversations.css') }}">
@endpush
@section('admin-content')
<div class="conv-app-layout">
    @include('admin.conversations.partials.sidebar', [
        'conversations' => $conversations ?? collect(),
        'statistics' => $statistics ?? []
    ])
    
    <main class="conv-workspace" id="conversation-workspace">
        {{-- NEW LOADING STATE: Centered Dots --}}
        <div id="conversation-loading" class="conv-loading-wrapper" style="display: none;">
            <div class="conv-loading-dots-container">
                <div class="conv-loading-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <p class="conv-loading-text">Opening conversation...</p>
            </div>
        </div>

        <div id="conversation-empty-state" class="conv-empty-wrapper">
            <x-admin.conversations.empty-state
                icon="ti-messages"
                title="Select a conversation"
                description="Choose a thread from the sidebar to view messages, or wait for new assignments to automatically create communication channels."
                iconColor="#f8773c"
                iconBg="#fff5f0"
            />
        </div>

        <div id="conversation-content" class="conv-content-wrapper" style="display: none;">
            @if(isset($conversation))
                @include('admin.conversations.show', [
                    'conversation' => $conversation,
                    'messages' => $messages ?? collect()
                ])
            @endif
        </div>
    </main>
</div>
@endsection
@push('admin-scripts')
<script src="{{ asset('assets/admin/js/conversations/constants.js') }}" defer></script>
<script src="{{ asset('assets/admin/js/conversations/utils.js') }}" defer></script>
<script src="{{ asset('assets/admin/js/conversations/events.js') }}" defer></script>
<script src="{{ asset('assets/admin/js/conversations/api.js') }}" defer></script>
<script src="{{ asset('assets/admin/js/conversations/workspace.js') }}" defer></script>
<script src="{{ asset('assets/admin/js/conversations/sidebar.js') }}" defer></script>
<script src="{{ asset('assets/admin/js/conversations/composer.js') }}" defer></script>
<script src="{{ asset('assets/admin/js/conversations/participants.js') }}" defer></script>
<script src="{{ asset('assets/admin/js/conversations/attachments.js') }}" defer></script>
<script src="{{ asset('assets/admin/js/conversations/app.js') }}" defer></script>
<script src="{{ asset('assets/admin/js/conversation.js') }}" defer></script>
@endpush