<?php

namespace App\Filament\AppPanel\Pages\Auth;

use App\Models\Auth\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as BaseLogin;

final class Login extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/login.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/login.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/login.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();

        if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            $this->throwFailureValidationException();
        }

        $user = Filament::auth()->user();

        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }

        if (($user instanceof User) && $user->isClient()) {
            if (!$user->client()->exists()) {
                Filament::auth()->logout();

                Notification::make()
                ->title('Failed to login')
                ->body('Your account has been deactivated. Please contact admin for further informations')
                ->danger()
                ->send();

                return null;
            }

            if (!$user->company()->exists()) {
                Filament::auth()->logout();

                Notification::make()
                ->title('Failed to login')
                ->body('Your company workspace has been deactivated. Please contact admin for further informations')
                ->danger()
                ->send();

                return null;
            }
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }
}
