<!DOCTYPE html>
<html data-bs-theme="dark" lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Public Asset | AssetManager</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="d-flex flex-column min-vh-100">
        @include('partials.navbar')

        <main class="container py-4">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <a href="{{ route('asset.explore') }}" class="btn btn-outline-secondary mb-3">Back to Explore</a>
                    @if (isset($asset) && $asset->is_public)
                        @php
                            $statusColors = ['active' => '#6ed9a6', 'stored' => '#b7afc6', 'maintenance' => '#ffb36b', 'broken' => '#ff879c', 'retired' => '#b7afc6', 'sold' => '#b7afc6', 'lost' => '#ff879c'];
                            $conditionColors = ['excellent' => '#b99aff', 'good' => '#6ed9a6', 'fair' => '#ffb36b', 'poor' => '#ff879c', 'broken' => '#ff879c'];
                            $allowedSpecifications = [
                                'processor', 'cpu', 'graphics', 'gpu', 'memory', 'memory_gb', 'ram', 'ram_gb',
                                'storage', 'storage_gb', 'storage_capacity_gb', 'storage_type', 'capacity_gb',
                                'display_size_inches', 'screen_size_inches', 'resolution', 'refresh_rate_hz',
                                'keyboard_layout', 'color', 'connection', 'interface', 'form_factor',
                                'microcontroller', 'cores', 'threads', 'base_clock_ghz', 'socket',
                                'memory_type', 'speed_mhz', 'rotation_speed_rpm',
                            ];
                            $publicSpecifications = collect($asset->specifications ?? [])->filter(function ($value) {
                                return is_scalar($value) && (is_bool($value) || trim((string) $value) !== '');
                            });
                        @endphp
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-body-secondary" aria-hidden="true">
                                        <circle cx="12" cy="7" r="4"/>
                                        <path d="M4 22v-2a8 8 0 0 1 16 0v2"/>
                                    </svg>
                                    <p class="fw-semibold mb-0">{{ $asset->user?->username ?? 'User' }}</p>
                                    @if (auth()->check() && auth()->id() === $asset->user_id)
                                        
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1.7em" width="1.7em" viewBox="0 0 640 640"><!--!Font Awesome Free v7.3.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path fill="currentColor" d="M96 96C60.7 96 32 124.7 32 160L32 480C32 515.3 60.7 544 96 544L544 544C579.3 544 608 515.3 608 480L608 160C608 124.7 579.3 96 544 96L96 96zM176 352L240 352C284.2 352 320 387.8 320 432C320 440.8 312.8 448 304 448L112 448C103.2 448 96 440.8 96 432C96 387.8 131.8 352 176 352zM152 256C152 225.1 177.1 200 208 200C238.9 200 264 225.1 264 256C264 286.9 238.9 312 208 312C177.1 312 152 286.9 152 256zM392 208L504 208C517.3 208 528 218.7 528 232C528 245.3 517.3 256 504 256L392 256C378.7 256 368 245.3 368 232C368 218.7 378.7 208 392 208zM392 304L504 304C517.3 304 528 314.7 528 328C528 341.3 517.3 352 504 352L392 352C378.7 352 368 341.3 368 328C368 314.7 378.7 304 392 304z"/></svg>
                                        <span>My Asset / <a href="{{ route('asset.show', ['id' => $asset->id]) }}" class="text-body-secondary">Go to Asset</a></span>    
                                    @endif
                                </div>
                                <h1 class="h3 card-title mb-3">{{ $asset->name }}</h1>
                                @if (filled($asset->model) || filled($asset->manufacturer))
                                    <p class="h5 text-body-secondary mb-2">{{ $asset->model }}{{ filled($asset->model) && filled($asset->manufacturer) ? ' | ' : '' }}{{ $asset->manufacturer }}</p>
                                @endif
                                @if (filled($asset->assetType?->name))
                                    <p class="text-body-secondary mb-4">{{ $asset->assetType->name }}</p>
                                @endif
                                @if (isset($statusColors[$asset->status]))
                                    <p>Status: <span class="small text-uppercase" style="color: {{ $statusColors[$asset->status] }}">{{ $asset->status }}</span></p>
                                @endif
                                @if (isset($conditionColors[$asset->condition]))
                                    <p>Condition: <span class="small text-uppercase" style="color: {{ $conditionColors[$asset->condition] }}">{{ $asset->condition }}</span></p>
                                @endif
                                @if ($publicSpecifications->isNotEmpty())
                                    <hr class="my-4">
                                    <h2 class="h5 mb-3">Specifications</h2>
                                    <dl class="row mb-0">
                                        @foreach ($publicSpecifications as $key => $value)
                                            <dt class="col-sm-5 mb-2">{{ ucfirst(str_replace('_', ' ', $key)) }}</dt>
                                            <dd class="col-sm-7 mb-2">{{ is_bool($value) ? ($value ? 'Yes' : 'No') : $value }}</dd>
                                        @endforeach
                                    </dl>
                                @endif
                                <button type="button" class="btn btn-outline-secondary mt-3" onclick="copyAssetLink(this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1.2em" width="1.2em" viewBox="0 0 640 640"><!--!Font Awesome Free v7.3.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path fill="currentColor" d="M371.8 82.4C359.8 87.4 352 99 352 112L352 192L240 192C142.8 192 64 270.8 64 368C64 481.3 145.5 531.9 164.2 542.1C166.7 543.5 169.5 544 172.3 544C183.2 544 192 535.1 192 524.3C192 516.8 187.7 509.9 182.2 504.8C172.8 496 160 478.4 160 448.1C160 395.1 203 352.1 256 352.1L352 352.1L352 432.1C352 445 359.8 456.7 371.8 461.7C383.8 466.7 397.5 463.9 406.7 454.8L566.7 294.8C579.2 282.3 579.2 262 566.7 249.5L406.7 89.5C397.5 80.3 383.8 77.6 371.8 82.6z"/></svg>
                                </button>
                                <script>
                                    async function copyAssetLink(button) {
                                    if (button.disabled) return;
                                    const originalContent = button.innerHTML;
                                    button.disabled = true;
                                    try {
                                        await navigator.clipboard.writeText(window.location.href);
                                        button.textContent = 'Link copied onto Clipboard';
                                    } catch {
                                        button.textContent = 'Could not copy';
                                    }
                                    setTimeout(() => {
                                        button.innerHTML = originalContent;
                                        button.disabled = false;
                                    }, 2000);
                                }
                                </script>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-secondary" role="alert">Asset not found or unavailable.</div>
                    @endif
                </div>
            </div>
        </main>

        @include('partials.footer')
    </body>
</html>