(function () {
    window.Conversation = window.Conversation || {};
    var C = window.Conversation.Constants;
    var U = window.Conversation.Utils;
    var Events = window.Conversation.Events;

    var currentConversationId = null;
    var currentState = 'EMPTY';
    var pendingRetryUrl = null;
    var pendingMessages = new Map();
    var SCROLL_THRESHOLD = 100;

    function setState(newState) {
        currentState = newState;
        var emptyEl = document.querySelector(C.SELECTORS.EMPTY_STATE);
        var loadingEl = document.querySelector(C.SELECTORS.LOADING);
        var contentEl = document.querySelector(C.SELECTORS.CONTENT);

        if (emptyEl) emptyEl.style.display = (newState === 'EMPTY') ? 'flex' : 'none';
        if (loadingEl) loadingEl.style.display = (newState === 'LOADING') ? 'flex' : 'none';
        if (contentEl) contentEl.style.display = (newState === 'CONTENT' || newState === 'ERROR') ? 'flex' : 'none';
    }

    function getErrorHTML(message) {
        return '<div class="conv-error-state">' +
            '<div class="conv-error-icon"><i class="ti ti-alert-triangle"></i></div>' +
            '<h3 class="conv-error-title">Failed to load conversation</h3>' +
            '<p class="conv-error-desc">' + U.escapeHtml(message) + '</p>' +
            '<button class="conv-error-retry" id="convRetryBtn">' +
            '<i class="ti ti-refresh"></i> Try Again</button>' +
            '</div>';
    }

    function executeScripts(container) {
        var scripts = container.querySelectorAll('script');
        scripts.forEach(function (script) {
            var newScript = document.createElement('script');
            if (script.src) {
                newScript.src = script.src;
            } else {
                newScript.textContent = script.textContent;
            }
            document.body.appendChild(newScript);
            document.body.removeChild(newScript);
        });
    }

    function bindRetryButton() {
        var retryBtn = document.getElementById('convRetryBtn');
        if (retryBtn) {
            retryBtn.addEventListener('click', function () {
                window.Conversation.Workspace.retryLoad();
            });
        }
    }

    function getChatListContainer() {
        return document.getElementById('convChatList') || document.querySelector('.conv-chat-list');
    }

    function getChatScrollContainer() {
        return document.getElementById('convChatScroll') || document.querySelector('.conv-chat-scroll');
    }

    function removeEmptyStateInChat() {
        var chatList = getChatListContainer();
        if (!chatList) return;
        var emptyState = chatList.querySelector('.conv-chat-empty');
        if (emptyState) emptyState.remove();
    }

    function findMessage(messageId) {
        return document.querySelector('[data-message-id="' + messageId + '"]');
    }

    function findBubbleByTempId(tempId) {
        return document.querySelector('[data-temp-id="' + tempId + '"]');
    }

    function updateStatusIndicator(bubbleEl, status) {
        if (!bubbleEl) return;
        bubbleEl.classList.remove('conv-msg-sending', 'conv-msg-failed', 'conv-msg-sent', 'conv-msg-delivered', 'conv-msg-read');
        bubbleEl.classList.add('conv-msg-' + status);

        var statusIndicator = bubbleEl.querySelector('[data-status-indicator]');
        if (!statusIndicator) return;

        statusIndicator.className = 'conv-msg-status conv-status-sm conv-msg-status-' + status;
        statusIndicator.setAttribute('data-status-indicator', '');

        var iconMap = {
            sending: '<i class="ti ti-clock"></i>',
            sent: '<i class="ti ti-check"></i>',
            delivered: '<i class="ti ti-checks"></i>',
            read: '<i class="ti ti-checks"></i>',
            failed: '<i class="ti ti-alert-circle"></i>'
        };
        statusIndicator.innerHTML = iconMap[status] || '';
    }

    function injectRetryButtons(bubbleEl, tempId) {
        if (!bubbleEl) return;
        var footer = bubbleEl.querySelector('.conv-msg-footer');
        if (!footer || footer.querySelector('.conv-msg-retry-btn')) return;

        var actionsHtml =
            '<button class="conv-msg-retry-btn" data-temp-id="' + tempId + '">' +
            '<i class="ti ti-refresh"></i> Retry' +
            '</button>' +
            '<button class="conv-msg-delete-failed-btn" data-temp-id="' + tempId + '">' +
            '<i class="ti ti-trash"></i> Delete' +
            '</button>';
        footer.insertAdjacentHTML('beforeend', actionsHtml);
    }

    function removeRetryButtons(bubbleEl) {
        if (!bubbleEl) return;
        var footer = bubbleEl.querySelector('.conv-msg-footer');
        if (!footer) return;

        var retryBtn = footer.querySelector('.conv-msg-retry-btn');
        var deleteBtn = footer.querySelector('.conv-msg-delete-failed-btn');

        if (retryBtn) retryBtn.remove();
        if (deleteBtn) deleteBtn.remove();
    }

    function bindMessageActions() {
        document.addEventListener('click', function (e) {
            var retryBtn = e.target.closest('.conv-msg-retry-btn');
            var deleteBtn = e.target.closest('.conv-msg-delete-failed-btn');

            if (retryBtn) {
                var tempId = retryBtn.getAttribute('data-temp-id');
                if (tempId) {
                    Events.emit(C.EVENTS.MESSAGE_RETRY, { tempId: tempId });
                    Events.emit('conv:retry-message', { tempId: tempId });
                }
            }

            if (deleteBtn) {
                var tempId = deleteBtn.getAttribute('data-temp-id');
                if (tempId) {
                    Events.emit(C.EVENTS.MESSAGE_REMOVED, { tempId: tempId });
                    Events.emit('conv:delete-optimistic', { tempId: tempId });
                }
            }

            var actionBtn = e.target.closest('.conv-msg-action-btn');
            if (!actionBtn) return;

            var action = actionBtn.getAttribute('data-action');
            var msgRow = actionBtn.closest('.conv-msg-row');
            if (!msgRow) return;

            var messageId = msgRow.getAttribute('data-message-id');

            if (action === 'copy') {
                var textEl = msgRow.querySelector('.conv-msg-text');
                if (textEl) {
                    navigator.clipboard.writeText(textEl.innerText).then(function () {
                        actionBtn.innerHTML = '<i class="ti ti-check"></i>';
                        setTimeout(function () {
                            actionBtn.innerHTML = '<i class="ti ti-copy"></i>';
                        }, 1500);
                    });
                }
            }
            if (action === 'reply') {
                Events.emit(C.EVENTS.MESSAGE_REPLY || 'conversation:message:reply', { messageId: messageId });
            }
            if (action === 'delete') {
                Events.emit(C.EVENTS.MESSAGE_DELETE, { messageId: messageId });
            }
            if (action === 'pin') {
                Events.emit(C.EVENTS.MESSAGE_PIN || 'conversation:message:pin', { messageId: messageId });
            }
        });
    }

    window.Conversation.Workspace = {
        init: function () {
            this.handleInitialLoad();
            this.bindHistory();
            this.bindEvents();
            this.bindMessageEngineEvents();
            bindMessageActions();
        },

        handleInitialLoad: function () {
            var id = U.parseConversationIdFromUrl();
            if (id) {
                currentConversationId = id;
                var contentEl = document.querySelector(C.SELECTORS.CONTENT);
                if (contentEl && contentEl.innerHTML.trim().length > 0) {
                    setState('CONTENT');
                    this.markAsRead(id);
                    this.scrollToBottom(false);
                } else {
                    var link = document.querySelector(C.SELECTORS.CARD_LINK + '[data-conversation-id="' + id + '"]');
                    var url = link ? link.getAttribute('href') : C.ROUTES.CONVERSATION(id);
                    this.loadConversation(id, url, false);
                }
            } else {
                setState('EMPTY');
            }
        },

        bindHistory: function () {
            var self = this;
            window.addEventListener('popstate', function (e) {
                var id = null;
                if (e.state && e.state.conversationId) {
                    id = e.state.conversationId;
                } else {
                    id = U.parseConversationIdFromUrl();
                }

                if (id) {
                    var link = document.querySelector(C.SELECTORS.CARD_LINK + '[data-conversation-id="' + id + '"]');
                    var url = link ? link.getAttribute('href') : C.ROUTES.CONVERSATION(id);
                    self.loadConversation(id, url, false);
                } else {
                    currentConversationId = null;
                    setState('EMPTY');
                    if (window.Conversation.Sidebar && window.Conversation.Sidebar.updateActiveState) {
                        window.Conversation.Sidebar.updateActiveState(null);
                    }
                }
            });
        },

        bindEvents: function () {
            var self = this;
            Events.on(C.EVENTS.CONVERSATION_SELECTED, function (data) {
                if (data && data.id) {
                    self.loadConversation(data.id, data.url);
                }
            });
        },

        bindMessageEngineEvents: function () {
            var self = this;
            Events.on(C.EVENTS.MESSAGE_SENDING, function (data) {
                if (data && data.html && data.tempId) {
                    self.appendOptimisticMessage(data.html, data.tempId, data.payload);
                }
            });
            Events.on(C.EVENTS.MESSAGE_SENT, function (data) {
                if (data && data.tempId && data.serverHtml) {
                    self.replaceOptimisticMessage(data.tempId, data.serverHtml, data.serverId);
                }
            });
            Events.on(C.EVENTS.MESSAGE_FAILED, function (data) {
                if (data && data.tempId) {
                    self.updateMessageStatus(data.tempId, 'failed');
                }
            });
            Events.on(C.EVENTS.MESSAGE_RETRY, function (data) {
                if (data && data.tempId) {
                    self.updateMessageStatus(data.tempId, 'sending');
                }
            });
            Events.on(C.EVENTS.MESSAGE_REMOVED, function (data) {
                if (data && data.tempId) {
                    self.removeMessage(data.tempId);
                }
            });
            Events.on(C.EVENTS.MESSAGE_DELIVERED, function (data) {
                if (data) {
                    self.updateMessageStatus(data.serverId || data.tempId, 'delivered');
                }
            });
            Events.on(C.EVENTS.MESSAGE_READ, function (data) {
                if (data) {
                    self.updateMessageStatus(data.serverId || data.tempId, 'read');
                }
            });

            // DELETE MESSAGE WORKFLOW
            Events.on(C.EVENTS.MESSAGE_DELETE, function (data) {
                if (!data || !data.messageId) return;

                var messageId = data.messageId;
                var bubbleEl = findMessage(messageId);
                if (!bubbleEl) return;

                if (!confirm('Are you sure you want to delete this message?')) return;

                var parentRow = bubbleEl.closest('.conv-msg-row') || bubbleEl;
                var nextRow = parentRow.nextElementSibling;
                var previousHTML = parentRow.outerHTML;
                var conversationId = self.getCurrentId();

                // Optimistic UI removal
                parentRow.remove();

                var chatList = getChatListContainer();
                if (chatList && chatList.querySelectorAll('.conv-msg-row').length === 0) {
                    var emptyHTML = '<div class="conv-chat-empty"><div class="conv-chat-empty-icon"><i class="ti ti-messages"></i></div><h3 class="conv-chat-empty-title">No messages yet</h3><p class="conv-chat-empty-desc">Start the conversation by sending a message below.</p></div>';
                    chatList.innerHTML = emptyHTML;
                }

                // API Request
                window.Conversation.API.delete(C.ROUTES.DELETE_MESSAGE(conversationId, messageId))
                    .then(function () {
                        Events.emit('conversation:message:deleted', { messageId: messageId, conversationId: conversationId });
                    })
                    .catch(function () {
                        // Restore bubble on failure
                        if (nextRow) {
                            nextRow.insertAdjacentHTML('beforebegin', previousHTML);
                        } else if (chatList) {
                            chatList.innerHTML = previousHTML;
                        }

                        // Show Toast
                        var toast = document.createElement('div');
                        toast.textContent = 'Unable to delete message.';
                        toast.style.cssText = 'position:fixed;bottom:24px;right:24px;background:#EF4444;color:#fff;padding:12px 24px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.1);z-index:9999;font-family:Plus Jakarta Sans,sans-serif;font-size:14px;font-weight:500;transition:opacity 0.3s;';
                        document.body.appendChild(toast);
                        setTimeout(function () {
                            toast.style.opacity = '0';
                            setTimeout(function () { toast.remove(); }, 300);
                        }, 3000);
                    });
            });
        },

        getCurrentId: function () {
            return currentConversationId;
        },

        getCurrentState: function () {
            return currentState;
        },

        retryLoad: function () {
            if (pendingRetryUrl && currentConversationId) {
                this.loadConversation(currentConversationId, pendingRetryUrl, false);
            }
        },

        loadConversation: function (id, url, pushState) {
            if (typeof pushState === 'undefined') pushState = true;
            if (currentConversationId == id && currentState === 'CONTENT') return;

            var self = this;
            currentConversationId = id;
            pendingRetryUrl = url;
            pendingMessages.clear();

            setState('LOADING');
            var contentEl = document.querySelector(C.SELECTORS.CONTENT);
            if (contentEl) {
                contentEl.innerHTML = '<div class="conv-loading-wrapper"><div class="conv-loading-state"><i class="ti ti-loader conv-spin"></i></div></div>';
            }

            window.Conversation.API.getHtml(url, 'conversation_load')
                .then(function (html) {
                    if (html === null) return;
                    if (contentEl) {
                        contentEl.innerHTML = html;
                        self.executeScripts(contentEl);
                    }
                    if (window.Conversation.Composer && window.Conversation.Composer.bind) {
                        window.Conversation.Composer.bind();
                    }
                    self.scrollToBottom(false);
                    self.markAsRead(id);

                    if (window.Conversation.Sidebar && window.Conversation.Sidebar.updateActiveState) {
                        window.Conversation.Sidebar.updateActiveState(id);
                    }

                    setState('CONTENT');
                    if (pushState) {
                        window.history.pushState({ conversationId: id }, '', url);
                    }
                    Events.emit(C.EVENTS.CONVERSATION_LOADED, { id: id, url: url });
                })
                .catch(function (error) {
                    if (error.name === 'AbortError') return;
                    var msg = 'Network error. Please check your connection.';
                    if (error.message && error.message.indexOf('HTTP_ERROR_') === 0) {
                        if (error.message === 'HTTP_ERROR_404') msg = 'This conversation was not found.';
                        else if (error.message === 'HTTP_ERROR_403') msg = 'You do not have permission to view this conversation.';
                        else if (error.message === 'HTTP_ERROR_500') msg = 'Server error. Please try again later.';
                    }
                    self.showError(msg);
                });
        },

        showError: function (message) {
            setState('ERROR');
            var contentEl = document.querySelector(C.SELECTORS.CONTENT);
            if (contentEl) {
                contentEl.innerHTML = getErrorHTML(message);
                bindRetryButton();
            }
        },

        injectHtml: function (html) {
            var container = document.querySelector(C.SELECTORS.CONTENT);
            if (!container) return;
            container.innerHTML = html;
            this.executeScripts(container);
        },

        executeScripts: function (container) {
            executeScripts(container);
        },

        isNearBottom: function () {
            var chatScroll = getChatScrollContainer();
            if (!chatScroll) return true;
            var distanceFromBottom = chatScroll.scrollHeight - chatScroll.scrollTop - chatScroll.clientHeight;
            return distanceFromBottom <= SCROLL_THRESHOLD;
        },

        scrollToBottom: function (smooth) {
            var chatScroll = getChatScrollContainer();
            if (!chatScroll) return;
            U.scrollToBottom(chatScroll, smooth !== false);
        },

        appendBubble: function (html, shouldScroll) {
            var chatList = getChatListContainer();
            if (!chatList) return false;
            removeEmptyStateInChat();

            var tempContainer = document.createElement('div');
            tempContainer.innerHTML = html.trim();
            var fragment = document.createDocumentFragment();
            var scripts = [];

            while (tempContainer.firstChild) {
                var node = tempContainer.firstChild;
                if (node.tagName === 'SCRIPT') {
                    scripts.push(node);
                } else {
                    fragment.appendChild(node);
                }
            }
            chatList.appendChild(fragment);

            scripts.forEach(function (script) {
                var newScript = document.createElement('script');
                if (script.src) {
                    newScript.src = script.src;
                } else {
                    newScript.textContent = script.textContent;
                }
                document.body.appendChild(newScript);
                document.body.removeChild(newScript);
            });

            if (shouldScroll !== false) {
                this.scrollToBottom(true);
            }
            return true;
        },

        appendMessage: function (html, shouldScroll) {
            return this.appendBubble(html, shouldScroll);
        },

        appendOptimisticMessage: function (html, tempId, payload) {
            var chatList = getChatListContainer();
            if (!chatList) return false;
            removeEmptyStateInChat();

            chatList.insertAdjacentHTML('beforeend', html);
            var bubbleEl = findBubbleByTempId(tempId);

            if (bubbleEl) {
                pendingMessages.set(tempId, {
                    element: bubbleEl,
                    status: 'sending',
                    payload: payload || {},
                    serverId: null
                });
            }
            this.scrollToBottom(true);
            return true;
        },

        appendOptimisticBubble: function (html, tempId, body, files) {
            return this.appendOptimisticMessage(html, tempId, { body: body, files: files || [] });
        },

        replaceOptimisticMessage: function (tempId, serverHtml, serverId) {
            var oldBubble = findBubbleByTempId(tempId);
            if (!oldBubble) return false;

            var tempContainer = document.createElement('div');
            tempContainer.innerHTML = serverHtml.trim();
            var newBubble = tempContainer.firstElementChild;

            if (newBubble) {
                oldBubble.replaceWith(newBubble);
                pendingMessages.delete(tempId);
                if (serverId) {
                    newBubble.setAttribute('data-message-id', serverId);
                }
                return true;
            }
            return false;
        },

        replaceOptimisticBubble: function (tempId, serverHtml) {
            return this.replaceOptimisticMessage(tempId, serverHtml, null);
        },

        updateMessageStatus: function (identifier, status) {
            var bubbleEl = findBubbleByTempId(identifier) || findMessage(identifier);
            if (!bubbleEl) return false;

            updateStatusIndicator(bubbleEl, status);
            if (status === 'failed') {
                injectRetryButtons(bubbleEl, identifier);
            } else {
                removeRetryButtons(bubbleEl);
            }

            var pending = pendingMessages.get(identifier);
            if (pending) {
                pending.status = status;
            }
            return true;
        },

        updateBubbleStatus: function (tempId, status) {
            return this.updateMessageStatus(tempId, status);
        },

        removeMessage: function (identifier) {
            var bubbleEl = findBubbleByTempId(identifier) || findMessage(identifier);
            if (bubbleEl) {
                bubbleEl.remove();
            }
            pendingMessages.delete(identifier);
            return true;
        },

        removeBubble: function (tempId) {
            return this.removeMessage(tempId);
        },

        getPendingPayload: function (tempId) {
            var pending = pendingMessages.get(tempId);
            return pending ? pending.payload : null;
        },

        getPendingMessage: function (tempId) {
            var pending = pendingMessages.get(tempId);
            if (!pending) return null;
            return {
                body: pending.payload ? pending.payload.body : '',
                files: pending.payload ? (pending.payload.files || []) : []
            };
        },

        prependBubbles: function (html) {
            var chatList = getChatListContainer();
            if (!chatList) return false;

            var tempContainer = document.createElement('div');
            tempContainer.innerHTML = html.trim();
            var fragment = document.createDocumentFragment();

            while (tempContainer.firstChild) {
                if (tempContainer.firstChild.tagName !== 'SCRIPT') {
                    fragment.appendChild(tempContainer.firstChild);
                } else {
                    tempContainer.removeChild(tempContainer.firstChild);
                }
            }
            chatList.insertBefore(fragment, chatList.firstChild);
            return true;
        },

        markAsRead: function (id) {
            window.Conversation.API.post(C.ROUTES.MARK_READ(id), {})
                .then(function () {
                    var cardLink = document.querySelector(
                        C.SELECTORS.CARD_LINK + '[data-conversation-id="' + id + '"]'
                    );
                    if (cardLink) {
                        var badge = cardLink.querySelector('[data-unread-badge]');
                        if (badge) {
                            badge.remove();
                        }
                        cardLink.setAttribute('data-unread-count', '0');
                    }
                })
                .catch(function () { });
        },

        scrollToAndHighlightMessage: function (messageId) {
            var targetEl = document.querySelector('[data-message-id="' + messageId + '"]');
            if (!targetEl) return;

            targetEl.scrollIntoView({ behavior: 'smooth', block: 'center' });

            // Remove existing highlight to restart animation if clicked rapidly
            targetEl.classList.remove('conv-msg-highlight');

            // Force reflow
            void targetEl.offsetWidth;

            targetEl.classList.add('conv-msg-highlight');

            setTimeout(function () {
                targetEl.classList.remove('conv-msg-highlight');
            }, 2000);
        },
    };
})();