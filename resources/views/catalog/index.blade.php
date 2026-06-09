@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Catálogo de T-Shirts') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Escolhe a tua próxima t-shirt e personaliza-a a teu gosto.</p>
            </div>
            <a href="{{ route('cart.index') }}" class="hidden sm:inline-flex items-center gap-2 rounded-lg bg-indigo-50 text-indigo-700 px-3 py-1.5 text-sm font-semibold hover:bg-indigo-100 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Carrinho
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">

            @include('admin.partials.flash')

            <form action="{{ url('/') }}" method="GET" class="mb-6 flex flex-wrap items-end gap-3 bg-white p-4 rounded-2xl shadow-sm ring-1 ring-gray-200">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Pesquisar (nome ou descrição)</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Ex.: dragão, abstrato…"
                           class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Categoria</label>
                    <select name="category" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todas</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="rounded-lg bg-indigo-600 text-white text-sm font-semibold px-4 py-2 shadow-sm hover:bg-indigo-700 transition">
                    Pesquisar
                </button>

                @if(request('search') || request('category'))
                    <a href="{{ url('/') }}" class="rounded-lg bg-white border border-gray-300 text-gray-700 text-sm font-semibold px-4 py-2 hover:bg-gray-50 transition">
                        Limpar
                    </a>
                @endif
            </form>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                @forelse($tshirts as $tshirt)
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 flex flex-col overflow-hidden transition hover:shadow-md hover:-translate-y-0.5 hover:ring-indigo-200">
                        <div class="p-4 flex items-center justify-center bg-gradient-to-br from-slate-50 to-indigo-50/60 aspect-square">
                            <img src="{{ asset('storage/tshirt_images/' . $tshirt->image_url) }}"
                                 alt="{{ $tshirt->name }}"
                                 class="h-44 w-full object-contain">
                        </div>

                        <div class="p-4 flex-1 flex flex-col">
                            <h3 class="font-semibold text-gray-900 line-clamp-1">{{ $tshirt->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1 h-12 overflow-hidden">
                                {{ Str::limit($tshirt->description, 90) }}
                            </p>

                            <div class="mt-3 flex items-baseline gap-2">
                                <p class="text-xl font-bold text-indigo-700">
                                    {{ number_format($price->unit_price_catalog, 2, ',', ' ') }} €
                                </p>
                            </div>
                            <p class="text-xs text-emerald-700 font-semibold mb-3">
                                A partir de {{ $price->qty_discount }} un.: {{ number_format($price->unit_price_catalog_discount, 2, ',', ' ') }} € cada
                            </p>

                            <form action="{{ route('cart.add', $tshirt->id) }}" method="POST" class="mt-auto space-y-2">
                                @csrf

                                <div class="grid grid-cols-2 gap-2">
                                    <select name="size" required class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Tamanho</option>
                                        @foreach($sizes as $size)
                                            <option value="{{ $size }}">{{ $size }}</option>
                                        @endforeach
                                    </select>
                                    <select name="color_code" required class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Cor</option>
                                        @foreach($colors as $color)
                                            <option value="{{ $color->code }}">{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <input type="number" name="quantity" value="1" min="1"
                                       class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">

                                <button type="submit" class="w-full rounded-lg bg-indigo-600 text-white text-sm font-semibold py-2 shadow-sm hover:bg-indigo-700 transition">
                                    Adicionar ao Carrinho
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-10 text-center text-gray-500">
                        Sem t-shirts para os filtros escolhidos.
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $tshirts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
