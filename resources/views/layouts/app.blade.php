<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="format-detection" content="telephone=no">
    <meta name="msapplication-tap-highlight" content="no">
    <title>@yield('meta_title', config('app.name', 'WINKY STORE') . ' - Premium Gaming & Tech Store')</title>
    <meta name="description" content="@yield('meta_description', 'WINKY STORE - Toko online premium untuk gaming gear, aksesoris tech, dan produk digital. Multi-seller marketplace dengan pengiriman cepat dan aman.')">
    <meta name="keywords" content="WINKY STORE, gaming store, tech store, marketplace, aksesoris gaming, PC gaming, streaming gear">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#00e5ff">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="WINKY STORE">
    <meta property="og:title" content="@yield('meta_title', config('app.name', 'WINKY STORE'))">
    <meta property="og:description" content="@yield('meta_description', 'Toko online premium untuk gaming gear dan aksesoris tech.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/og-banner.png') }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('meta_title', config('app.name', 'WINKY STORE'))">
    <meta name="twitter:description" content="@yield('meta_description', 'Toko online premium untuk gaming gear dan aksesoris tech.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *,*::before,*::after{box-sizing:border-box}
        :root {
            --bg-deep: #080d18;
            --bg-card: #0e1425;
            --bg-elevated: #121a30;
            --bg-glass: rgba(14, 20, 37, 0.65);
            --cyan: #00e5ff;
            --cyan-dim: #00b8d4;
            --blue: #2979ff;
            --magenta: #e040fb;
            --text: #f0f2f8;
            --text-secondary: rgba(240,242,248,0.55);
            --border: rgba(255,255,255,0.06);
            --border-light: rgba(255,255,255,0.10);
            --radius-sm: 12px;
            --radius-md: 18px;
            --radius-lg: 24px;
            --radius-xl: 32px;
            /* Compatibility aliases (used by auth, payment, older views) */
            --color-navy-700: #121a30;
            --color-navy-800: #0e1425;
            --color-navy-900: #080d18;
            --color-cyan-400: #00e5ff;
            --color-cyan-500: #00b8d4;
            --color-blue-500: #2979ff;
            --color-blue-600: #2962ff;
        }
        html { scroll-behavior: smooth; }
        body { margin: 0; font-family: 'Inter', sans-serif; background: var(--bg-deep); color: var(--text); -webkit-font-smoothing: antialiased; }
        .font-display { font-family: 'Space Grotesk', sans-serif; }

        /* ── Gradient Text ── */
        .gx-text {
            background: linear-gradient(135deg, var(--cyan), var(--blue), #82b1ff);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .gx-text-hot {
            background: linear-gradient(135deg, #ff6d00, #ff3d00, #ff1744);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .glow-cyan {
            background: linear-gradient(135deg, rgba(0,229,255,0.15), rgba(41,121,255,0.1));
            border: 1px solid rgba(0,229,255,0.2);
            box-shadow: 0 0 20px rgba(0,229,255,0.15);
        }
        .gradient-text {
            background: linear-gradient(135deg, var(--cyan), var(--blue));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }

        /* ── Buttons ── */
        .btn-glow {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 16px 36px; border-radius: 14px; font-weight: 700; font-size: 15px;
            background: linear-gradient(135deg, var(--cyan), var(--blue));
            color: #000; border: none; cursor: pointer; text-decoration: none;
            transition: all .35s cubic-bezier(.23,1,.32,1);
            box-shadow: 0 0 30px rgba(0,229,255,0.25);
        }
        .btn-glow:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 8px 40px rgba(0,229,255,0.45);
        }
        .btn-ghost {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 16px 36px; border-radius: 14px; font-weight: 700; font-size: 15px;
            background: transparent; color: var(--cyan); border: 1.5px solid rgba(0,229,255,0.35);
            cursor: pointer; text-decoration: none;
            transition: all .35s ease;
        }
        .btn-ghost:hover {
            background: rgba(0,229,255,0.08); border-color: var(--cyan);
            box-shadow: 0 0 25px rgba(0,229,255,0.15);
        }
        .btn-primary {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 12px 28px; border-radius: 14px; font-weight: 600; font-size: 14px;
            background: linear-gradient(135deg, var(--cyan), var(--blue));
            color: #000; border: none; cursor: pointer; text-decoration: none;
            transition: all .3s ease; font-family: inherit;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 32px rgba(0,229,255,0.3); }
        .btn-secondary {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 12px 28px; border-radius: 14px; font-weight: 600; font-size: 14px;
            background: transparent; color: var(--text-secondary); border: 1px solid var(--border);
            cursor: pointer; text-decoration: none; transition: all .3s ease; font-family: inherit;
        }
        .btn-secondary:hover { border-color: var(--border-light); color: var(--text); background: rgba(255,255,255,0.03); }

        /* ── Sticky Nav ── */
        .sticky-active {
            backdrop-filter: blur(30px) saturate(200%);
            -webkit-backdrop-filter: blur(30px) saturate(200%);
            background: rgba(8,13,24,0.88) !important;
            border-bottom: 1px solid var(--border);
        }

        /* ── Nav Link Underline ── */
        .nx-link { position: relative; transition: color .3s; }
        .nx-link::after {
            content: ''; position: absolute; bottom: -3px; left: 50%; width: 0; height: 2px;
            background: var(--cyan); transition: all .3s ease; transform: translateX(-50%);
        }
        .nx-link:hover::after, .nx-link.active::after { width: 55%; }

        /* ── Section Spacing ── */
        .sec { padding: 100px 0; }
        .sec-sm { padding: 60px 0; }
        .sec-dark { background: var(--bg-card); }
        .container { max-width: 1280px; margin: 0 auto; padding: 0 24px; }

        /* ── Section Header ── */
        .sh { text-align: center; margin-bottom: 56px; }
        .sh-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 14px; border-radius: 8px; font-size: 11px; font-weight: 700;
            letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 16px;
            background: rgba(0,229,255,0.08); border: 1px solid rgba(0,229,255,0.15); color: var(--cyan);
        }
        .sh h2 { font-family: 'Space Grotesk', sans-serif; font-size: clamp(32px,5vw,48px); font-weight: 800; margin: 0 0 12px; line-height: 1.1; }
        .sh p { color: var(--text-secondary); font-size: 17px; max-width: 560px; margin: 0 auto; line-height: 1.6; }

        /* ── Product Cards ── */
        .pcard {
            background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md);
            overflow: hidden; transition: all .45s cubic-bezier(.23,1,.32,1); position: relative;
        }
        .pcard:hover {
            transform: translateY(-8px);
            border-color: rgba(0,229,255,0.2);
            box-shadow: 0 24px 48px rgba(0,0,0,0.4), 0 0 0 1px rgba(0,229,255,0.1);
        }
        .pcard-img {
            background: #fff; border-radius: var(--radius-md); margin: 10px;
            display: flex; align-items: center; justify-content: center;
            min-height: 220px; overflow: hidden; position: relative;
        }
        .pcard-img img {
            width: 100%; height: 210px; object-fit: contain; padding: 20px;
            transition: transform .5s cubic-bezier(.23,1,.32,1);
        }
        .pcard:hover .pcard-img img { transform: scale(1.08); }
        .pcard-info { padding: 18px 20px 22px; }
        .pcard-brand { font-size: 11px; font-weight: 600; color: var(--cyan); text-transform: uppercase; letter-spacing: .8px; margin-bottom: 6px; }
        .pcard-name {
            font-size: 14px; font-weight: 600; color: var(--text); line-height: 1.45;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
            min-height: 42px; margin-bottom: 10px; transition: color .3s;
        }
        .pcard:hover .pcard-name { color: var(--cyan); }
        .pcard-price { font-family: 'Space Grotesk', sans-serif; font-size: 20px; font-weight: 800; color: var(--cyan); }
        .pcard-old { font-size: 12px; color: var(--text-secondary); text-decoration: line-through; margin-bottom: 2px; }
        .pcard-stars { display: flex; align-items: center; gap: 4px; margin-bottom: 8px; }
        .pcard-stars svg { width: 13px; height: 13px; fill: #ffc107; }
        .pcard-stars span { font-size: 12px; color: var(--text-secondary); }
        .pcard-stock { margin-left: auto; font-size: 11px; color: rgba(255,255,255,0.3); }

        /* ── Badge ── */
        .badge-sale {
            position: absolute; top: 12px; left: 12px; z-index: 2;
            padding: 4px 10px; border-radius: 8px;
            background: linear-gradient(135deg, #ff3d00, #ff6d00);
            color: #fff; font-size: 11px; font-weight: 800;
            box-shadow: 0 4px 12px rgba(255,61,0,0.35);
        }
        .wishlist-float {
            position: absolute; top: 16px; right: 16px; z-index: 2;
            width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
            background: rgba(0,0,0,0.5); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.7); cursor: pointer;
            opacity: 0; transform: translateY(6px); transition: all .3s ease;
        }
        .pcard:hover .wishlist-float { opacity: 1; transform: translateY(0); }
        .wishlist-float:hover { background: #ff3d00; color: #fff; }

        /* ── Category Cards ── */
        .ccard {
            background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md);
            padding: 32px 20px; text-align: center; cursor: pointer;
            transition: all .4s cubic-bezier(.23,1,.32,1); text-decoration: none; color: inherit;
        }
        .ccard:hover {
            border-color: rgba(0,229,255,0.25);
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.3), 0 0 0 1px rgba(0,229,255,0.1);
        }
        .ccard-icon {
            width: 64px; height: 64px; border-radius: 18px; margin: 0 auto 16px;
            display: flex; align-items: center; justify-content: center;
            transition: transform .35s cubic-bezier(.23,1,.32,1);
        }
        .ccard:hover .ccard-icon { transform: scale(1.12) rotate(-3deg); }
        .ccard h4 { font-family: 'Space Grotesk', sans-serif; font-size: 15px; font-weight: 700; margin: 0 0 4px; }
        .ccard span { font-size: 13px; color: var(--text-secondary); }

        /* ── Flash Sale Horizontal ── */
        .fs-scroll { display: flex; gap: 16px; overflow-x: auto; padding-bottom: 8px; scroll-snap-type: x mandatory; }
        .fs-scroll::-webkit-scrollbar { height: 4px; }
        .fs-scroll::-webkit-scrollbar-track { background: rgba(255,255,255,0.03); border-radius: 2px; }
        .fs-scroll::-webkit-scrollbar-thumb { background: rgba(0,229,255,0.3); border-radius: 2px; }
        .fs-card {
            flex: 0 0 280px; scroll-snap-align: start;
            background: var(--bg-card); border: 1px solid rgba(255,61,0,0.15); border-radius: var(--radius-md);
            overflow: hidden; transition: all .4s ease; position: relative;
        }
        .fs-card:hover { border-color: rgba(255,61,0,0.4); transform: translateY(-4px); box-shadow: 0 12px 32px rgba(255,61,0,0.1); }
        .fs-card .pcard-img { min-height: 180px; }
        .fs-card .pcard-img img { height: 170px; }

        /* ── Countdown ── */
        .cdigit {
            width: 52px; height: 58px; border-radius: 14px;
            background: var(--bg-deep); border: 1px solid rgba(0,229,255,0.15);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            font-family: 'Space Grotesk', sans-serif;
        }
        .cdigit-num { font-size: 24px; font-weight: 800; color: var(--cyan); line-height: 1; }
        .cdigit-label { font-size: 9px; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; margin-top: 2px; }
        .cdot { font-size: 24px; font-weight: 800; color: var(--cyan); padding: 0 4px; align-self: flex-start; margin-top: 12px; }

        /* ── Promo Banner ── */
        .promo-wrap {
            background: linear-gradient(135deg, #0d1b3e 0%, #1a0a3e 40%, #2d0a4e 100%);
            border-radius: var(--radius-xl); overflow: hidden; position: relative;
            border: 1px solid rgba(224,64,251,0.15);
        }
        .promo-glow-1 { position: absolute; top: -100px; right: -100px; width: 400px; height: 400px; background: rgba(0,229,255,0.08); border-radius: 50%; filter: blur(80px); pointer-events: none; }
        .promo-glow-2 { position: absolute; bottom: -80px; left: -80px; width: 300px; height: 300px; background: rgba(224,64,251,0.08); border-radius: 50%; filter: blur(80px); pointer-events: none; }

        /* ── Brand Logos ── */
        .blogo {
            background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md);
            padding: 24px; display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 8px; min-height: 100px; transition: all .4s ease; cursor: pointer; text-decoration: none;
        }
        .blogo:hover { border-color: rgba(0,229,255,0.2); transform: translateY(-3px); background: var(--bg-elevated); }
        .blogo-letter {
            width: 48px; height: 48px; border-radius: 14px;
            background: linear-gradient(135deg, rgba(0,229,255,0.1), rgba(41,121,255,0.1));
            display: flex; align-items: center; justify-content: center;
            font-family: 'Space Grotesk', sans-serif; font-weight: 800; font-size: 20px; color: var(--cyan);
            transition: transform .3s ease;
        }
        .blogo:hover .blogo-letter { transform: scale(1.1); }
        .blogo span { font-size: 12px; color: var(--text-secondary); font-weight: 500; }

        /* ── Advantage Cards ── */
        .adv-card {
            background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg);
            padding: 40px 32px; transition: all .4s cubic-bezier(.23,1,.32,1);
        }
        .adv-card:hover { border-color: rgba(0,229,255,0.15); transform: translateY(-4px); }
        .adv-icon {
            width: 60px; height: 60px; border-radius: 18px; display: flex; align-items: center; justify-content: center;
            margin-bottom: 24px; transition: transform .3s ease;
        }
        .adv-card:hover .adv-icon { transform: scale(1.08); }
        .adv-card h3 { font-family: 'Space Grotesk', sans-serif; font-size: 18px; font-weight: 700; margin: 0 0 10px; }
        .adv-card p { font-size: 14px; color: var(--text-secondary); line-height: 1.65; margin: 0; }

        /* ── Newsletter ── */
        .nl-input {
            flex: 1; padding: 16px 22px; border-radius: 12px;
            background: rgba(0,0,0,0.35); border: 1px solid var(--border-light);
            color: #fff; font-size: 15px; outline: none;
            transition: border-color .3s ease;
        }
        .nl-input::placeholder { color: rgba(255,255,255,0.3); }
        .nl-input:focus { border-color: rgba(0,229,255,0.4); }

        /* ── Footer ── */
        .footer-link { color: var(--text-secondary); text-decoration: none; font-size: 14px; transition: color .3s; display: block; padding: 5px 0; }
        .footer-link:hover { color: var(--cyan); }
        .footer-head { font-family: 'Space Grotesk', sans-serif; font-size: 13px; font-weight: 700; color: var(--text); text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 20px; }
        .footer-social {
            width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
            background: var(--bg-elevated); border: 1px solid var(--border); color: var(--text-secondary);
            transition: all .3s ease; cursor: pointer; text-decoration: none;
        }
        .footer-social:hover { color: var(--cyan); border-color: rgba(0,229,255,0.3); transform: translateY(-2px); }

        /* ── Mobile Menu ── */
        .mmenu { transform: translateX(100%); transition: transform .4s cubic-bezier(.23,1,.32,1); }
        .mmenu.open { transform: translateX(0); }
        body.menu-open { overflow: hidden; }

        /* ── Toast ── */
        .toast-box {
            position: fixed; top: 100px; right: 24px; z-index: 9999;
            display: flex; flex-direction: column; gap: 10px; pointer-events: none;
        }
        .toast-item {
            pointer-events: auto; display: flex; align-items: center; gap: 10px;
            padding: 14px 20px; border-radius: 14px; font-size: 14px; font-weight: 500;
            background: rgba(14,20,37,0.95); backdrop-filter: blur(12px);
            border: 1px solid var(--border-light); box-shadow: 0 12px 40px rgba(0,0,0,0.5);
            transform: translateX(120%); opacity: 0;
            transition: transform .35s cubic-bezier(.4,0,.2,1), opacity .35s ease; max-width: 360px;
        }
        .toast-item.show { transform: translateX(0); opacity: 1; }
        .toast-item.hide { transform: translateX(120%); opacity: 0; }
        .toast-item .ti { width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

        /* ── Search Overlay ── */
        .sovl {
            position: fixed; inset: 0; background: rgba(8,13,24,0.94);
            backdrop-filter: blur(30px); z-index: 9998;
            display: flex; align-items: flex-start; justify-content: center; padding-top: 15vh;
            opacity: 0; visibility: hidden; transition: all .3s ease;
        }
        .sovl.open { opacity: 1; visibility: visible; }
        .sovl-box { width: 90%; max-width: 600px; transform: translateY(-20px) scale(.97); transition: transform .3s cubic-bezier(.23,1,.32,1); }
        .sovl.open .sovl-box { transform: translateY(0) scale(1); }

        /* ── Search Spinner ── */
        .search-spinner {
            width: 28px; height: 28px; border: 3px solid rgba(255,255,255,0.08);
            border-top-color: var(--cyan); border-radius: 50%;
            animation: spin .7s linear infinite; margin: 0 auto;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Mobile Bottom Navigation ── */
        .bottom-nav {
            display: none; position: fixed; bottom: 0; left: 0; right: 0; z-index: 90;
            background: rgba(8,13,24,0.95); backdrop-filter: blur(20px) saturate(200%);
            border-top: 1px solid var(--border); padding: 6px 0 env(safe-area-inset-bottom, 8px);
        }
        .bnav-item {
            flex: 1; display: flex; flex-direction: column; align-items: center; gap: 2px;
            padding: 6px 0; color: var(--text-secondary); text-decoration: none;
            font-size: 10px; font-weight: 500; transition: color .2s;
            background: none; border: none; cursor: pointer; font-family: inherit;
        }
        .bnav-item.active { color: var(--cyan); }
        .bnav-item svg { width: 20px; height: 20px; }
        .bnav-badge {
            position: absolute; top: -4px; right: -8px; min-width: 16px; height: 16px;
            background: var(--cyan); color: #000; font-size: 9px; font-weight: 800;
            border-radius: 8px; display: flex; align-items: center; justify-content: center;
            padding: 0 4px;
        }
        @media (max-width: 768px) {
            .bottom-nav { display: flex; }
            body { padding-bottom: 72px; }
        }

        /* ── Quick View Modal ── */
        .qv-overlay {
            position: fixed; inset: 0; z-index: 9995; background: rgba(8,13,24,0.85);
            backdrop-filter: blur(12px); opacity: 0; visibility: hidden; transition: all .3s ease;
        }
        .qv-overlay.open { opacity: 1; visibility: visible; }
        .qv-modal {
            position: fixed; top: 50%; left: 50%; transform: translate(-50%,-50%) scale(.95);
            z-index: 9996; width: 90%; max-width: 800px; max-height: 85vh;
            background: var(--bg-card); border: 1px solid var(--border-light); border-radius: var(--radius-lg);
            overflow: hidden; opacity: 0; visibility: hidden; transition: all .35s cubic-bezier(.23,1,.32,1);
        }
        .qv-modal.open { opacity: 1; visibility: visible; transform: translate(-50%,-50%) scale(1); }
        .qv-close {
            position: absolute; top: 16px; right: 16px; z-index: 10;
            width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
            background: rgba(0,0,0,0.4); border: 1px solid var(--border); color: var(--text-secondary);
            cursor: pointer; transition: all .25s ease;
        }
        .qv-close:hover { color: var(--text); background: rgba(255,255,255,0.08); }
        .qv-body { display: grid; grid-template-columns: 1fr 1fr; max-height: 85vh; overflow-y: auto; }
        .qv-img { background: #fff; display: flex; align-items: center; justify-content: center; padding: 32px; min-height: 300px; }
        .qv-img img { max-width: 100%; max-height: 350px; object-fit: contain; }
        .qv-info { padding: 32px; display: flex; flex-direction: column; gap: 16px; overflow-y: auto; }
        .qv-brand { font-size: 12px; font-weight: 700; color: var(--cyan); text-transform: uppercase; letter-spacing: 1px; }
        .qv-name { font-family: 'Space Grotesk', sans-serif; font-size: 22px; font-weight: 800; color: var(--text); margin: 0; line-height: 1.3; }
        .qv-rating { display: flex; align-items: center; gap: 6px; font-size: 13px; color: var(--text-secondary); }
        .qv-price-row { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .qv-price { font-family: 'Space Grotesk', sans-serif; font-size: 26px; font-weight: 800; color: var(--cyan); }
        .qv-old-price { font-size: 14px; color: var(--text-secondary); text-decoration: line-through; }
        .qv-discount { display: inline-flex; padding: 3px 10px; border-radius: 8px; background: linear-gradient(135deg, #ff3d00, #ff6d00); color: #fff; font-size: 12px; font-weight: 800; }
        .qv-desc { font-size: 13px; color: var(--text-secondary); line-height: 1.6; margin: 0; }
        .qv-variants { margin-top: 4px; }
        .qv-qty-row { display: flex; align-items: center; gap: 16px; }
        .qv-qty-label { font-size: 13px; font-weight: 600; color: var(--text-secondary); }
        .qv-stock { font-size: 12px; font-weight: 600; margin-left: auto; }
        .qv-actions { display: flex; gap: 10px; margin-top: 4px; }
        .qv-wish-btn {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; padding: 12px; border-radius: 12px; font-size: 13px; font-weight: 600;
            border: 1px solid var(--border); background: transparent; color: var(--text-secondary);
            cursor: pointer; transition: all .25s ease; font-family: inherit;
        }
        .qv-wish-btn:hover { border-color: #ff3d00; color: #ff3d00; background: rgba(255,61,0,0.06); }
        @media (max-width: 640px) {
            .qv-modal { width: 95%; max-height: 90vh; }
            .qv-body { grid-template-columns: 1fr; }
            .qv-img { min-height: 200px; padding: 20px; }
            .qv-info { padding: 20px; }
        }

        /* ── Scrollbar ── */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

        /* ── Product Grid & Card ── */
@media (min-width: 1025px) {
    .prod-grid { grid-template-columns: repeat(4,1fr); gap: 20px; }
    .cat-grid { grid-template-columns: repeat(6,1fr); gap: 16px; }
    .adv-grid, .adv-grid-2 { grid-template-columns: repeat(4,1fr); gap: 20px; }
    .brand-grid { grid-template-columns: repeat(8,1fr); gap: 12px; }
    .fs-scroll { gap: 12px; }
    .fs-card { flex: 0 0 280px; }
}

@media (min-width: 768px) and (max-width: 1024px) {
    .prod-grid { grid-template-columns: repeat(3,1fr); gap: 16px; }
    .cat-grid { grid-template-columns: repeat(4,1fr); gap: 14px; }
    .adv-grid, .adv-grid-2 { grid-template-columns: repeat(3,1fr); gap: 16px; }
    .brand-grid { grid-template-columns: repeat(6,1fr); gap: 14px; }
    .fs-scroll { gap: 10px; }
    .fs-card { flex: 0 0 240px; }
}

@media (max-width: 767px) {
    .prod-grid, .catalog-grid { grid-template-columns: repeat(2,1fr) !important; gap: 12px !important; }
    .cat-grid, .adv-grid, .adv-grid-2 { grid-template-columns: repeat(2,1fr) !important; }
    .brand-grid { grid-template-columns: repeat(3,1fr) !important; }
    .sec { padding: 48px 0; }
    .sh h2 { font-size: 24px !important; }
    .fs-card { flex: 0 0 220px; }
}

        /* ── Product Image (used by partials/product-image.blade.php) ── */
        .product-image { width: 100%; height: 220px; object-fit: contain; padding: 20px; }

        /* ── Add to Cart Button ── */
        .add-cart-btn {
            width: 100%; padding: 10px 0; border: 1px solid rgba(0,229,255,0.2); border-radius: 10px;
            background: transparent; color: var(--cyan); font-size: 12px; font-weight: 700;
            cursor: pointer; transition: all .3s ease; text-align: center; margin-top: 10px;
        }
        .add-cart-btn:hover { background: rgba(0,229,255,0.08); border-color: var(--cyan); }

        /* ── Catalog Page ── */
        .cat-sidebar { position: sticky; top: 88px; align-self: start; }
        .filter-panel {
            background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md);
            padding: 24px; transition: all .3s ease;
        }
        .filter-panel h3 {
            font-family: 'Space Grotesk', sans-serif; font-size: 13px; font-weight: 700;
            color: var(--text); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px;
        }
        .filter-link {
            display: flex; align-items: center; justify-content: space-between;
            padding: 10px 14px; border-radius: 10px; font-size: 13px; color: var(--text-secondary);
            text-decoration: none; transition: all .25s ease;
        }
        .filter-link:hover { color: var(--text); background: rgba(255,255,255,0.04); }
        .filter-link.active { color: var(--cyan); background: rgba(0,229,255,0.08); font-weight: 600; }
        .filter-link .cnt { font-size: 11px; opacity: 0.5; }
        .filter-chip {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 14px; border-radius: 100px; font-size: 12px; font-weight: 500;
            background: rgba(0,229,255,0.08); border: 1px solid rgba(0,229,255,0.15); color: var(--cyan);
            text-decoration: none; transition: all .25s ease;
        }
        .filter-chip:hover { background: rgba(0,229,255,0.14); border-color: var(--cyan); }
        .filter-chip svg { width: 12px; height: 12px; }
        .sort-btn {
            padding: 8px 16px; border-radius: 10px; font-size: 12px; font-weight: 600;
            border: 1px solid var(--border); background: transparent; color: var(--text-secondary);
            cursor: pointer; transition: all .25s ease; text-decoration: none;
        }
        .sort-btn:hover { border-color: rgba(0,229,255,0.3); color: var(--text); }
        .sort-btn.active { background: rgba(0,229,255,0.1); border-color: rgba(0,229,255,0.3); color: var(--cyan); }
        .cat-card {
            background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md);
            overflow: hidden; transition: all .4s cubic-bezier(.23,1,.32,1); position: relative;
            display: flex; flex-direction: column;
        }
        .cat-card:hover { transform: translateY(-6px); border-color: rgba(0,229,255,0.2); box-shadow: 0 20px 40px rgba(0,0,0,0.35); }
        .cat-card .card-img-wrap {
            background: #fff; border-radius: var(--radius-md); margin: 8px;
            display: flex; align-items: center; justify-content: center;
            min-height: 200px; overflow: hidden; position: relative;
        }
        .cat-card .card-img-wrap img { width: 100%; height: 190px; object-fit: contain; padding: 16px; transition: transform .5s cubic-bezier(.23,1,.32,1); }
        .cat-card:hover .card-img-wrap img { transform: scale(1.06); }
        .cat-card .card-body { padding: 16px 18px 18px; flex: 1; display: flex; flex-direction: column; }
        .cat-card .card-brand { font-size: 10px; font-weight: 700; color: var(--cyan); text-transform: uppercase; letter-spacing: .8px; margin-bottom: 4px; }
        .cat-card .card-name {
            font-size: 13px; font-weight: 600; color: var(--text); line-height: 1.4;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
            min-height: 38px; margin-bottom: 8px; transition: color .3s; flex: 1;
        }
        .cat-card:hover .card-name { color: var(--cyan); }
        .cat-card .card-rating { display: flex; align-items: center; gap: 4px; margin-bottom: 8px; }
        .cat-card .card-rating svg { width: 12px; height: 12px; fill: #ffc107; }
        .cat-card .card-rating span { font-size: 11px; color: var(--text-secondary); }
        .cat-card .card-price { font-family: 'Space Grotesk', sans-serif; font-size: 18px; font-weight: 800; color: var(--cyan); margin-top: auto; }
        .cat-card .card-old { font-size: 11px; color: var(--text-secondary); text-decoration: line-through; margin-bottom: 2px; }
        .cat-card .card-stock { font-size: 11px; color: rgba(255,255,255,0.3); margin-top: 4px; }
        .cat-card .card-actions {
            display: flex; gap: 8px; margin-top: 12px; opacity: 0; transform: translateY(8px);
            transition: all .3s ease;
        }
        .cat-card:hover .card-actions { opacity: 1; transform: translateY(0); }
        .card-actions button, .card-actions a {
            flex: 1; padding: 9px 0; border-radius: 10px; font-size: 11px; font-weight: 700;
            cursor: pointer; transition: all .25s ease; text-align: center; text-decoration: none;
            border: 1px solid rgba(0,229,255,0.2); background: transparent; color: var(--cyan);
        }
        .card-actions button:hover, .card-actions a:hover { background: rgba(0,229,255,0.1); border-color: var(--cyan); }

        /* ── AI Suggest Buttons ── */
        .ai-suggest-btn {
            padding: 8px 16px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.08);
            background: rgba(255,255,255,0.04); color: var(--text-secondary); font-size: 12px;
            cursor: pointer; transition: all 0.2s; white-space: nowrap; font-family: inherit;
        }
        .ai-suggest-btn:hover {
            border-color: rgba(0,229,255,0.3); color: var(--cyan); background: rgba(0,229,255,0.08);
        }
        .card-actions .btn-wish { flex: 0 0 38px; border-color: rgba(255,255,255,0.1); color: var(--text-secondary); }
        .card-actions .btn-wish:hover { border-color: #ff3d00; color: #ff3d00; background: rgba(255,61,0,0.08); }
        .card-badge {
            position: absolute; top: 12px; left: 12px; z-index: 2;
            padding: 4px 10px; border-radius: 8px;
            background: linear-gradient(135deg, #ff3d00, #ff6d00);
            color: #fff; font-size: 11px; font-weight: 800;
            box-shadow: 0 4px 12px rgba(255,61,0,0.35);
        }
        .card-badge.featured { background: linear-gradient(135deg, var(--blue), var(--cyan)); }
        .card-soldout {
            position: absolute; inset: 0; z-index: 10; display: flex; align-items: center; justify-content: center;
            background: rgba(8,13,24,0.7); backdrop-filter: blur(4px); border-radius: var(--radius-md);
        }
        .card-soldout span {
            padding: 8px 20px; background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3);
            color: #f87171; font-size: 12px; font-weight: 700; border-radius: 10px;
        }
        .mobile-filter-overlay {
            position: fixed; inset: 0; z-index: 9990; background: rgba(8,13,24,0.85);
            backdrop-filter: blur(12px); opacity: 0; visibility: hidden; transition: all .3s ease;
        }
        .mobile-filter-overlay.open { opacity: 1; visibility: visible; }
        .mobile-filter-panel {
            position: fixed; top: 0; right: 0; bottom: 0; width: 320px; max-width: 85vw;
            z-index: 9991; background: var(--bg-deep); border-left: 1px solid var(--border);
            transform: translateX(100%); transition: transform .35s cubic-bezier(.23,1,.32,1);
            overflow-y: auto; padding: 24px;
        }
        .mobile-filter-panel.open { transform: translateX(0); }

        /* ── Catalog Grid/List Responsive ── */
        @media (max-width: 1024px) {
            .catalog-layout { grid-template-columns: 1fr !important; }
        }
        @media (max-width: 768px) {
            .catalog-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 12px !important; }
        }
        .pcard-list {
            flex-direction: row !important; align-items: center !important;
        }
        .pcard-list .pcard-img {
            width: 140px !important; min-height: 140px !important; flex-shrink: 0;
        }
        .pcard-list .pcard-img img { height: 120px !important; }
        .pcard-list .pcard-info {
            flex: 1 !important; padding: 16px 20px !important; display: flex;
            flex-direction: row; flex-wrap: wrap; align-items: center; gap: 8px 20px;
        }
        .pcard-list .pcard-brand { width: 100% !important; }
        .pcard-list .pcard-name { font-size: 15px !important; min-height: auto !important; flex: 1 !important; }
        .pcard-list .pcard-price { margin-top: 0 !important; }
        .pcard-list .add-cart-btn { width: auto !important; margin-top: 0 !important; padding: 8px 20px !important; }
        .pcard-list .wishlist-float, .pcard-list .qv-float { display: none !important; }

        /* ── Detail Page ── */
        .detail-gallery { position: relative; }
        .detail-main-img {
            background: #fff; border-radius: var(--radius-lg); overflow: hidden;
            aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center;
            border: 1px solid var(--border); position: relative;
        }
        .detail-main-img img { width: 100%; height: 100%; object-fit: contain; padding: 24px; transition: transform .4s ease; }
        .detail-main-img:hover img { transform: scale(1.05); }
        .detail-thumbs { display: flex; gap: 10px; margin-top: 12px; overflow-x: auto; padding-bottom: 4px; }
        .detail-thumbs::-webkit-scrollbar { height: 3px; }
        .detail-thumbs::-webkit-scrollbar-thumb { background: rgba(0,229,255,0.2); border-radius: 2px; }
        .detail-thumb {
            flex: 0 0 72px; height: 72px; border-radius: 12px; overflow: hidden;
            border: 2px solid transparent; cursor: pointer; transition: all .25s ease;
            background: #fff; display: flex; align-items: center; justify-content: center;
        }
        .detail-thumb.active { border-color: var(--cyan); box-shadow: 0 0 12px rgba(0,229,255,0.2); }
        .detail-thumb:hover { border-color: rgba(0,229,255,0.4); }
        .detail-thumb img { width: 100%; height: 100%; object-fit: contain; padding: 6px; }
        .detail-info { display: flex; flex-direction: column; gap: 20px; }
        .detail-brand {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 5px 14px; border-radius: 100px; font-size: 12px; font-weight: 600;
            background: rgba(0,229,255,0.08); border: 1px solid rgba(0,229,255,0.15); color: var(--cyan);
            text-decoration: none; width: fit-content; transition: all .25s ease;
        }
        .detail-brand:hover { background: rgba(0,229,255,0.14); }
        .detail-price-box {
            background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md);
            padding: 24px;
        }
        .detail-price { font-family: 'Space Grotesk', sans-serif; font-size: 32px; font-weight: 800; color: var(--cyan); }
        .detail-old-price { font-size: 14px; color: var(--text-secondary); text-decoration: line-through; }
        .detail-discount {
            display: inline-flex; padding: 4px 12px; border-radius: 8px;
            background: linear-gradient(135deg, #ff3d00, #ff6d00); color: #fff;
            font-size: 13px; font-weight: 800;
        }
        .variant-group { margin-bottom: 4px; }
        .variant-label { font-size: 12px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .8px; margin-bottom: 10px; }
        .variant-btn {
            padding: 10px 20px; border-radius: 12px; font-size: 13px; font-weight: 600;
            border: 1.5px solid var(--border-light); background: transparent; color: var(--text-secondary);
            cursor: pointer; transition: all .25s ease;
        }
        .variant-btn:hover { border-color: rgba(0,229,255,0.4); color: var(--text); }
        .variant-btn.active { border-color: var(--cyan); color: var(--cyan); background: rgba(0,229,255,0.06); box-shadow: 0 0 12px rgba(0,229,255,0.1); }
        .variant-btn.out { opacity: 0.35; cursor: not-allowed; text-decoration: line-through; }
        .qty-box {
            display: inline-flex; align-items: center; border: 1.5px solid var(--border-light);
            border-radius: 12px; overflow: hidden;
        }
        .qty-box button {
            width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;
            background: transparent; border: none; color: var(--text-secondary); font-size: 18px;
            cursor: pointer; transition: all .2s ease;
        }
        .qty-box button:hover { color: var(--cyan); background: rgba(0,229,255,0.06); }
        .qty-box .qty-val {
            width: 52px; text-align: center; font-size: 15px; font-weight: 700; color: var(--text);
            background: transparent; border: none; border-left: 1px solid var(--border-light);
            border-right: 1px solid var(--border-light); height: 44px;
        }
        .cta-row { display: flex; gap: 12px; }
        .cta-cart {
            flex: 1; padding: 16px 24px; border-radius: 14px; font-size: 15px; font-weight: 800;
            border: 2px solid var(--cyan); background: transparent; color: var(--cyan);
            cursor: pointer; transition: all .35s cubic-bezier(.23,1,.32,1);
            display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .cta-cart:hover { background: rgba(0,229,255,0.08); box-shadow: 0 0 30px rgba(0,229,255,0.15); transform: translateY(-1px); }
        .cta-buy {
            flex: 1; padding: 16px 24px; border-radius: 14px; font-size: 15px; font-weight: 800;
            border: none; background: linear-gradient(135deg, var(--cyan), var(--blue));
            color: #000; cursor: pointer; transition: all .35s cubic-bezier(.23,1,.32,1);
            box-shadow: 0 0 30px rgba(0,229,255,0.2);
            display: flex; align-items: center; justify-content: center; gap: 10px; text-decoration: none;
        }
        .cta-buy:hover { transform: translateY(-2px) scale(1.01); box-shadow: 0 8px 40px rgba(0,229,255,0.4); }
        .detail-tabs { border-top: 1px solid var(--border); padding-top: 32px; }
        .tab-btns { display: flex; gap: 0; border-bottom: 1px solid var(--border); margin-bottom: 24px; }
        .tab-btn {
            padding: 14px 24px; font-size: 14px; font-weight: 600; color: var(--text-secondary);
            border: none; background: none; cursor: pointer; position: relative; transition: color .3s;
        }
        .tab-btn::after {
            content: ''; position: absolute; bottom: -1px; left: 0; right: 0; height: 2px;
            background: var(--cyan); transform: scaleX(0); transition: transform .3s ease;
        }
        .tab-btn.active { color: var(--cyan); }
        .tab-btn.active::after { transform: scaleX(1); }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .spec-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .spec-table tr:nth-child(even) { background: rgba(255,255,255,0.02); }
        .spec-table td { padding: 12px 16px; font-size: 13px; border-bottom: 1px solid var(--border); }
        .spec-table td:first-child { color: var(--text-secondary); width: 40%; font-weight: 500; }
        .spec-table td:last-child { color: var(--text); }
        .trust-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .trust-item {
            display: flex; align-items: center; gap: 10px; padding: 12px 14px;
            background: rgba(255,255,255,0.02); border: 1px solid var(--border); border-radius: 12px;
        }
        .trust-item svg { width: 18px; height: 18px; color: var(--cyan); flex-shrink: 0; }
        .trust-item span { font-size: 12px; color: var(--text-secondary); }
        .sticky-cart-bar {
            display: none; position: fixed; bottom: 0; left: 0; right: 0; z-index: 80;
            background: rgba(8,13,24,0.95); backdrop-filter: blur(20px);
            border-top: 1px solid var(--border); padding: 12px 16px;
        }
        .sticky-cart-bar .cta-row { gap: 8px; }
        .sticky-cart-bar .cta-cart, .sticky-cart-bar .cta-buy { padding: 14px 16px; font-size: 13px; border-radius: 12px; }
        .share-btn {
            display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px;
            border-radius: 10px; font-size: 12px; font-weight: 600; border: 1px solid var(--border-light);
            background: transparent; color: var(--text-secondary); cursor: pointer; transition: all .25s;
        }
        .share-btn:hover { border-color: rgba(0,229,255,0.3); color: var(--cyan); }

        /* ── Pagination ── */
        .page-link {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 40px; height: 40px; padding: 0 12px; border-radius: 10px;
            font-size: 13px; font-weight: 600; color: var(--text-secondary);
            border: 1px solid var(--border); background: transparent;
            text-decoration: none; transition: all .25s ease;
        }
        .page-link:hover { border-color: rgba(0,229,255,0.3); color: var(--text); background: rgba(255,255,255,0.03); }
        .page-link.active { background: rgba(0,229,255,0.1); border-color: rgba(0,229,255,0.3); color: var(--cyan); }

        /* ── Cart Page ── */
        .cart-item {
            display: flex; gap: 20px; padding: 24px; background: var(--bg-card);
            border: 1px solid var(--border); border-radius: var(--radius-md);
            transition: all .3s ease; position: relative;
        }
        .cart-item:hover { border-color: rgba(0,229,255,0.15); }
        .cart-item-img {
            flex: 0 0 120px; height: 120px; background: #fff; border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center; overflow: hidden;
        }
        .cart-item-img img { width: 100%; height: 100%; object-fit: contain; padding: 12px; }
        .cart-item-body { flex: 1; min-width: 0; display: flex; flex-direction: column; justify-content: space-between; }
        .cart-item-brand { font-size: 11px; font-weight: 700; color: var(--cyan); text-transform: uppercase; letter-spacing: .8px; margin-bottom: 2px; }
        .cart-item-name { font-size: 15px; font-weight: 600; color: var(--text); line-height: 1.35; margin-bottom: 2px; }
        .cart-item-variant { font-size: 12px; color: var(--text-secondary); margin-bottom: 8px; }
        .cart-item-price-row { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
        .cart-item-unit { font-size: 12px; color: var(--text-secondary); }
        .cart-item-total { font-family: 'Space Grotesk', sans-serif; font-size: 18px; font-weight: 800; color: var(--cyan); }
        .cart-item-old { font-size: 11px; color: var(--text-secondary); text-decoration: line-through; }
        .cart-item-delete {
            position: absolute; top: 16px; right: 16px; width: 32px; height: 32px;
            border-radius: 8px; display: flex; align-items: center; justify-content: center;
            background: transparent; border: 1px solid transparent; color: var(--text-secondary);
            cursor: pointer; transition: all .25s ease;
        }
        .cart-item-delete:hover { color: #f87171; border-color: rgba(239,68,68,0.3); background: rgba(239,68,68,0.06); }
        .cart-summary {
            background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg);
            padding: 28px; position: sticky; top: 88px;
        }
        .cart-summary h3 {
            font-family: 'Space Grotesk', sans-serif; font-size: 16px; font-weight: 700;
            color: var(--text); margin: 0 0 20px; padding-bottom: 16px; border-bottom: 1px solid var(--border);
        }
        .summary-row {
            display: flex; justify-content: space-between; align-items: center;
            font-size: 14px; color: var(--text-secondary); padding: 8px 0;
        }
        .summary-row.total {
            font-size: 18px; font-weight: 800; color: var(--text); padding-top: 16px;
            margin-top: 8px; border-top: 1px solid var(--border);
        }
        .summary-row.total span:last-child { font-family: 'Space Grotesk', sans-serif; color: var(--cyan); font-size: 22px; }
        .cart-empty {
            text-align: center; padding: 80px 24px;
        }
        .cart-empty-icon {
            width: 88px; height: 88px; border-radius: 24px; background: var(--bg-card);
            border: 1px solid var(--border); display: flex; align-items: center; justify-content: center;
            margin: 0 auto 28px;
        }
        .cart-item-qty { display: flex; align-items: center; gap: 0; border: 1.5px solid var(--border-light); border-radius: 10px; overflow: hidden; }
        .cart-item-qty button {
            width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
            background: transparent; border: none; color: var(--text-secondary); font-size: 16px;
            cursor: pointer; transition: all .2s ease;
        }
        .cart-item-qty button:hover { color: var(--cyan); background: rgba(0,229,255,0.06); }
        .cart-item-qty span { width: 40px; text-align: center; font-size: 14px; font-weight: 700; color: var(--text); border-left: 1px solid var(--border-light); border-right: 1px solid var(--border-light); height: 36px; display: flex; align-items: center; justify-content: center; }

        /* ── Checkout Page ── */
        .checkout-stepper {
            display: flex; align-items: center; justify-content: center; gap: 0;
            margin-bottom: 48px; padding: 0 24px;
        }
        .step { display: flex; align-items: center; gap: 10px; }
        .step-num {
            width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-family: 'Space Grotesk', sans-serif; font-size: 14px; font-weight: 800;
            border: 2px solid var(--border); color: var(--text-secondary); transition: all .3s;
        }
        .step.active .step-num { border-color: var(--cyan); color: var(--cyan); background: rgba(0,229,255,0.08); box-shadow: 0 0 16px rgba(0,229,255,0.15); }
        .step.done .step-num { border-color: var(--cyan); color: #000; background: var(--cyan); }
        .step-label { font-size: 13px; font-weight: 600; color: var(--text-secondary); }
        .step.active .step-label { color: var(--text); }
        .step.done .step-label { color: var(--cyan); }
        .step-line { flex: 1; height: 2px; background: var(--border); margin: 0 16px; max-width: 80px; }
        .step-line.done { background: var(--cyan); }
        .checkout-section {
            background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg);
            padding: 28px; margin-bottom: 20px;
        }
        .checkout-section h3 {
            font-family: 'Space Grotesk', sans-serif; font-size: 16px; font-weight: 700;
            color: var(--text); margin: 0 0 20px; display: flex; align-items: center; gap: 10px;
        }
        .checkout-section h3 .sec-num {
            width: 28px; height: 28px; border-radius: 8px; background: rgba(0,229,255,0.08);
            border: 1px solid rgba(0,229,255,0.15); display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 800; color: var(--cyan); flex-shrink: 0;
        }
        .form-group { margin-bottom: 16px; }
        .form-group label {
            display: block; font-size: 12px; font-weight: 600; color: var(--text-secondary);
            margin-bottom: 6px; text-transform: uppercase; letter-spacing: .5px;
        }
        .form-input {
            width: 100%; padding: 12px 16px; background: var(--bg-deep); border: 1.5px solid var(--border-light);
            border-radius: 10px; color: var(--text); font-size: 14px; outline: none;
            transition: border-color .25s ease; font-family: inherit;
        }
        .form-input:focus { border-color: rgba(0,229,255,0.5); }
        .form-input.error { border-color: rgba(239,68,68,0.5); }
        .form-error { font-size: 12px; color: #f87171; margin-top: 4px; }
        .form-textarea { resize: vertical; min-height: 80px; }
        .address-card {
            padding: 16px 20px; border: 1.5px solid var(--border-light); border-radius: 12px;
            cursor: pointer; transition: all .25s ease; position: relative;
        }
        .address-card:hover { border-color: rgba(0,229,255,0.3); }
        .address-card.selected { border-color: var(--cyan); background: rgba(0,229,255,0.04); box-shadow: 0 0 16px rgba(0,229,255,0.08); }
        .address-card .radio-dot {
            position: absolute; top: 16px; right: 16px; width: 20px; height: 20px; border-radius: 50%;
            border: 2px solid var(--border-light); transition: all .25s;
        }
        .address-card.selected .radio-dot { border-color: var(--cyan); }
        .address-card.selected .radio-dot::after {
            content: ''; position: absolute; inset: 3px; border-radius: 50%; background: var(--cyan);
        }
        .checkout-product {
            display: flex; gap: 14px; padding: 14px 0; border-bottom: 1px solid var(--border);
        }
        .checkout-product:last-child { border-bottom: none; }
        .checkout-product-img {
            flex: 0 0 64px; height: 64px; background: #fff; border-radius: 10px;
            display: flex; align-items: center; justify-content: center; overflow: hidden;
        }
        .checkout-product-img img { width: 100%; height: 100%; object-fit: contain; padding: 6px; }
        .checkout-product-info { flex: 1; min-width: 0; }
        .checkout-product-name { font-size: 13px; font-weight: 600; color: var(--text); line-height: 1.35; margin-bottom: 2px; }
        .checkout-product-variant { font-size: 11px; color: var(--text-secondary); }
        .checkout-product-qty { font-size: 12px; color: var(--text-secondary); margin-top: 4px; }
        .checkout-product-price { font-family: 'Space Grotesk', sans-serif; font-size: 14px; font-weight: 700; color: var(--cyan); text-align: right; white-space: nowrap; }
        .checkout-summary {
            background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg);
            padding: 28px; position: sticky; top: 88px;
        }
        .checkout-total-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 10px 0; font-size: 14px; color: var(--text-secondary);
        }
        .checkout-total-row.grand {
            font-size: 20px; font-weight: 800; color: var(--text);
            padding-top: 16px; margin-top: 8px; border-top: 1px solid var(--border);
        }
        .checkout-total-row.grand span:last-child { font-family: 'Space Grotesk', sans-serif; color: var(--cyan); font-size: 26px; }
        .pay-btn {
            width: 100%; padding: 18px; border-radius: 14px; font-size: 16px; font-weight: 800;
            border: none; background: linear-gradient(135deg, var(--cyan), var(--blue));
            color: #000; cursor: pointer; transition: all .35s cubic-bezier(.23,1,.32,1);
            box-shadow: 0 0 30px rgba(0,229,255,0.2); display: flex; align-items: center; justify-content: center; gap: 10px;
            text-decoration: none;
        }
        .pay-btn:hover { transform: translateY(-2px) scale(1.01); box-shadow: 0 8px 40px rgba(0,229,255,0.4); }
        .pay-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }
        .checkout-mobile-bar {
            display: none; position: fixed; bottom: 0; left: 0; right: 0; z-index: 80;
            background: rgba(8,13,24,0.95); backdrop-filter: blur(20px);
            border-top: 1px solid var(--border); padding: 12px 16px;
        }

        /* ── Responsive product pages ── */
        @media (max-width: 1024px) {
            .detail-gallery { margin-bottom: 32px; }
            .sticky-cart-bar { display: block !important; }
            body { padding-bottom: 80px; }
            .cart-summary { position: static; }
            .checkout-summary { position: static; }
            .checkout-mobile-bar { display: block !important; }
            body { padding-bottom: 80px; }
        }
        @media (max-width: 768px) {
            .cart-item { flex-direction: column; gap: 14px; padding: 18px; }
            .cart-item-img { flex: none; width: 100%; height: 180px; }
            .cart-item-delete { top: 12px; right: 12px; }
            .step-label { display: none; }
            .step-line { max-width: 40px; margin: 0 8px; }
        }
        @media (max-width: 640px) {
            .cat-card .card-img-wrap { min-height: 160px; }
            .cat-card .card-img-wrap img { height: 150px; }
            .cat-card .card-body { padding: 12px 14px 14px; }
            .cat-card .card-price { font-size: 16px; }
            .cat-card .card-actions { opacity: 1; transform: translateY(0); }
            .detail-main-img { border-radius: var(--radius-md); }
            .detail-main-img img { padding: 16px; }
            .detail-price { font-size: 26px; }
            .cta-cart, .cta-buy { padding: 14px 16px; font-size: 13px; }
            .tab-btn { padding: 12px 16px; font-size: 13px; }
            .trust-grid { grid-template-columns: 1fr; }
            .checkout-section { padding: 20px; }
            .cart-item-img { height: 140px; }
        }

        /* ── Back to Top ── */
        .btt {
            position: fixed; bottom: 32px; right: 32px; z-index: 90;
            width: 48px; height: 48px; border-radius: 14px;
            background: var(--bg-card); border: 1px solid var(--border-light);
            display: flex; align-items: center; justify-content: center;
            color: var(--cyan); cursor: pointer; opacity: 0; visibility: hidden; transform: translateY(12px);
            transition: all .3s ease; box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }
        .btt.show { opacity: 1; visibility: visible; transform: translateY(0); }
        .btt:hover { background: var(--bg-elevated); border-color: rgba(0,229,255,0.3); transform: translateY(-2px); }

        /* ── Animations ── */
        .fade-up { opacity: 0; transform: translateY(24px); transition: all .6s cubic-bezier(.23,1,.32,1); }
        .fade-up.visible { opacity: 1; transform: translateY(0); }
        .floating { animation: floaty 7s ease-in-out infinite; }
        @keyframes floaty { 0%,100%{ transform: translateY(0) rotate(-1deg); } 50%{ transform: translateY(-16px) rotate(1deg); } }
        .ping-dot { animation: ping 2s ease-in-out infinite; }
        @keyframes ping { 0%,100%{ opacity: .75; transform: scale(1); } 50%{ opacity: .3; transform: scale(1.6); } }

        /* ═══════════════════════════════════════════════
           ACCOUNT DASHBOARD — User Panel
           ═══════════════════════════════════════════════ */
        .acct-section { min-height: 100vh; padding: 100px 0 60px; }
        .acct-wrap { max-width: 1200px; margin: 0 auto; padding: 0 24px; display: flex; gap: 32px; position: relative; }

        /* ── Sidebar ── */
        .acct-sidebar {
            width: 280px; flex-shrink: 0; position: sticky; top: 100px; align-self: flex-start;
            background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg);
            padding: 28px 20px; display: flex; flex-direction: column; gap: 8px;
            transition: transform .35s cubic-bezier(.23,1,.32,1);
        }
        .acct-sidebar-head {
            display: flex; align-items: center; gap: 14px; padding-bottom: 20px;
            border-bottom: 1px solid var(--border); margin-bottom: 4px; position: relative;
        }
        .acct-avatar {
            width: 52px; height: 52px; border-radius: 16px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--cyan), var(--blue));
            display: flex; align-items: center; justify-content: center;
            font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 20px; color: #000;
        }
        .acct-sidebar-user { flex: 1; min-width: 0; }
        .acct-sidebar-greeting { font-size: 12px; color: var(--text-secondary); margin-bottom: 2px; }
        .acct-sidebar-name { font-family: 'Space Grotesk', sans-serif; font-weight: 600; font-size: 15px; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .acct-sidebar-close {
            display: none; position: absolute; top: -4px; right: -4px;
            width: 32px; height: 32px; border-radius: 10px; border: 1px solid var(--border);
            background: var(--bg-deep); color: var(--text-secondary); cursor: pointer;
            align-items: center; justify-content: center; transition: all .2s;
        }
        .acct-sidebar-close:hover { color: var(--text); border-color: var(--border-light); }

        /* Nav Links */
        .acct-nav { display: flex; flex-direction: column; gap: 2px; }
        .acct-nav-link {
            display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 14px;
            font-size: 14px; font-weight: 500; color: var(--text-secondary); text-decoration: none;
            transition: all .25s ease; border: 1px solid transparent;
        }
        .acct-nav-link:hover { color: var(--text); background: rgba(255,255,255,0.04); }
        .acct-nav-link.active {
            color: var(--cyan); background: rgba(0,229,255,0.06);
            border-color: rgba(0,229,255,0.15); font-weight: 600;
        }
        .acct-nav-link.active svg { filter: drop-shadow(0 0 6px rgba(0,229,255,0.4)); }
        .acct-nav-divider { height: 1px; background: var(--border); margin: 8px 0; }
        .acct-logout:hover { color: #ff5252 !important; background: rgba(255,82,82,0.08) !important; border-color: rgba(255,82,82,0.15) !important; }

        .acct-sidebar-foot { margin-top: auto; padding-top: 12px; border-top: 1px solid var(--border); }

        /* ── Main Content ── */
        .acct-main { flex: 1; min-width: 0; }

        /* ── Alerts ── */
        .acct-alert {
            display: flex; align-items: center; gap: 12px; padding: 16px 20px;
            border-radius: 16px; margin-bottom: 24px; font-size: 14px; font-weight: 500;
        }
        .acct-alert-success { background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.2); color: #4ade80; }
        .acct-alert-error { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2); color: #f87171; }

        /* ── Cards / Panels ── */
        .acct-card {
            background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg);
            padding: 28px; transition: border-color .3s, box-shadow .3s;
        }
        .acct-card:hover { border-color: var(--border-light); }
        .acct-card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .acct-card-title {
            font-family: 'Space Grotesk', sans-serif; font-weight: 600; font-size: 18px; color: var(--text);
        }
        .acct-card-link {
            font-size: 13px; font-weight: 500; color: var(--cyan); text-decoration: none;
            display: flex; align-items: center; gap: 4px; transition: opacity .2s;
        }
        .acct-card-link:hover { opacity: .8; }

        /* ── Page Header ── */
        .acct-header { margin-bottom: 32px; }
        .acct-header-row { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
        .acct-title { font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 28px; color: var(--text); }
        .acct-subtitle { font-size: 14px; color: var(--text-secondary); margin-top: 6px; }

        /* ── Stats Grid ── */
        .acct-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }
        .stat-card {
            background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md);
            padding: 22px 20px; display: flex; align-items: center; gap: 16px;
            transition: all .3s ease; cursor: default;
        }
        .stat-card:hover { border-color: var(--border-light); transform: translateY(-2px); box-shadow: 0 8px 32px rgba(0,0,0,0.2); }
        .stat-icon {
            width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .stat-icon.cyan { background: rgba(0,229,255,0.1); color: var(--cyan); }
        .stat-icon.blue { background: rgba(41,121,255,0.1); color: var(--blue); }
        .stat-icon.green { background: rgba(34,197,94,0.1); color: #22c55e; }
        .stat-icon.magenta { background: rgba(224,64,251,0.1); color: var(--magenta); }
        .stat-info { flex: 1; min-width: 0; }
        .stat-label { font-size: 12px; color: var(--text-secondary); margin-bottom: 4px; }
        .stat-value { font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 24px; color: var(--text); }

        /* ── Quick Actions ── */
        .acct-actions { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 28px; }
        .acct-action {
            display: flex; align-items: center; gap: 12px; padding: 16px 20px;
            background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md);
            text-decoration: none; color: var(--text-secondary); font-size: 14px; font-weight: 500;
            transition: all .25s ease;
        }
        .acct-action:hover { color: var(--cyan); border-color: rgba(0,229,255,0.2); background: rgba(0,229,255,0.04); transform: translateY(-1px); }
        .acct-action svg { flex-shrink: 0; }

        /* ── Order List ── */
        .acct-order {
            display: flex; align-items: center; gap: 16px; padding: 18px 20px;
            background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md);
            text-decoration: none; transition: all .25s ease;
        }
        .acct-order:hover { border-color: rgba(0,229,255,0.2); transform: translateX(4px); }
        .acct-order + .acct-order { margin-top: 10px; }
        .acct-order-icon {
            width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
            background: rgba(0,229,255,0.06); display: flex; align-items: center; justify-content: center; color: var(--cyan);
        }
        .acct-order-info { flex: 1; min-width: 0; }
        .acct-order-num { font-family: 'Space Grotesk', sans-serif; font-weight: 600; font-size: 14px; color: var(--text); }
        .acct-order-date { font-size: 12px; color: var(--text-secondary); margin-top: 2px; }
        .acct-order-right { text-align: right; flex-shrink: 0; }
        .acct-order-total { font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 15px; color: var(--cyan); }
        .acct-order-count { font-size: 12px; color: var(--text-secondary); margin-top: 2px; }

        /* ── Status Badge ── */
        .badge-status {
            display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px;
            border-radius: 20px; font-size: 12px; font-weight: 600;
        }
        .badge-status::before { content: ''; width: 6px; height: 6px; border-radius: 50%; }
        .badge-yellow { background: rgba(250,204,21,0.1); color: #facc15; border: 1px solid rgba(250,204,21,0.2); }
        .badge-yellow::before { background: #facc15; }
        .badge-blue { background: rgba(41,121,255,0.1); color: #60a5fa; border: 1px solid rgba(41,121,255,0.2); }
        .badge-blue::before { background: #60a5fa; }
        .badge-purple { background: rgba(168,85,247,0.1); color: #c084fc; border: 1px solid rgba(168,85,247,0.2); }
        .badge-purple::before { background: #c084fc; }
        .badge-green { background: rgba(34,197,94,0.1); color: #4ade80; border: 1px solid rgba(34,197,94,0.2); }
        .badge-green::before { background: #4ade80; }
        .badge-red { background: rgba(239,68,68,0.1); color: #f87171; border: 1px solid rgba(239,68,68,0.2); }
        .badge-red::before { background: #f87171; }
        .badge-gray { background: rgba(148,163,184,0.1); color: #94a3b8; border: 1px solid rgba(148,163,184,0.2); }
        .badge-gray::before { background: #94a3b8; }

        /* ── Filter Chips ── */
        .acct-filters { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 24px; }
        .acct-chip {
            padding: 8px 18px; border-radius: 24px; font-size: 13px; font-weight: 500;
            color: var(--text-secondary); background: var(--bg-card); border: 1px solid var(--border);
            text-decoration: none; transition: all .25s ease;
        }
        .acct-chip:hover { border-color: var(--border-light); color: var(--text); }
        .acct-chip.active { background: rgba(0,229,255,0.08); color: var(--cyan); border-color: rgba(0,229,255,0.25); }

        /* ── Forms ── */
        .acct-form { max-width: 640px; }
        .acct-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .acct-field { display: flex; flex-direction: column; gap: 8px; }
        .acct-label { font-size: 13px; font-weight: 600; color: var(--text-secondary); letter-spacing: 0.02em; }
        .acct-input {
            width: 100%; padding: 14px 18px; border-radius: 14px; font-size: 14px; color: var(--text);
            background: var(--bg-deep); border: 1px solid var(--border); outline: none;
            transition: all .25s ease; font-family: inherit;
        }
        .acct-input::placeholder { color: rgba(255,255,255,0.25); }
        .acct-input:focus { border-color: rgba(0,229,255,0.5); box-shadow: 0 0 0 3px rgba(0,229,255,0.1); }
        .acct-textarea { resize: vertical; min-height: 100px; }
        .acct-checkbox-row { display: flex; align-items: center; gap: 10px; cursor: pointer; }
        .acct-checkbox-row input[type="checkbox"] {
            width: 18px; height: 18px; border-radius: 6px; accent-color: var(--cyan);
            border: 1px solid var(--border); background: var(--bg-deep); cursor: pointer;
        }
        .acct-checkbox-label { font-size: 13px; color: var(--text-secondary); }
        .acct-form-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 28px; }

        /* ── Buttons ── */
        .btn-acct-primary {
            display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px;
            border-radius: 14px; font-size: 14px; font-weight: 600; font-family: inherit;
            background: linear-gradient(135deg, var(--cyan), var(--blue)); color: #000; border: none;
            cursor: pointer; transition: all .3s ease; text-decoration: none;
        }
        .btn-acct-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 32px rgba(0,229,255,0.3); }
        .btn-acct-secondary {
            display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px;
            border-radius: 14px; font-size: 14px; font-weight: 600; font-family: inherit;
            background: transparent; color: var(--text-secondary); border: 1px solid var(--border);
            cursor: pointer; transition: all .3s ease; text-decoration: none;
        }
        .btn-acct-secondary:hover { border-color: var(--border-light); color: var(--text); background: rgba(255,255,255,0.03); }
        .btn-acct-danger {
            display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px;
            border-radius: 12px; font-size: 13px; font-weight: 500; font-family: inherit;
            background: rgba(239,68,68,0.08); color: #f87171; border: 1px solid rgba(239,68,68,0.2);
            cursor: pointer; transition: all .25s ease;
        }
        .btn-acct-danger:hover { background: rgba(239,68,68,0.15); border-color: rgba(239,68,68,0.35); }
        .btn-acct-small { padding: 8px 14px; font-size: 12px; border-radius: 10px; }

        /* ── Address Cards ── */
        .acct-address {
            background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md);
            padding: 24px; position: relative; transition: all .3s ease;
        }
        .acct-address:hover { border-color: var(--border-light); }
        .acct-address.is-default { border-color: rgba(0,229,255,0.3); }
        .acct-address-badge {
            position: absolute; top: 16px; right: 16px; padding: 4px 12px; border-radius: 20px;
            font-size: 11px; font-weight: 600; background: rgba(0,229,255,0.1); color: var(--cyan);
            border: 1px solid rgba(0,229,255,0.2);
        }
        .acct-address-name { font-weight: 600; font-size: 15px; color: var(--text); margin-bottom: 4px; }
        .acct-address-phone { font-size: 13px; color: var(--text-secondary); margin-bottom: 10px; }
        .acct-address-text { font-size: 14px; color: rgba(255,255,255,0.7); line-height: 1.6; }
        .acct-address-actions { display: flex; align-items: center; gap: 16px; margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border); }
        .acct-address-actions button, .acct-address-actions a {
            font-size: 13px; font-weight: 500; background: none; border: none; cursor: pointer; padding: 0;
            transition: color .2s;
        }

        /* ── Empty State ── */
        .acct-empty {
            text-align: center; padding: 60px 24px;
            background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg);
        }
        .acct-empty-icon {
            width: 80px; height: 80px; margin: 0 auto 20px; border-radius: 24px;
            background: rgba(255,255,255,0.03); display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.15);
        }
        .acct-empty-title { font-family: 'Space Grotesk', sans-serif; font-weight: 600; font-size: 18px; color: var(--text); margin-bottom: 8px; }
        .acct-empty-text { font-size: 14px; color: var(--text-secondary); margin-bottom: 24px; }

        /* ── Profile Header ── */
        .acct-profile-head { display: flex; align-items: center; gap: 20px; margin-bottom: 32px; padding-bottom: 28px; border-bottom: 1px solid var(--border); }
        .acct-profile-avatar {
            width: 72px; height: 72px; border-radius: 22px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--cyan), var(--blue));
            display: flex; align-items: center; justify-content: center;
            font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 28px; color: #000;
        }
        .acct-profile-name { font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 22px; color: var(--text); }
        .acct-profile-email { font-size: 14px; color: var(--text-secondary); margin-top: 4px; }

        /* ── Timeline (Order Detail) ── */
        .order-timeline { display: flex; flex-direction: column; gap: 0; padding: 8px 0; }
        .timeline-step { display: flex; gap: 16px; position: relative; padding-bottom: 28px; }
        .timeline-step:last-child { padding-bottom: 0; }
        .timeline-dot-wrap { display: flex; flex-direction: column; align-items: center; position: relative; }
        .timeline-dot {
            width: 36px; height: 36px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; z-index: 1; transition: all .3s;
        }
        .timeline-dot.done { background: rgba(0,229,255,0.15); color: var(--cyan); border: 1px solid rgba(0,229,255,0.3); }
        .timeline-dot.active { background: var(--cyan); color: #000; box-shadow: 0 0 20px rgba(0,229,255,0.4); }
        .timeline-dot.pending { background: var(--bg-elevated); color: rgba(255,255,255,0.2); border: 1px solid var(--border); }
        .timeline-line {
            width: 2px; flex: 1; min-height: 20px; margin-top: 4px;
            background: var(--border); transition: background .3s;
        }
        .timeline-line.done { background: rgba(0,229,255,0.3); }
        .timeline-content { flex: 1; padding-top: 6px; }
        .timeline-label { font-weight: 600; font-size: 14px; color: var(--text); }
        .timeline-label.pending-label { color: var(--text-secondary); }
        .timeline-date { font-size: 12px; color: var(--text-secondary); margin-top: 3px; }

        /* ── Mobile Toggle ── */
        .acct-mob-toggle {
            display: none; position: fixed; bottom: 24px; left: 24px; z-index: 100;
            padding: 14px 20px; border-radius: 16px; border: 1px solid rgba(0,229,255,0.3);
            background: var(--bg-card); color: var(--cyan); font-size: 14px; font-weight: 600;
            cursor: pointer; align-items: center; gap: 8px; backdrop-filter: blur(20px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.4); transition: all .3s;
        }
        .acct-mob-toggle:hover { background: var(--bg-elevated); box-shadow: 0 12px 40px rgba(0,229,255,0.15); }
        .acct-mob-overlay {
            display: none; position: fixed; inset: 0; z-index: 200;
            background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); opacity: 0;
            transition: opacity .3s;
        }
        .acct-mob-overlay.open { opacity: 1; }

        /* ── Responsive ── */
        @media (max-width: 1024px) {
            .acct-sidebar {
                position: fixed; top: 0; left: 0; bottom: 0; z-index: 210;
                width: 300px; max-width: 85vw; border-radius: 0 var(--radius-lg) var(--radius-lg) 0;
                transform: translateX(-100%); overflow-y: auto;
            }
            .acct-sidebar.open { transform: translateX(0); }
            .acct-sidebar-close { display: flex; }
            .acct-mob-toggle { display: flex; }
            .acct-mob-overlay { display: block; pointer-events: none; }
            .acct-mob-overlay.open { pointer-events: all; }
            .acct-stats { grid-template-columns: repeat(2, 1fr); }
            .acct-form-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) {
            .acct-section { padding: 80px 0 40px; }
            .acct-wrap { padding: 0 16px; gap: 0; }
            .acct-title { font-size: 22px; }
            .acct-stats { grid-template-columns: 1fr 1fr; gap: 10px; }
            .stat-card { padding: 16px; gap: 12px; }
            .stat-icon { width: 40px; height: 40px; border-radius: 12px; }
            .stat-value { font-size: 20px; }
            .acct-card { padding: 20px; }
            .acct-profile-head { flex-direction: column; text-align: center; }
            .acct-order { flex-direction: column; align-items: flex-start; gap: 12px; }
            .acct-order-right { text-align: left; }
            .acct-address-actions { flex-wrap: wrap; gap: 12px; }
            .acct-form-actions { flex-direction: column; }
            .acct-form-actions .btn-acct-primary, .acct-form-actions .btn-acct-secondary { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    @include('partials.navbar')

    <main>@yield('content')</main>

    @include('partials.footer')

    <div id="toast-container" class="toast-box"></div>

    <button id="btt" class="btt" aria-label="Back to top">
        <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
    </button>

    <div id="search-overlay" class="sovl">
        <div class="sovl-box">
            <div class="flex items-center gap-3" style="background:var(--bg-card);border:1px solid var(--border-light);border-radius:18px;padding:16px 20px;box-shadow:0 20px 60px rgba(0,0,0,.5);">
                <svg style="width:22px;height:22px;color:var(--cyan);flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input id="search-input" type="text" placeholder="Search smartphone, laptop, audio..." class="flex-1 bg-transparent text-white text-lg placeholder-white/30 focus:outline-none" autocomplete="off">
                <button id="search-close-btn" style="padding:8px;color:rgba(255,255,255,.5);border:none;background:none;cursor:pointer;border-radius:8px;" aria-label="Close search">
                    <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div id="search-results" style="margin-top:12px;max-height:55vh;overflow-y:auto;border-radius:16px;"></div>
        </div>
    </div>

    {{-- ═══ Mobile Bottom Navigation ═══ --}}
    <nav id="bottom-nav" class="bottom-nav" aria-label="Mobile navigation">
        <a href="{{ route('home') }}" class="bnav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span>Home</span>
        </a>
        <a href="{{ route('products.index') }}" class="bnav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <span>Kategori</span>
        </a>
        <button onclick="document.getElementById('search-btn').click()" class="bnav-item" aria-label="Search">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <span>Cari</span>
        </button>
        @auth
        <a href="{{ route('wishlist.index') }}" class="bnav-item">
            <div style="position:relative;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                @if(Auth::user()->wishlists()->count() > 0)
                <span class="bnav-badge">{{ Auth::user()->wishlists()->count() }}</span>
                @endif
            </div>
            <span>Wishlist</span>
        </a>
        @else
        <a href="{{ route('login') }}" class="bnav-item">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            <span>Wishlist</span>
        </a>
        @endauth
        @auth
        <a href="{{ route('cart.index') }}" class="bnav-item">
            <div style="position:relative;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                @php $cartCount = Auth::user()->cart ? Auth::user()->cart->total_quantity : 0; @endphp
                @if($cartCount > 0)
                <span class="bnav-badge">{{ $cartCount }}</span>
                @endif
            </div>
            <span>Cart</span>
        </a>
        @else
        <a href="{{ route('login') }}" class="bnav-item">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <span>Cart</span>
        </a>
        @endauth
    </nav>

    {{-- ═══ Quick View Modal ═══ --}}
    <div id="qv-overlay" class="qv-overlay" onclick="closeQuickView()"></div>
    <div id="qv-modal" class="qv-modal" role="dialog" aria-label="Quick view product">
        <button onclick="closeQuickView()" class="qv-close" aria-label="Close quick view">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="qv-body">
            <div class="qv-img">
                <img id="qv-image" src="" alt="" loading="lazy">
            </div>
            <div class="qv-info">
                <div id="qv-brand" class="qv-brand"></div>
                <h2 id="qv-name" class="qv-name"></h2>
                <div id="qv-rating" class="qv-rating">
                    <svg viewBox="0 0 20 20" width="16" height="16"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" fill="#ffc107"/></svg>
                    <span>4.8</span>
                </div>
                <div class="qv-price-row">
                    <span id="qv-old-price" class="qv-old-price"></span>
                    <span id="qv-price" class="qv-price"></span>
                    <span id="qv-discount" class="qv-discount"></span>
                </div>
                <p id="qv-desc" class="qv-desc"></p>
                <div id="qv-variants" class="qv-variants"></div>
                <div class="qv-qty-row">
                    <span class="qv-qty-label">Qty</span>
                    <div class="qty-box">
                        <button onclick="qvQty(-1)" aria-label="Decrease quantity">−</button>
                        <input type="number" id="qv-qty" class="qty-val" value="1" min="1" readonly>
                        <button onclick="qvQty(1)" aria-label="Increase quantity">+</button>
                    </div>
                    <span id="qv-stock" class="qv-stock"></span>
                </div>
                <div class="qv-actions">
                    <button onclick="qvAddToCart()" class="cta-cart" aria-label="Add to cart">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Add to Cart
                    </button>
                    <a id="qv-buy" href="#" class="cta-buy">Buy Now</a>
                </div>
                <button onclick="qvWishlist()" id="qv-wish-btn" class="qv-wish-btn" aria-label="Add to wishlist">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    Add to Wishlist
                </button>
            </div>
        </div>
    </div>

    <script>
    /* Toast — global function */
    function showToast(msg,type){
        type=type||'success';
        var c=document.getElementById('toast-container');if(!c)return;
        var t=document.createElement('div');t.className='toast-item';
        var icons={success:'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',wishlist:'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',cart:'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>',info:'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'};
        var colors={success:'#22c55e',wishlist:'#ff3d00',cart:'var(--blue)',info:'var(--cyan)'};
        t.innerHTML='<div class="ti" style="background:'+colors[type]+'"><svg style="width:14px;height:14px;color:#fff" fill="none" stroke="currentColor" viewBox="0 0 24 24">'+(icons[type]||icons.success)+'</svg></div><span>'+msg+'</span>';
        c.appendChild(t);
        requestAnimationFrame(function(){requestAnimationFrame(function(){t.classList.add('show');});});
        setTimeout(function(){t.classList.remove('show');t.classList.add('hide');setTimeout(function(){t.remove();},400);},3000);
    }

    document.addEventListener('DOMContentLoaded',function(){

        /* Mobile menu */
        var mBtn=document.getElementById('mobile-menu-btn'),mMenu=document.getElementById('mobile-menu'),mIcon=document.getElementById('menu-icon'),cIcon=document.getElementById('close-icon');
        function closeMM(){if(mMenu)mMenu.classList.remove('open');if(mIcon)mIcon.classList.remove('hidden');if(cIcon)cIcon.classList.add('hidden');if(mBtn)mBtn.setAttribute('aria-expanded','false');document.body.classList.remove('menu-open');}
        function openMM(){if(mMenu)mMenu.classList.add('open');if(mIcon)mIcon.classList.add('hidden');if(cIcon)cIcon.classList.remove('hidden');if(mBtn)mBtn.setAttribute('aria-expanded','true');document.body.classList.add('open');}
        if(mBtn&&mMenu)mBtn.addEventListener('click',function(){mMenu.classList.contains('open')?closeMM():openMM();});
        if(mMenu)mMenu.querySelectorAll('a').forEach(function(a){a.addEventListener('click',closeMM);});

        /* Sticky nav */
        var nav=document.getElementById('main-navbar');
        window.addEventListener('scroll',function(){if(!nav)return;window.scrollY>30?nav.classList.add('sticky-active'):nav.classList.remove('sticky-active');});

        /* Back to top */
        var bttBtn=document.getElementById('btt');
        window.addEventListener('scroll',function(){if(!bttBtn)return;window.scrollY>400?bttBtn.classList.add('show'):bttBtn.classList.remove('show');});
        if(bttBtn)bttBtn.addEventListener('click',function(){window.scrollTo({top:0,behavior:'smooth'});});

        /* Countdown */
        function endOfDay(){var d=new Date();d.setHours(23,59,59,999);return d;}
        function tick(){
            var diff=endOfDay()-new Date();
            var h=document.getElementById('cd-h'),m=document.getElementById('cd-m'),s=document.getElementById('cd-s');
            if(diff<=0){if(h)h.textContent='00';if(m)m.textContent='00';if(s)s.textContent='00';return;}
            var hh=Math.floor(diff/3600000),mm=Math.floor((diff%3600000)/60000),ss=Math.floor((diff%60000)/1000);
            if(h)h.textContent=String(hh).padStart(2,'0');
            if(m)m.textContent=String(mm).padStart(2,'0');
            if(s)s.textContent=String(ss).padStart(2,'0');
        }
        tick();setInterval(tick,1000);

        /* Smooth scroll */
        document.querySelectorAll('a[href^="#"]').forEach(function(a){
            a.addEventListener('click',function(e){
                var href=this.getAttribute('href');if(href.length<2)return;
                var t=document.querySelector(href);if(!t)return;
                e.preventDefault();closeMM();
                var top=t.getBoundingClientRect().top+window.pageYOffset-(nav?nav.offsetHeight+10:80);
                window.scrollTo({top:top,behavior:'smooth'});
            });
        });

        /* Wishlist */
        document.querySelectorAll('.wishlist-float').forEach(function(b){
            b.addEventListener('click',function(e){
                e.preventDefault();e.stopPropagation();
                var card=this.closest('.pcard,.fs-card');var name='';
                if(card){var n=card.querySelector('.pcard-name');if(n)name=n.textContent.trim();}
                var active=this.classList.toggle('active');
                var svg=this.querySelector('svg');
                if(active){svg.innerHTML='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>';showToast(name?name+' added to Wishlist':'Added to Wishlist','wishlist');}
                else{svg.innerHTML='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>';showToast('Removed from Wishlist','info');}
            });
        });

        /* Add to cart */
        document.querySelectorAll('.add-cart-btn').forEach(function(b){
            b.addEventListener('click',function(e){
                e.preventDefault();e.stopPropagation();
                var card=this.closest('.pcard,.fs-card');var name='';
                if(card){var n=card.querySelector('.pcard-name');if(n)name=n.textContent.trim();}
                this.textContent='✓ Added';showToast(name?name+' added to Cart':'Added to Cart','cart');
                var self=this;setTimeout(function(){self.textContent='Add to Cart';},1500);
            });
        });

        /* Search — Live API with debounce */
        var sBtn=document.getElementById('search-btn'),sOvl=document.getElementById('search-overlay'),sInp=document.getElementById('search-input'),sClose=document.getElementById('search-close-btn'),sRes=document.getElementById('search-results');
        var searchTimer=null,searchIdx=-1;
        function openS(){if(sOvl)sOvl.classList.add('open');if(sInp){sInp.value='';sInp.focus();}if(sRes)sRes.innerHTML='';document.body.classList.add('menu-open');}
        function closeS(){if(sOvl)sOvl.classList.remove('open');document.body.classList.remove('menu-open');}
        function fmtPrice(n){return 'Rp '+Number(n).toLocaleString('id-ID');}
        function renderSearch(r){
            if(!sRes)return;
            var h='';
            if(r.products.length){
                h+='<div style="padding:8px 14px;font-size:11px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:1px;">Products</div>';
                h+='<div style="display:flex;flex-direction:column;gap:4px;">';
                r.products.forEach(function(p){
                    h+='<a href="/products/'+p.slug+'" style="display:flex;align-items:center;gap:14px;padding:12px 14px;border-radius:12px;text-decoration:none;color:inherit;transition:background .2s;" onmouseover="this.style.background=\'rgba(255,255,255,0.04)\'" onmouseout="this.style.background=\'transparent\'">';
                    h+='<div style="width:48px;height:48px;border-radius:10px;background:#fff;flex-shrink:0;display:flex;align-items:center;justify-content:center;overflow:hidden;"><img src="'+p.image+'" style="width:100%;height:100%;object-fit:contain;padding:4px;" loading="lazy"></div>';
                    h+='<div style="flex:1;min-width:0;"><div style="font-size:13px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'+p.name+'</div><div style="font-size:11px;color:var(--text-secondary);margin-top:2px;">'+p.brand+' · '+p.category+'</div></div>';
                    h+='<div style="text-align:right;flex-shrink:0;"><div style="font-family:Space Grotesk,sans-serif;font-size:14px;font-weight:700;color:var(--cyan);">'+fmtPrice(p.effective_price)+'</div>';
                    if(p.discount_percent)h+='<div style="font-size:10px;color:#ff5252;font-weight:700;">-'+p.discount_percent+'%</div>';
                    h+='</div></a>';
                });
                h+='</div>';
            }
            if(r.categories.length){
                h+='<div style="padding:8px 14px;font-size:11px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:1px;margin-top:8px;">Categories</div>';
                h+='<div style="display:flex;flex-wrap:wrap;gap:6px;padding:0 14px;">';
                r.categories.forEach(function(c){
                    h+='<a href="/products?category='+c.slug+'" style="display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:100px;font-size:12px;font-weight:600;background:rgba(0,229,255,0.06);border:1px solid rgba(0,229,255,0.12);color:var(--cyan);text-decoration:none;transition:all .2s;" onmouseover="this.style.background=\'rgba(0,229,255,0.12)\'" onmouseout="this.style.background=\'rgba(0,229,255,0.06)\'">'+c.name+'</a>';
                });
                h+='</div>';
            }
            if(r.brands.length){
                h+='<div style="padding:8px 14px;font-size:11px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:1px;margin-top:8px;">Brands</div>';
                h+='<div style="display:flex;flex-wrap:wrap;gap:6px;padding:0 14px;">';
                r.brands.forEach(function(b){
                    h+='<a href="/products?brand='+b.slug+'" style="display:inline-flex;padding:7px 14px;border-radius:100px;font-size:12px;font-weight:600;background:rgba(255,255,255,0.04);border:1px solid var(--border);color:var(--text-secondary);text-decoration:none;transition:all .2s;" onmouseover="this.style.color=\'var(--cyan)\';this.style.borderColor=\'rgba(0,229,255,0.3)\'" onmouseout="this.style.color=\'var(--text-secondary)\';this.style.borderColor=\'var(--border)\'">'+b.name+'</a>';
                });
                h+='</div>';
            }
            if(!r.products.length && !r.categories.length && !r.brands.length){
                h='<div style="text-align:center;padding:40px 0;color:var(--text-secondary);"><svg style="width:40px;height:40px;margin:0 auto 12px;opacity:0.3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg><div style="font-size:14px;">No results found</div><div style="font-size:12px;margin-top:4px;opacity:0.5;">Try different keywords</div></div>';
            }
            if(r.products.length){
                h+='<div style="padding:12px 14px;text-align:center;border-top:1px solid var(--border);margin-top:8px;"><a href="/products?search='+encodeURIComponent(sInp.value)+'" style="font-size:13px;font-weight:600;color:var(--cyan);text-decoration:none;">View all results →</a></div>';
            }
            sRes.innerHTML=h;searchIdx=-1;
        }
        function doSearch(q){
            if(!q||q.length<2){if(sRes)sRes.innerHTML='';return;}
            if(sRes)sRes.innerHTML='<div style="text-align:center;padding:24px;"><div class="search-spinner"></div></div>';
            fetch('/api/search?q='+encodeURIComponent(q)).then(function(r){return r.json();}).then(renderSearch).catch(function(){if(sRes)sRes.innerHTML='';});
        }
        if(sInp){
            sInp.addEventListener('input',function(){clearTimeout(searchTimer);var v=this.value;searchTimer=setTimeout(function(){doSearch(v);},250);});
            sInp.addEventListener('keydown',function(e){
                var items=sRes?sRes.querySelectorAll('a'):[];
                if(e.key==='ArrowDown'){e.preventDefault();searchIdx=Math.min(searchIdx+1,items.length-1);items.forEach(function(a,i){a.style.background=i===searchIdx?'rgba(255,255,255,0.06)':'';});}
                else if(e.key==='ArrowUp'){e.preventDefault();searchIdx=Math.max(searchIdx-1,0);items.forEach(function(a,i){a.style.background=i===searchIdx?'rgba(255,255,255,0.06)':'';});}
                else if(e.key==='Enter'){e.preventDefault();if(searchIdx>=0&&items[searchIdx])items[searchIdx].click();else if(sInp.value)window.location.href='/products?search='+encodeURIComponent(sInp.value);}
                else if(e.key==='Escape'){closeS();}
            });
        }
        if(sBtn)sBtn.addEventListener('click',openS);
        if(sClose)sClose.addEventListener('click',closeS);
        if(sOvl)sOvl.addEventListener('click',function(e){if(e.target===sOvl)closeS();});
        document.querySelectorAll('[data-mobile-search]').forEach(function(b){b.addEventListener('click',function(){closeMM();setTimeout(openS,300);});});
        document.addEventListener('keydown',function(e){if(e.key==='Escape'){closeS();closeMM();closeQuickView();}});

        /* User dropdown */
        var uBtn=document.getElementById('user-dropdown-btn'),uMenu=document.getElementById('user-dropdown-menu'),uArrow=document.getElementById('dropdown-arrow');
        window.toggleUD=function(){if(!uMenu)return;var open=uMenu.style.opacity==='1';open?closeUD():openUD();};
        function openUD(){uMenu.style.opacity='1';uMenu.style.visibility='visible';uMenu.style.transform='scale(1)';uMenu.classList.remove('opacity-0','invisible','scale-95');if(uArrow)uArrow.style.transform='rotate(180deg)';}
        function closeUD(){uMenu.style.opacity='0';uMenu.style.visibility='hidden';uMenu.style.transform='scale(0.95)';uMenu.classList.add('opacity-0','invisible','scale-95');if(uArrow)uArrow.style.transform='';}
        if(uBtn&&uMenu)document.addEventListener('click',function(e){if(!uBtn.contains(e.target)&&!uMenu.contains(e.target))closeUD();});

        /* Intersection observer */
        var obs=new IntersectionObserver(function(entries){entries.forEach(function(e){if(e.isIntersecting){e.target.classList.add('visible');obs.unobserve(e.target);}});},{threshold:0.08,rootMargin:'0px 0px -40px 0px'});
        document.querySelectorAll('.fade-up').forEach(function(el){obs.observe(el);});

        /* Quick View */
        var qvData=null,qvQty=1;
        window.openQuickView=function(slug){
            var ov=document.getElementById('qv-overlay'),md=document.getElementById('qv-modal');
            if(!ov||!md)return;
            document.getElementById('qv-image').src='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
            ov.classList.add('open');md.classList.add('open');document.body.classList.add('menu-open');
            fetch('/api/quick-view?slug='+encodeURIComponent(slug)).then(function(r){return r.json();}).then(function(d){
                qvData=d;qvQty=1;
                document.getElementById('qv-image').src=d.image;
                document.getElementById('qv-image').alt=d.name;
                document.getElementById('qv-brand').textContent=d.brand;
                document.getElementById('qv-name').textContent=d.name;
                document.getElementById('qv-desc').textContent=d.short_description||d.description||'';
                document.getElementById('qv-price').textContent=fmtPrice(d.effective_price);
                var oldEl=document.getElementById('qv-old-price');
                var discEl=document.getElementById('qv-discount');
                if(d.discount_percent){oldEl.textContent=fmtPrice(d.price);oldEl.style.display='';discEl.textContent='-'+d.discount_percent+'%';discEl.style.display='';}
                else{oldEl.style.display='none';discEl.style.display='none';}
                document.getElementById('qv-qty').value=1;
                var stockEl=document.getElementById('qv-stock');
                if(d.stock>0){stockEl.textContent=d.stock+' in stock';stockEl.style.color='var(--cyan)';}
                else{stockEl.textContent='Out of stock';stockEl.style.color='#f87171';}
                document.getElementById('qv-buy').href='/products/'+d.slug;
                var vBox=document.getElementById('qv-variants');
                if(d.variants&&d.variants.length){
                    var vh='<div class="variant-group"><div class="variant-label">Variant</div><div style="display:flex;flex-wrap:wrap;gap:8px;">';
                    d.variants.forEach(function(v){vh+='<button class="variant-btn" data-vid="'+v.id+'" data-vprice="'+v.price+'" data-vstock="'+v.stock+'" onclick="qvSelectVariant(this)"'+(v.stock<=0?' disabled style="opacity:0.35;text-decoration:line-through;"':'')+'>'+v.name+'</button>';});
                    vh+='</div></div>';vBox.innerHTML=vh;
                }else{vBox.innerHTML='';}
            }).catch(function(){closeQuickView();});
        };
        window.closeQuickView=function(){
            var ov=document.getElementById('qv-overlay'),md=document.getElementById('qv-modal');
            if(ov)ov.classList.remove('open');if(md)md.classList.remove('open');
            document.body.classList.remove('menu-open');qvData=null;
        };
        window.qvQty=function(d){
            var inp=document.getElementById('qv-qty');
            var max=qvData?qvData.stock:99;
            var v=parseInt(inp.value)+d;
            if(v>=1&&v<=max)inp.value=v;
        };
        window.qvSelectVariant=function(btn){
            btn.closest('.variant-group').querySelectorAll('.variant-btn').forEach(function(b){b.classList.remove('active');});
            btn.classList.add('active');
            var stockEl=document.getElementById('qv-stock');
            var stock=parseInt(btn.dataset.vstock)||0;
            if(stock>0){stockEl.textContent=stock+' in stock';stockEl.style.color='var(--cyan)';}
            else{stockEl.textContent='Out of stock';stockEl.style.color='#f87171';}
        };
        window.qvAddToCart=function(){
            if(!qvData)return;
            showToast(qvData.name+' added to Cart','cart');
            closeQuickView();
        };
        window.qvWishlist=function(){
            if(!qvData)return;
            showToast(qvData.name+' added to Wishlist','wishlist');
        };
    });
    </script>
    @stack('scripts')

    <script>
        // Lazy load images for better mobile performance
        document.addEventListener('DOMContentLoaded', function() {
            const lazyImages = document.querySelectorAll('img[loading="lazy"]');
            
            if ('IntersectionObserver' in window) {
                const lazyImageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const lazyImage = entry.target;
                            lazyImage.src = lazyImage.dataset.src || lazyImage.src;
                            lazyImage.classList.remove('lazy');
                            lazyImageObserver.unobserve(lazyImage);
                        }
                    });
                });
                
                lazyImages.forEach(lazyImage => {
                    lazyImageObserver.observe(lazyImage);
                });
            } else {
                // Fallback for browsers without IntersectionObserver
                lazyImages.forEach(lazyImage => {
                    lazyImage.src = lazyImage.dataset.src || lazyImage.src;
                });
            }
        });
    });
    </script>

    @if(!request()->routeIs('admin.*') && !request()->routeIs('seller.*'))
    @include('partials.winky-ai-chat')
    @endif
</body>
</html>
