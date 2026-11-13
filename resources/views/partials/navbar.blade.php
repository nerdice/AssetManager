<nav class="navbar navbar-expand-lg am-navbar">
    <div class="container-xl px-3 px-lg-4">
        <a class="navbar-brand" href="{{ route('landingpage') }}">Asset<span>Manager</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#amNavigation" aria-controls="amNavigation" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="amNavigation">
            <ul class="navbar-nav me-auto gap-lg-1">
                @auth
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" @if(request()->routeIs('dashboard')) aria-current="page" @endif href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('asset.*') && !request()->routeIs('asset.explore*') ? 'active' : '' }}" @if(request()->routeIs('asset.*') && !request()->routeIs('asset.explore*')) aria-current="page" @endif href="{{ route('asset.index') }}">My Assets</a></li>
                @endauth
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('asset.explore*') ? 'active' : '' }}" @if(request()->routeIs('asset.explore*')) aria-current="page" @endif href="{{ route('asset.explore') }}">Explore</a></li>
            </ul>
            <div class="d-flex flex-wrap align-items-center gap-3 py-2 py-lg-0">
                @auth
                    <a class="small link-secondary text-decoration-none" href="{{ route('profile.edit') }}">{{ auth()->user()->username ?: auth()->user()->name }}</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-secondary btn-sm" type="submit">Log out</button>
                    </form>
                @else
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('login') }}">Log in</a>
                    @if (Route::has('register'))
                        <a class="btn btn-primary btn-sm" href="{{ route('register') }}">Create account</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</nav>
