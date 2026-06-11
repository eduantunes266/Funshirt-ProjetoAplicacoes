<x-app-layout>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('As minhas imagens') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Carrega as tuas estampas para personalizares as t-shirts.</p>
            </div>
            {{-- Botão Adicionar Imagem --}}
            <a href="{{ route('custom-images.create') }}"
               class="inline-flex items-center gap-1 rounded-lg bg-indigo-600 text-white text-sm font-semibold px-4 py-2 shadow-sm hover:bg-indigo-700 transition">
                + Adicionar imagem
            </a>
        </div>
    </x-slot>

    {{-- Área Principal --}}
    <div class="py-6">
        <div class="max-w-7xl mx-auto">

            {{-- Mensagens Flash --}}
            @include('admin.partials.flash')

            {{-- Informação de Preços para Imagens Personalizadas --}}
            <div class="mb-6 p-4 bg-gradient-to-r from-indigo-50 to-fuchsia-50 ring-1 ring-indigo-100 rounded-2xl text-sm text-indigo-900">
                Preço base personalizada: <strong>{{ number_format($price->unit_price_own, 2, ',', ' ') }} €</strong>
                · A partir de {{ $price->qty_discount }} un.: {{ number_format($price->unit_price_own_discount, 2, ',', ' ') }} € cada.
            </div>

            {{-- Verifica se existem imagens carregadas --}}
            @if($images->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-10 text-center">
                    <p class="text-gray-500">Ainda não tem imagens. <a href="{{ route('custom-images.create') }}" class="text-indigo-600 hover:underline">Adicionar a primeira</a>.</p>
                </div>
            @else
                {{-- Grelha de Imagens --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                    @foreach($images as $image)
                        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 flex flex-col overflow-hidden transition hover:shadow-md hover:ring-indigo-200">
                            {{-- Pré-visualização da Imagem --}}
                            <div class="p-4 flex items-center justify-center bg-gradient-to-br from-slate-50 to-fuchsia-50/60 aspect-square">
                                <img src="{{ route('custom-images.file', $image) }}"
                                     alt="{{ $image->name }}"
                                     class="h-40 w-full object-contain">
                            </div>

                            <div class="p-4 flex-1 flex flex-col">
                                {{-- Detalhes da Imagem --}}
                                <h3 class="font-semibold text-gray-900">{{ $image->name }}</h3>
                                <p class="text-xs text-gray-500 mt-1 h-10 overflow-hidden">{{ $image->description }}</p>

                                {{-- Ações (Editar e Remover) --}}
                                <div class="flex gap-3 my-2 text-sm">
                                    <a href="{{ route('custom-images.edit', $image) }}" class="text-indigo-600 hover:underline font-medium">Editar</a>
                                    <form action="{{ route('custom-images.destroy', $image) }}" method="POST"
                                          onsubmit="return confirm('Remover esta imagem?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline font-medium">Remover</button>
                                    </form>
                                </div>

                                {{-- Formulário para Adicionar ao Carrinho --}}
                                <form action="{{ route('cart.add', $image->id) }}" method="POST" class="mt-auto space-y-2 border-t border-gray-100 pt-3">
                                    @csrf
                                    <div class="grid grid-cols-2 gap-2">
                                        {{-- Seleção de Tamanho --}}
                                        <select name="size" required class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">Tamanho</option>
                                            @foreach($sizes as $size)
                                                <option value="{{ $size }}">{{ $size }}</option>
                                            @endforeach
                                        </select>
                                        {{-- Seleção de Cor --}}
                                        <select name="color_code" required class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">Cor</option>
                                            @foreach($colors as $color)
                                                <option value="{{ $color->code }}">{{ $color->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- Quantidade e Botão Submeter --}}
                                    <input type="number" name="quantity" value="1" min="1"
                                           class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <button type="submit" class="w-full rounded-lg bg-indigo-600 text-white text-sm font-semibold py-2 shadow-sm hover:bg-indigo-700 transition">
                                        Adicionar ao Carrinho
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Paginação --}}
                <div class="mt-6">
                    {{ $images->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
