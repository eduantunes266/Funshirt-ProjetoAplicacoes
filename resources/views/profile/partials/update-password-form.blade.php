<section>
    {{-- Cabeçalho da Secção --}}
    <header>
        <h2 class="text-lg font-semibold text-gray-900">
            {{ __('Alterar palavra-passe') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            {{ __('Por seguranca, confirme a palavra-passe atual antes de definir uma nova.') }}
        </p>
    </header>

    {{-- Formulário de Atualização da Palavra-passe --}}
    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        {{-- Campo Palavra-passe Atual --}}
        <div>
            <x-input-label for="update_password_current_password" :value="__('Palavra-passe atual')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        {{-- Campo Nova Palavra-passe --}}
        <div>
            <x-input-label for="update_password_password" :value="__('Nova palavra-passe')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        {{-- Campo Confirmação da Nova Palavra-passe --}}
        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmar nova palavra-passe')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Botão de Guardar e Mensagem de Sucesso --}}
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Guardado.') }}</p>
            @endif
        </div>
    </form>
</section>
