(function () {
    window.Conversation = window.Conversation || {};

    var C = window.Conversation.Constants;

    function dispatchModal(eventName, detail) {
        window.Conversation.Events.emit(eventName, detail || {});
        try {
            window.dispatchEvent(new CustomEvent(eventName, { detail: detail || {} }));
        } catch (e) {
            var event = document.createEvent('CustomEvent');
            event.initCustomEvent(eventName, true, true, detail || {});
            window.dispatchEvent(event);
        }
    }

    window.Conversation.Participants = {
        init: function () {
            this.bindGlobalTriggers();
            window.Conversation.Events.on(C.EVENTS.OPEN_PARTICIPANTS, this.openPanel.bind(this));
            window.Conversation.Events.on(C.EVENTS.CONVERSATION_LOADED, this.onConversationLoaded.bind(this));
        },

        bindGlobalTriggers: function () {
            document.addEventListener('click', function (e) {
                var createBtn = e.target.closest('[data-action="open-create-participant"]');
                if (createBtn) {
                    e.preventDefault();
                    dispatchModal(C.EVENTS.OPEN_CREATE_PARTICIPANT, {});
                    return;
                }

                var manageBtn = e.target.closest('[data-action="manage-participant"]');
                if (manageBtn) {
                    e.preventDefault();
                    dispatchModal(C.EVENTS.OPEN_EDIT_PARTICIPANT, {
                        id: manageBtn.getAttribute('data-participant-id'),
                        user_id: manageBtn.getAttribute('data-user-id'),
                        name: manageBtn.getAttribute('data-name'),
                        email: manageBtn.getAttribute('data-email'),
                        role: manageBtn.getAttribute('data-role'),
                        is_muted: manageBtn.getAttribute('data-is-muted') === 'true',
                        is_read_only: manageBtn.getAttribute('data-is-read-only') === 'true'
                    });
                }
            });
        },

        onConversationLoaded: function () {
        },

        openPanel: function () {
            var currentId = window.Conversation.Workspace.getCurrentId();
            if (!currentId) {
                console.warn('[Conversation.Participants] No active conversation');
                return;
            }
            var url = '/admin/conversations/' + currentId + '/participants';
            console.log('[Conversation.Participants] Opening panel:', url);
        }
    };
})();