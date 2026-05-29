<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('O meu Carrinho') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-4 sm:p-8">

                @include('admin.partials.flash')

                @if(empty($cart))
                    <p class="text-gray-500">O seu carrinho está vazio. <a href="{{ route('home') }}" class="text-indigo-600 hover:underline">Ver catálogo</a>.</p>
                @else
                    <div class="divide-y divide-gray-200">
                        @foreach($cart as $key => $item)
                            <div class="py-4 flex flex-wrap items-center gap-4">

                                <x-tshirt-preview
                                    :color-code="$item['color_code'] ?? null"
                                    :image-url="$item['display_image_url']"
                                    size="md"
                                    class="shrink-0" />

                                <div class="min-w-[200px] flex-1">
                                    <h3 class="font-medium text-gray-900">{{ $item['name'] }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ $item['description'] }}</p>
                                    <div class="mt-2 flex flex-wrap items-center gap-2">
                                        <span class="text-sm text-gray-700">
                                            <strong>{{ number_format($item['unit_price'], 2, ',', ' ') }} €</strong> / un.
                                        </span>
                                        @if($item['has_discount'])
                                            <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">
                                                Desconto de quantidade aplicado
                                            </span>
                                        @endif
                                        @if(($item['is_custom'] ?? false))
                                            <span class="inline-block bg-purple-100 text-purple-800 text-xs font-semibold px-2 py-1 rounded">
                                                Personalizada
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <form action="{{ route('cart.update', $key) }}" method="POST" class="flex flex-wrap items-end gap-2">
                                    @csrf

                                    <div>
                                        <label class="block text-xs text-gray-500">Tamanho</label>
                                        <select name="size" required class="rounded-md border-gray-300 text-sm">
                                            @foreach($sizes as $sz)
                                                <option value="{{ $sz }}" {{ ($item['size'] ?? '') === $sz ? 'selected' : '' }}>{{ $sz }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs text-gray-500">Cor</label>
                                        <select name="color_code" required class="rounded-md border-gray-300 text-sm">
                                            @foreach($colors as $color)
                                                <option value="{{ $color->code }}" {{ ($item['color_code'] ?? '') === $color->code ? 'selected' : '' }}>{{ $color->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs text-gray-500">Qtd <span class="text-gray-400">(0 = remover)</span></label>
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0"
                                               class="w-20 rounded-md border-gray-300 text-sm">
                                    </div>

                                    <button type="submit" class="rounded-md bg-indigo-600 text-white text-sm font-semibold px-3 py-2 hover:bg-indigo-500">
                                        Atualizar
                                    </button>
                                </form>

                                <div class="text-right min-w-[100px]">
                                    <div class="text-xs text-gray-500">Subtotal</div>
                                    <div class="font-bold text-gray-900">{{ number_format($item['subtotal'], 2, ',', ' ') }} €</div>
                                </div>

                                <form action="{{ route('cart.remove', $key) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:underline text-sm">Remover</button>
                                </form>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 flex flex-wrap items-center justify-between gap-4 border-t border-gray-200 pt-4">
                        <form action="{{ route('cart.clear') }}" method="POST"
                              onsubmit="return confirm('Esvaziar o carrinho?')">
                            @csrf
                            <button type="submit" class="rounded-md bg-gray-600 text-white text-sm font-semibold px-4 py-2 hover:bg-gray-500">
                                Limpar carrinho
                            </button>
                        </form>

                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <div class="text-sm text-gray-500">Total</div>
                                <div class="text-2xl font-bold text-gray-900">{{ number_format($total, 2, ',', ' ') }} €</div>
                            </div>
                            <a href="{{ route('checkout.index') }}"
                               class="rounded-md bg-green-600 text-white font-semibold px-5 py-3 hover:bg-green-500">
                                Ir para pagamento
                            </a>
                        </div>
                    </div>

                    <p class="text-xs text-gray-500 mt-3">
                        Desconto de quantidade aplica-se a partir de {{ $priceRule->qty_discount }} unidades do mesmo item.
                    </p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
