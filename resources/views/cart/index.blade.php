<x-app-layout>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('O meu Carrinho') }}
        </h2>
        <p class="text-sm text-gray-500 mt-0.5">Revê os artigos antes de finalizar a encomenda.</p>
    </x-slot>

    {{-- Área Principal --}}
    <div class="py-6">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-2xl p-4 sm:p-8">

                {{-- Mensagens Flash --}}
                @include('admin.partials.flash')

                {{-- Verifica se o carrinho está vazio --}}
                @if(empty($cart))
                    <div class="text-center py-10">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <p class="text-gray-600">O seu carrinho está vazio.</p>
                        <a href="{{ route('home') }}" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-indigo-600 text-white px-4 py-2 text-sm font-semibold shadow-sm hover:bg-indigo-700 transition">
                            Ver catálogo
                        </a>
                    </div>
                @else
                    {{-- Lista de Itens no Carrinho --}}
                    <div class="divide-y divide-gray-100">
                        @foreach($cart as $key => $item)
                            <div class="py-4 flex flex-wrap items-center gap-4">

                                {{-- Pré-visualização da T-shirt --}}
                                <x-tshirt-preview
                                    :color-code="$item['color_code'] ?? null"
                                    :image-url="$item['display_image_url']"
                                    size="md"
                                    class="shrink-0" />

                                {{-- Detalhes da T-shirt (Nome, Descrição, Preço, Etiquetas) --}}
                                <div class="min-w-[200px] flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $item['name'] }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ $item['description'] }}</p>
                                    <div class="mt-2 flex flex-wrap items-center gap-2">
                                        <span class="text-sm text-gray-700">
                                            <strong>{{ number_format($item['unit_price'], 2, ',', ' ') }} €</strong> / un.
                                        </span>
                                        @if($item['has_discount'])
                                            <span class="inline-block bg-emerald-50 text-emerald-700 text-xs font-semibold px-2 py-1 rounded-full ring-1 ring-emerald-200">
                                                Desconto de quantidade aplicado
                                            </span>
                                        @endif
                                        @if(($item['is_custom'] ?? false))
                                            <span class="inline-block bg-fuchsia-50 text-fuchsia-700 text-xs font-semibold px-2 py-1 rounded-full ring-1 ring-fuchsia-200">
                                                Personalizada
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Formulário para atualizar tamanho, cor e quantidade --}}
                                <form action="{{ route('cart.update', $key) }}" method="POST" class="flex flex-wrap items-end gap-2">
                                    @csrf

                                    <div>
                                        <label class="block text-xs text-gray-500">Tamanho</label>
                                        <select name="size" required class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @foreach($sizes as $sz)
                                                <option value="{{ $sz }}" {{ ($item['size'] ?? '') === $sz ? 'selected' : '' }}>{{ $sz }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs text-gray-500">Cor</label>
                                        <select name="color_code" required class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @foreach($colors as $color)
                                                <option value="{{ $color->code }}" {{ ($item['color_code'] ?? '') === $color->code ? 'selected' : '' }}>{{ $color->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs text-gray-500">Qtd <span class="text-gray-400">(0 = remover)</span></label>
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0"
                                               class="w-20 rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <button type="submit" class="rounded-lg bg-indigo-600 text-white text-sm font-semibold px-3 py-2 shadow-sm hover:bg-indigo-700 transition">
                                        Atualizar
                                    </button>
                                </form>

                                {{-- Subtotal do Item --}}
                                <div class="text-right min-w-[100px]">
                                    <div class="text-xs text-gray-500">Subtotal</div>
                                    <div class="font-bold text-gray-900">{{ number_format($item['subtotal'], 2, ',', ' ') }} €</div>
                                </div>

                                {{-- Formulário para remover o item --}}
                                <form action="{{ route('cart.remove', $key) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-medium">Remover</button>
                                </form>
                            </div>
                        @endforeach
                    </div>

                    {{-- Rodapé do Carrinho com Ações Finais --}}
                    <div class="mt-6 flex flex-wrap items-center justify-between gap-4 border-t border-gray-200 pt-4">
                        {{-- Botão Limpar Carrinho --}}
                        <form action="{{ route('cart.clear') }}" method="POST"
                              onsubmit="return confirm('Esvaziar o carrinho?')">
                            @csrf
                            <button type="submit" class="rounded-lg bg-white border border-gray-300 text-gray-700 text-sm font-semibold px-4 py-2 hover:bg-gray-50 transition">
                                Limpar carrinho
                            </button>
                        </form>

                        {{-- Total e Botão Pagar --}}
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <div class="text-sm text-gray-500">Total</div>
                                <div class="text-2xl font-bold text-gray-900">{{ number_format($total, 2, ',', ' ') }} €</div>
                            </div>
                            {{-- O checkout é exclusivo de clientes; anónimos são encaminhados para login --}}
                            @unless(auth()->check() && ! auth()->user()->isCustomer())
                                <a href="{{ route('checkout.index') }}"
                                   class="rounded-lg bg-emerald-600 text-white font-semibold px-5 py-3 shadow-sm hover:bg-emerald-700 transition">
                                    Ir para pagamento
                                </a>
                            @endunless
                        </div>
                    </div>

                    {{-- Nota Informativa sobre Descontos --}}
                    <p class="text-xs text-gray-500 mt-3">
                        Desconto de quantidade aplica-se a partir de {{ $priceRule->qty_discount }} unidades do mesmo item.
                    </p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
