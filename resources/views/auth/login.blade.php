<!DOCTYPE html>
<html data-bs-theme="dark" lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
    </head>
    <body class="d-flex flex-column min-vh-100">
        @include('partials.navbar')        



        <main class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-5 col-xl-4">
                    <div class="card border-secondary shadow">
                        <div class="card-body p-4">
                            <h1 class="h3 mb-4 text-center">Log in</h1>
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                            @endif
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address</label>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" autocomplete="email" autofocus required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="current-password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-check mb-4">
                                    <input id="remember" type="checkbox" name="remember" value="1" class="form-check-input" @checked(old('remember'))>
                                    <label for="remember" class="form-check-label">Remember me</label>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Log in</button>
                            </form>
                            @if (Route::has('password.request'))
                                <div class="text-center mt-3">
                                    <a href="{{ route('password.request') }}" class="link-secondary">Forgot your password?</a>
                                </div>
                            @endif
                            <hr class="my-4">
                            <p class="text-center mb-0">Don't have an account? <a href="{{ route('register') }}">Register</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        @include('partials.footer')
    </body>
</html>
