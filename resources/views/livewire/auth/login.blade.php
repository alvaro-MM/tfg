<?php

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Features;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('layouts.blank')] class extends Component {

    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        $user = $this->validateCredentials();

        if (Features::canManageTwoFactorAuthentication() && $user->hasEnabledTwoFactorAuthentication()) {
            Session::put([
                'login.id' => $user->getKey(),
                'login.remember' => $this->remember,
            ]);

            $this->redirect(route('two-factor.login'), navigate: true);
            return;
        }

        Auth::login($user, $this->remember);

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        redirect()->intended(route('dashboard'));
    }

    protected function validateCredentials(): User
    {
        $user = Auth::getProvider()->retrieveByCredentials([
            'email' => $this->email,
            'password' => $this->password,
        ]);

        if (! $user || ! Auth::getProvider()->validateCredentials($user, ['password' => $this->password])) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        return $user;
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
};
?>

<div class="min-h-screen bg-white flex items-center justify-center px-4">
    <div class="w-full max-w-sm flex flex-col gap-6">

        <!-- Header -->
        <div class="flex flex-col items-center gap-3 text-center">
            <img
                src="{{ asset('images/sushi-logo.png') }}"
                alt="Sushi Logo"
                class="h-20 w-auto">

            <h1 class="text-2xl font-semibold text-zinc-900">
                {{ __('auth.Log in to your account') }}
            </h1>

            <p class="text-sm text-zinc-500">
                {{ __('auth.Enter your email and password to continue') }}
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status
            class="text-center text-sm"
            :status="session('status')" />

        <!-- Form -->
        <form wire:submit="login" class="flex flex-col gap-4">

            <flux:input
                wire:model="email"
                :label="__('auth.Email address')"
                type="email"
                required
                autofocus
                autocomplete="email"
                :placeholder="__('email@example.com')" />

            <div class="relative">
                <!-- Password con solo el ojo de Flux (viewable) -->
                <flux:input
                    wire:model="password"
                    :label="__('auth.Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('auth.Password')"
                    viewable />

                @if (Route::has('password.request'))
                <flux:link
                    class="absolute end-0 top-0 text-sm font-medium text-red-600 hover:underline"
                    :href="route('password.request')"
                    wire:navigate>
                    {{ __('auth.Forgot your password?') }}
                </flux:link>
                @endif
            </div>

            <flux:checkbox
                wire:model="remember"
                :label="__('auth.Remember me')" />

            <flux:button
                type="submit"
                variant="primary"
                class="w-full mt-2"
                data-test="login-button">
                {{ __('auth.Log in') }}
            </flux:button>
        </form>

        <!-- Register -->
        @if (Route::has('register'))
        <div class="text-center text-sm text-zinc-600">
            {{ __('auth.Don\'t have an account?') }}
            <flux:link
                :href="route('register')"
                wire:navigate
                class="font-medium text-red-600 hover:underline">
                {{ __('auth.Sign up') }}
            </flux:link>
        </div>
        @endif

    </div>
</div>