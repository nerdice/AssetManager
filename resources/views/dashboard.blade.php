<!DOCTYPE html>
<html data-bs-theme="dark" lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard | AssetManager</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="d-flex flex-column min-vh-100">
        @include('partials.navbar')

        <main class="container py-4 py-lg-5">
            @if (session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif
            @if (session('success'))
                <div class="alert alert-success" role="alert">{{ session('success') }}</div>
            @endif

            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <p class="small text-uppercase text-body-secondary mb-2">Your overview</p>
                    <h1 class="h2 mb-2 text-break">Welcome back, {{ $user->name }}.</h1>
                    <p class="text-body-secondary mb-0">Here's where things stand with your devices.</p>
                </div>
                <span class="badge text-bg-secondary">Early Access</span>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body p-3 p-lg-4">
                            <p class="text-body-secondary mb-2">Total assets</p>
                            <p class="display-6 fw-semibold mb-0">{{ number_format($stats['total']) }}</p>
                            <p class="small text-body-secondary mt-2 mb-0">In your inventory</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body p-3 p-lg-4">
                            <p class="text-body-secondary mb-2">Active</p>
                            <p class="display-6 fw-semibold text-success mb-0">{{ number_format($stats['active']) }}</p>
                            <p class="small text-body-secondary mt-2 mb-0">Marked as active</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body p-3 p-lg-4">
                            <p class="text-body-secondary mb-2">Public</p>
                            <p class="display-6 fw-semibold mb-0">{{ number_format($stats['public']) }}</p>
                            <p class="small text-body-secondary mt-2 mb-0">Visible in Explore</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body p-3 p-lg-4">
                            <p class="text-body-secondary mb-2">Maintenance</p>
                            <p class="display-6 fw-semibold mb-0" style="color: #c05600">{{ number_format($stats['maintenance']) }}</p>
                            <p class="small text-body-secondary mt-2 mb-0">Marked for maintenance</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            @if ($stats['total'] === 0)
                                <h2 class="h5 mb-3">Your inventory starts here</h2>
                                <p class="text-body-secondary mb-3">Add your first device to keep its details, specifications, and warranty information together.</p>
                                <p class="text-body-secondary mb-0">Open <strong>My Assets</strong> in the navigation, then choose <strong>Add a new Asset</strong>.</p>
                            @else
                                <h2 class="h5 mb-3">Needs attention</h2>
                                @if ($brokenCount === 0 && $stats['maintenance'] === 0 && $expiringWarrantyCount === 0)
                                    <div class="d-flex align-items-start gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="text-success flex-shrink-0" aria-hidden="true">
                                            <circle cx="12" cy="12" r="9"/>
                                            <path d="m8 12 3 3 5-6"/>
                                        </svg>
                                        <div>
                                            <p class="fw-semibold mb-1">Nothing needs your attention right now.</p>
                                            <p class="text-body-secondary mb-0">No devices marked as broken or under maintenance, and no warranties expiring in the next 30 days.</p>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-body-secondary mb-3">Based on the information saved in your inventory.</p>
                                    <ul class="list-group list-group-flush">
                                        @if ($brokenCount > 0)
                                            <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center gap-3">
                                                <div>
                                                    <p class="fw-semibold mb-1">Broken devices</p>
                                                    <p class="small text-body-secondary mb-0">Status or condition is marked as broken.</p>
                                                </div>
                                                <span class="badge text-bg-danger rounded-pill">{{ $brokenCount }}</span>
                                            </li>
                                        @endif
                                        @if ($stats['maintenance'] > 0)
                                            <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center gap-3">
                                                <div>
                                                    <p class="fw-semibold mb-1">Under maintenance</p>
                                                    <p class="small text-body-secondary mb-0">Devices marked as needing maintenance.</p>
                                                </div>
                                                <span class="badge rounded-pill text-white" style="background-color: #c05600">{{ $stats['maintenance'] }}</span>
                                            </li>
                                        @endif
                                        @if ($expiringWarrantyCount > 0)
                                            <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center gap-3">
                                                <div>
                                                    <p class="fw-semibold mb-1">Warranties expiring soon</p>
                                                    <p class="small text-body-secondary mb-0">Within the next 30 days, including today.</p>
                                                </div>
                                                <span class="badge rounded-pill text-white" style="background-color: #c05600">{{ $expiringWarrantyCount }}</span>
                                            </li>
                                        @endif
                                    </ul>
                                    <p class="small text-body-secondary mt-3 mb-0">Open <a href="{{ route('asset.index') }}">Assets</a> to review your devices.</p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <h2 class="h5 mb-3">Your account</h2>
                            <p class="fw-semibold text-break mb-1">{{ $user->username }}</p>
                            <p class="small text-body-secondary text-break mb-4">{{ $user->email }}</p>
                            <p class="text-body-secondary" hidden>Manage your profile, email address, and password.</p>
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary" disabled hidden>Account settings</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        @include('partials.footer')
    </body>
</html>