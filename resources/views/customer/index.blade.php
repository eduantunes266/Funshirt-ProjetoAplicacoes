<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('As Minhas Encomendas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if($orders->isEmpty())
                    <p class="text-gray-500">Ainda não realizou nenhuma encomenda.</p>
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