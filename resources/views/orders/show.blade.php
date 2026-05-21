@extends('layouts.app')

@section('content')
    <div style="max-width: 1000px; margin: 40px auto; background-color: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #edf2f7;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h1 style="font-size: 24px; font-weight: 600; color: #1a202c; margin: 0;">Detalhes da Encomenda #{{ $order->id }}</h1>
            <a href="{{ route('orders.index') }}" style="background-color: #718096; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-weight: 600; font-size: 14px;">Voltar</a>
        </div>

        <div style="display: flex; gap: 20px; margin-bottom: 40px;">
            <div style="flex: 1; padding: 20px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px;">
                <p style="margin-top: 0; color: #718096; font-size: 14px; margin-bottom: 15px;">Informação Geral</p>
                <p style="margin-bottom: 12px; font-size: 15px; color: #1a202c;"><strong>Data:</strong> {{ $order->date }}</p>
                <p style="margin-bottom: 20px; font-size: 15px; color: #1a202c;"><strong>Total:</strong> {{ number_format($order->total_price, 2, ',', ' ') }} €</p>
                
                <div style="display: flex; align-items: center; gap: 15px;">
                    <p style="margin: 0; font-size: 15px; color: #1a202c;"><strong>Estado:</strong> 
                        @if($order->status === 'pending')
                            <span style="background-color: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; margin-left: 5px;">Pendente</span>
                        @elseif($order->status === 'closed')
                            <span style="background-color: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; margin-left: 5px;">Fechada</span>
                        @else
                            <span style="background-color: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; margin-left: 5px;">Anulada</span>
                        @endif
                    </p>

                    @if($order->status === 'pending')
                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="closed">
                            <button type="submit" style="background-color: #38a169; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: 600;">
                                Marcar como Fechada
                            </button>
                        </form>

                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="canceled">
                            <button type="submit" style="background-color: #e53e3e; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: 600;">
                                Anular Encomenda
                            </button>
                        </form>
                    @endif
                </div>

                @if($order->status === 'closed' && $order->receipt_url)
                    @can('downloadReceipt', $order)
                        <p style="margin: 15px 0 0 0;">
                            <a href="{{ route('receipts.download', $order->id) }}" style="display: inline-block; background-color: #3182ce; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-weight: 600; font-size: 14px;">
                                Descarregar recibo (PDF)
                            </a>
                        </p>
                    @endcan
                @endif
            </div>

            <div style="flex: 1; padding: 20px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px;">
                <p style="margin-top: 0; color: #718096; font-size: 14px; margin-bottom: 15px;">Dados de Faturação</p>
                <p style="margin-bottom: 12px; font-size: 15px; color: #1a202c;"><strong>NIF:</strong> {{ $order->nif }}</p>
                <p style="margin-bottom: 12px; font-size: 15px; color: #1a202c;"><strong>Morada:</strong> {{ $order->address }}</p>
                <p style="margin-bottom: 0; font-size: 15px; color: #1a202c;"><strong>Pagamento:</strong> {{ $order->payment_type }} (Ref: {{ $order->payment_ref }})</p>
            </div>
        </div>

        <h2 style="font-size: 18px; font-weight: 600; color: #2d3748; margin-bottom: 20px;">Itens da Encomenda</h2>
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 12px 12px 12px 0; font-weight: 500; color: #4a5568; font-size: 14px;">ID Imagem</th>
                    <th style="padding: 12px; font-weight: 500; color: #4a5568; font-size: 14px;">Cor</th>
                    <th style="padding: 12px; font-weight: 500; color: #4a5568; font-size: 14px;">Tamanho</th>
                    <th style="padding: 12px; font-weight: 500; color: #4a5568; font-size: 14px;">Quantidade</th>
                    <th style="padding: 12px; font-weight: 500; color: #4a5568; font-size: 14px;">Preço Unit.</th>
                    <th style="padding: 12px; font-weight: 500; color: #4a5568; font-size: 14px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr style="border-bottom: 1px solid #edf2f7;">
                        <td style="padding: 16px 12px 16px 0; color: #1a202c; font-size: 14px;">{{ $item->tshirt_image_id }}</td>
                        <td style="padding: 16px 12px; color: #1a202c; font-size: 14px;">{{ $item->color_code }}</td>
                        <td style="padding: 16px 12px; color: #1a202c; font-size: 14px;">{{ $item->size }}</td>
                        <td style="padding: 16px 12px; color: #1a202c; font-size: 14px;">{{ $item->qty }}</td>
                        <td style="padding: 16px 12px; color: #1a202c; font-size: 14px;">{{ number_format($item->unit_price, 2, ',', ' ') }} €</td>
                        <td style="padding: 16px 12px; color: #1a202c; font-size: 14px;">{{ number_format($item->sub_total, 2, ',', ' ') }} €</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>
@endsection