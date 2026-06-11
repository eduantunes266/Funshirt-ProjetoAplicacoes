<x-app-layout>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Encomenda #' . $order->id) }}
            </h2>
            <a href="{{ route('my-orders.index') }}" class="text-sm text-gray-600 hover:text-indigo-600 transition">← Voltar</a>
        </div>
    </x-slot>

    {{-- Área Principal --}}
    <div class="py-6">
        <div class="max-w-5xl mx-auto space-y-6">

            {{-- Grelha de Informações da Encomenda --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Cartão com Resumo da Encomenda --}}
                <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-2xl p-6">
                    <h3 class="text-xs uppercase tracking-wider font-semibold text-gray-500 mb-4">Informação geral</h3>
                    <dl class="space-y-2.5 text-sm">
                        <div class="flex justify-between"><dt class="text-gray-500">Data:</dt><dd class="text-gray-900">{{ $order->date }}</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Total:</dt><dd class="font-semibold text-gray-900">{{ number_format($order->total_price, 2, ',', ' ') }} €</dd></div>
                        <div class="flex justify-between items-center">
                            <dt class="text-gray-500">Estado:</dt>
                            <dd>
                                @if($order->status === 'pending')
                                    <span class="inline-block rounded-full bg-amber-50 text-amber-700 text-xs font-semibold px-2.5 py-1 ring-1 ring-amber-200">Pendente</span>
                                @elseif($order->status === 'closed')
                                    <span class="inline-block rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-1 ring-1 ring-emerald-200">Fechada</span>
                                @else
                                    <span class="inline-block rounded-full bg-red-50 text-red-700 text-xs font-semibold px-2.5 py-1 ring-1 ring-red-200">Anulada</span>
                                @endif
                            </dd>
                        </div>
                    </dl>

                    {{-- Notas da Encomenda --}}
                    @if($order->notes)
                        <div class="mt-5 border-t border-gray-100 pt-4">
                            <h4 class="text-xs uppercase tracking-wider font-semibold text-gray-500 mb-1">Notas</h4>
                            <p class="text-sm text-gray-700">{{ $order->notes }}</p>
                        </div>
                    @endif

                    {{-- Motivo de Anulação --}}
                    @if($order->status === 'canceled' && $order->reason_for_cancellation)
                        <div class="mt-5 border-t border-gray-100 pt-4">
                            <h4 class="text-xs uppercase tracking-wider font-semibold text-gray-500 mb-1">Motivo da anulação</h4>
                            <p class="text-sm text-red-700">{{ $order->reason_for_cancellation }}</p>
                        </div>
                    @endif

                    {{-- Link para Descarregar Recibo --}}
                    @if($order->status === 'closed' && $order->receipt_url)
                        <a href="{{ route('receipts.download', $order->id) }}"
                           class="mt-5 inline-flex items-center gap-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold px-4 py-2 shadow-sm hover:bg-indigo-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg>
                            Descarregar recibo (PDF)
                        </a>
                    @endif
                </div>

                {{-- Cartão com Dados de Faturação --}}
                <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-2xl p-6">
                    <h3 class="text-xs uppercase tracking-wider font-semibold text-gray-500 mb-4">Dados de faturação</h3>
                    <dl class="space-y-2.5 text-sm">
                        <div class="flex justify-between"><dt class="text-gray-500">NIF:</dt><dd class="text-gray-900">{{ $order->nif }}</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Morada:</dt><dd class="text-gray-900 text-right">{{ $order->address }}</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Pagamento:</dt><dd class="text-gray-900">{{ $order->payment_type }}</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Referência:</dt><dd class="text-gray-900 font-mono text-xs">{{ $order->payment_ref }}</dd></div>
                    </dl>
                </div>
            </div>

            {{-- Tabela com Itens da Encomenda --}}
            <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-2xl p-6">
                <h3 class="text-xs uppercase tracking-wider font-semibold text-gray-500 mb-4">Itens da encomenda</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs uppercase tracking-wider text-gray-500 bg-gray-50">
                                <th class="px-3 py-2 rounded-l-lg">T-shirt</th>
                                <th class="px-3 py-2">Cor</th>
                                <th class="px-3 py-2">Tamanho</th>
                                <th class="px-3 py-2 text-right">Qtd</th>
                                <th class="px-3 py-2 text-right">Preço un.</th>
                                <th class="px-3 py-2 text-right rounded-r-lg">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            {{-- Itera sobre os itens comprados --}}
                            @foreach($order->items as $item)
                                <tr class="hover:bg-gray-50/60">
                                    <td class="px-3 py-2">
                                        <x-tshirt-preview
                                            :color-code="$item->color_code"
                                            :image-url="$item->tshirtImage?->fileUrl()"
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
