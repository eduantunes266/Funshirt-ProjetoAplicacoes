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

                {{-- Separadores e Mensagens --}}
                @include('admin.partials.tabs')
                @include('admin.partials.flash')

                {{-- Cabeçalho da Listagem com Botão Nova Cor --}}
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Cores</h3>
                    <a href="{{ route('admin.colors.create') }}"
                       class="inline-flex items-center gap-1 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition">
                        + Nova cor
                    </a>
                </div>

                {{-- Tabela de Cores --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs uppercase tracking-wider text-gray-500 bg-gray-50">
                                <th class="px-3 py-3 rounded-l-lg">Amostra</th>
                                <th class="px-3 py-3">Código</th>
                                <th class="px-3 py-3">Nome</th>
                                <th class="px-3 py-3 text-right rounded-r-lg">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            {{-- Itera sobre a lista de cores passadas --}}
                            @forelse ($colors as $color)
                                <tr class="hover:bg-gray-50/60">
                                    {{-- Amostra de cor real baseada no código --}}
                                    <td class="px-3 py-3">
                                        <span class="inline-block h-8 w-8 rounded-lg border border-gray-300 shadow-sm"
                                              style="background-color: #{{ $color->code }};"></span>
                                    </td>
                                    <td class="px-3 py-3 font-mono text-gray-700">#{{ $color->code }}</td>
                                    <td class="px-3 py-3 text-gray-900">{{ $color->name }}</td>
                                    
                                    {{-- Ações disponíveis por cor --}}
                                    <td class="px-3 py-3">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('admin.colors.edit', $color->code) }}" class="text-indigo-600 hover:underline font-medium">Editar</a>

                                            <form method="post" action="{{ route('admin.colors.destroy', $color->code) }}"
                                                  onsubmit="return confirm('Remover a cor {{ $color->name }}?')">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="text-red-600 hover:underline font-medium">Remover</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 py-8 text-center text-gray-500">Sem cores a apresentar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginação --}}
                <div class="mt-4">
                    {{ $colors->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
