<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', new \App\Rules\PasswordCustom()],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico no es válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirectIntended(route('home', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header title="Crear una cuenta" description="Ingresa tus datos para crear tu cuenta" />

    <!-- Estado de la sesión -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Nombre -->
        <flux:input
            wire:model="name"
            label="Nombre"
            type="text"
            required
            autofocus
            autocomplete="name"
            placeholder="Nombre completo"
        />

        <!-- Correo electrónico -->
        <flux:input
            wire:model="email"
            label="Correo electrónico"
            type="email"
            required
            autocomplete="email"
            placeholder="correo@ejemplo.com"
        />

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

        <!-- Confirmar contraseña -->
        <flux:input
            wire:model="password_confirmation"
            label="Confirmar contraseña"
            type="password"
            required
            autocomplete="new-password"
            placeholder="Confirmar contraseña"
            viewable
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                Crear cuenta
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        <span>¿Ya tienes una cuenta?</span>
        <flux:link :href="route('login')" wire:navigate>Iniciar sesión</flux:link>
    </div>
</div>
