(function () {
    window.Conversation = window.Conversation || {};
    var activeRequests = {};

    function getHeaders(options) {
        options = options || {};
        var headers = {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': window.Conversation.Utils.getCsrfToken(),
            'Accept': options.accept || 'application/json'
        };
        return headers;
    }

    function executeRequest(url, options) {
        options = options || {};
        var requestId = options.requestId || url;
        if (activeRequests[requestId] && options.abortPrevious !== false) {
            if (activeRequests[requestId] instanceof AbortController) {
                activeRequests[requestId].abort();
            } else if (activeRequests[requestId] && typeof activeRequests[requestId].abort === 'function') {
                activeRequests[requestId].abort();
            }
        }
        var controller = new AbortController();
        activeRequests[requestId] = controller;
        var headers = getHeaders(options);
        var body = options.body;
        if (body && typeof body === 'object' && !(body instanceof FormData)) {
            headers['Content-Type'] = 'application/json';
            body = JSON.stringify(body);
        }
        return fetch(url, {
            method: options.method || 'GET',
            headers: headers,
            signal: controller.signal,
            credentials: 'same-origin',
            body: body
        }).then(function (response) {
            delete activeRequests[requestId];
            if (!response.ok) {
                throw new Error('HTTP_ERROR_' + response.status);
            }
            var contentType = response.headers.get('content-type') || '';
            if (contentType.indexOf('application/json') !== -1) {
                return response.json();
            }
            return response.text();
        }).catch(function (error) {
            delete activeRequests[requestId];
            if (error.name === 'AbortError') return null;
            console.error('[Conversation.API] Request failed:', error);
            throw error;
        });
    }

    function uploadWithProgress(url, formData, onProgress, requestId) {
        return new Promise(function (resolve, reject) {
            var xhr = new XMLHttpRequest();
            activeRequests[requestId] = xhr;

            xhr.upload.addEventListener('progress', function (e) {
                if (e.lengthComputable && typeof onProgress === 'function') {
                    onProgress((e.loaded / e.total) * 100);
                }
            });

            xhr.addEventListener('load', function () {
                delete activeRequests[requestId];
                if (xhr.status >= 200 && xhr.status < 300) {
                    var contentType = xhr.getResponseHeader('content-type') || '';
                    if (contentType.indexOf('application/json') !== -1) {
                        resolve(JSON.parse(xhr.responseText));
                    } else {
                        resolve(xhr.responseText);
                    }
                } else {
                    reject(new Error('HTTP_ERROR_' + xhr.status));
                }
            });

            xhr.addEventListener('error', function () {
                delete activeRequests[requestId];
                reject(new Error('Network error'));
            });

            xhr.addEventListener('abort', function () {
                delete activeRequests[requestId];
                reject(new DOMException('Aborted', 'AbortError'));
            });

            xhr.open('POST', url, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('X-CSRF-TOKEN', window.Conversation.Utils.getCsrfToken());
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.withCredentials = true;
            xhr.send(formData);
        });
    }

    window.Conversation.API = {
        request: executeRequest,
        get: function (url, options) { return executeRequest(url, Object.assign({ method: 'GET' }, options || {})); },
        post: function (url, data, options) { return executeRequest(url, Object.assign({ method: 'POST', body: data }, options || {})); },
        put: function (url, data, options) { return executeRequest(url, Object.assign({ method: 'PUT', body: data }, options || {})); },
        patch: function (url, data, options) { return executeRequest(url, Object.assign({ method: 'PATCH', body: data }, options || {})); },
        delete: function (url, options) { return executeRequest(url, Object.assign({ method: 'DELETE' }, options || {})); },
        getHtml: function (url, requestId) {
            return executeRequest(url, { method: 'GET', accept: 'text/html', abortPrevious: true, requestId: requestId || 'conversation_load' });
        },
        upload: function (url, formData, requestId) {
            return executeRequest(url, { method: 'POST', body: formData, accept: 'application/json', requestId: requestId || 'upload_' + Date.now() });
        },
        uploadWithProgress: uploadWithProgress,
        submitForm: function (form, requestId) {
            var formData = new FormData(form);
            return executeRequest(form.action, { method: form.method || 'POST', body: formData, accept: 'application/json', requestId: requestId || 'form_' + form.id });
        },
        abortAll: function () {
            Object.keys(activeRequests).forEach(function (key) {
                if (activeRequests[key] instanceof AbortController) {
                    activeRequests[key].abort();
                } else if (activeRequests[key] && typeof activeRequests[key].abort === 'function') {
                    activeRequests[key].abort();
                }
                delete activeRequests[key];
            });
        }
    };
})();