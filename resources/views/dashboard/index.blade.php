@extends('layouts.app')

@section('content')
    <div style="max-width: 1000px; margin: 40px auto;">
        <h1 style="font-size: 24px; font-weight: 600; color: #1a202c; margin-bottom: 25px;">Painel de Resumo</h1>

        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
            <div style="flex: 1; background-color: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border: 1px solid #edf2f7; text-align: center;">
                <p style="margin: 0; color: #718096; font-size: 14px; font-weight: 500;">Encomendas Pendentes</p>
                <p style="margin: 10px 0 0 0; color: #d97706; font-size: 32px; font-weight: bold;">{{ $pendingOrders }}</p>
            </div>

            <div style="flex: 1; background-color: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border: 1px solid #edf2f7; text-align: center;">
                <p style="margin: 0; color: #718096; font-size: 14px; font-weight: 500;">Encomendas Fechadas</p>
                <p style="margin: 10px 0 0 0; color: #059669; font-size: 32px; font-weight: bold;">{{ $closedOrders }}</p>
            </div>

            <div style="flex: 1; background-color: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border: 1px solid #edf2f7; text-align: center;">
                <p style="margin: 0; color: #718096; font-size: 14px; font-weight: 500;">Encomendas Anuladas</p>
                <p style="margin: 10px 0 0 0; color: #dc2626; font-size: 32px; font-weight: bold;">{{ $canceledOrders }}</p>
            </div>
        </div>

        <div style="display: flex; gap: 20px; margin-bottom: 40px;">
            <div style="flex: 1; background-color: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border: 1px solid #edf2f7; text-align: center;">
                <p style="margin: 0; color: #718096; font-size: 14px; font-weight: 500;">Faturação Total (Fechadas)</p>
                <p style="margin: 10px 0 0 0; color: #059669; font-size: 32px; font-weight: bold;">{{ number_format($totalRevenue, 2, ',', ' ') }} €</p>
            </div>

            <div style="flex: 1; background-color: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border: 1px solid #edf2f7; text-align: center;">
                <p style="margin: 0; color: #718096; font-size: 14px; font-weight: 500;">Clientes Registados</p>
                <p style="margin: 10px 0 0 0; color: #2563eb; font-size: 32px; font-weight: bold;">{{ $totalClients }}</p>
            </div>
        </div>

        <h2 style="font-size: 18px; font-weight: 600; color: #2d3748; margin-bottom: 15px;">Últimas 5 Encomendas</h2>
        <div style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border: 1px solid #edf2f7; padding: 20px;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 12px; font-weight: 500; color: #4a5568; font-size: 14px;">ID</th>
                        <th style="padding: 12px; font-weight: 500; color: #4a5568; font-size: 14px;">Data</th>
                        <th style="padding: 12px; font-weight: 500; color: #4a5568; font-size: 14px;">Total</th>
                        <th style="padding: 12px; font-weight: 500; color: #4a5568; font-size: 14px;">Estado</th>
                        <th style="padding: 12px; font-weight: 500; color: #4a5568; font-size: 14px;">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        <tr style="border-bottom: 1px solid #edf2f7;">
                            <td style="padding: 12px; color: #1a202c; font-size: 14px;">#{{ $order->id }}</td>
                            <td style="padding: 12px; color: #1a202c; font-size: 14px;">{{ $order->date }}</td>
                            <td style="padding: 12px; color: #1a202c; font-size: 14px;">{{ number_format($order->total_price, 2, ',', ' ') }} €</td>
                            <td style="padding: 12px;">
                                @if($order->status === 'pending')
                                    <span style="background-color: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Pendente</span>
                                @elseif($order->status === 'closed')
                                    <span style="background-color: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Fechada</span>
                                @else
                                    <span style="background-color: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Anulada</span>
                                @endif
                            </td>
                            <td style="padding: 12px;">
                                <a href="{{ route('orders.show', $order->id) }}" style="color: #3182ce; text-decoration: none; font-size: 14px; font-weight: 600;">Ver</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection