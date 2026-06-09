<x-app-layout>
@php
    $euro = fn ($v) => number_format((float) $v, 2, ',', ' ') . ' €';
    $maxMonthly = collect($monthlyRevenue)->max('total') ?: 0;
@endphp

<div class="py-6 space-y-8">

    <div class="rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-fuchsia-500 text-white p-6 sm:p-8 shadow-sm">
        <h1 class="text-2xl sm:text-3xl font-bold">Painel de Estatísticas</h1>
        <p class="mt-1 text-sm text-indigo-100">Visão global do negócio FunShirt em tempo real.</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-5">
            <p class="text-xs uppercase tracking-wider font-semibold text-gray-500">Faturação total</p>
            <p class="mt-2 text-2xl font-bold text-emerald-600">{{ $euro($totalRevenue) }}</p>
            <p class="text-xs text-gray-400 mt-1">Encomendas fechadas</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-5">
            <p class="text-xs uppercase tracking-wider font-semibold text-gray-500">Valor médio</p>
            <p class="mt-2 text-2xl font-bold text-gray-800">{{ $euro($avgOrder) }}</p>
            <p class="text-xs text-gray-400 mt-1">Por encomenda fechada</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-5">
            <p class="text-xs uppercase tracking-wider font-semibold text-gray-500">T-shirts vendidas</p>
            <p class="mt-2 text-2xl font-bold text-gray-800">{{ number_format($tshirtsSold, 0, ',', ' ') }}</p>
            <p class="text-xs text-gray-400 mt-1">Unidades fechadas</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-5">
            <p class="text-xs uppercase tracking-wider font-semibold text-gray-500">Total encomendas</p>
            <p class="mt-2 text-2xl font-bold text-gray-800">{{ number_format($totalOrders, 0, ',', ' ') }}</p>
            <p class="text-xs text-gray-400 mt-1">Todos os estados</p>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-5">
            <p class="text-xs uppercase tracking-wider font-semibold text-gray-500">Faturação este mês</p>
            <p class="mt-2 text-xl font-bold text-emerald-600">{{ $euro($revenueThisMonth) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-5">
            <p class="text-xs uppercase tracking-wider font-semibold text-gray-500">Faturação este ano</p>
            <p class="mt-2 text-xl font-bold text-emerald-600">{{ $euro($revenueThisYear) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-5">
            <p class="text-xs uppercase tracking-wider font-semibold text-gray-500">Clientes registados</p>
            <p class="mt-2 text-xl font-bold text-indigo-600">{{ number_format($totalClients, 0, ',', ' ') }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-5">
            <p class="text-xs uppercase tracking-wider font-semibold text-gray-500">Catálogo</p>
            <p class="mt-2 text-xl font-bold text-gray-800">{{ $catalogImages }} imagens</p>
            <p class="text-xs text-gray-400 mt-1">{{ $totalCategories }} categorias · {{ $totalColors }} cores</p>
        </div>
    </div>

    <div>
        <h2 class="text-base font-semibold text-gray-800 mb-3">Encomendas por estado</h2>
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-5 text-center">
                <p class="text-xs uppercase tracking-wider font-semibold text-gray-500">Pendentes</p>
                <p class="mt-2 text-3xl font-bold text-amber-600">{{ $pendingOrders }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-5 text-center">
                <p class="text-xs uppercase tracking-wider font-semibold text-gray-500">Fechadas</p>
                <p class="mt-2 text-3xl font-bold text-emerald-600">{{ $closedOrders }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-5 text-center">
                <p class="text-xs uppercase tracking-wider font-semibold text-gray-500">Anuladas</p>
                <p class="mt-2 text-3xl font-bold text-red-600">{{ $canceledOrders }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Faturação dos últimos 12 meses</h2>
        @if ($maxMonthly > 0)
            <div class="flex items-end gap-2" style="height: 220px;">
                @foreach ($monthlyRevenue as $m)
                    <div class="flex-1 flex flex-col items-center justify-end h-full">
                        <span class="text-[10px] text-gray-500 mb-1">{{ $m['total'] > 0 ? number_format($m['total'], 0, ',', ' ') : '' }}</span>
                        <div class="w-full rounded-t bg-gradient-to-t from-indigo-500 to-fuchsia-500" style="height: {{ (int) ($m['total'] / $maxMonthly * 170) }}px;" title="{{ $euro($m['total']) }}"></div>
                        <span class="text-[10px] text-gray-500 mt-1">{{ $m['label'] }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500">Ainda não há faturação registada.</p>
        @endif
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-800 mb-4">Top 5 imagens mais vendidas</h2>
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wider text-gray-500 border-b border-gray-200">
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

        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-800 mb-4">Top 5 categorias por faturação</h2>
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wider text-gray-500 border-b border-gray-200">
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

    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Top 5 clientes por valor gasto</h2>
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-wider text-gray-500 border-b border-gray-200">
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

    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Últimas 5 encomendas</h2>
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-wider text-gray-500 border-b border-gray-200">
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
                                <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">Pendente</span>
                            @elseif ($order->status === 'closed')
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">Fechada</span>
                            @else
                                <span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700 ring-1 ring-red-200">Anulada</span>
                            @endif
                        </td>
                        <td class="py-2 text-right">
                            <a href="{{ route('orders.show', $order->id) }}" class="text-indigo-600 hover:underline font-medium">Ver</a>
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
