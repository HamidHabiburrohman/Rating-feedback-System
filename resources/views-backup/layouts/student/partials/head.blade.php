<meta charset="UTF-8">
<link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Itenas Portal')</title>

<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "on-tertiary-fixed": "#001c3b",
                    "primary": "#ad2b00",
                    "tertiary-fixed": "#d5e3ff",
                    "surface-bright": "#f7f9fb",
                    "on-secondary-container": "#681600",
                    "background": "#f7f9fb",
                    "primary-fixed": "#ffdbd1",
                    "secondary": "#a83818",
                    "on-surface": "#191c1e",
                    "tertiary": "#005cad",
                    "surface-dim": "#d8dadc",
                    "on-error": "#ffffff",
                    "on-primary": "#ffffff",
                    "on-secondary-fixed": "#3b0900",
                    "surface-container": "#eceef0",
                    "surface-container-highest": "#e0e3e5",
                    "primary-container": "#d93900",
                    "on-primary-fixed": "#3b0900",
                    "on-tertiary-fixed-variant": "#004787",
                    "primary-fixed-dim": "#ffb5a1",
                    "secondary-fixed": "#ffdbd1",
                    "surface-tint": "#b12d00",
                    "on-tertiary": "#ffffff",
                    "outline": "#926f66",
                    "on-secondary": "#ffffff",
                    "on-primary-fixed-variant": "#882000",
                    "surface-container-low": "#f2f4f6",
                    "surface-container-high": "#e6e8ea",
                    "error": "#ba1a1a",
                    "inverse-primary": "#ffb5a1",
                    "surface": "#f7f9fb",
                    "on-surface-variant": "#5d4038",
                    "tertiary-fixed-dim": "#a6c8ff",
                    "surface-variant": "#e0e3e5",
                    "error-container": "#ffdad6",
                    "on-secondary-fixed-variant": "#872101",
                    "inverse-surface": "#2d3133",
                    "on-error-container": "#93000a",
                    "tertiary-container": "#0075d8",
                    "on-background": "#191c1e",
                    "secondary-container": "#fc7550",
                    "inverse-on-surface": "#eff1f3",
                    "on-primary-container": "#fffbff",
                    "on-tertiary-container": "#fefcff",
                    "outline-variant": "#e7bdb2",
                    "surface-container-lowest": "#ffffff",
                    "secondary-fixed-dim": "#ffb5a1"
                },
                fontFamily: {
                    "headline": ["Manrope", "sans-serif"],
                    "body": ["Manrope", "sans-serif"],
                    "label": ["Manrope", "sans-serif"]
                },
                borderRadius: {
                    "DEFAULT": "1rem",
                    "lg": "2rem",
                    "xl": "3rem",
                    "full": "9999px"
                },
            },
        },
    }
</script>

<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
    rel="stylesheet">

<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
        vertical-align: middle;
        transition: all 0.2s ease;
    }

    .fill-icon {
        font-variation-settings: 'FILL' 1;
    }

    .no-line {
        border: none !important;
    }

    .tonal-transition {
        transition: background-color 0.3s ease;
    }

    .star-active {
        font-variation-settings: 'FILL' 1;
        color: #ad2b00;
    }

    @keyframes heartbeat {
        0% {
            transform: scale(1);
        }

        25% {
            transform: scale(1.3);
        }

        50% {
            transform: scale(1.1);
        }

        75% {
            transform: scale(1.2);
        }

        100% {
            transform: scale(1);
        }
    }

    .animate-heartbeat {
        animation: heartbeat 0.6s ease-in-out;
    }

    @keyframes modalOverlayIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes modalOverlayOut {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }
    }

    @keyframes modalCardIn {
        from {
            opacity: 0;
            transform: translateY(60px) scale(0.9);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes modalCardOut {
        from {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        to {
            opacity: 0;
            transform: translateY(40px) scale(0.92);
        }
    }

    .auth-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 100;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        background: rgba(25, 28, 30, 0.3);
        backdrop-filter: blur(4px);
    }

    .auth-modal-overlay.active {
        display: flex;
        animation: modalOverlayIn 0.4s ease-out forwards;
    }

    .auth-modal-overlay.closing {
        display: flex;
        animation: modalOverlayOut 0.3s ease-in forwards;
    }

    .auth-modal-card {
        width: 100%;
        max-width: 28rem;
        background: #ffffff;
        border-radius: 2rem;
        padding: 2.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        box-shadow: 0 25px 50px rgba(173, 43, 0, 0.15);
        border: 1px solid rgba(226, 191, 182, 0.15);
        position: relative;
        overflow: hidden;
    }

    .auth-modal-overlay.active .auth-modal-card {
        animation: modalCardIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    .auth-modal-overlay.closing .auth-modal-card {
        animation: modalCardOut 0.3s ease-in forwards;
    }

    @media (min-width: 768px) {
        .auth-modal-card {
            padding: 2.5rem;
        }
    }
</style>
