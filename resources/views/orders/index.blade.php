@extends('layouts.app')

@section('content')
    <div style="max-width: 1000px; margin: 40px auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h1 style="font-size: 24px; font-weight: bold; color: #333; margin-bottom: 20px;">Gestão de Encomendas</h1>
        <div style="margin-bottom: 20px; padding: 15px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px;">
            <form action="{{ route('orders.index') }}" method="GET" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
                <div>
                    <label for="status" style="display: block; font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #495057;">Estado</label>
                    <select name="status" id="status" style="padding: 8px; border: 1px solid #ced4da; border-radius: 4px; width: 150px;">
                        <option value="">Todos</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Fechada</option>
                        <option value="canceled" {{ request('status') === 'canceled' ? 'selected' : '' }}>Anulada</option>
                    </select>
                </div>

                <div>
                    <label for="start_date" style="display: block; font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #495057;">Data Inicial</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" style="padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                </div>

                <div>
                    <label for="end_date" style="display: block; font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #495057;">Data Final</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" style="padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                </div>

                <div>
                    <button type="submit" style="background-color: #007bff; color: white; border: none; padding: 9px 15px; border-radius: 4px; cursor: pointer; font-weight: bold;">
                        Aplicar Filtros
                    </button>
                    <a href="{{ route('orders.index') }}" style="display: inline-block; background-color: #e2e8f0; color: #4a5568; text-decoration: none; padding: 9px 15px; border-radius: 4px; font-weight: bold; margin-left: 8px;">
                        Limpar
                    </a>
                </div>
            </form>
        </div>
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #f7fafc; border-bottom: 2px solid #cbd5e0;">
                    <th style="padding: 12px; font-weight: bold; color: #4a5568;">ID</th>
                    <th style="padding: 12px; font-weight: bold; color: #4a5568;">Data</th>
                    <th style="padding: 12px; font-weight: bold; color: #4a5568;">Cliente (ID)</th>
                    <th style="padding: 12px; font-weight: bold; color: #4a5568;">Total</th>
                    <th style="padding: 12px; font-weight: bold; color: #4a5568;">Estado</th>
                    <th style="padding: 12px; font-weight: bold; color: #4a5568;">Ações</th>
                </tr>
            </thead>
           <tbody>
                @forelse($orders as $order)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px;">#{{ $order->id }}</td>
                        <td style="padding: 12px;">{{ $order->date }}</td>
                        <td style="padding: 12px;">{{ $order->customer_id }}</td>
                        <td style="padding: 12px;">{{ number_format($order->total_price, 2, ',', ' ') }} €</td>
                        <td style="padding: 12px;">
                            @if($order->status === 'pending')
                                <span style="background-color: #ffeeba; color: #856404; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">Pendente</span>
                            @elseif($order->status === 'closed')
                                <span style="background-color: #d4edda; color: #155724; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">Fechada</span>
                            @else
                                <span style="background-color: #f8d7da; color: #721c24; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">Anulada</span>
                            @endif
                        </td>
   <td style="padding: 12px;">
    <a href="{{ route('orders.show', $order->id) }}" style="display: inline-block; background-color: #007bff; color: white; text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: bold;">
        Detalhes
    </a>
</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 30px; text-align: center; color: #718096; font-size: 16px;">
                            Não existem encomendas para os filtros aplicados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection