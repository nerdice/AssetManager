<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AssetManager') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="d-flex flex-column min-vh-100">
    @include('partials.navbar')
    <main class="container py-5">
        <div class="card mx-auto am-auth-card"><div class="card-body p-4 p-sm-5">{{ $slot }}</div></div>
    </main>
    @include('partials.footer')
</body>
</html>
