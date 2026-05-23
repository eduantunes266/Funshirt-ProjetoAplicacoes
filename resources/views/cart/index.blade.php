@extends('layouts.app')

@section('content')
<div style="padding: 20px;">
    <h1>O meu Carrinho</h1>

    @if(empty($cart))
        <p>O seu carrinho está vazio.</p>
    @else
        @foreach($cart as $key => $item)
            <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #ccc; padding:15px 0;">
                <div style="display:flex; gap:15px; align-items:center;">
                    <img src="{{ asset('storage/tshirt_images/' . $item['display_image_url']) }}"
                         style="width:80px; height:80px; object-fit:contain;">

                    <div>
                        <h3>{{ $item['name'] }}</h3>
                        <p>{{ $item['description'] }}</p>
                        <p><strong>Preço Un.:</strong> {{ number_format($item['unit_price'], 2, ',', ' ') }} €</p>
                    </div>
                </div>

                <form action="{{ route('cart.update', $key) }}" method="POST" style="display:flex; gap:10px; align-items:center;">
                    @csrf

                    <select name="size" required>
                        @foreach(['S','M','L','XL','XXL'] as $size)
                            <option value="{{ $size }}" {{ $item['size'] == $size ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>

                    <select name="color" required>
                        @foreach(['Preto','Branco','Azul','Vermelho','Verde'] as $color)
                            <option value="{{ $color }}" {{ $item['color'] == $color ? 'selected' : '' }}>
                                {{ $color }}
                            </option>
                        @endforeach
                    </select>

                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" style="width:70px;">

                    <button type="submit" style="background:#0056b3; color:white; border:none; padding:8px 12px; border-radius:4px;">
                        Atualizar
                    </button>
                </form>

                <strong>{{ number_format($item['subtotal'], 2, ',', ' ') }} €</strong>

                <form action="{{ route('cart.remove', $key) }}" method="POST">
                    @csrf
                    <button type="submit" style="background:#dc3545; color:white; border:none; padding:8px 12px; border-radius:4px;">
                        Remover
                    </button>
                </form>
            </div>
        @endforeach

        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:20px;">
            <form action="{{ route('cart.clear') }}" method="POST">
                @csrf
                <button type="submit" style="background:#6c757d; color:white; border:none; padding:10px 16px; border-radius:4px;">
                    Limpar Carrinho
                </button>
            </form>

            <div>
                <strong style="font-size:22px;">Total: {{ number_format($total, 2, ',', ' ') }} €</strong>

                <a href="{{ route('checkout.index') }}"
                   style="margin-left:20px; background:#28a745; color:white; padding:12px 20px; border-radius:4px; text-decoration:none;">
                    Ir para Pagamento
                </a>
            </div>
        </div>
    @endif
</div>
@endsection