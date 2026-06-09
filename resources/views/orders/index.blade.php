<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestão de Encomendas') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-2xl p-4 sm:p-6">

                @include('admin.partials.flash')

                @if(auth()->user()->isAdmin())
                    <form method="GET" action="{{ route('orders.index') }}" class="mb-5 flex flex-wrap items-end gap-3">
                        <div>
                            <label for="status" class="block text-xs font-medium text-gray-500 mb-1">Estado</label>
                            <select name="status" id="status" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente</option>
                                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Fechada</option>
                                <option value="canceled" {{ request('status') === 'canceled' ? 'selected' : '' }}>Anulada</option>
                            </select>
                        </div>

                        <div>
                            <label for="start_date" class="block text-xs font-medium text-gray-500 mb-1">Data inicial</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                   class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="end_date" class="block text-xs font-medium text-gray-500 mb-1">Data final</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                   class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="customer" class="block text-xs font-medium text-gray-500 mb-1">Cliente (nome ou email)</label>
                            <input type="text" name="customer" id="customer" value="{{ request('customer') }}"
                                   class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <x-primary-button>Filtrar</x-primary-button>
                        <a href="{{ route('orders.index') }}" class="text-sm text-gray-600 hover:underline">Limpar</a>
                    </form>
                @else
                    <p class="mb-4 text-sm text-gray-600">A apresentar apenas encomendas pendentes (processamento logístico).</p>
                @endif

                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs uppercase tracking-wider text-gray-500 bg-gray-50">
                                <th class="px-4 py-3 rounded-l-lg">#</th>
                                <th class="px-4 py-3">Data</th>
                                <th class="px-4 py-3">Cliente</th>
                                <th class="px-4 py-3 text-right">Total</th>
                                <th class="px-4 py-3">Estado</th>
                                <th class="px-4 py-3 text-right rounded-r-lg">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($orders as $order)
                                <tr class="hover:bg-gray-50/60">
                                    <td class="px-4 py-3 font-semibold text-gray-900">#{{ $order->id }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $order->date }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $order->customer?->name ?? '#'.$order->customer_id }}</td>
                                    <td class="px-4 py-3 text-right text-gray-900">{{ number_format($order->total_price, 2, ',', ' ') }} €</td>
                                    <td class="px-4 py-3">
                                        @if($order->status === 'pending')
                                            <span class="inline-block rounded-full bg-amber-50 text-amber-700 text-xs font-semibold px-2.5 py-1 ring-1 ring-amber-200">Pendente</span>
                                        @elseif($order->status === 'closed')
                                            <span class="inline-block rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-1 ring-1 ring-emerald-200">Fechada</span>
                                        @else
                                            <span class="inline-block rounded-full bg-red-50 text-red-700 text-xs font-semibold px-2.5 py-1 ring-1 ring-red-200">Anulada</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:underline font-medium">Detalhes</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        Sem encomendas a apresentar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
