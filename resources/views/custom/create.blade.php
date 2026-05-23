<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cria a tua T-Shirt Personalizada') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                <div class="mb-6 p-4 bg-indigo-50 border border-indigo-100 rounded-md text-center">
                    <p class="text-lg font-bold text-indigo-700 mb-1">
                        Preço base: {{ number_format($price->unit_price_own, 2, ',', ' ') }} €
                    </p>
                    <p class="text-xs text-green-700 font-semibold">
                        A partir de {{ $price->qty_discount }} un.: {{ number_format($price->unit_price_own_discount, 2, ',', ' ') }} € cada
                    </p>
                </div>

                <form action="{{ route('custom.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="image" value="Imagem (PNG/JPG/GIF, máx. 2MB)" />
                        <input id="image" name="image" type="file" accept="image/*" required
                               class="mt-1 block w-full text-sm text-gray-700">
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="color_code" value="Cor base da t-shirt" />
                        <select id="color_code" name="color_code" required
                                class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                            <option value="">— Selecione —</option>
                            @foreach($colors as $color)
                                <option value="{{ $color->code }}">{{ $color->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('color_code')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="size" value="Tamanho" />
                        <select id="size" name="size" required
                                class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                            @foreach($sizes as $size)
                                <option value="{{ $size }}">{{ $size }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('size')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="quantity" value="Quantidade" />
                        <input id="quantity" name="quantity" type="number" min="1" max="10" value="1" required
                               class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                        <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                    </div>

                    <x-primary-button class="w-full justify-center">
                        Adicionar ao Carrinho
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
