<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.blank')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:40', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);
        Session::regenerate();

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
};
?>

<div class="min-h-screen bg-white flex items-center justify-center px-4">
    <div class="w-full max-w-sm flex flex-col gap-6">

        <div class="flex flex-col items-center gap-3 text-center">
            <img
                src="{{ asset('images/sushi-logo.png') }}"
                alt="Sushi Logo"
                class="h-20 w-auto">

            <h1 class="text-2xl font-semibold text-zinc-900">
                {{ __('auth.Create account') }}
            </h1>

            <p class="text-sm text-zinc-500">
                {{ __('auth.Sign up to start your experience') }}
            </p>
        </div>

        <x-auth-session-status
            class="text-center text-sm"
            :status="session('status')" />

        <form wire:submit="register" class="flex flex-col gap-4">

            <flux:input
                wire:model="name"
                :label="__('auth.Full name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('auth.Your name')" />

            <flux:input
                wire:model="email"
                :label="__('auth.Email address')"
                type="email"
                required
                autocomplete="email"
                :placeholder="__('email@example.com')" />

            <!-- Password -->
            <flux:input
                wire:model="password"
                :label="__('auth.Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('auth.Password')"
                viewable />

            <!-- Confirm Password -->
            <flux:input
                wire:model="password_confirmation"
                :label="__('auth.Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('auth.Confirm password')"
                viewable />

            <flux:button
                type="submit"
                variant="primary"
                class="w-full mt-2"
                data-test="register-user-button">
                {{ __('auth.Create account') }}
            </flux:button>
        </form>

        <div class="text-center text-sm text-zinc-600">
            {{ __('auth.Already have an account?') }}
            <flux:link
                :href="route('login')"
                wire:navigate
                class="font-medium text-red-600 hover:underline">
                {{ __('auth.Sign in') }}
            </flux:link>
        </div>

    </div>
</div>