<x-guest-layout>
    <h2 class="text-xl font-semibold text-gray-900 mb-1">Recuperar palavra-passe</h2>
    <p class="text-sm text-gray-500 mb-6">
        {{ __('Indica o teu email e enviamos-te um link para definir uma nova palavra-passe.') }}
    </p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-indigo-600 transition">← Voltar ao login</a>
            <x-primary-button>
                {{ __('Enviar link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
