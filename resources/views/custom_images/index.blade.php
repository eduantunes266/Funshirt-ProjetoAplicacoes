<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('As minhas imagens') }}
            </h2>
            <a href="{{ route('custom-images.create') }}"
               class="rounded-md bg-indigo-600 text-white text-sm font-semibold px-4 py-2 hover:bg-indigo-500">
                Adicionar imagem
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @include('admin.partials.flash')

            <div class="mb-4 p-4 bg-indigo-50 border border-indigo-100 rounded-md text-sm text-indigo-800">
                Preço base personalizada: <strong>{{ number_format($price->unit_price_own, 2, ',', ' ') }} €</strong>
                · A partir de {{ $price->qty_discount }} un.: {{ number_format($price->unit_price_own_discount, 2, ',', ' ') }} € cada.
            </div>

            @if($images->isEmpty())
                <p class="text-gray-500">Ainda não tem imagens. <a href="{{ route('custom-images.create') }}" class="text-indigo-600 hover:underline">Adicionar a primeira</a>.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                    @foreach($images as $image)
                        <div class="bg-white rounded-lg shadow flex flex-col">
                            <div class="p-4 flex items-center justify-center bg-gray-50">
                                <img src="{{ route('custom-images.file', $image) }}"
                                     alt="{{ $image->name }}"
                                     class="h-40 w-full object-contain">
                            </div>

                            <div class="p-4 flex-1 flex flex-col">
                                <h3 class="font-semibold text-gray-900">{{ $image->name }}</h3>
                                <p class="text-xs text-gray-500 mt-1 h-10 overflow-hidden">{{ $image->description }}</p>

                                <div class="flex gap-3 my-2 text-sm">
                                    <a href="{{ route('custom-images.edit', $image) }}" class="text-indigo-600 hover:underline">Editar</a>
                                    <form action="{{ route('custom-images.destroy', $image) }}" method="POST"
                                          onsubmit="return confirm('Remover esta imagem?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Remover</button>
                                    </form>
                                </div>

                                <form action="{{ route('cart.add', $image->id) }}" method="POST" class="mt-auto space-y-2 border-t border-gray-100 pt-3">
                                    @csrf
                                    <div class="grid grid-cols-2 gap-2">
                                        <select name="size" required class="rounded-md border-gray-300 text-sm">
                                            <option value="">Tamanho</option>
                                            @foreach($sizes as $size)
                                                <option value="{{ $size }}">{{ $size }}</option>
                                            @endforeach
                                        </select>
                                        <select name="color_code" required class="rounded-md border-gray-300 text-sm">
                                            <option value="">Cor</option>
                                            @foreach($colors as $color)
                                                <option value="{{ $color->code }}">{{ $color->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="number" name="quantity" value="1" min="1"
                                           class="w-full rounded-md border-gray-300 text-sm">
                                    <button type="submit" class="w-full rounded-md bg-indigo-600 text-white text-sm font-semibold py-2 hover:bg-indigo-500">
                                        Adicionar ao Carrinho
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $images->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
