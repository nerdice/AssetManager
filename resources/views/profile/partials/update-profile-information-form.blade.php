<section>
    <header>
        <h2 class="h5 fw-medium text-body">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 small text-body-secondary">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
        <div class="mt-3 p-3 rounded border">
            <h3 class="h6 mb-2">Verify your email address</h3>
            <p class="small text-body-secondary mb-3">Confirm your email address so you can access all account features.</p>
            @if (session('status') === 'verification-link-sent')
                <div class="alert alert-success mb-3" role="status">Verification email sent. Check your inbox.</div>
            @endif
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-outline-primary btn-sm">Send verification email</button>
            </form>
        </div>
    @elseif ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && $user->hasVerifiedEmail())
        <div class="mt-3 p-3 rounded border">
            <h3 class="h6 my-2 text-body-secondary">Your email address is already verified. ✅</h3>
        </div>
    @endif

    <form method="post" action="{{ route('profile.update') }}" class="mt-4 am-stack">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 d-block w-100" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 d-block w-100" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="small mt-2 text-body">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="btn btn-link p-0 small">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 fw-medium small text-success">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="small text-body-secondary"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
