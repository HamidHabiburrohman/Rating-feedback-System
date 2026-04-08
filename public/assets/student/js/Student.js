document.addEventListener('DOMContentLoaded', function() {
    TabNavigation.init();
    ReadMore.init();
    FavoriteButton.init();
    DropdownMenu.init();
    ReportModal.init();
});

const TabNavigation = {
    init() {
        this.tabs = document.querySelectorAll('.tab-nav__item');
        this.panels = document.querySelectorAll('.tab-panel');
        this.bindEvents();
    },

    bindEvents() {
        this.tabs.forEach(tab => {
            tab.addEventListener('click', (e) => this.handleTabClick(e));
        });
    },

    handleTabClick(e) {
        const clickedTab = e.currentTarget;
        const tabId = clickedTab.dataset.tab;

        this.tabs.forEach(tab => {
            tab.classList.remove('tab-nav__item--active');
            tab.setAttribute('aria-selected', 'false');
        });

        clickedTab.classList.add('tab-nav__item--active');
        clickedTab.setAttribute('aria-selected', 'true');

        this.switchPanel(tabId);
    },

    switchPanel(tabId) {
        this.panels.forEach(panel => {
            panel.classList.remove('tab-panel--active');
        });

        const targetPanel = document.querySelector(`[data-panel="${tabId}"]`);
        if (targetPanel) {
            targetPanel.classList.add('tab-panel--active');
        }
    }
};

const ReadMore = {
    init() {
        this.button = document.getElementById('readMoreBtn');
        this.content = document.querySelector('.description-section__content');
        
        if (this.button && this.content) {
            this.bindEvents();
            this.setupInitialState();
        }
    },

    bindEvents() {
        this.button.addEventListener('click', () => this.toggle());
    },

    setupInitialState() {
        const paragraph = this.content.querySelector('p');
        if (paragraph && paragraph.textContent.length > 280) {
            this.fullText = paragraph.textContent;
            this.truncateContent();
        } else {
            this.button.style.display = 'none';
        }
    },

    truncateContent() {
        const paragraph = this.content.querySelector('p');
        const truncatedText = this.fullText.substring(0, 180).trim() + '...';
        paragraph.textContent = truncatedText;
        this.isTruncated = true;
    },

    toggle() {
        const paragraph = this.content.querySelector('p');
        const buttonText = this.button.querySelector('span');
        
        if (this.isTruncated) {
            paragraph.textContent = this.fullText;
            buttonText.textContent = 'Read less';
            this.button.classList.add('read-more-btn--expanded');
            this.isTruncated = false;
        } else {
            this.truncateContent();
            buttonText.textContent = 'Read more';
            this.button.classList.remove('read-more-btn--expanded');
        }
    }
};

const FavoriteButton = {
    init() {
        this.button = document.getElementById('favoriteBtn');
        this.isFavorite = false;
        
        if (this.button) {
            this.bindEvents();
        }
    },

    bindEvents() {
        this.button.addEventListener('click', () => this.toggle());
    },

    toggle() {
        this.isFavorite = !this.isFavorite;
        const svg = this.button.querySelector('svg');
        const path = svg.querySelector('path');
        
        if (this.isFavorite) {
            path.setAttribute('fill', '#E87A3D');
            path.setAttribute('stroke', '#E87A3D');
            this.button.setAttribute('aria-label', 'Remove from favorites');
            Toast.show('Added to favorites');
        } else {
            path.setAttribute('fill', 'none');
            path.setAttribute('stroke', 'currentColor');
            this.button.setAttribute('aria-label', 'Add to favorites');
            Toast.show('Removed from favorites');
        }

        this.animateButton();
    },

    animateButton() {
        this.button.style.transform = 'scale(0.9)';
        setTimeout(() => {
            this.button.style.transform = '';
        }, 150);
    }
};

const DropdownMenu = {
    init() {
        this.trigger = document.getElementById('moreBtn');
        this.menu = document.getElementById('moreDropdown');
        
        if (this.trigger && this.menu) {
            this.bindEvents();
        }
    },

    bindEvents() {
        this.trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggle();
        });

        document.addEventListener('click', (e) => {
            if (!this.menu.contains(e.target) && !this.trigger.contains(e.target)) {
                this.close();
            }
        });

        const items = this.menu.querySelectorAll('.dropdown-menu__item');
        items.forEach(item => {
            item.addEventListener('click', (e) => this.handleItemClick(e));
        });
    },

    toggle() {
        this.menu.classList.toggle('dropdown-menu--active');
    },

    close() {
        this.menu.classList.remove('dropdown-menu--active');
    },

    handleItemClick(e) {
        const action = e.currentTarget.dataset.action;
        this.close();

        switch(action) {
            case 'save':
                Toast.show('Saved to your collection');
                break;
            case 'copy':
                navigator.clipboard.writeText(window.location.href).then(() => {
                    Toast.show('Link copied to clipboard');
                });
                break;
            case 'report':
                ReportModal.open();
                break;
        }
    }
};

const ReportModal = {
    init() {
        this.modal = document.getElementById('reportModal');
        this.closeBtn = this.modal?.querySelector('.modal__close');
        this.cancelBtn = document.getElementById('cancelReport');
        this.submitBtn = document.getElementById('submitReport');
        this.form = document.getElementById('reportForm');
        
        if (this.modal) {
            this.bindEvents();
        }
    },

    bindEvents() {
        this.closeBtn.addEventListener('click', () => this.close());
        this.cancelBtn.addEventListener('click', () => this.close());
        this.submitBtn.addEventListener('click', () => this.submit());
        
        this.modal.querySelector('.modal__backdrop').addEventListener('click', () => this.close());
        
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen()) {
                this.close();
            }
        });
    },

    open() {
        this.modal.classList.add('modal--active');
        document.body.style.overflow = 'hidden';
    },

    close() {
        this.modal.classList.remove('modal--active');
        document.body.style.overflow = '';
        this.form.reset();
    },

    isOpen() {
        return this.modal.classList.contains('modal--active');
    },

    submit() {
        const reason = document.getElementById('reportReason').value;
        const details = document.getElementById('reportDetails').value;
        
        if (!reason) {
            Toast.show('Please select a reason');
            return;
        }
        
        Toast.show('Report submitted successfully');
        this.close();
    }
};

const Toast = {
    show(message) {
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) {
            existingToast.remove();
        }
        
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.textContent = message;
        toast.style.cssText = `
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(20px);
            background: rgba(26, 26, 26, 0.95);
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            z-index: 10000;
            opacity: 0;
            transition: opacity 250ms ease, transform 250ms ease;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        `;
        
        document.body.appendChild(toast);
        
        requestAnimationFrame(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateX(-50%) translateY(0)';
        });
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(-50%) translateY(20px)';
            setTimeout(() => toast.remove(), 250);
        }, 2500);
    }
};