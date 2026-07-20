(function () {
    window.Conversation = window.Conversation || {};
    var C = window.Conversation.Constants;
    var U = window.Conversation.Utils;
    var Events = window.Conversation.Events;

    var state = {
        queue: [],
        maxSize: C.CONFIG.MAX_FILE_SIZE,
        allowedTypes: C.CONFIG.ALLOWED_TYPES,
        activeUploads: {}
    };

    function getFileExtension(filename) {
        return filename.split('.').pop().toLowerCase();
    }

    function getExtIcon(ext) {
        var icons = {
            pdf: 'ti-file-text', doc: 'ti-file-word', docx: 'ti-file-word',
            xls: 'ti-file-excel', xlsx: 'ti-file-excel',
            jpg: 'ti-photo', jpeg: 'ti-photo', png: 'ti-photo', gif: 'ti-photo', webp: 'ti-photo'
        };
        return icons[ext] || 'ti-file';
    }

    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    function isAllowedType(file) {
        return state.allowedTypes.indexOf(file.type) !== -1 || file.type.startsWith('image/');
    }

    function isAllowedSize(file) {
        return file.size <= state.maxSize;
    }

    function generateQueueId() {
        return 'queue_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }

    function addToQueue(files) {
        var added = [];
        Array.from(files).forEach(function (file) {
            if (!isAllowedType(file)) {
                console.warn('[Conversation.Attachments] File type not allowed:', file.name);
                return;
            }
            if (!isAllowedSize(file)) {
                console.warn('[Conversation.Attachments] File too large:', file.name);
                return;
            }
            if (state.queue.length >= C.CONFIG.MAX_FILES) {
                console.warn('[Conversation.Attachments] Max files reached');
                return;
            }

            var queueItem = {
                id: generateQueueId(),
                file: file,
                name: file.name,
                size: file.size,
                type: file.type,
                ext: getFileExtension(file.name),
                status: C.UPLOAD_STATES.QUEUED,
                progress: 0,
                serverId: null,
                preview: null,
                error: null
            };

            if (file.type.startsWith('image/')) {
                queueItem.preview = URL.createObjectURL(file);
            }

            state.queue.push(queueItem);
            added.push(queueItem);
            Events.emit(C.EVENTS.ATTACHMENT_QUEUED, { item: queueItem });
        });

        if (added.length > 0) {
            renderQueue();
            Events.emit(C.EVENTS.ATTACHMENT_QUEUE_UPDATED, { count: state.queue.length });
        }
    }

    function removeFromQueue(queueId) {
        var index = state.queue.findIndex(function (item) { return item.id === queueId; });
        if (index !== -1) {
            var removed = state.queue.splice(index, 1)[0];
            
            if (state.activeUploads[queueId]) {
                state.activeUploads[queueId].abort();
                delete state.activeUploads[queueId];
                Events.emit(C.EVENTS.ATTACHMENT_CANCELLED, { item: removed });
            }

            if (removed.preview) URL.revokeObjectURL(removed.preview);
            
            renderQueue();
            Events.emit(C.EVENTS.ATTACHMENT_REMOVED, { item: removed });
            Events.emit(C.EVENTS.ATTACHMENT_QUEUE_UPDATED, { count: state.queue.length });
        }
    }

    function clearQueue() {
        Object.keys(state.activeUploads).forEach(function (queueId) {
            if (state.activeUploads[queueId] && typeof state.activeUploads[queueId].abort === 'function') {
                state.activeUploads[queueId].abort();
            }
        });
        state.activeUploads = {};

        state.queue.forEach(function (item) {
            if (item.preview) URL.revokeObjectURL(item.preview);
        });
        state.queue = [];
        renderQueue();
        Events.emit(C.EVENTS.ATTACHMENT_QUEUE_UPDATED, { count: 0 });
    }

    function getQueue() {
        return state.queue.slice();
    }

    function getQueueCount() {
        return state.queue.length;
    }

    function getUploadedIds() {
        return state.queue
            .filter(function (item) { return item.status === C.UPLOAD_STATES.UPLOADED && item.serverId; })
            .map(function (item) { return item.serverId; });
    }

    function clearUploaded() {
        var toRemove = state.queue.filter(function (item) { return item.status === C.UPLOAD_STATES.UPLOADED; });
        toRemove.forEach(function (item) {
            removeFromQueue(item.id);
        });
    }

    function uploadQueue() {
        var toUpload = state.queue.filter(function (item) {
            return item.status === C.UPLOAD_STATES.QUEUED || item.status === C.UPLOAD_STATES.FAILED || item.status === C.UPLOAD_STATES.CANCELLED;
        });

        toUpload.forEach(function (item) {
            uploadItem(item);
        });
    }

    function uploadItem(item) {
        var conversationId = window.Conversation.Workspace ? window.Conversation.Workspace.getCurrentId() : null;
        if (!conversationId) {
            console.error('[Conversation.Attachments] No active conversation');
            return;
        }

        var url = C.ROUTES.UPLOAD_ATTACHMENT(conversationId);
        var formData = new FormData();
        formData.append('attachments[]', item.file);
        formData.append('message_id', '0'); 

        item.status = C.UPLOAD_STATES.UPLOADING;
        item.progress = 0;
        item.error = null;
        renderQueue();
        Events.emit(C.EVENTS.ATTACHMENT_UPLOADING, { item: item });

        var xhr = new XMLHttpRequest();
        state.activeUploads[item.id] = xhr;

        xhr.upload.addEventListener('progress', function (e) {
            if (e.lengthComputable) {
                item.progress = (e.loaded / e.total) * 100;
                renderQueue();
            }
        });

        xhr.addEventListener('load', function () {
            delete state.activeUploads[item.id];
            if (xhr.status >= 200 && xhr.status < 300) {
                var response = {};
                try { response = JSON.parse(xhr.responseText); } catch (e) {}
                
                if (response.success && response.data && response.data.length > 0) {
                    item.serverId = response.data[0].id;
                    item.status = C.UPLOAD_STATES.UPLOADED;
                    item.progress = 100;
                    Events.emit(C.EVENTS.ATTACHMENT_UPLOADED, { item: item });
                } else {
                    item.status = C.UPLOAD_STATES.FAILED;
                    item.error = response.message || 'Upload failed';
                    Events.emit(C.EVENTS.ATTACHMENT_FAILED, { item: item });
                }
            } else {
                item.status = C.UPLOAD_STATES.FAILED;
                item.error = 'HTTP Error ' + xhr.status;
                Events.emit(C.EVENTS.ATTACHMENT_FAILED, { item: item });
            }
            renderQueue();
        });

        xhr.addEventListener('error', function () {
            delete state.activeUploads[item.id];
            item.status = C.UPLOAD_STATES.FAILED;
            item.error = 'Network error';
            renderQueue();
            Events.emit(C.EVENTS.ATTACHMENT_FAILED, { item: item });
        });

        xhr.addEventListener('abort', function () {
            delete state.activeUploads[item.id];
            item.status = C.UPLOAD_STATES.CANCELLED;
            renderQueue();
            Events.emit(C.EVENTS.ATTACHMENT_CANCELLED, { item: item });
        });

        xhr.open('POST', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('X-CSRF-TOKEN', U.getCsrfToken());
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.withCredentials = true;
        xhr.send(formData);
    }

    function cancelUpload(queueId) {
        if (state.activeUploads[queueId]) {
            state.activeUploads[queueId].abort();
        }
    }

    function retryUpload(queueId) {
        var item = state.queue.find(function (i) { return i.id === queueId; });
        if (item && (item.status === C.UPLOAD_STATES.FAILED || item.status === C.UPLOAD_STATES.CANCELLED)) {
            item.status = C.UPLOAD_STATES.QUEUED;
            item.progress = 0;
            item.error = null;
            renderQueue();
            uploadItem(item);
        }
    }

    function renderQueue() {
        var uploadQueueList = document.getElementById('convUploadQueueList');
        var composerPreviews = document.getElementById('convComposerPreviews');
        var composerFileInputs = document.getElementById('convFileInputsContainer');
        var uploadEmpty = document.getElementById('convUploadQueueEmpty');
        var uploadSubmitBtn = document.getElementById('convUploadSubmitBtn');

        if (composerFileInputs) {
            composerFileInputs.innerHTML = '';
            state.queue.forEach(function (item) {
                if (item.status === C.UPLOAD_STATES.UPLOADED && item.serverId) {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'attachment_ids[]';
                    input.value = item.serverId;
                    composerFileInputs.appendChild(input);
                }
            });
        }

        if (composerPreviews) {
            composerPreviews.innerHTML = '';
            if (state.queue.length === 0) {
                composerPreviews.style.display = 'none';
            } else {
                composerPreviews.style.display = 'flex';
                state.queue.forEach(function (item) {
                    var badge = document.createElement('div');
                    badge.className = 'conv-composer-att-badge';
                    var iconClass = item.type.startsWith('image/') ? 'ti-photo' : getExtIcon(item.ext);
                    badge.innerHTML = '<i class="ti ' + iconClass + '"></i><span>' + U.escapeHtml(item.name) + '</span>';
                    composerPreviews.appendChild(badge);
                });
            }
        }

        if (uploadQueueList && uploadEmpty) {
            uploadQueueList.innerHTML = '';
            if (state.queue.length === 0) {
                uploadEmpty.style.display = 'flex';
                if (uploadSubmitBtn) uploadSubmitBtn.disabled = true;
            } else {
                uploadEmpty.style.display = 'none';
                if (uploadSubmitBtn) uploadSubmitBtn.disabled = false;

                state.queue.forEach(function (item) {
                    var previewHtml = '';
                    if (item.type.startsWith('image/') && item.preview) {
                        previewHtml = '<div class="conv-file-thumb"><img src="' + item.preview + '" alt="' + U.escapeHtml(item.name) + '"></div>';
                    } else {
                        previewHtml = '<div class="conv-file-icon"><i class="ti ' + getExtIcon(item.ext) + '"></i></div>';
                    }

                    var statusHtml = '';
                    if (item.status === C.UPLOAD_STATES.UPLOADING) {
                        statusHtml = '<div class="conv-file-progress-wrap"><div class="conv-file-progress"><div class="conv-file-progress-bar" style="width: ' + item.progress + '%"></div></div><span class="conv-file-progress-text">' + Math.round(item.progress) + '%</span></div>';
                    } else if (item.status === C.UPLOAD_STATES.UPLOADED) {
                        statusHtml = '<div class="conv-file-status"><i class="ti ti-circle-check"></i> Uploaded</div>';
                    } else if (item.status === C.UPLOAD_STATES.FAILED) {
                        statusHtml = '<div class="conv-file-status error"><i class="ti ti-alert-circle"></i> ' + (item.error || 'Failed') + '</div>';
                    } else if (item.status === C.UPLOAD_STATES.CANCELLED) {
                        statusHtml = '<div class="conv-file-status error"><i class="ti ti-ban"></i> Cancelled</div>';
                    }

                    var actionsHtml = '';
                    if (item.status === C.UPLOAD_STATES.UPLOADING) {
                        actionsHtml = '<button type="button" class="conv-file-action" data-action="cancel" data-queue-id="' + item.id + '" title="Cancel"><i class="ti ti-x"></i></button>';
                    } else if (item.status === C.UPLOAD_STATES.FAILED || item.status === C.UPLOAD_STATES.CANCELLED) {
                        actionsHtml = '<button type="button" class="conv-file-action" data-action="retry" data-queue-id="' + item.id + '" title="Retry"><i class="ti ti-refresh"></i></button>' +
                                      '<button type="button" class="conv-file-action" data-action="remove" data-queue-id="' + item.id + '" title="Remove"><i class="ti ti-trash"></i></button>';
                    } else {
                        actionsHtml = '<button type="button" class="conv-file-action" data-action="remove" data-queue-id="' + item.id + '" title="Remove"><i class="ti ti-trash"></i></button>';
                    }

                    var row = document.createElement('div');
                    row.className = 'conv-file-item';
                    row.innerHTML = previewHtml +
                        '<div class="conv-file-info">' +
                            '<p class="conv-file-name" title="' + U.escapeHtml(item.name) + '">' + U.escapeHtml(item.name) + '</p>' +
                            '<span class="conv-file-meta">' + item.ext.toUpperCase() + ' • ' + formatFileSize(item.size) + '</span>' +
                            statusHtml +
                        '</div>' +
                        '<div class="conv-file-actions">' + actionsHtml + '</div>';
                    uploadQueueList.appendChild(row);
                });
            }
        }
    }

    function bindQueueActions() {
        document.addEventListener('click', function (e) {
            var actionBtn = e.target.closest('.conv-file-action');
            if (!actionBtn) return;
            
            var queueId = actionBtn.getAttribute('data-queue-id');
            var action = actionBtn.getAttribute('data-action');

            if (action === 'remove') removeFromQueue(queueId);
            if (action === 'cancel') cancelUpload(queueId);
            if (action === 'retry') retryUpload(queueId);
        });

        var uploadSubmitBtn = document.getElementById('convUploadSubmitBtn');
        if (uploadSubmitBtn) {
            uploadSubmitBtn.addEventListener('click', function () {
                uploadQueue();
            });
        }
    }

    function handleAttachmentSelected(data) {
        if (data && data.files) {
            addToQueue(data.files);
        }
    }

    function handleAttachmentPreview(data) {
        if (data && data.attachment) {
            Events.emit('attachment:preview-requested', { attachment: data.attachment });
        }
    }

    function handleAttachmentGallery() {
        Events.emit('attachment:gallery-requested', {});
    }

    window.Conversation.Attachments = {
        init: function () {
            bindQueueActions();
            Events.on(C.EVENTS.ATTACHMENT_SELECTED, handleAttachmentSelected);
            Events.on(C.EVENTS.ATTACHMENT_PREVIEW, handleAttachmentPreview);
            Events.on(C.EVENTS.ATTACHMENT_GALLERY, handleAttachmentGallery);
            
            Events.on(C.EVENTS.MESSAGE_SENT, function () {
                clearUploaded();
            });
        },
        addToQueue: addToQueue,
        removeFromQueue: removeFromQueue,
        clearQueue: clearQueue,
        getQueue: getQueue,
        getQueueCount: getQueueCount,
        getUploadedIds: getUploadedIds,
        clearUploaded: clearUploaded,
        uploadQueue: uploadQueue,
        cancelUpload: cancelUpload,
        retryUpload: retryUpload,
        isUploading: function () {
            return state.queue.some(function (item) { return item.status === C.UPLOAD_STATES.UPLOADING; });
        },
        reset: function () {
            clearQueue();
        }
    };
})();