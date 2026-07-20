<div class="conv-msg-search-bar" x-data="convMessageSearch()" x-show="isOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" @conv:open-message-search.window="open()" @keydown.escape.window="isOpen && close()">

    <div class="conv-msg-search-inner">
        {{-- Left: Input Area --}}
        <div class="conv-msg-search-input-wrap">
            <i class="ti ti-search conv-msg-search-icon"></i>
            <input type="text" x-ref="searchInput" x-model="query" @input.debounce.250ms="search()" @keydown.enter.prevent="next()" @keydown.shift.enter.prevent="prev()" placeholder="Search in conversation..." class="conv-msg-search-input">

            <button x-show="query.length > 0" @click="clear()" class="conv-msg-search-clear" title="Clear">
                <i class="ti ti-x"></i>
            </button>

            <div x-show="isLoading" class="conv-msg-search-spinner-wrap">
                <div class="conv-msg-search-spinner"></div>
            </div>
        </div>

        {{-- Right: Controls & Counter --}}
        <div class="conv-msg-search-controls" x-show="query.length > 0" x-cloak>
            <span class="conv-msg-search-counter" x-text="counterText"></span>
            <div class="conv-msg-search-nav">
                <button @click="prev()" class="conv-msg-search-btn" title="Previous (Shift+Enter)">
                    <i class="ti ti-chevron-up"></i>
                </button>
                <button @click="next()" class="conv-msg-search-btn" title="Next (Enter)">
                    <i class="ti ti-chevron-down"></i>
                </button>
            </div>
        </div>

        {{-- Close Button --}}
        <button @click="close()" class="conv-msg-search-close" title="Close (ESC)">
            <i class="ti ti-x"></i>
        </button>
    </div>
</div>

@once
@push('styles')
<style>
    [x-cloak] {
        display: none !important;
    }

    .conv-msg-search-bar {
        position: sticky;
        top: 16px;
        z-index: 30;
        margin: 0 auto 16px auto;
        max-width: 600px;
        width: 100%;
        background: #ffffff;
        border: 1px solid #ECECEC;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06), 0 2px 8px rgba(15, 23, 42, 0.04);
        font-family: 'Plus Jakarta Sans', sans-serif;
        overflow: hidden;
    }

    .conv-msg-search-inner {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px 10px 16px;
    }

    .conv-msg-search-input-wrap {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 10px;
        position: relative;
    }

    .conv-msg-search-icon {
        font-size: 18px;
        color: #94a3b8;
        flex-shrink: 0;
    }

    .conv-msg-search-input {
        flex: 1;
        border: none;
        outline: none;
        background: transparent;
        font-size: 14px;
        font-weight: 500;
        color: #0f172a;
        padding: 6px 0;
        min-width: 0;
        font-family: inherit;
    }

    .conv-msg-search-input::placeholder {
        color: #94a3b8;
        font-weight: 400;
    }

    .conv-msg-search-clear,
    .conv-msg-search-close {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: #64748b;
        cursor: pointer;
        transition: all 0.15s ease;
        flex-shrink: 0;
    }

    .conv-msg-search-clear:hover,
    .conv-msg-search-close:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .conv-msg-search-clear i,
    .conv-msg-search-close i {
        font-size: 18px;
        stroke-width: 2;
    }

    .conv-msg-search-spinner-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
    }

    .conv-msg-search-spinner {
        width: 16px;
        height: 16px;
        border: 2px solid #e2e8f0;
        border-top-color: #f8773c;
        border-radius: 50%;
        animation: conv-spin 0.6s linear infinite;
    }

    @keyframes conv-spin {
        to {
            transform: rotate(360deg);
        }
    }

    .conv-msg-search-controls {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-left: 12px;
        border-left: 1px solid #f1f5f9;
    }

    .conv-msg-search-counter {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        white-space: nowrap;
        min-width: 60px;
        text-align: center;
    }

    .conv-msg-search-nav {
        display: flex;
        gap: 4px;
    }

    .conv-msg-search-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .conv-msg-search-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #f8773c;
    }

    .conv-msg-search-btn i {
        font-size: 16px;
        stroke-width: 2;
    }

    /* Highlight Styles inside Chat Bubbles */
    .conv-search-highlight {
        background: #fef3c7;
        color: #0f172a;
        border-radius: 4px;
        padding: 0 2px;
        box-shadow: 0 0 0 1px rgba(245, 158, 11, 0.3);
        transition: all 0.2s ease;
    }

    .conv-search-highlight-active {
        background: #f8773c !important;
        color: #ffffff !important;
        box-shadow: 0 0 0 2px rgba(248, 119, 60, 0.4) !important;
    }

</style>
@endpush
@endonce

@once
@push('admin-scripts')
<script>
    function convMessageSearch() {
        return {
            isOpen: false
            , query: ''
            , isLoading: false
            , matches: []
            , currentIndex: -1,

            get counterText() {
                if (this.query.trim().length < 2) return '';
                if (this.isLoading) return 'Searching...';
                if (this.matches.length === 0) return 'No results';
                return `${this.currentIndex + 1} of ${this.matches.length}`;
            },

            open() {
                this.isOpen = true;
                this.$nextTick(() => {
                    if (this.$refs.searchInput) this.$refs.searchInput.focus();
                });
            },

            close() {
                this.isOpen = false;
                this.clear();
            },

            clear() {
                this.query = '';
                this.clearHighlights();
                this.matches = [];
                this.currentIndex = -1;
                if (this.isOpen && this.$refs.searchInput) {
                    this.$refs.searchInput.focus();
                }
            },

            search() {
                this.clearHighlights();
                this.matches = [];
                this.currentIndex = -1;

                if (this.query.trim().length < 2) return;

                this.isLoading = true;
                setTimeout(() => {
                    this.highlightMatches();
                    this.isLoading = false;
                    if (this.matches.length > 0) {
                        this.currentIndex = 0;
                        this.scrollToCurrent();
                    }
                }, 150);
            },

            highlightMatches() {
                const chatArea = document.getElementById('convChatArea') || document.getElementById('chatMessages');
                if (!chatArea) return;

                // Target the specific text containers inside the bubbles
                const textElements = chatArea.querySelectorAll('.ws-msg-text, .msg-text, .message-text');
                const safeQuery = this.query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                const regex = new RegExp(`(${safeQuery})`, 'gi');

                textElements.forEach(el => {
                    this.walkAndHighlight(el, regex);
                });

                this.matches = Array.from(chatArea.querySelectorAll('.conv-search-highlight'));
            },

            walkAndHighlight(node, regex) {
                if (node.nodeType === Node.TEXT_NODE) {
                    if (regex.test(node.textContent)) {
                        const span = document.createElement('span');
                        span.innerHTML = node.textContent.replace(regex, '<mark class="conv-search-highlight">$1</mark>');
                        node.parentNode.replaceChild(span, node);
                    }
                } else if (node.nodeType === Node.ELEMENT_NODE && node.tagName !== 'MARK' && !node.classList.contains('conv-msg-search-bar')) {
                    Array.from(node.childNodes).forEach(child => this.walkAndHighlight(child, regex));
                }
            },

            clearHighlights() {
                const chatArea = document.getElementById('convChatArea') || document.getElementById('chatMessages');
                if (!chatArea) return;

                chatArea.querySelectorAll('.conv-search-highlight').forEach(mark => {
                    const parent = mark.parentNode;
                    parent.replaceChild(document.createTextNode(mark.textContent), mark);
                    parent.normalize();
                });

                chatArea.querySelectorAll('span').forEach(span => {
                    if (span.childNodes.length === 1 && span.childNodes[0].nodeType === Node.TEXT_NODE && !span.className) {
                        span.parentNode.replaceChild(span.childNodes[0], span);
                        span.parentNode ? .normalize();
                    }
                });
            },

            next() {
                if (this.matches.length === 0) return;
                this.currentIndex = (this.currentIndex + 1) % this.matches.length;
                this.scrollToCurrent();
            },

            prev() {
                if (this.matches.length === 0) return;
                this.currentIndex = (this.currentIndex - 1 + this.matches.length) % this.matches.length;
                this.scrollToCurrent();
            },

            scrollToCurrent() {
                this.matches.forEach((m, i) => m.classList.toggle('conv-search-highlight-active', i === this.currentIndex));
                if (this.matches[this.currentIndex]) {
                    this.matches[this.currentIndex].scrollIntoView({
                        behavior: 'smooth'
                        , block: 'center'
                    });
                }
            }
        }
    }

</script>
@endpush
@endonce
