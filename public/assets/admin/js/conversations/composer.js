(function () {
    window.Conversation = window.Conversation || {};
    var C = window.Conversation.Constants;
    var U = window.Conversation.Utils;
    var Workspace = window.Conversation.Workspace;
    var API = window.Conversation.API;
    var Events = window.Conversation.Events;

    var state = {
        isSending: false,
        attachedFiles: [],
        bound: false,
        elements: {},
        replyTo: null
    };

    function cacheElements() {
        state.elements.wrapper = document.querySelector(C.SELECTORS.COMPOSER_WRAPPER);
        state.elements.form = document.querySelector(C.SELECTORS.COMPOSER_FORM);
        state.elements.textarea = document.querySelector(C.SELECTORS.COMPOSER_TEXTAREA);
        state.elements.sendBtn = document.querySelector(C.SELECTORS.COMPOSER_SEND_BTN);
        state.elements.attachBtn = document.querySelector(C.SELECTORS.COMPOSER_ATTACH_BTN);
        state.elements.charCounter = document.querySelector(C.SELECTORS.CHAR_COUNTER);
        state.elements.previewContainer = document.querySelector(C.SELECTORS.COMPOSER_PREVIEWS);
        state.elements.fileInputsContainer = document.querySelector(C.SELECTORS.COMPOSER_FILE_INPUTS);
        state.elements.dragOverlay = document.querySelector(C.SELECTORS.DRAG_OVERLAY);
        state.elements.replyBanner = document.getElementById('convReplyBanner');
        state.elements.replySender = document.getElementById('convReplySender');
        state.elements.replyPreview = document.getElementById('convReplyPreview');
        state.elements.replyCancel = document.getElementById('convReplyCancel');
        state.elements.replyToIdInput = document.getElementById('convReplyToId');
    }

    function autoResize() {
        if (!state.elements.textarea) return;
        state.elements.textarea.style.height = 'auto';
        state.elements.textarea.style.height = Math.min(
            state.elements.textarea.scrollHeight,
            C.CONFIG.MAX_TEXTAREA_HEIGHT
        ) + 'px';
    }

    function updateState() {
        if (!state.elements.textarea || !state.elements.sendBtn) return;
        var hasText = state.elements.textarea.value.trim().length > 0;
        var hasFiles = state.attachedFiles.length > 0;
        state.elements.sendBtn.disabled = state.isSending || !(hasText || hasFiles);
        var len = state.elements.textarea.value.length;
        if (state.elements.charCounter) {
            state.elements.charCounter.textContent = len + ' / ' + C.CONFIG.MAX_TEXT_LENGTH;
            state.elements.charCounter.classList.remove('warning', 'danger');
            if (len > C.CONFIG.MAX_TEXT_LENGTH - 500) {
                state.elements.charCounter.classList.add('danger');
            } else if (len > C.CONFIG.MAX_TEXT_LENGTH - 1000) {
                state.elements.charCounter.classList.add('warning');
            }
        }
    }

    function generateTempId() {
        return 'temp_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }

    function getCurrentTime() {
        var now = new Date();
        return now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
    }

    function getCurrentUserName() {
        var metaEl = document.querySelector('meta[name="conv-user-name"]');
        if (metaEl && metaEl.getAttribute('content')) {
            return metaEl.getAttribute('content');
        }
        var titleEl = document.querySelector('.conv-title');
        if (titleEl && titleEl.textContent.trim()) {
            return titleEl.textContent.trim();
        }
        return 'You';
    }

    function getCurrentUserRole() {
        var metaEl = document.querySelector('meta[name="conv-user-role"]');
        if (metaEl && metaEl.getAttribute('content')) {
            return metaEl.getAttribute('content');
        }
        return 'Admin';
    }

    function buildOptimisticBubbleHTML(body, files, tempId, replyTo) {
        var time = getCurrentTime();
        var senderName = getCurrentUserName();
        var senderRole = getCurrentUserRole();
        var escapedBody = U.escapeHtml(body).replace(/\n/g, '<br>');

        var replyHtml = '';
        if (replyTo) {
            replyHtml =
                '<div class="conv-msg-reply-preview" data-reply-to-id="' + replyTo.id + '" onclick="window.Conversation.Workspace.scrollToAndHighlightMessage(' + replyTo.id + ')" title="Go to message">' +
                '<i class="ti ti-corner-up-left"></i>' +
                '<span class="conv-msg-reply-sender">' + U.escapeHtml(replyTo.sender) + ':</span>' +
                '<span class="conv-msg-reply-text">' + U.escapeHtml(replyTo.preview) + '</span>' +
                '</div>';
        }

        var attachmentsHtml = '';
        if (files.length > 0) {
            attachmentsHtml = '<div class="conv-msg-attachments">';
            files.forEach(function (f) {
                var ext = f.name.split('.').pop();
                if (f.type.startsWith('image/')) {
                    var url = URL.createObjectURL(f);
                    attachmentsHtml +=
                        '<div class="conv-att-card conv-att-compact">' +
                        '<div class="conv-att-image-wrapper">' +
                        '<img src="' + url + '" alt="' + U.escapeHtml(f.name) + '" class="conv-att-image">' +
                        '</div></div>';
                } else {
                    attachmentsHtml +=
                        '<div class="conv-att-card conv-att-compact">' +
                        '<div class="conv-att-body">' +
                        '<div class="conv-att-info">' +
                        '<div class="conv-att-icon" style="background:#f1f5f9;color:#64748b;"><i class="ti ' + U.getExtIcon(ext) + '"></i></div>' +
                        '<div class="conv-att-meta">' +
                        '<p class="conv-att-name">' + U.escapeHtml(f.name) + '</p>' +
                        '<span class="conv-att-details">' + ext.toUpperCase() + ' \u2022 ' + U.formatFileSize(f.size) + '</span>' +
                        '</div></div></div></div>';
                }
            });
            attachmentsHtml += '</div>';
        }

        return '<div class="conv-msg-row conv-msg-own conv-msg-sending" data-temp-id="' + tempId + '" data-sender-name="' + U.escapeHtml(senderName) + '">' +
            '<div class="conv-msg-avatar-col"></div>' +
            '<div class="conv-msg-content-col">' +
            '<div class="conv-msg-header">' +
            '<span class="conv-msg-sender">' + U.escapeHtml(senderName) + '</span>' +
            '<span class="conv-msg-role">' + U.escapeHtml(senderRole) + '</span>' +
            '<span class="conv-msg-time">' + time + '</span>' +
            '</div>' +
            replyHtml +
            '<div class="conv-msg-bubble conv-msg-bubble-own">' +
            (body ? '<div class="conv-msg-text">' + escapedBody + '</div>' : '') +
            attachmentsHtml +
            '</div>' +
            '<div class="conv-msg-footer">' +
            '<span class="conv-msg-time-own">' + time + '</span>' +
            '<span class="conv-msg-status conv-status-sm conv-msg-status-sending" data-status-indicator><i class="ti ti-clock"></i></span>' +
            '</div>' +
            '</div>' +
            '<div class="conv-msg-actions">' +
            '<button class="conv-msg-action-btn" title="Reply" data-action="reply" data-message-id="' + tempId + '"><i class="ti ti-corner-up-left"></i></button>' +
            '<button class="conv-msg-action-btn" title="Copy" data-action="copy"><i class="ti ti-copy"></i></button>' +
            '<button class="conv-msg-action-btn" title="React" data-action="react"><i class="ti ti-mood-smile"></i></button>' +
            '<button class="conv-msg-action-btn conv-msg-action-danger" title="Delete" data-action="delete"><i class="ti ti-trash"></i></button>' +
            '</div></div>';
    }

    function renderPreviews() {
        if (!state.elements.previewContainer || !state.elements.fileInputsContainer) return;
        state.elements.previewContainer.innerHTML = '';
        state.elements.fileInputsContainer.innerHTML = '';
        if (state.attachedFiles.length === 0) {
            state.elements.previewContainer.style.display = 'none';
            updateState();
            return;
        }
        state.elements.previewContainer.style.display = 'flex';
        state.attachedFiles.forEach(function (file, index) {
            var dt = new DataTransfer();
            dt.items.add(file);
            var input = document.createElement('input');
            input.type = 'file';
            input.name = 'attachments[]';
            input.style.display = 'none';
            input.files = dt.files;
            state.elements.fileInputsContainer.appendChild(input);

            var ext = file.name.split('.').pop();
            var previewHtml = '';
            if (file.type.startsWith('image/')) {
                var url = URL.createObjectURL(file);
                previewHtml = '<div style="width:40px;height:40px;border-radius:8px;overflow:hidden;flex-shrink:0;"><img src="' + url + '" alt="' + U.escapeHtml(file.name) + '" style="width:100%;height:100%;object-fit:cover;"></div>';
            } else {
                previewHtml = '<div style="width:40px;height:40px;border-radius:8px;background:#e2e8f0;color:#475569;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="ti ' + U.getExtIcon(ext) + '" style="font-size:24px;"></i></div>';
            }

            var card = document.createElement('div');
            card.innerHTML =
                '<div style="display:flex;align-items:center;gap:12px;background:#f8fafc;border:1px solid rgba(15,23,42,0.06);border-radius:12px;padding:8px;width:220px;max-width:100%;position:relative;">' +
                previewHtml +
                '<div style="flex:1;min-width:0;display:flex;flex-direction:column;gap:2px;">' +
                '<p style="font-size:13px;font-weight:600;color:#0f172a;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="' + U.escapeHtml(file.name) + '">' + U.escapeHtml(file.name) + '</p>' +
                '<span style="font-size:11px;font-weight:500;color:#64748b;">' + ext.toUpperCase() + ' \u2022 ' + U.formatFileSize(file.size) + '</span>' +
                '</div>' +
                '<button type="button" class="conv-att-remove" data-index="' + index + '" title="Remove" style="position:absolute;top:-6px;right:-6px;width:20px;height:20px;background:#ef4444;color:#fff;border:2px solid #fff;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;"><i class="ti ti-x" style="font-size:12px;stroke-width:2;"></i></button>' +
                '</div>';
            state.elements.previewContainer.appendChild(card);
        });
        updateState();
    }

    function addFiles(fileList) {
        Array.from(fileList).forEach(function (file) {
            if (state.attachedFiles.length >= C.CONFIG.MAX_FILES) return;
            var isAllowed = C.CONFIG.ALLOWED_TYPES.indexOf(file.type) !== -1 || file.type.startsWith('image/');
            if (!isAllowed) return;
            if (file.size > C.CONFIG.MAX_FILE_SIZE) return;
            state.attachedFiles.push(file);
        });
        renderPreviews();
    }

    function triggerFilePicker() {
        var picker = document.createElement('input');
        picker.type = 'file';
        picker.multiple = true;
        picker.accept = 'image/*,.pdf,.doc,.docx,.xls,.xlsx';
        picker.style.display = 'none';
        picker.addEventListener('change', function (e) {
            if (e.target.files && e.target.files.length > 0) {
                addFiles(e.target.files);
            }
            if (picker.parentNode) picker.parentNode.removeChild(picker);
        });
        document.body.appendChild(picker);
        picker.click();
    }

    function bindDragAndDrop() {
        if (!state.elements.wrapper || !state.elements.dragOverlay) return;
        var dragCounter = 0;
        state.elements.wrapper.addEventListener('dragenter', function (e) {
            e.preventDefault();
            dragCounter++;
            state.elements.dragOverlay.classList.add('active');
        });
        state.elements.wrapper.addEventListener('dragleave', function (e) {
            e.preventDefault();
            dragCounter--;
            if (dragCounter === 0) state.elements.dragOverlay.classList.remove('active');
        });
        state.elements.wrapper.addEventListener('dragover', function (e) {
            e.preventDefault();
        });
        state.elements.wrapper.addEventListener('drop', function (e) {
            e.preventDefault();
            dragCounter = 0;
            state.elements.dragOverlay.classList.remove('active');
            if (e.dataTransfer.files.length > 0) addFiles(e.dataTransfer.files);
        });
    }

    function setSending(isSending) {
        state.isSending = isSending;
        if (state.elements.sendBtn) {
            if (isSending) {
                state.elements.sendBtn.classList.add('loading');
                state.elements.sendBtn.disabled = true;
            } else {
                state.elements.sendBtn.classList.remove('loading');
            }
        }
        updateState();
    }

    function resetComposer() {
        if (state.elements.textarea) {
            state.elements.textarea.value = '';
            state.elements.textarea.style.height = 'auto';
        }
        state.attachedFiles = [];
        renderPreviews();
        clearReply();
        updateState();
    }

    function clearReply() {
        state.replyTo = null;
        if (state.elements.replyBanner) state.elements.replyBanner.style.display = 'none';
        if (state.elements.replyToIdInput) state.elements.replyToIdInput.value = '';
    }

    function setReply(messageId, senderName, previewText) {
        state.replyTo = { id: messageId, sender: senderName, preview: previewText };
        if (state.elements.replyBanner) state.elements.replyBanner.style.display = 'flex';
        if (state.elements.replySender) state.elements.replySender.textContent = senderName;
        if (state.elements.replyPreview) state.elements.replyPreview.textContent = previewText;
        if (state.elements.replyToIdInput) state.elements.replyToIdInput.value = messageId;
        if (state.elements.textarea) state.elements.textarea.focus();
    }

    function showError(message) {
        var existing = document.querySelector('.conv-composer-error');
        if (existing) existing.remove();
        var errorDiv = document.createElement('div');
        errorDiv.className = 'conv-composer-error';
        errorDiv.style.cssText = 'background:#fef2f2;color:#dc2626;padding:10px 14px;border-radius:8px;font-size:13px;font-weight:500;margin-bottom:10px;border:1px solid #fecaca;display:flex;align-items:center;gap:8px;';
        errorDiv.innerHTML = '<i class="ti ti-alert-circle" style="font-size:16px;flex-shrink:0;"></i><span>' + U.escapeHtml(message) + '</span>';
        if (state.elements.wrapper) {
            state.elements.wrapper.insertBefore(errorDiv, state.elements.wrapper.firstChild);
            setTimeout(function () {
                if (errorDiv && errorDiv.parentNode) errorDiv.remove();
            }, 5000);
        }
    }

    function sendRequest(tempId, body, files) {
        var formData = new FormData(state.elements.form);
        formData.set('body', body);
        files.forEach(function (file) {
            formData.append('attachments[]', file);
        });

        API.upload(state.elements.form.action, formData, 'send_message_' + tempId)
            .then(function (result) {
                if (!result) return;
                if (result.success && result.html) {
                    Workspace.replaceOptimisticBubble(tempId, result.html);
                    Events.emit(C.EVENTS.MESSAGE_SENT, {
                        messageId: result.message_id || (result.data ? result.data.id : null),
                        tempId: tempId,
                        isOwn: true,
                        data: result
                    });
                } else {
                    throw new Error(result.message || 'Failed to send message');
                }
            })
            .catch(function (error) {
                console.error('[Conversation.Composer] Send failed:', error);
                var msg = 'Failed to send message. Please try again.';
                if (error.message && error.message.indexOf('HTTP_ERROR_') === 0) {
                    if (error.message === 'HTTP_ERROR_401' || error.message === 'HTTP_ERROR_403') msg = 'You do not have permission to send messages.';
                    else if (error.message === 'HTTP_ERROR_422') msg = 'Message validation failed.';
                    else if (error.message === 'HTTP_ERROR_413') msg = 'File too large.';
                    else if (error.message === 'HTTP_ERROR_500') msg = 'Server error. Please try again later.';
                }
                Workspace.updateBubbleStatus(tempId, 'failed');
                showError(msg);
            })
            .finally(function () {
                setSending(false);
            });
    }

    function handleSubmit(e) {
        e.preventDefault();
        if (state.isSending) return;
        if (!state.elements.form) return;
        var body = state.elements.textarea ? state.elements.textarea.value.trim() : '';
        var files = state.attachedFiles.slice();
        if (!body && files.length === 0) return;

        var tempId = generateTempId();
        var optimisticHtml = buildOptimisticBubbleHTML(body, files, tempId, state.replyTo);
        Workspace.appendOptimisticBubble(optimisticHtml, tempId, body, files);
        resetComposer();
        setSending(true);
        sendRequest(tempId, body, files);
    }

    function handleRetry(e) {
        if (!e.detail || !e.detail.tempId) return;
        var tempId = e.detail.tempId;
        var pending = Workspace.getPendingMessage(tempId);
        if (!pending) return;
        Workspace.updateBubbleStatus(tempId, 'sending');
        setSending(true);
        sendRequest(tempId, pending.body, pending.files);
    }

    function handleDelete(e) {
        if (!e.detail || !e.detail.tempId) return;
        Workspace.removeBubble(e.detail.tempId);
    }

    function handleReplyClick(e) {
        var btn = e.target.closest('[data-action="reply"]');
        if (!btn) return;
        var msgId = btn.getAttribute('data-message-id');
        var row = btn.closest('.conv-msg-row');
        if (!row) return;

        var senderName = row.getAttribute('data-sender-name');
        if (!senderName) {
            var senderEl = row.querySelector('.conv-msg-sender');
            senderName = senderEl ? senderEl.textContent.trim() : getCurrentUserName();
        }

        var textEl = row.querySelector('.conv-msg-text');
        var previewText = textEl ? textEl.textContent.trim() : 'Attachment';

        if (previewText.length > 80) {
            previewText = previewText.substring(0, 80) + '...';
        }

        setReply(msgId, senderName, previewText);
    }

    function bind() {
        cacheElements();
        if (!state.elements.form || state.bound) return;
        state.bound = true;

        state.elements.form.addEventListener('submit', handleSubmit);

        if (state.elements.textarea) {
            state.elements.textarea.addEventListener('input', function () {
                autoResize();
                updateState();
            });
            state.elements.textarea.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    if (!state.elements.sendBtn.disabled && !state.isSending) {
                        state.elements.form.requestSubmit();
                    }
                }
            });
        }

        if (state.elements.attachBtn) {
            state.elements.attachBtn.addEventListener('click', triggerFilePicker);
        }

        if (state.elements.previewContainer) {
            state.elements.previewContainer.addEventListener('click', function (e) {
                var btn = e.target.closest('.conv-att-remove');
                if (btn) {
                    state.attachedFiles.splice(parseInt(btn.dataset.index, 10), 1);
                    renderPreviews();
                }
            });
        }

        if (state.elements.replyCancel) {
            state.elements.replyCancel.addEventListener('click', clearReply);
        }

        document.addEventListener('click', handleReplyClick);

        bindDragAndDrop();
        updateState();

        Events.on('conv:retry-message', handleRetry);
        Events.on('conv:delete-optimistic', handleDelete);
    }

    window.Conversation.Composer = {
        init: function () {
            bind();
            Events.on(C.EVENTS.CONVERSATION_LOADED, function () {
                state.bound = false;
                state.attachedFiles = [];
                state.isSending = false;
                clearReply();
                bind();
            });
        },
        bind: bind,
        reset: function () {
            state.attachedFiles = [];
            renderPreviews();
            clearReply();
        },
        focus: function () {
            if (state.elements.textarea) state.elements.textarea.focus();
        },
        isSending: function () {
            return state.isSending;
        },
        setReply: setReply,
        clearReply: clearReply
    };
})();