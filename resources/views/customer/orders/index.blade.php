<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('As Minhas Encomendas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <form method="GET" action="{{ route('customer.orders.index') }}" class="bg-white p-4 shadow-sm sm:rounded-lg flex flex-wrap gap-4 items-end">
                <div>
                    <x-input-label for="status" value="Estado" />
                    <select name="status" id="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full text-sm">
                        <option value="">Todos</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Fechada</option>
                        <option value="canceled" {{ request('status') === 'canceled' ? 'selected' : '' }}>Anulada</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="start_date" value="Data Inicial" />
                    <x-text-input id="start_date" type="date" name="start_date" value="{{ request('start_date') }}" class="block mt-1 w-full text-sm" />
                </div>
                <div>
                    <x-input-label for="end_date" value="Data Final" />
                    <x-text-input id="end_date" type="date" name="end_date" value="{{ request('end_date') }}" class="block mt-1 w-full text-sm" />
                </div>
                <div>
                    <x-input-label for="sort" value="Ordenar por" />
                    <select name="sort" id="sort" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full text-sm">
                        <option value="recente" {{ request('sort') === 'recente' ? 'selected' : '' }}>Mais recente</option>
                        <option value="antiga" {{ request('sort') === 'antiga' ? 'selected' : '' }}>Mais antiga</option>
                        <option value="cara" {{ request('sort') === 'cara' ? 'selected' : '' }}>Mais cara</option>
                        <option value="barata" {{ request('sort') === 'barata' ? 'selected' : '' }}>Mais barata</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <x-primary-button>Filtrar</x-primary-button>
                    <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">Limpar</a>
                </div>
            </form>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if($orders->isEmpty())
                    <p class="text-gray-500">Não foram encontradas encomendas com estes critérios.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left font-medium text-gray-500">Nº Encomenda</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500">Data</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500">Estado</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500">Total</th>
                                <th class="px-6 py-3 text-right font-medium text-gray-500">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 font-medium text-gray-900">#{{ $order->id }}</td>
                                    <td class="px-6 py-4">{{ $order->date }}</td>
                                    <td class="px-6 py-4 uppercase font-semibold text-xs 
                                        {{ $order->status === 'pending' ? 'text-yellow-600' : ($order->status === 'closed' ? 'text-green-600' : 'text-red-600') }}">
                                        {{ $order->status }}
                                    </td>
                                    <td class="px-6 py-4">{{ number_format($order->total_price, 2, ',', ' ') }} €</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('customer.orders.show', $order) }}" class="text-indigo-600 hover:underline">Ver Detalhes</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>