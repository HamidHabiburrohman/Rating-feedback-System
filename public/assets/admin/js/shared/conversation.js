(function () {
    document.addEventListener('DOMContentLoaded', function () {
        if (window.Conversation && window.Conversation.App) {
            window.Conversation.App.init();
        }
    });
})();