(function () {
    window.Conversation = window.Conversation || {};

    window.Conversation.App = {
        initialized: false,

        init: function () {
            if (this.initialized) return;
            this.initialized = true;

            if (window.Conversation.Sidebar) {
                window.Conversation.Sidebar.init();
            }
            if (window.Conversation.Workspace) {
                window.Conversation.Workspace.init();
            }
            if (window.Conversation.Composer) {
                window.Conversation.Composer.init();
            }
            if (window.Conversation.Participants) {
                window.Conversation.Participants.init();
            }
            if (window.Conversation.Attachments) {
                window.Conversation.Attachments.init();
            }

            this.bindGlobalShortcuts();
        },

        bindGlobalShortcuts: function () {
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    var dropdowns = document.querySelectorAll('.conv-dropdown.open');
                    dropdowns.forEach(function (d) { d.classList.remove('open'); });
                }
            });
        }
    };
})();