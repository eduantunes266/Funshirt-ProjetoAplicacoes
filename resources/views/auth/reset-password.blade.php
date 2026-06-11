<x-guest-layout>
    {{-- Cabeçalho e Descrição --}}
    <h2 class="text-xl font-semibold text-gray-900 mb-1">Definir nova palavra-passe</h2>
    <p class="text-sm text-gray-500 mb-6">Escolhe uma palavra-passe segura para a tua conta.</p>

    {{-- Formulário de Redefinição de Palavra-passe --}}
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        {{-- Token Oculto --}}
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Campo Email --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Campo Nova Palavra-passe --}}
        <div class="mt-4">
            <x-input-label for="password" :value="__('Nova palavra-passe')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Campo Confirmar Palavra-passe --}}
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar palavra-passe')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Botão de Submissão --}}
        <div class="flex items-center justify-end mt-6">
            <x-primary-button>
                {{ __('Guardar nova palavra-passe') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
