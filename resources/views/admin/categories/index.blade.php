<x-app-layout>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestão do Catálogo') }}
        </h2>
    </x-slot>

    {{-- Área Principal --}}
    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-2xl p-4 sm:p-8">

                {{-- Separadores de Navegação (T-shirts, Categorias, Cores) --}}
                @include('admin.partials.tabs')
                
                {{-- Mensagens de Sucesso ou Erro --}}
                @include('admin.partials.flash')

                {{-- Cabeçalho da Listagem com Botão Nova Categoria --}}
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Categorias</h3>
                    <a href="{{ route('admin.categories.create') }}"
                       class="inline-flex items-center gap-1 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition">
                        + Nova categoria
                    </a>
                </div>

                {{-- Tabela de Categorias --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs uppercase tracking-wider text-gray-500 bg-gray-50">
                                <th class="px-3 py-3 rounded-l-lg">Imagem</th>
                                <th class="px-3 py-3">Nome</th>
                                <th class="px-3 py-3 text-right rounded-r-lg">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            {{-- Itera sobre as categorias passadas pelo controlador --}}
                            @forelse ($categories as $category)
                                <tr class="hover:bg-gray-50/60">
                                    <td class="px-3 py-3">
                                        {{-- Apresenta a imagem se existir --}}
                                        @if ($category->image_url)
                                            <img src="{{ asset('storage/categories/' . $category->image_url) }}"
                                                 alt="{{ $category->name }}"
                                                 class="h-12 w-12 object-contain border border-gray-200 rounded-lg bg-gradient-to-br from-slate-50 to-indigo-50/60">
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 text-gray-900 font-medium">{{ $category->name }}</td>
                                    <td class="px-3 py-3">
                                        {{-- Botões de Ação: Editar e Eliminar --}}
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600 hover:underline font-medium">Editar</a>
                                            <form method="post" action="{{ route('admin.categories.destroy', $category) }}"
                                                  onsubmit="return confirm('Eliminar a categoria {{ $category->name }}?')">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="text-red-600 hover:underline font-medium">Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-3 py-8 text-center text-gray-500">Sem categorias a apresentar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginação --}}
                <div class="mt-4">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
