<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes da Encomenda #' . $order->id) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Informações de Envio e Pagamento</h3>
                        <p class="mb-2"><strong>Data do Pedido:</strong> {{ $order->date }}</p>
                        <p class="mb-2"><strong>Estado atual:</strong> <span class="uppercase font-semibold {{ $order->status === 'pending' ? 'text-yellow-600' : ($order->status === 'closed' ? 'text-green-600' : 'text-red-600') }}">{{ $order->status }}</span></p>
                        <p class="mb-2"><strong>Método de Pagamento:</strong> {{ $order->payment_type }}</p>
                        <p class="mb-2"><strong>NIF:</strong> {{ $order->nif }}</p>
                        <p class="mb-2"><strong>Morada de Entrega:</strong> {{ $order->address }}</p>
                        
                        @if(!empty($order->notes))
                            <div class="mt-4 p-3 bg-gray-50 rounded-md border border-gray-200">
                                <strong class="text-gray-700 block mb-1">Notas Adicionais:</strong>
                                <span class="text-gray-600 italic">{{ $order->notes }}</span>
                            </div>
                        @endif

                        @if($order->status === 'canceled' && $order->reason_for_cancellation)
                            <div class="mt-4 p-3 bg-red-50 rounded-md border border-red-200">
                                <strong class="text-red-800 block mb-1">Motivo da Anulação:</strong>
                                <span class="text-red-700">{{ $order->reason_for_cancellation }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="text-right flex flex-col justify-between items-end">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Total Pago</h3>
                            <p class="text-3xl font-bold text-gray-900">{{ number_format($order->total_price, 2, ',', ' ') }} €</p>
                        </div>
                        @if($order->receipt_url)
                            <a href="{{ route('receipt.download', $order) }}" class="mt-4 inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                Descarregar Recibo (PDF)
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Artigos Incluídos</h3>
                <div class="divide-y divide-gray-200">
                    @foreach($items as $item)
                        <div class="py-4 flex items-center gap-6">
                            <x-tshirt-preview
                                :color-code="$item->color_code"
                                :image="$item->tshirtImage->image_url ?? null"
                                size="sm"
                                class="shrink-0" />

                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $item->tshirtImage->name ?? 'T-Shirt Personalizada' }}</h4>
                                <p class="text-sm text-gray-500 mt-1">Tamanho: {{ $item->size }} | Código Cor: {{ $item->color_code }}</p>
                            </div>

                            <div class="text-right">
                                <div class="text-xs text-gray-500">Quantidade</div>
                                <div class="text-sm font-medium text-gray-900 mt-1">
                                    {{ $item->qty }} <span class="text-gray-500 font-normal">x {{ number_format($item->unit_price, 2, ',', ' ') }} €</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>