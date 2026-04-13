<?php

namespace App\Services;

use App\Data\UserLoginData;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserAuthService
{
    public function login(UserLoginData $data): void
    {
        $this->ensureIsNotRateLimited($data);

        $credentials = [
            'email' => $data->email,
            'password' => $data->password,
        ];

        if (! Auth::attempt($credentials, $data->remember)) {
            RateLimiter::hit($this->throttleKey($data));

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($data));
    }

    public function logout(): void
    {
        Auth::guard('web')->logout();
    }

    private function ensureIsNotRateLimited(UserLoginData $data): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($data), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey($data));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    private function throttleKey(UserLoginData $data): string
    {
        return Str::lower($data->email).'|'.$data->ip;
    }
}
