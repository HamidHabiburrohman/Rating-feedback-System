(function () {
    window.Conversation = window.Conversation || {};

    var listeners = {};

    window.Conversation.Events = {
        on: function (eventName, callback) {
            if (!listeners[eventName]) {
                listeners[eventName] = [];
            }
            listeners[eventName].push(callback);
            return this;
        },

        off: function (eventName, callback) {
            if (!listeners[eventName]) return this;
            if (!callback) {
                listeners[eventName] = [];
                return this;
            }
            listeners[eventName] = listeners[eventName].filter(function (cb) {
                return cb !== callback;
            });
            return this;
        },

        emit: function (eventName, data) {
            var callbacks = listeners[eventName];
            if (!callbacks || callbacks.length === 0) return;
            callbacks.forEach(function (cb) {
                try {
                    cb(data);
                } catch (err) {
                    console.error('[Conversation.Events] Error in listener for ' + eventName, err);
                }
            });

            try {
                window.dispatchEvent(new CustomEvent(eventName, { detail: data || {} }));
            } catch (e) {
                var event = document.createEvent('CustomEvent');
                event.initCustomEvent(eventName, true, true, data || {});
                window.dispatchEvent(event);
            }
        },

        once: function (eventName, callback) {
            var self = this;
            var wrapper = function (data) {
                callback(data);
                self.off(eventName, wrapper);
            };
            this.on(eventName, wrapper);
            return this;
        }
    };
})();