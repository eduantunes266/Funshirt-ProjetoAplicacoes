<x-guest-layout>
    <h2 class="text-xl font-semibold text-gray-900 mb-2">Confirmar palavra-passe</h2>
    <p class="text-sm text-gray-500 mb-6">
        {{ __('Esta é uma área sensível. Confirma a tua palavra-passe para continuar.') }}
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Palavra-passe')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-6">
            <x-primary-button>
                {{ __('Confirmar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
