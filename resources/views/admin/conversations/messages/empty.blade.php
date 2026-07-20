<div class="conv-empty-wrapper">
    <x-admin.conversations.empty-state
        icon="ti-messages"
        title="Select a conversation"
        description="Choose a thread from the sidebar to view messages, or wait for new assignments to automatically create communication channels."
        iconColor="#f8773c"
        iconBg="#fff5f0"
    />
</div>

<style>
/* Ensure the parent container fills the workspace area */
#conversation-empty-state {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
}

.conv-empty-wrapper {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
    height: 100%;
}
</style>