<!DOCTYPE html>
<html data-bs-theme="dark" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AssetManager — Your devices. One place.</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body.am-welcome { margin: 0; min-height: 100vh; min-height: 100dvh; display: flex; flex-direction: column; background: #121018; color: #eee; font-family: system-ui, sans-serif; }
        .am-welcome-main { flex: 1; display: grid; place-items: center; padding: 2rem; text-align: center; }
        .am-welcome-title { font-size: clamp(2rem, 7vw, 4rem); margin-bottom: 1rem; }
        .am-welcome-title span { color: #b99aff; }
        .am-welcome-tagline { color: #b7afc6; }
        .am-welcome-button { display: inline-block; margin-top: 1.5rem; padding: .85rem 1.5rem; border: 1px solid #b99aff; border-radius: .75rem; background: #b99aff; color: #121018; font-weight: 600; text-decoration: none; animation: am-welcome-enter 1s ease .6s both; transition: background .2s, box-shadow .2s; }
        .am-welcome-button:hover { background: #cbb3ff; color: #121018; box-shadow: 0 0 24px #b99aff33; }
        .am-welcome-button:focus-visible { outline: 2px solid #eee; outline-offset: 4px; }
        @keyframes am-welcome-enter {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (prefers-reduced-motion: reduce) {
            .am-welcome-button { animation: none; transition: none; }
        }
    </style>
</head>
<body class="am-welcome">
    <main class="am-welcome-main">
        <div>
            <h1 class="am-welcome-title">Asset<span>Manager</span></h1>
            <p class="am-welcome-tagline">Your devices. One place.</p>
            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="am-welcome-button">Open AssetManager →</a>
        </div>
    </main>
    @include('partials.footer')
</body>
</html>