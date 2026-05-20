@extends('layouts.app')

@section('content')
    <div style="padding: 20px;">
        <h1>O seu carrinho está vazio.</h1>

        @if(empty($cart))
           
        @else
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @foreach($cart as $id => $item)
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #ccc; padding-bottom: 15px;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <img src="{{ asset('storage/tshirt_images/' . $item['image_url']) }}" alt="{{ $item['name'] }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
                            <div>
                                <h3 style="margin: 0; font-size: 18px;">{{ $item['name'] }}</h3>
                                <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">{{ $item['description'] }}</p>
                            </div>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <span style="font-weight: bold;">Qtd: {{ $item['quantity'] }}</span>
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf
                                <button type="submit" style="background-color: #dc3545; color: white; font-weight: bold; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">
                                    Remover
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection