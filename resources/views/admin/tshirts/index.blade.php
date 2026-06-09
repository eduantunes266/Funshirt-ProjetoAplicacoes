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

                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">T-shirts do catálogo</h3>
                    <a href="{{ route('admin.tshirts.create') }}"
                       class="inline-flex items-center gap-1 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition">
                        + Nova t-shirt
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs uppercase tracking-wider text-gray-500 bg-gray-50">
                                <th class="px-3 py-3 rounded-l-lg">Imagem</th>
                                <th class="px-3 py-3">Nome</th>
                                <th class="px-3 py-3">Categoria</th>
                                <th class="px-3 py-3 text-right rounded-r-lg">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($tshirts as $tshirt)
                                <tr class="hover:bg-gray-50/60">
                                    <td class="px-3 py-3">
                                        <img src="{{ asset('storage/tshirt_images/' . $tshirt->image_url) }}"
                                             alt="{{ $tshirt->name }}"
                                             class="h-14 w-14 object-contain border border-gray-200 rounded-lg bg-gradient-to-br from-slate-50 to-indigo-50/60">
                                    </td>
                                    <td class="px-3 py-3 text-gray-900">
                                        <div class="font-medium">{{ $tshirt->name }}</div>
                                        <div class="text-xs text-gray-500">{{ \Illuminate\Support\Str::limit($tshirt->description, 80) }}</div>
                                    </td>
                                    <td class="px-3 py-3 text-gray-700">
                                        {{ $tshirt->category?->name ?? '—' }}
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('admin.tshirts.edit', $tshirt) }}" class="text-indigo-600 hover:underline font-medium">Editar</a>
                                            <form method="post" action="{{ route('admin.tshirts.destroy', $tshirt) }}"
                                                  onsubmit="return confirm('Remover a t-shirt {{ $tshirt->name }}?')">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="text-red-600 hover:underline font-medium">Remover</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 py-8 text-center text-gray-500">Sem t-shirts a apresentar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $tshirts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
