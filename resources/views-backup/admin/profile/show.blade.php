@extends('layouts.admin.app')

@section('title', 'My Profile')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Outfit', sans-serif;
        }

        .profile-page {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .alert-custom {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .85rem 1.1rem;
            border-radius: 12px;
            font-size: .88rem;
            font-weight: 500;
            margin-bottom: 1.25rem;
        }

        .alert-custom.success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-custom.error {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #9f1239;
        }

        .profile-hero {
            background: #fff;
            border: 1px solid #f1f5f9;
            border-radius: 24px;
            overflow: visible;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .04), 0 8px 32px rgba(0, 0, 0, .04);
            margin-bottom: 1.5rem;
            position: relative;
        }

        .profile-banner {
            height: 185px;
            border-radius: 20px 20px 20px 20px;
            margin: 16px 16px 0 16px;
            position: relative;
            overflow: hidden;
        }

        .profile-banner.rainbow {
            background: linear-gradient(135deg, #f8773c, #f5a623, #f9d423, #56ccf2, #2f80ed, #1d4ed8);
        }

        .profile-banner.blue-sea {
            background: linear-gradient(135deg, #006994, #003d5c, #001f33);
        }

        .profile-banner.sunset {
            background: linear-gradient(135deg, #ff512f, #dd2476, #ff9a44);
        }

        .profile-banner.night-sky {
            background: linear-gradient(160deg, #060b1a 0%, #0e1d3f 45%, #0d0f1e 100%);
        }

        .profile-banner.night-sky::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(1.2px 1.2px at 5% 15%, rgba(255, 255, 255, .95) 0%, transparent 100%),
                radial-gradient(1px 1px at 12% 72%, rgba(255, 255, 255, .7) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 18% 35%, rgba(255, 255, 255, .9) 0%, transparent 100%),
                radial-gradient(1px 1px at 22% 58%, rgba(255, 255, 255, .6) 0%, transparent 100%),
                radial-gradient(1.2px 1.2px at 28% 20%, rgba(255, 255, 255, .85) 0%, transparent 100%),
                radial-gradient(1px 1px at 33% 80%, rgba(255, 255, 255, .7) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 38% 10%, rgba(255, 255, 255, .9) 0%, transparent 100%),
                radial-gradient(1px 1px at 42% 65%, rgba(255, 255, 255, .6) 0%, transparent 100%),
                radial-gradient(1.2px 1.2px at 48% 40%, rgba(255, 255, 255, .8) 0%, transparent 100%),
                radial-gradient(1px 1px at 53% 88%, rgba(255, 255, 255, .65) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 58% 25%, rgba(255, 255, 255, .95) 0%, transparent 100%),
                radial-gradient(1px 1px at 63% 55%, rgba(255, 255, 255, .7) 0%, transparent 100%),
                radial-gradient(1.2px 1.2px at 68% 12%, rgba(255, 255, 255, .85) 0%, transparent 100%),
                radial-gradient(1px 1px at 72% 70%, rgba(255, 255, 255, .6) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 77% 38%, rgba(255, 255, 255, .9) 0%, transparent 100%),
                radial-gradient(1px 1px at 82% 82%, rgba(255, 255, 255, .7) 0%, transparent 100%),
                radial-gradient(1.2px 1.2px at 87% 22%, rgba(255, 255, 255, .8) 0%, transparent 100%),
                radial-gradient(1px 1px at 92% 60%, rgba(255, 255, 255, .65) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 96% 45%, rgba(255, 255, 255, .9) 0%, transparent 100%),
                radial-gradient(1px 1px at 3% 50%, rgba(255, 255, 255, .75) 0%, transparent 100%),
                radial-gradient(1px 1px at 15% 90%, rgba(255, 255, 255, .6) 0%, transparent 100%),
                radial-gradient(1.2px 1.2px at 25% 5%, rgba(255, 255, 255, .85) 0%, transparent 100%),
                radial-gradient(1px 1px at 44% 92%, rgba(255, 255, 255, .7) 0%, transparent 100%),
                radial-gradient(1px 1px at 55% 5%, rgba(255, 255, 255, .8) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 70% 95%, rgba(255, 255, 255, .65) 0%, transparent 100%),
                radial-gradient(1px 1px at 80% 8%, rgba(255, 255, 255, .9) 0%, transparent 100%),
                radial-gradient(1.2px 1.2px at 90% 92%, rgba(255, 255, 255, .75) 0%, transparent 100%),
                radial-gradient(1px 1px at 8% 30%, rgba(200, 220, 255, .8) 0%, transparent 100%),
                radial-gradient(1.2px 1.2px at 35% 48%, rgba(200, 220, 255, .6) 0%, transparent 100%),
                radial-gradient(1px 1px at 60% 75%, rgba(200, 220, 255, .7) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 85% 50%, rgba(200, 220, 255, .85) 0%, transparent 100%);
            animation: twinkle 3.5s ease-in-out infinite alternate;
        }

        .profile-banner.night-sky::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(1px 1px at 7% 42%, rgba(255, 255, 255, .5) 0%, transparent 100%),
                radial-gradient(1px 1px at 19% 18%, rgba(255, 255, 255, .4) 0%, transparent 100%),
                radial-gradient(1px 1px at 30% 75%, rgba(255, 255, 255, .6) 0%, transparent 100%),
                radial-gradient(1px 1px at 45% 28%, rgba(255, 255, 255, .45) 0%, transparent 100%),
                radial-gradient(1px 1px at 57% 62%, rgba(255, 255, 255, .55) 0%, transparent 100%),
                radial-gradient(1px 1px at 66% 85%, rgba(255, 255, 255, .4) 0%, transparent 100%),
                radial-gradient(1px 1px at 74% 18%, rgba(255, 255, 255, .6) 0%, transparent 100%),
                radial-gradient(1px 1px at 88% 68%, rgba(255, 255, 255, .5) 0%, transparent 100%),
                radial-gradient(1px 1px at 94% 32%, rgba(255, 255, 255, .45) 0%, transparent 100%);
            animation: twinkle 2.5s ease-in-out infinite alternate-reverse;
        }

        @keyframes twinkle {
            0% { opacity: .6; }
            50% { opacity: 1; }
            100% { opacity: .75; }
        }

        .profile-banner.midnight-aurora {
            background: linear-gradient(160deg, #020c18 0%, #0a1628 50%, #06101e 100%);
        }

        .profile-banner.midnight-aurora::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 80%, rgba(56, 189, 248, .25) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(99, 102, 241, .2) 0%, transparent 55%),
                radial-gradient(ellipse at 55% 50%, rgba(16, 185, 129, .15) 0%, transparent 50%);
            animation: aurora-drift 6s ease-in-out infinite alternate;
        }

        .profile-banner.midnight-aurora::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(1px 1px at 8% 20%, rgba(255, 255, 255, .8) 0%, transparent 100%),
                radial-gradient(1px 1px at 16% 60%, rgba(255, 255, 255, .6) 0%, transparent 100%),
                radial-gradient(1.2px 1.2px at 27% 35%, rgba(255, 255, 255, .9) 0%, transparent 100%),
                radial-gradient(1px 1px at 38% 75%, rgba(255, 255, 255, .5) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 50% 15%, rgba(255, 255, 255, .85) 0%, transparent 100%),
                radial-gradient(1px 1px at 62% 55%, rgba(255, 255, 255, .7) 0%, transparent 100%),
                radial-gradient(1.2px 1.2px at 73% 28%, rgba(255, 255, 255, .9) 0%, transparent 100%),
                radial-gradient(1px 1px at 84% 82%, rgba(255, 255, 255, .6) 0%, transparent 100%),
                radial-gradient(1px 1px at 91% 45%, rgba(255, 255, 255, .8) 0%, transparent 100%),
                radial-gradient(1px 1px at 95% 10%, rgba(255, 255, 255, .7) 0%, transparent 100%),
                radial-gradient(1px 1px at 4% 90%, rgba(255, 255, 255, .5) 0%, transparent 100%),
                radial-gradient(1.2px 1.2px at 44% 92%, rgba(255, 255, 255, .65) 0%, transparent 100%),
                radial-gradient(1px 1px at 78% 5%, rgba(255, 255, 255, .75) 0%, transparent 100%);
            animation: twinkle 4s ease-in-out infinite alternate;
        }

        @keyframes aurora-drift {
            0% { opacity: .7; transform: scaleX(1); }
            100% { opacity: 1; transform: scaleX(1.05); }
        }

        .profile-banner.galaxy {
            background: linear-gradient(135deg, #0d001a 0%, #1a0533 40%, #2d0a4e 70%, #0d001a 100%);
        }

        .profile-banner.galaxy::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 30% 40%, rgba(168, 85, 247, .3) 0%, transparent 55%),
                radial-gradient(ellipse at 70% 60%, rgba(139, 92, 246, .25) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 20%, rgba(236, 72, 153, .15) 0%, transparent 45%);
        }

        .profile-banner.galaxy::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(1px 1px at 6% 18%, rgba(255, 255, 255, .9) 0%, transparent 100%),
                radial-gradient(1px 1px at 14% 65%, rgba(255, 255, 255, .6) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 22% 32%, rgba(255, 255, 255, .85) 0%, transparent 100%),
                radial-gradient(1px 1px at 31% 78%, rgba(255, 255, 255, .7) 0%, transparent 100%),
                radial-gradient(1.2px 1.2px at 41% 12%, rgba(255, 220, 255, .9) 0%, transparent 100%),
                radial-gradient(1px 1px at 49% 55%, rgba(255, 255, 255, .65) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 57% 88%, rgba(255, 255, 255, .8) 0%, transparent 100%),
                radial-gradient(1px 1px at 65% 25%, rgba(255, 220, 255, .75) 0%, transparent 100%),
                radial-gradient(1.2px 1.2px at 74% 68%, rgba(255, 255, 255, .9) 0%, transparent 100%),
                radial-gradient(1px 1px at 82% 42%, rgba(255, 255, 255, .6) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 89% 15%, rgba(255, 220, 255, .85) 0%, transparent 100%),
                radial-gradient(1px 1px at 95% 72%, rgba(255, 255, 255, .7) 0%, transparent 100%),
                radial-gradient(1px 1px at 10% 92%, rgba(255, 255, 255, .5) 0%, transparent 100%),
                radial-gradient(1.2px 1.2px at 35% 5%, rgba(255, 255, 255, .8) 0%, transparent 100%),
                radial-gradient(1px 1px at 60% 3%, rgba(255, 220, 255, .7) 0%, transparent 100%),
                radial-gradient(1px 1px at 78% 95%, rgba(255, 255, 255, .6) 0%, transparent 100%);
            animation: twinkle 5s ease-in-out infinite alternate;
        }

        .profile-banner.mesh-blue {
            background:
                radial-gradient(ellipse at 15% 65%, rgba(96, 165, 250, .95) 0%, transparent 52%),
                radial-gradient(ellipse at 82% 12%, rgba(59, 130, 246, .9) 0%, transparent 48%),
                radial-gradient(ellipse at 68% 88%, rgba(29, 78, 216, .95) 0%, transparent 52%),
                radial-gradient(ellipse at 48% 48%, rgba(147, 197, 253, .5) 0%, transparent 65%),
                #0369a1;
        }

        .profile-banner.mesh-red {
            background:
                radial-gradient(ellipse at 18% 62%, rgba(252, 165, 165, .95) 0%, transparent 52%),
                radial-gradient(ellipse at 80% 15%, rgba(239, 68, 68, .9) 0%, transparent 48%),
                radial-gradient(ellipse at 65% 85%, rgba(185, 28, 28, .95) 0%, transparent 52%),
                radial-gradient(ellipse at 50% 45%, rgba(254, 202, 202, .45) 0%, transparent 65%),
                #9b1c1c;
        }

        .profile-banner.mesh-orange {
            background:
                radial-gradient(ellipse at 14% 60%, rgba(251, 191, 36, .9) 0%, transparent 52%),
                radial-gradient(ellipse at 83% 18%, rgba(249, 115, 22, .95) 0%, transparent 48%),
                radial-gradient(ellipse at 62% 84%, rgba(234, 88, 12, .9) 0%, transparent 52%),
                radial-gradient(ellipse at 46% 50%, rgba(253, 224, 71, .5) 0%, transparent 65%),
                #c2410c;
        }

        .profile-banner.mesh-green {
            background:
                radial-gradient(ellipse at 16% 64%, rgba(134, 239, 172, .95) 0%, transparent 52%),
                radial-gradient(ellipse at 81% 14%, rgba(34, 197, 94, .9) 0%, transparent 48%),
                radial-gradient(ellipse at 66% 86%, rgba(21, 128, 61, .95) 0%, transparent 52%),
                radial-gradient(ellipse at 49% 47%, rgba(187, 247, 208, .45) 0%, transparent 65%),
                #14532d;
        }

        .profile-banner.mesh-black {
            background:
                radial-gradient(ellipse at 17% 63%, rgba(107, 114, 128, .85) 0%, transparent 52%),
                radial-gradient(ellipse at 79% 16%, rgba(75, 85, 99, .9) 0%, transparent 48%),
                radial-gradient(ellipse at 64% 87%, rgba(55, 65, 81, .85) 0%, transparent 52%),
                radial-gradient(ellipse at 47% 49%, rgba(156, 163, 175, .3) 0%, transparent 65%),
                #030712;
        }

        .profile-banner.blob-blue {
            background:
                radial-gradient(circle at 25% 35%, rgba(186, 230, 253, .95) 0%, rgba(96, 165, 250, .7) 28%, transparent 55%),
                radial-gradient(circle at 78% 20%, rgba(59, 130, 246, .9) 0%, rgba(37, 99, 235, .6) 25%, transparent 52%),
                radial-gradient(circle at 60% 80%, rgba(29, 78, 216, 1) 0%, rgba(30, 64, 175, .7) 30%, transparent 58%),
                #1e40af;
        }

        .profile-banner.blob-red {
            background:
                radial-gradient(circle at 22% 38%, rgba(254, 202, 202, .95) 0%, rgba(248, 113, 113, .7) 28%, transparent 55%),
                radial-gradient(circle at 76% 22%, rgba(239, 68, 68, .9) 0%, rgba(220, 38, 38, .6) 25%, transparent 52%),
                radial-gradient(circle at 58% 78%, rgba(185, 28, 28, 1) 0%, rgba(153, 27, 27, .7) 30%, transparent 58%),
                #7f1d1d;
        }

        .profile-banner.blob-orange {
            background:
                radial-gradient(circle at 24% 36%, rgba(254, 243, 199, .95) 0%, rgba(252, 211, 77, .75) 28%, transparent 55%),
                radial-gradient(circle at 77% 21%, rgba(249, 115, 22, .9) 0%, rgba(234, 88, 12, .65) 25%, transparent 52%),
                radial-gradient(circle at 59% 79%, rgba(194, 65, 12, 1) 0%, rgba(154, 52, 18, .7) 30%, transparent 58%),
                #7c2d12;
        }

        .profile-banner.blob-green {
            background:
                radial-gradient(circle at 23% 37%, rgba(209, 250, 229, .95) 0%, rgba(110, 231, 183, .75) 28%, transparent 55%),
                radial-gradient(circle at 75% 23%, rgba(34, 197, 94, .9) 0%, rgba(22, 163, 74, .65) 25%, transparent 52%),
                radial-gradient(circle at 57% 77%, rgba(21, 128, 61, 1) 0%, rgba(20, 83, 45, .7) 30%, transparent 58%),
                #14532d;
        }

        .profile-banner.blob-black {
            background:
                radial-gradient(circle at 26% 34%, rgba(148, 163, 184, .6) 0%, rgba(100, 116, 139, .4) 28%, transparent 55%),
                radial-gradient(circle at 74% 24%, rgba(71, 85, 105, .8) 0%, rgba(51, 65, 85, .55) 25%, transparent 52%),
                radial-gradient(circle at 56% 76%, rgba(30, 41, 59, 1) 0%, rgba(15, 23, 42, .85) 30%, transparent 58%),
                #020617;
        }

        .btn-banner-edit {
            position: absolute;
            top: 12px;
            right: 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px 6px 10px;
            background: rgba(255, 255, 255, .18);
            backdrop-filter: blur(12px);
            border: 1.5px solid rgba(255, 255, 255, .35);
            border-radius: 50px;
            color: #fff;
            font-size: .75rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s;
            z-index: 10;
        }

        .btn-banner-edit:hover {
            background: rgba(255, 255, 255, .32);
            color: #fff;
        }

        .avatar-container {
            position: relative;
            margin-top: -48px;
            margin-bottom: 15px;
            padding-left: 28px;
            z-index: 20;
        }

        .avatar-wrap {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            border: 5px solid #fff;
            overflow: hidden;
            background: #fff;
            position: relative;
        }

        .avatar-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-initials {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #F97316, #ea580c);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 2.2rem;
            font-weight: 800;
        }

        .profile-body {
            padding: 16px 28px 24px;
        }

        .profile-name {
            font-size: 1.55rem;
            font-weight: 800;
            color: #0F172A;
            margin: 0 0 3px;
        }

        .profile-sub {
            font-size: .88rem;
            color: #64748b;
            font-weight: 500;
            margin-bottom: 2px;
        }

        .profile-location-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: .8rem;
            color: #94a3b8;
            margin-top: 2px;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .role-badge.super-admin {
            background: #fff7ed;
            color: #ea580c;
            border: 1px solid #fed7aa;
        }

        .role-badge.admin {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .role-badge.unit {
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }

        .role-badge .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .meta-row {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .meta-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
        }

        .btn-edit-profile {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 20px;
            background: #F97316;
            color: #fff;
            border-radius: 50px;
            font-size: .83rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s;
        }

        .btn-edit-profile:hover {
            background: #ea580c;
            color: #fff;
        }

        .actions-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            padding: 0 20px 20px;
            margin-top: 4px;
        }

        .action-tile {
            display: flex;
            flex-direction: column;
            gap: 5px;
            padding: 16px;
            border: 1px solid #f1f5f9;
            border-radius: 14px;
            background: #fafafa;
            text-decoration: none;
            color: inherit;
            transition: all .18s;
            position: relative;
            overflow: hidden;
        }

        .action-tile::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(249, 115, 22, .04), transparent);
            opacity: 0;
            transition: opacity .18s;
        }

        .action-tile:hover {
            border-color: #fed7aa;
            transform: translateY(-1px);
        }

        .action-tile:hover::before {
            opacity: 1;
        }

        .action-tile-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, #F97316, #ea580c);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }

        .action-tile-icon svg {
            width: 16px;
            height: 16px;
            stroke: #fff;
            stroke-width: 2;
        }

        .action-tile-title {
            font-size: .85rem;
            font-weight: 700;
            color: #0F172A;
            margin: 0;
        }

        .action-tile-desc {
            font-size: .76rem;
            color: #94a3b8;
            margin: 0;
            line-height: 1.4;
        }

        .section-divider {
            border: none;
            border-top: 1px solid #f1f5f9;
            margin: 0 20px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 1.25rem;
        }

        .info-card {
            background: #fff;
            border: 1px solid #f1f5f9;
            border-radius: 18px;
            padding: 20px;
        }

        .info-card:hover {
            border-color: #fed7aa;
        }

        .info-card-head {
            display: flex;
            align-items: center;
            gap: 9px;
            padding-bottom: 12px;
            margin-bottom: 14px;
            border-bottom: 1px solid #f8fafc;
        }

        .info-card-icon {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            background: rgba(249, 115, 22, .08);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info-card-icon svg {
            width: 16px;
            height: 16px;
            stroke: #F97316;
            stroke-width: 2;
        }

        .info-card-title {
            font-size: .78rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 7px 0;
            font-size: .85rem;
            border-bottom: 1px solid #f8fafc;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-row .lbl {
            color: #94a3b8;
            font-weight: 400;
        }

        .info-row .val {
            font-weight: 600;
            color: #0F172A;
            max-width: 60%;
            text-align: right;
            word-break: break-word;
        }

        .info-row .val.success {
            color: #16a34a;
        }

        .info-row .val.muted {
            color: #cbd5e1;
        }

        .info-row .val.accent {
            color: #F97316;
        }

        .bio-card {
            background: #fff;
            border: 1px solid #f1f5f9;
            border-radius: 18px;
            padding: 20px;
            margin-top: 1.25rem;
        }

        .bio-card-head {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 10px;
        }

        .bio-text {
            color: #475569;
            font-size: .88rem;
            line-height: 1.72;
            margin: 0;
        }

        .text-capitalize {
            text-transform: capitalize;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            .actions-strip {
                grid-template-columns: 1fr;
            }
            .meta-row {
                flex-direction: column;
            }
            .meta-right {
                align-items: flex-start;
            }
        }
    </style>
@endpush

@section('admin-content')
    <div class="profile-page">

        @if (session('success'))
            <div class="alert-custom success">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert-custom error">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="profile-hero">
            @php
                $admin = Auth::guard('admin')->user();
                $banner = $admin->getPreference('profile_banner', 'mesh-blue');
                $roleClass = match ($admin->role) {
                    'super_admin' => 'super-admin',
                    'unit' => 'unit',
                    default => 'admin',
                };
            @endphp

            <div class="profile-banner {{ $banner }}">
                <a href="{{ route('admin.profile.edit') }}" class="btn-banner-edit">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                        <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z" />
                    </svg>
                    Edit Profile
                </a>
            </div>

            <div class="avatar-container">
                <div class="avatar-wrap">
                    @if ($admin->photo_url)
                        <img src="{{ $admin->photo_url }}" alt="{{ $admin->nama }}">
                    @else
                        <div class="avatar-initials">{{ $admin->initials }}</div>
                    @endif
                </div>
            </div>

            <div class="profile-body">
                <div class="meta-row">
                    <div>
                        <h1 class="profile-name">{{ $admin->nama }}</h1>
                        <p class="profile-sub">{{ $admin->position ?? 'Administrator' }}
                            @if ($admin->department)
                                · {{ $admin->department }}
                            @endif
                        </p>
                        @if ($admin->location)
                            <p class="profile-location-tag">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg>
                                {{ $admin->location }}
                            </p>
                        @endif
                    </div>
                    <div class="meta-right">
                        <span class="role-badge {{ $roleClass }}">
                            <span class="dot"></span>
                            {{ $admin->role_label }}
                        </span>
                        <a href="{{ route('admin.profile.edit') }}" class="btn-edit-profile">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z" />
                            </svg>
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <hr class="section-divider">

            <div class="actions-strip">
                <a href="{{ route('admin.profile.edit') }}" class="action-tile">
                    <div class="action-tile-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </div>
                    <p class="action-tile-title">Update Info</p>
                    <p class="action-tile-desc">Keep your profile and contact info current.</p>
                </a>
                <a href="{{ route('admin.profile.edit') }}#security" class="action-tile">
                    <div class="action-tile-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                    </div>
                    <p class="action-tile-title">Security</p>
                    <p class="action-tile-desc">Change password and security settings.</p>
                </a>
                <a href="{{ route('admin.profile.edit') }}#preferences" class="action-tile">
                    <div class="action-tile-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3" />
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                        </svg>
                    </div>
                    <p class="action-tile-title">Preferences</p>
                    <p class="action-tile-desc">Theme, timezone, and notifications.</p>
                </a>
            </div>
        </div>

        @if ($admin->bio)
            <div class="bio-card">
                <div class="bio-card-head">
                    <div class="info-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#F97316" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </div>
                    <span class="info-card-title">About Me</span>
                </div>
                <p class="bio-text">{{ $admin->bio }}</p>
            </div>
        @endif

        <div class="info-grid">
            <div class="info-card">
                <div class="info-card-head">
                    <div class="info-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#F97316" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    <span class="info-card-title">Account Info</span>
                </div>
                <div class="info-row"><span class="lbl">Employee ID</span><span class="val">{{ $admin->employee_id ?? '—' }}</span></div>
                <div class="info-row"><span class="lbl">Department</span><span class="val">{{ $admin->department ?? '—' }}</span></div>
                <div class="info-row"><span class="lbl">Member since</span><span class="val">{{ $admin->created_at->format('M d, Y') }}</span></div>
                <div class="info-row"><span class="lbl">Email</span><span class="val">{{ $admin->email }}</span></div>
                <div class="info-row"><span class="lbl">Phone</span><span class="val {{ $admin->phone ? '' : 'muted' }}">{{ $admin->phone ?? 'Not set' }}</span></div>
                <div class="info-row"><span class="lbl">Location</span><span class="val {{ $admin->location ? '' : 'muted' }}">{{ $admin->location ?? 'Not set' }}</span></div>
            </div>

            <div class="info-card">
                <div class="info-card-head">
                    <div class="info-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#F97316" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                    </div>
                    <span class="info-card-title">Security Status</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Account status</span>
                    <span class="val {{ $admin->is_active ? 'success' : 'muted' }}">{{ $admin->is_active ? '● Active' : '● Inactive' }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Email verified</span>
                    <span class="val {{ $admin->email_verified_at ? 'success' : 'muted' }}">{{ $admin->email_verified_at ? '✓ Verified' : 'Pending' }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Two-Factor</span>
                    <span class="val {{ $admin->two_factor_enabled ? 'success' : 'muted' }}">{{ $admin->two_factor_enabled ? 'Enabled' : 'Disabled' }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Last login</span>
                    <span class="val accent">{{ $admin->last_login_at ? $admin->last_login_at->diffForHumans() : 'Never' }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Login IP</span>
                    <span class="val">{{ $admin->last_login_ip ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Total logins</span>
                    <span class="val">{{ number_format($admin->login_count ?? 0) }}</span>
                </div>
            </div>

            <div class="info-card">
                <div class="info-card-head">
                    <div class="info-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#F97316" stroke-width="2">
                            <circle cx="12" cy="12" r="3" />
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-2.82 1.18V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                        </svg>
                    </div>
                    <span class="info-card-title">Preferences</span>
                </div>
                @php
                    $prefs = $admin->preferences ?? [];
                    if (is_string($prefs)) {
                        $prefs = json_decode($prefs, true) ?? [];
                    }
                @endphp
                <div class="info-row"><span class="lbl">Theme</span><span class="val text-capitalize">{{ $prefs['theme'] ?? 'Light' }}</span></div>
                <div class="info-row"><span class="lbl">Language</span><span class="val text-uppercase">{{ $prefs['language'] ?? 'ID' }}</span></div>
                <div class="info-row"><span class="lbl">Timezone</span><span class="val">{{ $admin->timezone ?? 'Asia/Jakarta' }}</span></div>
                <div class="info-row">
                    <span class="lbl">Notifications</span>
                    <span class="val {{ $prefs['notifications'] ?? false ? 'success' : 'muted' }}">
                        {{ $prefs['notifications'] ?? false ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>
                <div class="info-row"><span class="lbl">Banner</span><span class="val text-capitalize">{{ str_replace('-', ' ', $banner) }}</span></div>
            </div>
        </div>

    </div>
@endsection