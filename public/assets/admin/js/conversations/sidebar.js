(function () {
    window.Conversation = window.Conversation || {};
    var C = window.Conversation.Constants;

    function updateSidebarEmptyState(visibleCount, query, activeFilter) {
        var emptyEl = document.getElementById('convSidebarEmptyFilter');
        if (!emptyEl) return;

        if (visibleCount > 0) {
            emptyEl.style.display = 'none';
            return;
        }

        var iconClass = 'ti ti-message-off';
        var title = 'No conversations found';
        var desc = 'When you have active discussions, they will appear here.';

        if (query) {
            iconClass = 'ti ti-search-off';
            title = 'No results found';
            desc = 'No conversations match "' + query + '".';
        } else if (activeFilter === 'unread') {
            iconClass = 'ti ti-eye-off';
            title = 'No unread conversations';
            desc = 'You are all caught up!';
        } else if (activeFilter === 'archived') {
            iconClass = 'ti ti-archive-off';
            title = 'No archived conversations';
            desc = 'Archived conversations will appear here.';
        }

        var iconEl = emptyEl.querySelector('.conv-empty-icon i');
        var titleEl = emptyEl.querySelector('h3');
        var descEl = emptyEl.querySelector('p');

        if (iconEl) iconEl.className = iconClass;
        if (titleEl) titleEl.textContent = title;
        if (descEl) descEl.textContent = desc;

        emptyEl.style.display = '';
    }

    function filterConversations(query, activeFilter) {
        var cards = document.querySelectorAll(C.SELECTORS.CARD_LINK);
        query = (query || '').toLowerCase();
        activeFilter = activeFilter || 'all';
        var visibleCount = 0;

        cards.forEach(function (card) {
            var title = card.querySelector('.conv-card-title');
            var preview = card.querySelector('.conv-card-preview');
            var badge = card.querySelector('[data-unread-badge]');
            var status = card.getAttribute('data-status');
            var hasUnread = !!badge;

            var titleText = title ? title.textContent.toLowerCase() : '';
            var previewText = preview ? preview.textContent.toLowerCase() : '';
            var cardText = card.textContent.toLowerCase();

            var matchSearch = !query ||
                titleText.indexOf(query) !== -1 ||
                previewText.indexOf(query) !== -1 ||
                cardText.indexOf(query) !== -1;

            var matchFilter = activeFilter === 'all' ||
                (activeFilter === 'unread' && hasUnread) ||
                (activeFilter === 'archived' && status === 'archived');

            var isVisible = matchSearch && matchFilter;
            card.style.display = isVisible ? '' : 'none';
            if (isVisible) visibleCount++;
        });

        updateSidebarEmptyState(visibleCount, query, activeFilter);
    }

    window.Conversation.Sidebar = {
        currentFilter: 'all',
        currentQuery: '',
        
        init: function () {
            this.bindCardClicks();
            this.bindSearch();
            this.bindFilters();
            this.bindRefresh();
            this.bindSortDropdown();
            this.bindMobileSidebar();
            this.bindMessageDeletedEvent();
        },
        
        bindCardClicks: function () {
            document.addEventListener('click', function (e) {
                var card = e.target.closest(C.SELECTORS.CARD_LINK);
                if (!card) return;
                
                e.preventDefault();
                e.stopPropagation();
                
                var id = card.getAttribute('data-conversation-id');
                var url = card.getAttribute('href') || card.getAttribute('data-url');
                
                if (!id) return;
                
                window.Conversation.Events.emit(C.EVENTS.CONVERSATION_SELECTED, {
                    id: parseInt(id, 10),
                    url: url
                });
            });
        },
        
        bindSearch: function () {
            var self = this;
            var input = document.querySelector(C.SELECTORS.SEARCH_INPUT);
            var clear = document.querySelector(C.SELECTORS.SEARCH_CLEAR);
            var searchIcon = document.querySelector('.conv-spa-search-icon');
            
            if (!input) return;

            var handleSearch = window.Conversation.Utils.debounce(function () {
                self.currentQuery = input.value.trim();
                filterConversations(self.currentQuery, self.currentFilter);
                
                var hasValue = self.currentQuery.length > 0;
                if (clear) clear.style.display = hasValue ? '' : 'none';
                if (searchIcon) searchIcon.style.display = hasValue ? 'none' : '';
            }, C.TIMEOUTS.DEBOUNCE_SEARCH);

            input.addEventListener('input', handleSearch);

            if (clear) {
                clear.addEventListener('click', function () {
                    input.value = '';
                    self.currentQuery = '';
                    if (clear) clear.style.display = 'none';
                    if (searchIcon) searchIcon.style.display = '';
                    filterConversations('', self.currentFilter);
                    input.focus();
                });
            }

            var initialValue = input.value.trim();
            if (initialValue) {
                self.currentQuery = initialValue;
                if (clear) clear.style.display = '';
                if (searchIcon) searchIcon.style.display = 'none';
                filterConversations(initialValue, self.currentFilter);
            } else {
                if (clear) clear.style.display = 'none';
                if (searchIcon) searchIcon.style.display = '';
            }
        },
        
        bindFilters: function () {
            var self = this;
            var pills = document.querySelectorAll(C.SELECTORS.FILTER_PILLS);
            
            pills.forEach(function (pill) {
                pill.addEventListener('click', function () {
                    pills.forEach(function (p) { p.classList.remove('is-active'); });
                    pill.classList.add('is-active');
                    self.currentFilter = pill.getAttribute('data-filter') || 'all';
                    filterConversations(self.currentQuery, self.currentFilter);
                });
            });
        },

        bindRefresh: function () {
            var btn = document.querySelector(C.SELECTORS.REFRESH_BTN);
            if (btn) {
                btn.addEventListener('click', function () {
                    window.location.reload();
                });
            }
        },
        
        bindSortDropdown: function () {
            var dropdown = document.querySelector(C.SELECTORS.SORT_DROPDOWN);
            var trigger = document.querySelector(C.SELECTORS.SORT_TRIGGER);
            var menu = document.querySelector(C.SELECTORS.SORT_MENU);
            
            if (!dropdown || !trigger || !menu) return;
            
            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                dropdown.classList.toggle('open');
            });
            
            document.addEventListener('click', function (e) {
                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove('open');
                }
            });
            
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    dropdown.classList.remove('open');
                }
            });
            
            menu.querySelectorAll('.conv-dropdown-item').forEach(function (item) {
                item.addEventListener('click', function () {
                    menu.querySelectorAll('.conv-dropdown-item').forEach(function (i) {
                        i.classList.remove('active');
                    });
                    item.classList.add('active');
                    
                    var label = item.textContent.trim();
                    var span = trigger.querySelector('span');
                    if (span) span.textContent = label;
                    
                    dropdown.classList.remove('open');
                });
            });
        },
        
        bindMobileSidebar: function () {
            var sidebar = document.querySelector(C.SELECTORS.SIDEBAR);
            var overlay = document.querySelector(C.SELECTORS.SIDEBAR_OVERLAY);
            
            if (!sidebar || !overlay) return;
            
            function closeSidebar() {
                sidebar.classList.remove('is-open');
                overlay.classList.remove('is-visible');
                document.body.style.overflow = '';
            }
            
            overlay.addEventListener('click', closeSidebar);
            
            window.toggleConvSidebar = function () {
                if (sidebar.classList.contains('is-open')) {
                    closeSidebar();
                } else {
                    sidebar.classList.add('is-open');
                    overlay.classList.add('is-visible');
                    document.body.style.overflow = 'hidden';
                }
            };
        },

        bindMessageDeletedEvent: function () {
            window.Conversation.Events.on('conversation:message:deleted', function (data) {
                if (!data || !data.conversationId) return;
                
                var cardLink = document.querySelector(C.SELECTORS.CARD_LINK + '[data-conversation-id="' + data.conversationId + '"]');
                if (!cardLink) return;
                
                // Optimistic UI update for sidebar without relying on the /latest API endpoint
                // which may not be registered in routes/web.php (causing 404).
                
                // 1. Update preview text safely
                var previewEl = cardLink.querySelector('.conv-card-preview');
                if (previewEl) {
                    previewEl.innerHTML = '<span class="conv-preview-empty">Message deleted</span>';
                }
                
                // 2. Decrement unread count if applicable
                var badge = cardLink.querySelector('[data-unread-badge]');
                if (badge) {
                    var currentCount = parseInt(cardLink.getAttribute('data-unread-count') || '0', 10);
                    if (currentCount > 0) {
                        var newCount = currentCount - 1;
                        cardLink.setAttribute('data-unread-count', newCount.toString());
                        
                        if (newCount === 0) {
                            badge.remove();
                        } else {
                            badge.textContent = newCount > 99 ? '99+' : newCount;
                        }
                    }
                }
            });
        },
        
        updateActiveState: function (id) {
            document.querySelectorAll(C.SELECTORS.CARD).forEach(function (c) {
                c.classList.remove('conv-card-active');
            });
            
            if (!id) return;
            
            var activeLink = document.querySelector(C.SELECTORS.CARD_LINK + '[data-conversation-id="' + id + '"]');
            if (activeLink) {
                var card = activeLink.querySelector(C.SELECTORS.CARD);
                if (card) card.classList.add('conv-card-active');
                
                try {
                    activeLink.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                } catch (e) {}
            }
        }
    };
})();