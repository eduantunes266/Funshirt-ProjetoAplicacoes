<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestão de Utilizadores') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-4 sm:p-8">

                @include('admin.partials.tabs')
                @include('admin.partials.flash')

                <h3 class="mb-4 text-lg font-medium text-gray-900">Clientes</h3>

                <form method="get" action="{{ route('admin.customers.index') }}" class="mb-4 flex flex-wrap items-center gap-3">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Procurar por nome ou email"
                           class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                    <select name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                        <option value="ativos" @selected($status === 'ativos')>Ativos</option>
                        <option value="bloqueados" @selected($status === 'bloqueados')>Bloqueados</option>
                        <option value="removidos" @selected($status === 'removidos')>Removidos</option>
                    </select>
                    <x-primary-button>Filtrar</x-primary-button>
                    <a href="{{ route('admin.customers.index') }}" class="text-sm text-gray-600 hover:underline">Limpar</a>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="text-left text-gray-500">
                                <th class="px-3 py-2">Cliente</th>
                                <th class="px-3 py-2">Email</th>
                                <th class="px-3 py-2">NIF</th>
                                <th class="px-3 py-2">Estado</th>
                                <th class="px-3 py-2 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($customers as $customer)
                                <tr>
                                    <td class="px-3 py-2">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $customer->photoLink() }}" alt="" class="h-9 w-9 rounded-full object-cover border border-gray-200">
                                            <span class="font-medium text-gray-900">{{ $customer->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 text-gray-600">{{ $customer->email }}</td>
                                    <td class="px-3 py-2 text-gray-600">{{ $customer->customer?->nif ?? '—' }}</td>
                                    <td class="px-3 py-2">
                                        @if ($customer->trashed())
                                            <span class="rounded-full bg-gray-200 px-2 py-1 text-xs font-medium text-gray-700">Removido</span>
                                        @elseif ($customer->blocked)
                                            <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700">Bloqueado</span>
                                        @else
                                            <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700">Ativo</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2">
                                        <div class="flex items-center justify-end gap-3">
                                            @unless ($customer->trashed())
                                                <form method="post" action="{{ route('admin.accounts.toggle-block', $customer) }}">
                                                    @csrf
                                                    @method('patch')
                                                    <button type="submit" class="text-amber-600 hover:underline">
                                                        {{ $customer->blocked ? 'Desbloquear' : 'Bloquear' }}
                                                    </button>
                                                </form>
                                                <form method="post" action="{{ route('admin.customers.destroy', $customer) }}"
                                                      onsubmit="return confirm('Remover o cliente {{ $customer->name }}?')">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="text-red-600 hover:underline">Remover</button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-400">—</span>
                                            @endunless
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-6 text-center text-gray-500">Sem clientes a apresentar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
