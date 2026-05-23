<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Encomenda #' . $order->id) }}
            </h2>
            <a href="{{ route('orders.index') }}" class="text-sm text-gray-600 hover:underline">← Voltar</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white shadow rounded-lg p-5">
                    <h3 class="text-sm uppercase tracking-wide text-gray-500 mb-3">Informação geral</h3>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between"><dt class="text-gray-500">Data:</dt><dd class="text-gray-900">{{ $order->date }}</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Total:</dt><dd class="font-semibold text-gray-900">{{ number_format($order->total_price, 2, ',', ' ') }} €</dd></div>
                        <div class="flex justify-between items-center">
                            <dt class="text-gray-500">Estado:</dt>
                            <dd>
                                @if($order->status === 'pending')
                                    <span class="inline-block rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1">Pendente</span>
                                @elseif($order->status === 'closed')
                                    <span class="inline-block rounded-full bg-green-100 text-green-800 text-xs font-semibold px-2 py-1">Fechada</span>
                                @else
                                    <span class="inline-block rounded-full bg-red-100 text-red-800 text-xs font-semibold px-2 py-1">Anulada</span>
                                @endif
                            </dd>
                        </div>
                    </dl>

                    @if($order->status === 'pending')
                        <div class="mt-4 flex flex-wrap gap-2">
                            <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="closed">
                                <button type="submit" class="rounded-md bg-green-600 text-white text-sm font-semibold px-3 py-2 hover:bg-green-500">
                                    Marcar como fechada
                                </button>
                            </form>
                            <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="canceled">
                                <button type="submit" class="rounded-md bg-red-600 text-white text-sm font-semibold px-3 py-2 hover:bg-red-500">
                                    Anular encomenda
                                </button>
                            </form>
                        </div>
                    @endif

                    @if($order->status === 'closed' && $order->receipt_url)
                        @can('downloadReceipt', $order)
                            <a href="{{ route('receipts.download', $order->id) }}"
                               class="mt-4 inline-block rounded-md bg-indigo-600 text-white text-sm font-semibold px-4 py-2 hover:bg-indigo-500">
                                Descarregar recibo (PDF)
                            </a>
                        @endcan
                    @endif
                </div>

                <div class="bg-white shadow rounded-lg p-5">
                    <h3 class="text-sm uppercase tracking-wide text-gray-500 mb-3">Dados de faturação</h3>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between"><dt class="text-gray-500">NIF:</dt><dd class="text-gray-900">{{ $order->nif }}</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Morada:</dt><dd class="text-gray-900 text-right">{{ $order->address }}</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Pagamento:</dt><dd class="text-gray-900">{{ $order->payment_type }}</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Referência:</dt><dd class="text-gray-900 font-mono text-xs">{{ $order->payment_ref }}</dd></div>
                    </dl>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-5">
                <h3 class="text-sm uppercase tracking-wide text-gray-500 mb-3">Itens da encomenda</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="text-left text-gray-500">
                                <th class="px-3 py-2">T-shirt</th>
                                <th class="px-3 py-2">Cor</th>
                                <th class="px-3 py-2">Tamanho</th>
                                <th class="px-3 py-2 text-right">Qtd</th>
                                <th class="px-3 py-2 text-right">Preço un.</th>
                                <th class="px-3 py-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($items as $item)
                                <tr>
                                    <td class="px-3 py-2">
                                        <x-tshirt-preview
                                            :color-code="$item->color_code"
                                            :image="$item->tshirtImage?->image_url"
                                            size="sm" />
                                    </td>
                                    <td class="px-3 py-2">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-block h-4 w-4 rounded border border-gray-300"
                                                  style="background-color: #{{ $item->color_code }};"></span>
                                            <span class="font-mono text-xs text-gray-700">#{{ $item->color_code }}</span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 text-gray-900">{{ $item->size }}</td>
                                    <td class="px-3 py-2 text-right text-gray-900">{{ $item->qty }}</td>
                                    <td class="px-3 py-2 text-right text-gray-900">{{ number_format($item->unit_price, 2, ',', ' ') }} €</td>
                                    <td class="px-3 py-2 text-right font-semibold text-gray-900">{{ number_format($item->sub_total, 2, ',', ' ') }} €</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
