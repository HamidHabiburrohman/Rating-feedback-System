@extends('layouts.landing.app')

@section('content')
    <style>
        /* ═══════════════════════════════════════════════════════
                ITENAS UNITS — Swiss / International Typographic System
        ═══════════════════════════════════════════════════════ */

        :root {
            --orange: #E8520A;
            --orange-dark: #c04408;
            --orange-light: #f0703a;
            --black: #0a0a0a;
            --gray-900: #111111;
            --gray-800: #1a1a1a;
            --gray-700: #2a2a2a;
            --gray-600: #444444;
            --gray-500: #666666;
            --gray-400: #888888;
            --gray-300: #aaaaaa;
            --gray-200: #cccccc;
            --gray-100: #e5e5e5;
            --gray-50: #f4f4f4;
            --white: #ffffff;
            --font-display: 'Bebas Neue', 'Barlow Condensed', sans-serif;
            --font-body: 'Inter', sans-serif;
            --nav-h: 72px;
            --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
            font-size: 16px;
            -webkit-font-smoothing: antialiased;
        }

        body {
            font-family: var(--font-body);
            color: var(--black);
            background: var(--white);
            overflow-x: hidden;
            line-height: 1.6;
        }

        img {
            display: block;
            max-width: 100%;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        ul {
            list-style: none;
        }

        .container {
            width: min(1280px, 100% - 48px);
            margin-inline: auto;
        }

        .accent {
            color: var(--orange);
        }

        /* BUTTONS */
        .btn-primary,
        .btn-outline,
        .btn-cta,
        .btn-cta-large {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: var(--font-body);
            font-weight: 600;
            font-size: 0.8rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s var(--ease-out);
        }

        .btn-primary {
            background: var(--black);
            color: var(--white);
            padding: 14px 28px;
            border-color: var(--black);
        }

        .btn-primary:hover {
            background: var(--orange);
            border-color: var(--orange);
        }

        .btn-outline {
            background: transparent;
            color: var(--black);
            padding: 14px 28px;
            border-color: var(--black);
        }

        .btn-outline:hover {
            background: var(--black);
            color: var(--white);
        }

        /* NAVBAR */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: var(--nav-h);
            background: rgba(255, 255, 255, 0.97);
            border-bottom: 1px solid var(--gray-100);
            backdrop-filter: blur(8px);
            transition: box-shadow 0.3s ease;
        }

        .navbar.scrolled {
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
        }

        .nav-inner {
            display: flex;
            align-items: center;
            height: 100%;
            width: min(1280px, 100% - 48px);
            margin-inline: auto;
            gap: 40px;
        }

        .nav-logo {
            display: flex;
            align-items: baseline;
            gap: 4px;
            flex-shrink: 0;
        }

        .nav-logo .logo-main {
            font-family: var(--font-display);
            font-size: 1.6rem;
            letter-spacing: 0.06em;
            color: var(--black);
        }

        .nav-logo .logo-sub {
            font-family: var(--font-display);
            font-size: 1.6rem;
            letter-spacing: 0.06em;
            color: var(--orange);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 36px;
            margin-left: auto;
        }

        .nav-link {
            font-size: 0.78rem;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--gray-600);
            position: relative;
            transition: color 0.2s;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--orange);
            transition: width 0.25s var(--ease-out);
        }

        .nav-link:hover {
            color: var(--black);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .btn-cta {
            background: var(--orange);
            color: var(--white);
            padding: 11px 22px;
            border-color: var(--orange);
            font-size: 0.75rem;
            flex-shrink: 0;
        }

        .btn-cta:hover {
            background: var(--orange-dark);
            border-color: var(--orange-dark);
        }

        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            margin-left: auto;
        }

        .hamburger span {
            display: block;
            width: 24px;
            height: 2px;
            background: var(--black);
            transition: all 0.3s var(--ease-out);
        }

        /* HERO */
        .hero {
            min-height: 100svh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            padding-top: var(--nav-h);
        }

        .hero-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 80px min(80px, 5vw) 80px max(24px, calc((100vw - 1280px)/2 + 24px));
            padding-right: 60px;
        }

        .hero-eyebrow {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 32px;
        }

        .eyebrow-line {
            display: block;
            width: 40px;
            height: 2px;
            background: var(--orange);
            flex-shrink: 0;
        }

        .eyebrow-text {
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gray-500);
        }

        .hero-headline {
            font-family: var(--font-display);
            font-size: clamp(4rem, 8vw, 7rem);
            line-height: 0.92;
            letter-spacing: 0.01em;
            text-transform: uppercase;
            margin-bottom: 28px;
        }

        .headline-line {
            display: block;
        }

        .hero-subtitle {
            font-size: 1rem;
            color: var(--gray-600);
            line-height: 1.65;
            max-width: 420px;
            margin-bottom: 36px;
        }

        .hero-actions {
            display: flex;
            gap: 12px;
            margin-bottom: 56px;
            flex-wrap: wrap;
        }

        .hero-meta {
            display: flex;
            align-items: center;
            gap: 24px;
            padding-top: 32px;
            border-top: 1px solid var(--gray-100);
        }

        .meta-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .meta-num {
            font-family: var(--font-display);
            font-size: 1.6rem;
            letter-spacing: 0.02em;
            line-height: 1;
        }

        .meta-label {
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--gray-500);
        }

        .meta-divider {
            width: 1px;
            height: 36px;
            background: var(--gray-200);
        }

        .hero-visual {
            position: relative;
            overflow: hidden;
            background: var(--gray-100);
        }

        .hero-image-wrap {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .hero-image-frame {
            position: relative;
            flex: 1;
            overflow: hidden;
        }

        .hero-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        

        .image-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(255, 255, 255, 0.12) 0%, transparent 40%),
                linear-gradient(to bottom, transparent 70%, rgba(0, 0, 0, 0.3) 100%);
        }

        .image-label {
            display: flex;
            justify-content: space-between;
            padding: 12px 20px;
            background: var(--black);
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gray-400);
        }

        /* TICKER */
        .ticker-wrap {
            background: var(--black);
            padding: 18px 0;
            overflow: hidden;
            border-top: 3px solid var(--orange);
        }

        .ticker-track {
            display: flex;
            width: max-content;
            animation: ticker-scroll 28s linear infinite;
        }

        .ticker-content {
            display: block;
            white-space: nowrap;
            font-family: var(--font-display);
            font-size: 1.1rem;
            letter-spacing: 0.15em;
            color: var(--white);
            padding-right: 80px;
        }

        @keyframes ticker-scroll {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .ticker-wrap:hover .ticker-track {
            animation-play-state: paused;
        }

        /* STATS */
        .stats-bar {
            background: var(--white);
            border-bottom: 1px solid var(--gray-100);
            padding: 56px 0;
        }

        .stats-grid {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            text-align: center;
        }

        .stat-num {
            font-family: var(--font-display);
            font-size: clamp(3rem, 6vw, 5rem);
            letter-spacing: 0.01em;
            line-height: 1;
            color: var(--black);
        }

        .stat-label {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gray-500);
        }

        .stat-divider {
            width: 1px;
            height: 80px;
            background: var(--gray-200);
            flex-shrink: 0;
            margin: 0 40px;
        }

        /* SPLIT */
        .split-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 520px;
        }

        .split-panel {
            padding: 80px;
        }

        .split-dark {
            background: var(--gray-900);
            color: var(--white);
        }

        .split-light {
            background: var(--gray-50);
            color: var(--black);
        }

        .split-tag {
            display: block;
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--orange);
            margin-bottom: 28px;
        }

        .split-heading {
            font-family: var(--font-display);
            font-size: clamp(2.5rem, 4vw, 3.5rem);
            line-height: 0.95;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            margin-bottom: 28px;
        }

        .split-body {
            font-size: 0.95rem;
            line-height: 1.75;
            max-width: 440px;
            margin-bottom: 20px;
        }

        .split-dark .split-body {
            color: var(--gray-400);
        }

        .split-light .split-body {
            color: var(--gray-600);
        }

        .split-line {
            width: 40px;
            height: 2px;
            background: var(--gray-700);
            margin: 32px 0 24px;
        }

        .split-footnote {
            font-size: 0.68rem;
            font-weight: 500;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--gray-600);
        }

        .check-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 4px;
        }

        .check-item {
            display: flex;
            gap: 16px;
            align-items: flex-start;
            font-size: 0.9rem;
            color: var(--gray-600);
            line-height: 1.6;
        }

        .check-icon {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--orange);
            flex-shrink: 0;
            margin-top: 2px;
            width: 20px;
        }

        /* SECTION HEADER */
        .section-header {
            margin-bottom: 64px;
        }

        .section-tag {
            display: block;
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--orange);
            margin-bottom: 16px;
        }

        .section-heading {
            font-family: var(--font-display);
            font-size: clamp(2.8rem, 5vw, 4.5rem);
            line-height: 0.92;
            letter-spacing: 0.01em;
            text-transform: uppercase;
        }

        /* FEATURES */
        .features-section {
            padding: 120px 0;
            background: var(--white);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1px;
            background: var(--gray-100);
            border: 1px solid var(--gray-100);
        }

        .feature-card {
            position: relative;
            background: var(--white);
            padding: 44px 40px;
            transition: all 0.25s var(--ease-out);
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--orange);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s var(--ease-out);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            background: var(--gray-50);
        }

        .feature-icon {
            width: 36px;
            height: 36px;
            color: var(--orange);
            margin-bottom: 24px;
        }

        .feature-icon svg {
            width: 100%;
            height: 100%;
        }

        .feature-title {
            font-family: var(--font-display);
            font-size: 1.4rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin-bottom: 10px;
            color: var(--black);
        }

        .feature-desc {
            font-size: 0.85rem;
            color: var(--gray-500);
            line-height: 1.65;
        }

        /* TOP UNITS */
        .top-units-section {
            padding: 120px 0;
            background: var(--gray-50);
        }

        .unit-card {
            display: grid;
            grid-template-columns: 1fr 1fr;
            margin-bottom: 2px;
            background: var(--white);
            border: 1px solid var(--gray-100);
            transition: border-color 0.25s;
            overflow: hidden;
        }

        .unit-card:hover {
            border-color: var(--orange);
        }

        .unit-card:last-child {
            margin-bottom: 0;
        }

        .unit-card--reverse {
            direction: rtl;
        }

        .unit-card--reverse>* {
            direction: ltr;
        }

        .unit-image-col {
            position: relative;
            min-height: 360px;
            overflow: hidden;
        }

        .unit-img-wrap {
            position: absolute;
            inset: 0;
        }

        .unit-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .unit-content-col {
            display: flex;
            align-items: center;
        }

        .unit-inner {
            padding: 52px 56px;
        }

        .unit-label {
            display: block;
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--gray-400);
            margin-bottom: 16px;
        }

        .unit-name {
            font-family: var(--font-display);
            font-size: clamp(2rem, 3.5vw, 3rem);
            line-height: 0.9;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            margin-bottom: 20px;
            color: var(--black);
        }

        .unit-desc {
            font-size: 0.88rem;
            line-height: 1.7;
            color: var(--gray-600);
            max-width: 360px;
            margin-bottom: 28px;
        }

        .unit-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 28px;
        }

        .unit-rating {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stars {
            color: var(--orange);
            font-size: 0.9rem;
            letter-spacing: 2px;
        }

        .rating-num {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--gray-600);
        }

        .unit-members {
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--gray-400);
            padding-left: 20px;
            border-left: 1px solid var(--gray-200);
        }

        .unit-link {
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--orange);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: gap 0.2s;
        }

        .unit-link:hover {
            gap: 14px;
        }

        /* PROCESS */
        .process-section {
            padding: 120px 0;
            background: var(--white);
        }

        .process-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1px;
            background: var(--gray-100);
            border: 1px solid var(--gray-100);
        }

        .process-card {
            position: relative;
            background: var(--white);
            padding: 52px 44px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            transition: background 0.2s;
        }

        .process-card:hover {
            background: var(--gray-50);
        }

        .process-num {
            font-family: var(--font-display);
            font-size: 4rem;
            letter-spacing: -0.02em;
            color: var(--gray-100);
            line-height: 1;
        }

        .process-title {
            font-family: var(--font-display);
            font-size: 1.8rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--black);
        }

        .process-desc {
            font-size: 0.88rem;
            line-height: 1.7;
            color: var(--gray-500);
            flex: 1;
        }

        .process-arrow {
            position: absolute;
            top: 52px;
            right: 44px;
            font-size: 1.5rem;
            color: var(--orange);
            font-weight: 300;
        }

        .process-arrow--last {
            font-weight: 700;
        }

        /* CTA BAND */
        .cta-band {
            background: var(--black);
            padding: 100px 0;
            border-top: 4px solid var(--orange);
        }

        .cta-inner {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .cta-eyebrow {
            display: block;
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--orange);
            margin-bottom: 20px;
        }

        .cta-heading {
            font-family: var(--font-display);
            font-size: clamp(3rem, 6vw, 5.5rem);
            line-height: 0.9;
            letter-spacing: 0.01em;
            text-transform: uppercase;
            color: var(--white);
        }

        .cta-sub {
            font-size: 0.95rem;
            color: var(--gray-400);
            line-height: 1.7;
            margin-bottom: 36px;
        }

        .btn-cta-large {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--orange);
            color: var(--white);
            padding: 18px 40px;
            font-family: var(--font-body);
            font-weight: 700;
            font-size: 0.82rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            border: 2px solid var(--orange);
            transition: all 0.2s var(--ease-out);
            cursor: pointer;
        }

        .btn-cta-large:hover {
            background: transparent;
            color: var(--orange);
        }

        /* FOOTER */
        .site-footer {
            background: var(--gray-900);
            padding: 80px 0 0;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 60px;
            padding-bottom: 60px;
            border-bottom: 1px solid var(--gray-800);
        }

        .footer-logo {
            display: flex;
            align-items: baseline;
            gap: 4px;
            margin-bottom: 16px;
        }

        .footer-logo .logo-main {
            font-family: var(--font-display);
            font-size: 1.5rem;
            letter-spacing: 0.06em;
            color: var(--white);
        }

        .footer-logo .logo-sub {
            font-family: var(--font-display);
            font-size: 1.5rem;
            letter-spacing: 0.06em;
            color: var(--orange);
        }

        .footer-tagline {
            font-size: 0.78rem;
            color: var(--gray-500);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .footer-address {
            font-size: 0.82rem;
            color: var(--gray-600);
            line-height: 1.7;
        }

        .footer-col-title {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--white);
            margin-bottom: 20px;
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-links a {
            font-size: 0.85rem;
            color: var(--gray-500);
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--orange);
        }

        .footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px 0;
            font-size: 0.75rem;
            color: var(--gray-600);
        }

        .footer-bottom-links {
            display: flex;
            gap: 24px;
        }

        .footer-bottom-links a {
            color: var(--gray-600);
            transition: color 0.2s;
        }

        .footer-bottom-links a:hover {
            color: var(--orange);
        }

        /* REVEAL */
        .reveal {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity 0.7s var(--ease-out), transform 0.7s var(--ease-out);
        }

        .reveal.visible {
            opacity: 1;
            transform: none;
        }

        /* SCROLLBAR */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gray-300);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--orange);
        }

        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .split-panel {
                padding: 60px 48px;
            }

            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .process-grid {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .process-card {
                padding: 40px 36px;
            }

            .process-arrow {
                display: none;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            :root {
                --nav-h: 64px;
            }

            .hamburger {
                display: flex;
            }

            .nav-links {
                position: fixed;
                top: var(--nav-h);
                left: 0;
                right: 0;
                background: var(--white);
                flex-direction: column;
                align-items: flex-start;
                padding: 24px;
                gap: 24px;
                border-bottom: 1px solid var(--gray-100);
                transform: translateY(-110%);
                transition: transform 0.35s var(--ease-out);
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            }

            .nav-links.open {
                transform: translateY(0);
            }

            nav .btn-cta {
                display: none;
            }

            .hero {
                grid-template-columns: 1fr;
                min-height: auto;
            }

            .hero-content {
                padding: 60px 24px 40px;
            }

            .hero-visual {
                height: 300px;
            }

            .hero-image-frame {
                height: 100%;
            }

            .hero-img {
                height: 100%;
            }

            .split-section {
                grid-template-columns: 1fr;
            }

            .split-panel {
                padding: 60px 24px;
            }

            .unit-card {
                grid-template-columns: 1fr;
            }

            .unit-card--reverse {
                direction: ltr;
            }

            .unit-image-col {
                min-height: 240px;
                position: relative;
            }

            .unit-img-wrap {
                position: absolute;
            }

            .unit-img {
                height: 100%;
            }

            .unit-inner {
                padding: 36px 24px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .cta-inner {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 12px;
                text-align: center;
            }

            .stats-grid {
                flex-direction: column;
                gap: 32px;
            }

            .stat-divider {
                width: 60px;
                height: 1px;
            }

            .container {
                width: calc(100% - 32px);
            }
        }

        @media (max-width: 480px) {
            .hero-headline {
                font-size: 3.2rem;
            }

            .hero-actions {
                flex-direction: column;
            }

            .btn-primary,
            .btn-outline {
                justify-content: center;
            }

            .split-panel {
                padding: 48px 20px;
            }

            .section-header {
                margin-bottom: 40px;
            }

            .features-section,
            .top-units-section,
            .process-section {
                padding: 72px 0;
            }

            .cta-band {
                padding: 72px 0;
            }
        }
    </style>

    <body>

        <!-- ═══════════════════════ NAVBAR ═══════════════════════ -->
        <nav class="navbar" id="navbar">
            <div class="nav-inner">
                <a href="#" class="nav-logo">
                    <img src="{{ asset('assets/images/logos/Logo1.svg') }}" alt="ITENAS Units">
                </a>
                <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
                <ul class="nav-links" id="navLinks">
                    <li><a href="#explore" class="nav-link">Explore</a></li>
                    <li><a href="#about" class="nav-link">About</a></li>
                    <li><a href="#contact" class="nav-link">Contact</a></li>
                </ul>
                <a href="{{ route('student.login') }}" class="btn-cta">JOIN NOW</a>
            </div>
        </nav>

        <!-- ═══════════════════════ HERO ═══════════════════════ -->
        <section class="hero" id="home">
            <div class="hero-content">
                <div class="hero-eyebrow">
                    <span class="eyebrow-line"></span>
                    <span class="eyebrow-text">Institut Teknologi Nasional</span>
                </div>
                <h1 class="hero-headline">
                    <span class="headline-line line-1">DISCOVER</span>
                    <span class="headline-line line-2">YOUR <span class="accent">UNIT.</span></span>
                </h1>
                <p class="hero-subtitle">
                    Find the student activity unit that fits your passion. Connect with peers, build real skills, and leave
                    your mark at ITENAS.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('student.units.index') }}" class="btn-primary">EXPLORE UNITS</a>
                    <a href="#about" class="btn-outline">LEARN MORE</a>
                </div>
                <div class="hero-meta">
                    <div class="meta-item">
                        <span class="meta-num">47</span>
                        <span class="meta-label">Units</span>
                    </div>
                    <div class="meta-divider"></div>
                    <div class="meta-item">
                        <span class="meta-num">2,400+</span>
                        <span class="meta-label">Members</span>
                    </div>
                    <div class="meta-divider"></div>
                    <div class="meta-item">
                        <span class="meta-num">12</span>
                        <span class="meta-label">Categories</span>
                    </div>
                </div>
            </div>
            <div class="hero-visual">
                <div class="hero-image-wrap">
                    <div class="hero-image-frame">
                        <img src="{{ asset('assets/landing/Gedung-Itenas-Final.jpg') }}"
                            alt="ITENAS Campus" class="hero-img" loading="eager" />
                        <div class="image-overlay"></div>
                    </div>
                    <div class="image-label">
                        <span>ITENAS BANDUNG</span>
                        <span>EST. 1972</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════ TICKER ═══════════════════════ -->
        <div class="ticker-wrap">
            <div class="ticker-track" id="tickerTrack">
                <div class="ticker-content">
                    ROBOTICS &bull; MUSIC &bull; PHOTOGRAPHY &bull; CODING &bull; SPORTS &bull; DEBATE &bull; ART &bull;
                    ENGINEERING &bull; ROBOTICS &bull; MUSIC &bull; PHOTOGRAPHY &bull; CODING &bull; SPORTS &bull; DEBATE
                    &bull; ART &bull; ENGINEERING &bull; ROBOTICS &bull; MUSIC &bull; PHOTOGRAPHY &bull; CODING &bull;
                    SPORTS &bull; DEBATE &bull; ART &bull; ENGINEERING &bull;
                </div>
            </div>
        </div>

        <!-- ═══════════════════════ STATS ═══════════════════════ -->
        <section class="stats-bar">
            <div class="container">
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-num" data-target="47">47</span>
                        <span class="stat-label">Active Units</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <span class="stat-num" data-target="2400">2,400+</span>
                        <span class="stat-label">Members</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <span class="stat-num" data-target="12">12</span>
                        <span class="stat-label">Categories</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════ SPLIT ═══════════════════════ -->
        <section class="split-section" id="about">
            <div class="split-panel split-dark">
                <div class="split-inner">
                    <span class="split-tag">01 — PROBLEM</span>
                    <h2 class="split-heading">THE<br />CHALLENGE</h2>
                    <p class="split-body">Every year, thousands of new students arrive at ITENAS overwhelmed by choice. With
                        47 active units spanning robotics, music, sports, and engineering — finding the right fit is harder
                        than it looks.</p>
                    <p class="split-body">Students miss out on communities that could transform their college experience,
                        simply because there's no clear path to discovery. Information is scattered, recruitment windows are
                        missed, and potential goes untapped.</p>
                    <div class="split-line"></div>
                    <span class="split-footnote">Institut Teknologi Nasional, Bandung</span>
                </div>
            </div>
            <div class="split-panel split-light">
                <div class="split-inner">
                    <span class="split-tag">02 — SOLUTION</span>
                    <h2 class="split-heading">THE<br />SOLUTION</h2>
                    <p class="split-body">ITENAS Unit Discovery is a single, unified platform designed to connect every
                        student with their perfect unit — fast, clearly, and without friction.</p>
                    <ul class="check-list">
                        <li class="check-item">
                            <span class="check-icon">✓</span>
                            <span>Centralized profiles for all 47 units with real member reviews and achievement
                                records</span>
                        </li>
                        <li class="check-item">
                            <span class="check-icon">✓</span>
                            <span>Smart filtering by interest, schedule, and skill level so you find your match in
                                minutes</span>
                        </li>
                        <li class="check-item">
                            <span class="check-icon">✓</span>
                            <span>One-click registration with real-time recruitment status and event calendars</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════ FEATURES ═══════════════════════ -->
        <section class="features-section" id="explore">
            <div class="container">
                <div class="section-header">
                    <span class="section-tag">PLATFORM FEATURES</span>
                    <h2 class="section-heading">BUILT FOR<br /><span class="accent">STUDENTS.</span></h2>
                </div>
                <div class="features-grid">

                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </div>
                        <h3 class="feature-title">OPEN RECRUITMENT</h3>
                        <p class="feature-desc">Track live recruitment windows across all units in one unified calendar.</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path
                                    d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                            </svg>
                        </div>
                        <h3 class="feature-title">ACHIEVEMENT TRACK</h3>
                        <p class="feature-desc">Showcase unit trophies, competitions won, and milestones reached.</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path
                                    d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </div>
                        <h3 class="feature-title">MEMBER NETWORK</h3>
                        <p class="feature-desc">Connect with current members and alumni within your chosen unit.</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                        </div>
                        <h3 class="feature-title">EVENT CALENDAR</h3>
                        <p class="feature-desc">Never miss a unit event, showcase, or open house session again.</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path
                                    d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                            </svg>
                        </div>
                        <h3 class="feature-title">UNIT PROFILES</h3>
                        <p class="feature-desc">Deep-dive into every unit's story, structure, and requirements.</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="feature-title">EASY REGISTRATION</h3>
                        <p class="feature-desc">Apply to multiple units with one profile, track your application status
                            live.</p>
                    </div>

                </div>
            </div>
        </section>

        <!-- ═══════════════════════ TOP UNITS ═══════════════════════ -->
            <section class="top-units-section">
                <div class="container">
                    <div class="section-header">
                        <span class="section-tag">HIGHEST RATED</span>
                        <h2 class="section-heading">TOP RATED<br /><span class="accent">UNITS.</span></h2>
                    </div>

                    @forelse($topRatedUnits as $index => $unit)
                        @php
                            $fullStars = floor($unit->avg_rating);
                            $hasHalf = ($unit->avg_rating - $fullStars) >= 0.5;
                            $emptyStars = 5 - $fullStars - ($hasHalf ? 1 : 0);
                            $ratingDisplay = number_format($unit->avg_rating, 1);
                        @endphp

                        <div class="unit-card {{ $index % 2 == 1 ? 'unit-card--reverse' : '' }}">
                            <div class="unit-image-col">
                    <div class="unit-img-wrap">
                        @if($unit->primaryPhoto && $unit->primaryPhoto->thumbnail_url)
                            <img src="{{ $unit->primaryPhoto->thumbnail_url }}" alt="{{ $unit->name }}" class="unit-img" loading="lazy" />
                        @elseif($unit->photos && $unit->photos->isNotEmpty())
                            <img src="{{ $unit->photos->first()->thumbnail_url }}" alt="{{ $unit->name }}" class="unit-img" loading="lazy" />
                        @else
                            <img src="{{ asset('assets/images/UnitPlaceholder.png') }}" alt="{{ $unit->name }}" class="unit-img" loading="lazy" />
                        @endif
                        <div class="unit-img-overlay"></div>
                    </div>
                    </div>
                <div class="unit-content-col">
                    <div class="unit-inner">
                        <span class="unit-label">
                            UNIT {{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}
                            @if($unit->type_name)
                                • {{ $unit->type_name }}
                            @endif
                        </span>
                        <h3 class="unit-name">{{ $unit->name }}</h3>
                        <p class="unit-desc">{{ Str::limit($unit->description ?? 'Unit aktif di Institut Teknologi Nasional Bandung.', 120) }}</p>
                        <div class="unit-meta">
                            <div class="unit-rating">
                                <span class="stars">
                                    @for($i = 1; $i <= $fullStars; $i++)★@endfor
                                    @if($hasHalf)½@endif
                                    @for($i = 1; $i <= $emptyStars; $i++)☆@endfor
                                </span>
                                <span class="rating-num">{{ $ratingDisplay }}</span>
                            </div>
                            <span class="unit-members">{{ number_format($unit->total_ratings) }} Reviews</span>
                        </div>
                        <a href="{{ route('student.units.show', $unit->slug) }}" class="unit-link">VIEW UNIT →</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <p class="text-gray-500">Belum ada unit yang tersedia.</p>
            </div>
        @endforelse

    </div>
</section>

        <!-- ═══════════════════════ PROCESS ═══════════════════════ -->
        <section class="process-section">
            <div class="container">
                <div class="section-header">
                    <span class="section-tag">THE PROCESS</span>
                    <h2 class="section-heading">HOW TO<br /><span class="accent">JOIN.</span></h2>
                </div>
                <div class="process-grid">
                    <div class="process-card">
                        <div class="process-num">01</div>
                        <div class="process-content">
                            <h3 class="process-title">DISCOVER</h3>
                            <p class="process-desc">Browse all 47 units. Filter by interest, schedule, and commitment level.
                                Read profiles, check achievements, and talk to members.</p>
                        </div>
                        <div class="process-arrow">→</div>
                    </div>
                    <div class="process-card">
                        <div class="process-num">02</div>
                        <div class="process-content">
                            <h3 class="process-title">REGISTER</h3>
                            <p class="process-desc">Submit your application during the open recruitment window. One profile,
                                multiple units. Track your status in real time.</p>
                        </div>
                        <div class="process-arrow">→</div>
                    </div>
                    <div class="process-card">
                        <div class="process-num">03</div>
                        <div class="process-content">
                            <h3 class="process-title">CONTRIBUTE</h3>
                            <p class="process-desc">Show up, give your best, and build something meaningful. Compete,
                                create, lead — and leave your mark at ITENAS.</p>
                        </div>
                        <div class="process-arrow process-arrow--last">✓</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════ CTA BAND ═══════════════════════ -->
        <section class="cta-band" id="join">
            <div class="container">
                <div class="cta-inner">
                    <div class="cta-text-col">
                        <span class="cta-eyebrow">ITENAS UNITS — 2025</span>
                        <h2 class="cta-heading">READY TO<br />CONTRIBUTE?</h2>
                    </div>
                    <div class="cta-action-col">
                        <p class="cta-sub">Join thousands of ITENAS students already part of something bigger.</p>
                        <a href="#" class="btn-cta-large">GET STARTED NOW</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════ FOOTER ═══════════════════════ -->
        <footer class="site-footer" id="contact">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-brand">
                        <a href="#" class="footer-logo">
                            <span class="logo-main">ITENAS</span>
                            <span class="logo-sub">UNITS</span>
                        </a>
                        <p class="footer-tagline">Discover. Register. Contribute.</p>
                        <p class="footer-address">Jl. PHH. Mustapa No.23, Bandung<br />Jawa Barat, Indonesia</p>
                    </div>
                    <div class="footer-col">
                        <h4 class="footer-col-title">PLATFORM</h4>
                        <ul class="footer-links">
                            <li><a href="#explore">Explore Units</a></li>
                            <li><a href="#">All Categories</a></li>
                            <li><a href="#">Recruitment</a></li>
                            <li><a href="#">Events</a></li>
                        </ul>
                    </div>
                    <div class="footer-col">
                        <h4 class="footer-col-title">INSTITUTION</h4>
                        <ul class="footer-links">
                            <li><a href="#">About ITENAS</a></li>
                            <li><a href="#">Student Affairs</a></li>
                            <li><a href="#">Academic Calendar</a></li>
                            <li><a href="#">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="footer-col">
                        <h4 class="footer-col-title">CONNECT</h4>
                        <ul class="footer-links">
                            <li><a href="#">Instagram</a></li>
                            <li><a href="#">Twitter / X</a></li>
                            <li><a href="#">YouTube</a></li>
                            <li><a href="#">units@itenas.ac.id</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <span>© 2025 Institut Teknologi Nasional. All rights reserved.</span>
                    <div class="footer-bottom-links">
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Use</a>
                    </div>
                </div>
            </div>
        </footer>

        <script>
            (function () {
                'use strict';

                /* NAVBAR SCROLL */
                const navbar = document.getElementById('navbar');
                window.addEventListener('scroll', () => {
                    navbar.classList.toggle('scrolled', window.scrollY > 20);
                }, { passive: true });

                /* HAMBURGER */
                const hamburger = document.getElementById('hamburger');
                const navLinks = document.getElementById('navLinks');

                hamburger.addEventListener('click', () => {
                    const isOpen = navLinks.classList.toggle('open');
                    hamburger.setAttribute('aria-expanded', isOpen);
                    const s = hamburger.querySelectorAll('span');
                    if (isOpen) {
                        s[0].style.transform = 'translateY(7px) rotate(45deg)';
                        s[1].style.opacity = '0';
                        s[2].style.transform = 'translateY(-7px) rotate(-45deg)';
                    } else {
                        s[0].style.transform = s[1].style.opacity = s[2].style.transform = '';
                    }
                });

                navLinks.querySelectorAll('.nav-link').forEach(link => {
                    link.addEventListener('click', () => {
                        navLinks.classList.remove('open');
                        hamburger.setAttribute('aria-expanded', 'false');
                        const s = hamburger.querySelectorAll('span');
                        s[0].style.transform = s[1].style.opacity = s[2].style.transform = '';
                    });
                });

                document.addEventListener('click', (e) => {
                    if (!navbar.contains(e.target)) {
                        navLinks.classList.remove('open');
                        hamburger.setAttribute('aria-expanded', 'false');
                        const s = hamburger.querySelectorAll('span');
                        s[0].style.transform = s[1].style.opacity = s[2].style.transform = '';
                    }
                });

                /* SCROLL REVEAL */
                const revealSelectors = [
                    '.feature-card', '.unit-card', '.process-card',
                    '.stat-item', '.split-inner', '.section-header',
                    '.cta-inner > *'
                ];
                revealSelectors.forEach(sel => {
                    document.querySelectorAll(sel).forEach((el, i) => {
                        el.classList.add('reveal');
                        el.style.transitionDelay = (i * 0.07) + 's';
                    });
                });

                const io = new IntersectionObserver(entries => {
                    entries.forEach(e => {
                        if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); }
                    });
                }, { threshold: 0.1, rootMargin: '0px 0px -60px 0px' });
                document.querySelectorAll('.reveal').forEach(el => io.observe(el));

                /* SMOOTH ANCHOR SCROLL */
                document.querySelectorAll('a[href^="#"]').forEach(a => {
                    a.addEventListener('click', e => {
                        const id = a.getAttribute('href');
                        if (id === '#') return;
                        const target = document.querySelector(id);
                        if (!target) return;
                        e.preventDefault();
                        const top = target.getBoundingClientRect().top + window.scrollY - navbar.offsetHeight - 8;
                        window.scrollTo({ top, behavior: 'smooth' });
                    });
                });

                /* ACTIVE NAV */
                const sections = document.querySelectorAll('section[id], footer[id]');
                const links = document.querySelectorAll('.nav-link');
                const updateActive = () => {
                    const y = window.scrollY + 120;
                    let current = '';
                    sections.forEach(s => { if (s.offsetTop <= y) current = s.id; });
                    links.forEach(l => {
                        l.classList.toggle('active', l.getAttribute('href') === '#' + current);
                    });
                };
                window.addEventListener('scroll', updateActive, { passive: true });
                updateActive();

                /* STATS COUNT-UP */
                let counted = false;
                const statNums = document.querySelectorAll('.stat-num');
                const countUp = el => {
                    const raw = el.textContent.replace(/[^0-9]/g, '');
                    if (!raw) return;
                    const target = parseInt(raw);
                    const suffix = el.textContent.includes('+') ? '+' : '';
                    const fmt = n => n >= 1000 ? n.toLocaleString() : n;
                    let current = 0;
                    const step = target / 60;
                    const timer = setInterval(() => {
                        current = Math.min(current + step, target);
                        el.textContent = fmt(Math.round(current)) + suffix;
                        if (current >= target) clearInterval(timer);
                    }, 1600 / 60);
                };
                const statsObs = new IntersectionObserver(entries => {
                    if (entries[0].isIntersecting && !counted) {
                        counted = true;
                        statNums.forEach(countUp);
                        statsObs.disconnect();
                    }
                }, { threshold: 0.5 });
                const statsBar = document.querySelector('.stats-bar');
                if (statsBar) statsObs.observe(statsBar);

            })();
        </script>
@endsection