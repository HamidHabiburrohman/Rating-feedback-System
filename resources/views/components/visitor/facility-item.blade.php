@props(['icon', 'label'])

<div class="facility-item">
    <div class="facility-item__icon">
        @if($icon === 'microscope')
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/>
                <path d="M12 15V21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M9 21H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M19 12H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M3 12H5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M12 3V5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
        @elseif($icon === 'computer')
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="2" y="3" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/>
                <path d="M8 21H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M12 17V21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
        @elseif($icon === 'climate')
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="5" stroke="currentColor" stroke-width="1.5"/>
                <path d="M12 7V4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M12 20V17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M17 12H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M4 12H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
        @elseif($icon === 'lock')
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="5" y="11" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.5"/>
                <path d="M7 11V7C7 5.93913 7.42143 4.92172 8.17157 4.17157C8.92172 3.42143 9.93913 3 11 3H13C14.0609 3 15.0783 3.42143 15.8284 4.17157C16.5786 4.92172 17 5.93913 17 7V11" stroke="currentColor" stroke-width="1.5"/>
            </svg>
        @elseif($icon === 'wifi')
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 12.55C7.5 10.5 12 8 19 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M2 9C6 6 14 3 22 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <circle cx="12" cy="18" r="1" fill="currentColor"/>
            </svg>
        @elseif($icon === 'seat')
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 19V11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M18 19V11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M6 11H18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M4 19H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M8 11V7C8 6.46957 8.21071 5.96086 8.58579 5.58579C8.96086 5.21071 9.46957 5 10 5H14C14.5304 5 15.0391 5.21071 15.4142 5.58579C15.7893 5.96086 16 6.46957 16 7V11" stroke="currentColor" stroke-width="1.5"/>
            </svg>
        @elseif($icon === 'book')
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 19.5C4 18.837 4.26339 18.2011 4.73223 17.7322C5.20107 17.2634 5.83696 17 6.5 17H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M6.5 2H20V22H6.5C5.83696 22 5.20107 21.7366 4.73223 21.2678C4.26339 20.7989 4 20.163 4 19.5V4.5C4 3.83696 4.26339 3.20107 4.73223 2.73223C5.20107 2.26339 5.83696 2 6.5 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        @elseif($icon === 'flask')
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 3L9 10C9 10 6 14 4 16C2 18 4 21 4 21H20C20 21 22 18 20 16C18 14 15 10 15 10V3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M8 3H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
        @elseif($icon === 'document')
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M14 2V8H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M16 13H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M16 17H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M10 9H9H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
        @endif
    </div>
    <span class="facility-item__label">{{ $label }}</span>
</div>