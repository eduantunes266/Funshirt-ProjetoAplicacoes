@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Catálogo de T-Shirts') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @include('admin.partials.flash')

            <form action="{{ url('/') }}" method="GET" class="mb-6 flex flex-wrap items-end gap-3 bg-white p-4 rounded-lg shadow">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs text-gray-500 mb-1">Pesquisar (nome ou descrição)</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="w-full rounded-md border-gray-300 text-sm">
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Categoria</label>
                    <select name="category" class="rounded-md border-gray-300 text-sm">
                        <option value="">Todas</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="rounded-md bg-indigo-600 text-white text-sm font-semibold px-4 py-2 hover:bg-indigo-500">
                    Pesquisar
                </button>

                @if(request('search') || request('category'))
                    <a href="{{ url('/') }}" class="rounded-md bg-gray-500 text-white text-sm font-semibold px-4 py-2 hover:bg-gray-400">
                        Limpar
                    </a>
                @endif
            </form>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                @foreach($tshirts as $tshirt)
                    <div class="bg-white rounded-lg shadow flex flex-col">
                        <div class="p-4 flex items-center justify-center bg-gray-50">
                            <img src="{{ asset('storage/tshirt_images/' . $tshirt->image_url) }}"
                                 alt="{{ $tshirt->name }}"
                                 class="h-44 w-full object-contain">
                        </div>

                        <div class="p-4 flex-1 flex flex-col">
                            <h3 class="font-semibold text-gray-900">{{ $tshirt->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1 h-12 overflow-hidden">
                                {{ Str::limit($tshirt->description, 90) }}
                            </p>

                            <p class="text-lg font-bold text-indigo-700 mt-2">
                                {{ number_format($price->unit_price_catalog, 2, ',', ' ') }} €
                            </p>
                            <p class="text-xs text-green-700 font-semibold mb-3">
                                A partir de {{ $price->qty_discount }} un.: {{ number_format($price->unit_price_catalog_discount, 2, ',', ' ') }} € cada
                            </p>

                            <form action="{{ route('cart.add', $tshirt->id) }}" method="POST" class="mt-auto space-y-2">
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
                {{ $tshirts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
