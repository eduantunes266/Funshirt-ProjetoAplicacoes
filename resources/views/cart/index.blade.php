@extends('layouts.app')

@section('content')
    <div style="padding: 20px;">
        <h1>O meu Carrinho</h1>

        @if(empty($cart))
            <p>O seu carrinho está vazio.</p>
        @else
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @foreach($cart as $id => $item)
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #ccc; padding-bottom: 15px;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                                <img src="{{ asset('storage/tshirt_images/' . $item['display_image_url']) }}" alt="{{ $item['name'] }}" style="width: 80px; height: 80px; object-fit: contain; border-radius: 4px; background-color: #f7fafc; padding: 5px;">                            <div>
                                <h3 style="margin: 0; font-size: 18px;">{{ $item['name'] }}</h3>
                                <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">{{ $item['description'] }}</p>
                                <p style="margin: 5px 0 0 0; color: #333; font-size: 14px; font-weight: bold;">
                                    Preço Un.: {{ number_format($item['unit_price'], 2, ',', ' ') }} €
                                </p>
                            </div>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <span style="font-weight: bold;">Qtd: {{ $item['quantity'] }}</span>
                            <span style="font-weight: bold; min-width: 80px; text-align: right;">
                                {{ number_format($item['subtotal'], 2, ',', ' ') }} €
                            </span>
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf
                                <button type="submit" style="background-color: #dc3545; color: white; font-weight: bold; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">
                                    Remover
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach

                <div style="display: flex; justify-content: flex-end; align-items: center; gap: 20px; margin-top: 20px; padding-top: 15px; border-top: 2px solid #333;">
                    <span style="font-size: 22px; font-weight: bold;">Total Encomenda:</span>
                    <span style="font-size: 22px; font-weight: bold; color: #0056b3;">{{ number_format($total, 2, ',', ' ') }} €</span>
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
    <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
    <a href="{{ route('checkout.index') }}" style="background-color: #28a745; color: white; font-weight: bold; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; text-decoration: none;">
        Ir para Pagamento
    </a>
</div>
</div>
        @endif


    </div>
@endsection