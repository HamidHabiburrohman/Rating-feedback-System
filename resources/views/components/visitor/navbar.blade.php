<nav class="navbar">
    <div class="navbar__container">
        <a href="{{ route('home') }}" class="navbar__back-btn" aria-label="Go back">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
        
        <div class="navbar__actions">
            <button class="navbar__action-btn" aria-label="Share" id="shareBtn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 8C19.6569 8 21 6.65685 21 5C21 3.34315 19.6569 2 18 2C16.3431 2 15 3.34315 15 5C15 5.12548 15.0077 5.24907 15.0227 5.37025L8.08261 9.27126C7.56203 8.57832 6.75532 8.12229 5.85 8.12229C4.27525 8.12229 3 9.39754 3 10.9723C3 12.547 4.27525 13.8223 5.85 13.8223C6.75532 13.8223 7.56203 13.3662 8.08261 12.6733L15.0227 16.5743C15.0077 16.6955 15 16.8191 15 16.9446C15 18.5193 16.2753 19.7946 17.85 19.7946C19.4247 19.7946 20.7 18.5193 20.7 16.9446C20.7 15.3698 19.4247 14.0946 17.85 14.0946C16.9447 14.0946 16.138 14.5506 15.6174 15.2435L8.67729 11.3425C8.69231 11.2213 8.7 11.0977 8.7 10.9723C8.7 10.8468 8.69231 10.7232 8.67729 10.602L15.6174 6.701C16.138 7.39394 16.9447 7.84997 17.85 7.84997" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <button class="navbar__action-btn" aria-label="Add to favorites" id="favoriteBtn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 21.35L10.55 20.03C5.4 15.36 2 12.27 2 8.5C2 5.41 4.42 3 7.5 3C9.24 3 10.91 3.81 12 5.08C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.41 22 8.5C22 12.27 18.6 15.36 13.45 20.03L12 21.35Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <div class="navbar__dropdown">
                <button class="navbar__action-btn" aria-label="More options" id="moreBtn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="6" r="2" fill="currentColor"/>
                        <circle cx="12" cy="12" r="2" fill="currentColor"/>
                        <circle cx="12" cy="18" r="2" fill="currentColor"/>
                    </svg>
                </button>
                <div class="dropdown-menu" id="moreDropdown">
                    <button class="dropdown-menu__item" data-action="save">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H19C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 8V16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8 12H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Save</span>
                    </button>
                    <button class="dropdown-menu__item" data-action="copy">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="9" y="9" width="13" height="13" rx="2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5 15H4C3.46957 15 2.96086 14.7893 2.58579 14.4142C2.21071 14.0391 2 13.5304 2 13V4C2 3.46957 2.21071 2.96086 2.58579 2.58579C2.96086 2.21071 3.46957 2 4 2H13C13.5304 2 14.0391 2.21071 14.4142 2.58579C14.7893 2.96086 15 3.46957 15 4V5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Copy Link</span>
                    </button>
                    <div class="dropdown-menu__divider"></div>
                    <button class="dropdown-menu__item dropdown-menu__item--danger" data-action="report">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 9V13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 17H12.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10.29 3.86L1.82 18C1.64537 18.3024 1.55297 18.6453 1.55199 18.9945C1.55101 19.3437 1.64149 19.6871 1.81442 19.9905C1.98736 20.2939 2.23673 20.5467 2.53771 20.7239C2.83869 20.901 3.1808 20.9962 3.53 21H20.47C20.8192 20.9962 21.1613 20.901 21.4623 20.7239C21.7633 20.5467 22.0126 20.2939 22.1856 19.9905C22.3585 19.6871 22.449 19.3437 22.448 18.9945C22.447 18.6453 22.3546 18.3024 22.18 18L13.71 3.86C13.5317 3.56611 13.2807 3.32312 12.9822 3.15548C12.6838 2.98785 12.3486 2.90134 12.008 2.90494C11.6674 2.90854 11.3341 2.99212 11.0391 3.14739C10.7441 3.30265 10.4973 3.52414 10.3226 3.7905L10.29 3.86Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Report</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>