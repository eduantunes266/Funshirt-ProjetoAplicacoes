<section>
    {{-- Cabeçalho da Secção --}}
    <header>
        <h2 class="text-lg font-semibold text-gray-900">
            {{ __('Dados de faturacao') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            {{ __('Estes dados pre-preenchem as suas encomendas. Pode sempre edita-los no checkout.') }}
        </p>
    </header>

    {{-- Formulário de Atualização de Faturação --}}
    <form method="post" action="{{ route('profile.billing.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Campo NIF --}}
        <div>
            <x-input-label for="nif" :value="__('NIF')" />
            <x-text-input id="nif" name="nif" type="text" class="mt-1 block w-full"
                          :value="old('nif', $customer?->nif)" maxlength="9" inputmode="numeric" />
            <x-input-error class="mt-2" :messages="$errors->get('nif')" />
        </div>

        {{-- Campo Morada --}}
        <div>
            <x-input-label for="address" :value="__('Morada de entrega')" />
            <textarea id="address" name="address" rows="3"
                      class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm">{{ old('address', $customer?->address) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        {{-- Campo Tipo de Pagamento Preferencial --}}
        <div>
            <x-input-label for="default_payment_type" :value="__('Tipo de pagamento preferencial')" />
            <select id="default_payment_type" name="default_payment_type"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm">
                <option value="">{{ __('-- Sem preferencia --') }}</option>
                @foreach (['Visa', 'PayPal', 'MB WAY'] as $type)
                    <option value="{{ $type }}" @selected(old('default_payment_type', $customer?->default_payment_type) === $type)>{{ $type }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('default_payment_type')" />
        </div>

        {{-- Campo Referência de Pagamento Preferencial --}}
        <div>
            <x-input-label for="default_payment_ref" :value="__('Referencia de pagamento preferencial')" />
            <x-text-input id="default_payment_ref" name="default_payment_ref" type="text" class="mt-1 block w-full"
                          :value="old('default_payment_ref', $customer?->default_payment_ref)" />
            <p class="mt-1 text-xs text-gray-500">
                {{ __('Visa: 16 digitos iniciados por 4 | PayPal: email | MB WAY: 9 digitos iniciados por 9.') }}
            </p>
            <x-input-error class="mt-2" :messages="$errors->get('default_payment_ref')" />
        </div>

        {{-- Botão de Guardar e Mensagem de Sucesso --}}
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'billing-updated')
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
