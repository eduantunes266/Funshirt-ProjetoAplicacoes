<x-app-layout>
@php
    $euro = fn ($v) => number_format((float) $v, 2, ',', ' ') . ' €';
    $maxMonthly = collect($monthlyRevenue)->max('total') ?: 0;
@endphp

<div class="max-w-7xl mx-auto py-10 px-4">

    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Painel de Estatísticas</h1>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
        <div class="bg-white rounded-lg shadow border border-gray-100 p-5">
            <p class="text-sm text-gray-500">Faturação total</p>
            <p class="mt-1 text-2xl font-bold text-emerald-600">{{ $euro($totalRevenue) }}</p>
            <p class="text-xs text-gray-400 mt-1">Encomendas fechadas</p>
        </div>
        <div class="bg-white rounded-lg shadow border border-gray-100 p-5">
            <p class="text-sm text-gray-500">Valor médio por encomenda</p>
            <p class="mt-1 text-2xl font-bold text-gray-800">{{ $euro($avgOrder) }}</p>
            <p class="text-xs text-gray-400 mt-1">Encomendas fechadas</p>
        </div>
        <div class="bg-white rounded-lg shadow border border-gray-100 p-5">
            <p class="text-sm text-gray-500">T-shirts vendidas</p>
            <p class="mt-1 text-2xl font-bold text-gray-800">{{ number_format($tshirtsSold, 0, ',', ' ') }}</p>
            <p class="text-xs text-gray-400 mt-1">Unidades em encomendas fechadas</p>
        </div>
        <div class="bg-white rounded-lg shadow border border-gray-100 p-5">
            <p class="text-sm text-gray-500">Total de encomendas</p>
            <p class="mt-1 text-2xl font-bold text-gray-800">{{ number_format($totalOrders, 0, ',', ' ') }}</p>
            <p class="text-xs text-gray-400 mt-1">Todos os estados</p>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow border border-gray-100 p-5">
            <p class="text-sm text-gray-500">Faturação este mês</p>
            <p class="mt-1 text-xl font-bold text-emerald-600">{{ $euro($revenueThisMonth) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow border border-gray-100 p-5">
            <p class="text-sm text-gray-500">Faturação este ano</p>
            <p class="mt-1 text-xl font-bold text-emerald-600">{{ $euro($revenueThisYear) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow border border-gray-100 p-5">
            <p class="text-sm text-gray-500">Clientes registados</p>
            <p class="mt-1 text-xl font-bold text-blue-600">{{ number_format($totalClients, 0, ',', ' ') }}</p>
        </div>
        <div class="bg-white rounded-lg shadow border border-gray-100 p-5">
            <p class="text-sm text-gray-500">Catálogo</p>
            <p class="mt-1 text-xl font-bold text-gray-800">{{ $catalogImages }} imagens</p>
            <p class="text-xs text-gray-400 mt-1">{{ $totalCategories }} categorias · {{ $totalColors }} cores</p>
        </div>
    </div>

    <h2 class="text-lg font-medium text-gray-800 mb-3">Encomendas por estado</h2>
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow border border-gray-100 p-5 text-center">
            <p class="text-sm text-gray-500">Pendentes</p>
            <p class="mt-1 text-3xl font-bold text-amber-600">{{ $pendingOrders }}</p>
        </div>
        <div class="bg-white rounded-lg shadow border border-gray-100 p-5 text-center">
            <p class="text-sm text-gray-500">Fechadas</p>
            <p class="mt-1 text-3xl font-bold text-emerald-600">{{ $closedOrders }}</p>
        </div>
        <div class="bg-white rounded-lg shadow border border-gray-100 p-5 text-center">
            <p class="text-sm text-gray-500">Anuladas</p>
            <p class="mt-1 text-3xl font-bold text-red-600">{{ $canceledOrders }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow border border-gray-100 p-6 mb-8">
        <h2 class="text-lg font-medium text-gray-800 mb-4">Faturação dos últimos 12 meses</h2>
        @if ($maxMonthly > 0)
            <div class="flex items-end gap-2" style="height: 220px;">
                @foreach ($monthlyRevenue as $m)
                    <div class="flex-1 flex flex-col items-center justify-end h-full">
                        <span class="text-[10px] text-gray-500 mb-1">{{ $m['total'] > 0 ? number_format($m['total'], 0, ',', ' ') : '' }}</span>
                        <div class="w-full bg-indigo-500 rounded-t" style="height: {{ (int) ($m['total'] / $maxMonthly * 170) }}px;" title="{{ $euro($m['total']) }}"></div>
                        <span class="text-[10px] text-gray-500 mt-1">{{ $m['label'] }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500">Ainda não há faturação registada.</p>
        @endif
    </div>

    <div class="grid md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow border border-gray-100 p-6">
            <h2 class="text-lg font-medium text-gray-800 mb-4">Top 5 imagens mais vendidas</h2>
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200">
                        <th class="py-2">Imagem</th>
                        <th class="py-2 text-right">Unidades</th>
                        <th class="py-2 text-right">Faturação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($topImages as $img)
                        <tr>
                            <td class="py-2 text-gray-800">{{ $img->name }}</td>
                            <td class="py-2 text-right text-gray-700">{{ number_format($img->qty, 0, ',', ' ') }}</td>
                            <td class="py-2 text-right text-gray-700">{{ $euro($img->revenue) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-4 text-center text-gray-500">Sem dados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-lg shadow border border-gray-100 p-6">
            <h2 class="text-lg font-medium text-gray-800 mb-4">Top 5 categorias por faturação</h2>
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200">
                        <th class="py-2">Categoria</th>
                        <th class="py-2 text-right">Unidades</th>
                        <th class="py-2 text-right">Faturação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($topCategories as $cat)
                        <tr>
                            <td class="py-2 text-gray-800">{{ $cat->name }}</td>
                            <td class="py-2 text-right text-gray-700">{{ number_format($cat->qty, 0, ',', ' ') }}</td>
                            <td class="py-2 text-right text-gray-700">{{ $euro($cat->revenue) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-4 text-center text-gray-500">Sem dados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow border border-gray-100 p-6 mb-8">
        <h2 class="text-lg font-medium text-gray-800 mb-4">Top 5 clientes por valor gasto</h2>
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-200">
                    <th class="py-2">Cliente</th>
                    <th class="py-2 text-right">Encomendas</th>
                    <th class="py-2 text-right">Total gasto</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($topClients as $client)
                    <tr>
                        <td class="py-2 text-gray-800">{{ $client->name }}</td>
                        <td class="py-2 text-right text-gray-700">{{ $client->n }}</td>
                        <td class="py-2 text-right text-gray-700">{{ $euro($client->total) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="py-4 text-center text-gray-500">Sem dados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-lg shadow border border-gray-100 p-6">
        <h2 class="text-lg font-medium text-gray-800 mb-4">Últimas 5 encomendas</h2>
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-200">
                    <th class="py-2">#</th>
                    <th class="py-2">Data</th>
                    <th class="py-2 text-right">Total</th>
                    <th class="py-2">Estado</th>
                    <th class="py-2 text-right">Ação</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($recentOrders as $order)
                    <tr>
                        <td class="py-2 text-gray-800">#{{ $order->id }}</td>
                        <td class="py-2 text-gray-700">{{ $order->date }}</td>
                        <td class="py-2 text-right text-gray-700">{{ $euro($order->total_price) }}</td>
                        <td class="py-2">
                            @if ($order->status === 'pending')
                                <span class="rounded-full bg-amber-100 px-2 py-1 text-xs font-medium text-amber-700">Pendente</span>
                            @elseif ($order->status === 'closed')
                                <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-medium text-emerald-700">Fechada</span>
                            @else
                                <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700">Anulada</span>
                            @endif
                        </td>
                        <td class="py-2 text-right">
                            <a href="{{ route('orders.show', $order->id) }}" class="text-indigo-600 hover:underline">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-4 text-center text-gray-500">Sem encomendas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
</x-app-layout>
