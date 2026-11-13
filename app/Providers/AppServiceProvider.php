<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::toMailUsing(function ($user, string $token) {
        $url = route('password.reset', [
            'token' => $token,
            'email' => $user->getEmailForPasswordReset(),
        ]);

        $minutes = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');
        $name = $user->name ?: $user->username ?: 'there';

        return (new MailMessage)
            ->subject('Reset your AssetManager password')
            ->replyTo('support@pxnter.at', 'AssetManager Support')
            ->greeting("Hey {$name},")
            ->line('Let’s get you back to your devices.')
            ->line('Someone requested a password reset for your AssetManager account. If that was you, choose a new password by clicking the button below.')
            ->action('Reset my password', $url)
            ->line("This link expires in {$minutes} minutes and can only be used once.")
            ->line('Didn’t request this? You can ignore this email. Your password stays unchanged.')
            ->salutation('See you back in AssetManager.');
        });

        VerifyEmail::toMailUsing(function ($user, string $url) {
            $name = $user->name ?: $user->username ?: 'there';
            $minutes = config('auth.verification.expire', 60);

            return (new MailMessage)
                ->subject('Verify your email · AssetManager')
                ->replyTo('support@pxnter.at', 'AssetManager Support')
                ->greeting("Hey {$name},")
                ->line('Your devices. Your space. Almost ready.')
                ->line('Confirm this email address belongs to you by clicking the button below.')
                ->action('Verify my email', $url)
                ->line("This link expires in {$minutes} minutes. If it expires, you can request another from AssetManager.")
                ->line('Didn’t create an account or request this verification? You can ignore this email.')
                ->salutation('See you there, — AssetManager');
        });
    }
}
