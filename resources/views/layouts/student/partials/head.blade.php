<meta charset="UTF-8">
<link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Itenas Portal')</title>

<!-- Tailwind + Konfigurasi Desain -->
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

<!-- Google Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap"
    rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
    rel="stylesheet">

<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
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
</style>