<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestão do Catálogo') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-2xl p-4 sm:p-8">

                @include('admin.partials.tabs')
                @include('admin.partials.flash')

                <h3 class="text-lg font-semibold text-gray-900 mb-1">Configuração de preços</h3>
                <p class="text-sm text-gray-500 mb-6">
                    Estes preços aplicam-se a todo o catálogo e a todas as t-shirts personalizadas.
                </p>

                <form method="POST" action="{{ route('admin.prices.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        <div>
                            <x-input-label for="unit_price_catalog" value="Preço unitário — Catálogo (€)" />
                            <x-text-input id="unit_price_catalog" name="unit_price_catalog" type="number"
                                          step="0.01" min="0"
                                          value="{{ old('unit_price_catalog', $price->unit_price_catalog) }}" required
                                          class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('unit_price_catalog')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="unit_price_catalog_discount" value="Preço unitário — Catálogo c/ desconto (€)" />
                            <x-text-input id="unit_price_catalog_discount" name="unit_price_catalog_discount" type="number"
                                          step="0.01" min="0"
                                          value="{{ old('unit_price_catalog_discount', $price->unit_price_catalog_discount) }}" required
                                          class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('unit_price_catalog_discount')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="unit_price_own" value="Preço unitário — Personalizada (€)" />
                            <x-text-input id="unit_price_own" name="unit_price_own" type="number"
                                          step="0.01" min="0"
                                          value="{{ old('unit_price_own', $price->unit_price_own) }}" required
                                          class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('unit_price_own')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="unit_price_own_discount" value="Preço unitário — Personalizada c/ desconto (€)" />
                            <x-text-input id="unit_price_own_discount" name="unit_price_own_discount" type="number"
                                          step="0.01" min="0"
                                          value="{{ old('unit_price_own_discount', $price->unit_price_own_discount) }}" required
                                          class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('unit_price_own_discount')" class="mt-2" />
                        </div>

                        <div class="sm:col-span-2">
                            <x-input-label for="qty_discount" value="Quantidade a partir da qual aplica desconto" />
                            <x-text-input id="qty_discount" name="qty_discount" type="number"
                                          min="1"
                                          value="{{ old('qty_discount', $price->qty_discount) }}" required
                                          class="mt-1 block w-full" />
                            <p class="mt-1 text-xs text-gray-500">A partir desta quantidade (inclusive), aplica-se o preço com desconto.</p>
                            <x-input-error :messages="$errors->get('qty_discount')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <x-primary-button>Guardar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>