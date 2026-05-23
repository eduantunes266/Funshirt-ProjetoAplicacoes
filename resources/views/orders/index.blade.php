<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestão de Encomendas') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-4 sm:p-6">

                @include('admin.partials.flash')

                @if(auth()->user()->isAdmin())
                    <form method="GET" action="{{ route('orders.index') }}" class="mb-5 flex flex-wrap items-end gap-3">
                        <div>
                            <label for="status" class="block text-xs text-gray-500 mb-1">Estado</label>
                            <select name="status" id="status" class="rounded-md border-gray-300 text-sm">
                                <option value="">Todos</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente</option>
                                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Fechada</option>
                                <option value="canceled" {{ request('status') === 'canceled' ? 'selected' : '' }}>Anulada</option>
                            </select>
                        </div>

                        <div>
                            <label for="start_date" class="block text-xs text-gray-500 mb-1">Data inicial</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                   class="rounded-md border-gray-300 text-sm">
                        </div>

                        <div>
                            <label for="end_date" class="block text-xs text-gray-500 mb-1">Data final</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                   class="rounded-md border-gray-300 text-sm">
                        </div>

                        <x-primary-button>Filtrar</x-primary-button>
                        <a href="{{ route('orders.index') }}" class="text-sm text-gray-600 hover:underline">Limpar</a>
                    </form>
                @else
                    <p class="mb-4 text-sm text-gray-600">A apresentar apenas encomendas pendentes (processamento logístico).</p>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="text-left text-gray-500">
                                <th class="px-3 py-2">#</th>
                                <th class="px-3 py-2">Data</th>
                                <th class="px-3 py-2">Cliente (ID)</th>
                                <th class="px-3 py-2 text-right">Total</th>
                                <th class="px-3 py-2">Estado</th>
                                <th class="px-3 py-2 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($orders as $order)
                                <tr>
                                    <td class="px-3 py-2 font-medium text-gray-900">#{{ $order->id }}</td>
                                    <td class="px-3 py-2 text-gray-700">{{ $order->date }}</td>
                                    <td class="px-3 py-2 text-gray-700">{{ $order->customer_id }}</td>
                                    <td class="px-3 py-2 text-right text-gray-900">{{ number_format($order->total_price, 2, ',', ' ') }} €</td>
                                    <td class="px-3 py-2">
                                        @if($order->status === 'pending')
                                            <span class="inline-block rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1">Pendente</span>
                                        @elseif($order->status === 'closed')
                                            <span class="inline-block rounded-full bg-green-100 text-green-800 text-xs font-semibold px-2 py-1">Fechada</span>
                                        @else
                                            <span class="inline-block rounded-full bg-red-100 text-red-800 text-xs font-semibold px-2 py-1">Anulada</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-right">
                                        <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:underline">Detalhes</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-6 text-center text-gray-500">
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
