<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('home', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header
        title="Confirmar contraseña"
        description="Esta es un área segura de la aplicación. Por favor, confirma tu contraseña antes de continuar."
    />

    <!-- Estado de la sesión -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="confirmPassword" class="flex flex-col gap-6">
        <!-- Contraseña -->
        <flux:input
            wire:model="password"
            label="Contraseña"
            type="password"
            required
            autocomplete="new-password"
            placeholder="Contraseña"
            viewable
        />

        <flux:button variant="primary" type="submit" class="w-full">Confirmar</flux:button>
    </form>
</div>
