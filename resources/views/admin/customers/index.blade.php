<x-app-layout>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestão de Utilizadores') }}
        </h2>
    </x-slot>

    {{-- Área Principal --}}
    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-2xl p-4 sm:p-8">

                {{-- Separadores e Mensagens --}}
                @include('admin.partials.tabs')
                @include('admin.partials.flash')

                <h3 class="mb-4 text-lg font-semibold text-gray-900">Clientes</h3>

                {{-- Formulário de Filtros (Pesquisa e Estado) --}}
                <form method="get" action="{{ route('admin.customers.index') }}" class="mb-4 flex flex-wrap items-center gap-3">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Procurar por nome ou email"
                           class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm">
                    <select name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm">
                        <option value="ativos" @selected($status === 'ativos')>Ativos</option>
                        <option value="bloqueados" @selected($status === 'bloqueados')>Bloqueados</option>
                        <option value="removidos" @selected($status === 'removidos')>Removidos</option>
                    </select>
                    <x-primary-button>Filtrar</x-primary-button>
                    <a href="{{ route('admin.customers.index') }}" class="text-sm text-gray-600 hover:underline">Limpar</a>
                </form>

                {{-- Tabela de Clientes --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs uppercase tracking-wider text-gray-500 bg-gray-50">
                                <th class="px-3 py-3 rounded-l-lg">Cliente</th>
                                <th class="px-3 py-3">Email</th>
                                <th class="px-3 py-3">NIF</th>
                                <th class="px-3 py-3">Estado</th>
                                <th class="px-3 py-3 text-right rounded-r-lg">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            {{-- Itera sobre a lista de clientes --}}
                            @forelse ($customers as $customer)
                                <tr class="hover:bg-gray-50/60">
                                    <td class="px-3 py-3">
                                        {{-- Foto de perfil e nome --}}
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $customer->photoLink() }}" alt="" class="h-9 w-9 rounded-full object-cover border border-gray-200">
                                            <span class="font-medium text-gray-900">{{ $customer->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-gray-600">{{ $customer->email }}</td>
                                    <td class="px-3 py-3 text-gray-600">{{ $customer->customer?->nif ?? '—' }}</td>
                                    
                                    {{-- Estado da conta (Removido, Bloqueado ou Ativo) --}}
                                    <td class="px-3 py-3">
                                        @if ($customer->trashed())
                                            <span class="rounded-full bg-gray-100 text-gray-700 px-2.5 py-1 text-xs font-semibold ring-1 ring-gray-200">Removido</span>
                                        @elseif ($customer->blocked)
                                            <span class="rounded-full bg-red-50 text-red-700 px-2.5 py-1 text-xs font-semibold ring-1 ring-red-200">Bloqueado</span>
                                        @else
                                            <span class="rounded-full bg-emerald-50 text-emerald-700 px-2.5 py-1 text-xs font-semibold ring-1 ring-emerald-200">Ativo</span>
                                        @endif
                                    </td>
                                    
                                    {{-- Ações sobre o cliente --}}
                                    <td class="px-3 py-3">
                                        <div class="flex items-center justify-end gap-3">
                                            @unless ($customer->trashed())
                                                {{-- Alternar Bloqueio --}}
                                                <form method="post" action="{{ route('admin.accounts.toggle-block', $customer) }}">
                                                    @csrf
                                                    @method('patch')
                                                    <button type="submit" class="text-amber-600 hover:underline font-medium">
                                                        {{ $customer->blocked ? 'Desbloquear' : 'Bloquear' }}
                                                    </button>
                                                </form>
                                                {{-- Remover Cliente (Soft Delete) --}}
                                                <form method="post" action="{{ route('admin.customers.destroy', $customer) }}"
                                                      onsubmit="return confirm('Remover o cliente {{ $customer->name }}?')">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="text-red-600 hover:underline font-medium">Remover</button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-400">—</span>
                                            @endunless
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-8 text-center text-gray-500">Sem clientes a apresentar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginação --}}
                <div class="mt-4">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
