<x-guest-layout>
    <h2 class="text-xl font-semibold text-gray-900 mb-1">Criar conta</h2>
    <p class="text-sm text-gray-500 mb-6">Junta-te à FunShirt em poucos segundos.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Nome')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="gender" :value="__('Género')" />
            <select id="gender" name="gender" required
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm">
                <option value="" disabled @selected(! old('gender'))>-- Selecione o género --</option>
                <option value="M" @selected(old('gender') === 'M')>Masculino</option>
                <option value="F" @selected(old('gender') === 'F')>Feminino</option>
            </select>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Palavra-passe')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar palavra-passe')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-gray-600 hover:text-indigo-600 transition" href="{{ route('login') }}">
                {{ __('Já tenho conta') }}
            </a>

            <x-primary-button>
                {{ __('Criar conta') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
