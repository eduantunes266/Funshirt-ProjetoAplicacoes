<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('As minhas encomendas') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-4 sm:p-6">

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="text-left text-gray-500">
                                <th class="px-3 py-2">#</th>
                                <th class="px-3 py-2">Data</th>
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
                                        <a href="{{ route('my-orders.show', $order) }}" class="text-indigo-600 hover:underline">Detalhes</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-6 text-center text-gray-500">
                                        Ainda não tem encomendas. <a href="{{ route('home') }}" class="text-indigo-600 hover:underline">Ver catálogo</a>.
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
