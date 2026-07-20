(function () {
    window.Conversation = window.Conversation || {};

    window.Conversation.Utils = {
        debounce: function (func, wait) {
            var timeout;
            return function () {
                var context = this;
                var args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(function () {
                    func.apply(context, args);
                }, wait);
            };
        },

        throttle: function (func, limit) {
            var inThrottle;
            return function () {
                var context = this;
                var args = arguments;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(function () { inThrottle = false; }, limit);
                }
            };
        },

        getCsrfToken: function () {
            var meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.content : '';
        },

        scrollToBottom: function (element, smooth) {
            if (!element) return;
            setTimeout(function () {
                element.scrollTo({
                    top: element.scrollHeight,
                    behavior: smooth ? 'smooth' : 'auto'
                });
            }, window.Conversation.Constants.TIMEOUTS.AUTO_SCROLL);
        },

        formatFileSize: function (bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(1) + ' MB';
        },

        getExtIcon: function (ext) {
            var icons = {
                pdf: 'ti-file-text',
                doc: 'ti-file-word',
                docx: 'ti-file-word',
                xls: 'ti-file-excel',
                xlsx: 'ti-file-excel',
                png: 'ti-photo',
                jpg: 'ti-photo',
                jpeg: 'ti-photo',
                webp: 'ti-photo',
                zip: 'ti-file-zip',
                rar: 'ti-file-zip'
            };
            return icons[ext.toLowerCase()] || 'ti-file';
        },

        escapeHtml: function (str) {
            if (!str) return '';
            var div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        },

        generateId: function () {
            return 'id-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
        },

        parseConversationIdFromUrl: function () {
            var match = window.location.pathname.match(/\/admin\/conversations\/(\d+)/);
            return match ? parseInt(match[1], 10) : null;
        }
    };
})();