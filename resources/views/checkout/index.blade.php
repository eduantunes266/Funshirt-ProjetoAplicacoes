<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Finalizar Encomenda') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                @include('admin.partials.flash')

                <form action="{{ route('checkout.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="nif" value="NIF" />
                        <x-text-input id="nif" name="nif" type="text" class="mt-1 block w-full"
                                      :value="old('nif', $customer?->nif)" required />
                        <x-input-error :messages="$errors->get('nif')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="address" value="Morada de Envio" />
                        <x-text-input id="address" name="address" type="text" class="mt-1 block w-full"
                                      :value="old('address', $customer?->address)" required />
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <hr class="border-gray-200">

                    <div>
                        <x-input-label for="payment_type" value="Método de Pagamento" />
                        <select id="payment_type" name="payment_type" required
                                class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                            @foreach (['Visa', 'PayPal', 'MB WAY'] as $type)
                                <option value="{{ $type }}"
                                    {{ old('payment_type', $customer?->default_payment_type) === $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('payment_type')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="payment_ref" value="Referência de Pagamento" />
                        <x-text-input id="payment_ref" name="payment_ref" type="text" class="mt-1 block w-full"
                                      :value="old('payment_ref', $customer?->default_payment_ref)" required />
                        <p class="text-xs text-gray-500 mt-1">
                            Visa: 16 dígitos começados por 4 · PayPal: email · MB WAY: 9 dígitos começados por 9.
                        </p>
                        <x-input-error :messages="$errors->get('payment_ref')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="notes" value="Notas (opcional)" />
                        <textarea id="notes" name="notes" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 text-sm">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <button type="submit"
                            class="w-full rounded-md bg-green-600 text-white font-semibold py-3 hover:bg-green-500">
                        Confirmar Encomenda e Pagar
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
