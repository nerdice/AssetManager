<!DOCTYPE html>
<html data-bs-theme="dark" lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | AssetManager</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .am-legal-content { line-height: 1.75; overflow-wrap: anywhere; }
        .am-legal-content h2 { font-size: 1.2rem; margin: 2rem 0 1rem; }
        .am-legal-content h2:first-child { margin-top: 0; }
        .am-legal-content li { padding-left: .3rem; margin-bottom: 1.25rem; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    @include('partials.navbar')
    <main class="container py-4 py-lg-5 flex-grow-1" style="max-width: 960px;">
        <nav class="d-flex flex-wrap gap-2 justify-content-end mb-3" aria-label="Language">
            @foreach (['en' => 'English', 'de' => 'Deutsch', 'hu' => 'Magyar'] as $language => $label)
                <a href="{{ route('legal.show', ['document' => $document, 'locale' => $language]) }}" lang="{{ $language }}" hreflang="{{ $language }}" class="btn btn-sm {{ $locale === $language ? 'btn-secondary' : 'btn-outline-secondary' }}" @if ($locale === $language) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
        </nav>
        <article class="card">
            <div class="card-body p-4 p-lg-5">
                <h1 class="h3 mb-2">@yield('title')</h1>
                <p class="small text-body-secondary mb-4">{{ ['en' => 'Last updated: 21 October 2026', 'de' => 'Stand: 21. Oktober 2026', 'hu' => 'Utolsó frissítés: 2026. október 21.'][$locale] }}</p>
                <div class="am-legal-content">@yield('content')</div>
            </div>
        </article>
    </main>
    @include('partials.footer')
</body>
</html>
