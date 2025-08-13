<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico no es válido.'
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', 'Si el correo existe, se enviará un enlace de recuperación.');
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header title="¿Olvidaste tu contraseña?" description="Ingresa tu correo electrónico para recibir un enlace de recuperación" />

    <!-- Estado de la sesión -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
        <!-- Correo electrónico -->
        <flux:input
            wire:model="email"
            label="Correo electrónico"
            type="email"
            required
            autofocus
            placeholder="correo@ejemplo.com"
        />

        <flux:button variant="primary" type="submit" class="w-full">Enviar enlace de recuperación</flux:button>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
        <span>O vuelve a</span>
        <flux:link :href="route('login')" wire:navigate>iniciar sesión</flux:link>
    </div>
</div>
