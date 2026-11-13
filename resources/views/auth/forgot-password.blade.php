<!DOCTYPE html>
<html data-bs-theme="dark" lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Asset Manager</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
    </head>
    <body class="d-flex flex-column min-vh-100">
        @include('partials.navbar')        

        <main class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-5 col-xl-4">
                    <div class="card border-secondary shadow">
                        <div class="card-body p-4">
                            <h1 class="h3 mb-3 text-center">Forgot password</h1>
                            <p class="text-body-secondary text-center mb-4">Enter your email address and we will send you a password reset link.</p>
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                            @endif
                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="mb-4">
                                    <label for="email" class="form-label">Email address</label>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" autocomplete="email" autofocus required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Send password reset link</button>
                            </form>
                            <hr class="my-4">
                            <p class="text-center mb-0">Remember your password? <a href="{{ route('login') }}">Log in</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        @include('partials.footer')
    </body>
</html>
