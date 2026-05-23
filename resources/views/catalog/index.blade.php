@extends('layouts.app')

@php
use Illuminate\Support\Str;
@endphp

@section('content')
    <h1>Catálogo de T-Shirts</h1>
    
    <form action="{{ url('/') }}" method="GET" style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Pesquisar por nome..." style="padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 250px; font-size: 14px;">
        
        <select name="category" onchange="this.form.submit()" style="padding: 8px 40px 8px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; background-color: white; min-width: 160px;">
            <option value="">Todas as Categorias</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        @if(request('search') || request('category'))
            <a href="{{ url('/') }}" style="background-color: #6c757d; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-size: 14px; display: flex; align-items: center;">
                Limpar
            </a>
        @endif
    </form>

    <div style="display: flex; flex-wrap: wrap; gap: 20px;">
        @foreach($tshirts as $tshirt)
            <div style="border: 1px solid #ccc; padding: 15px; width: 230px; height: 530px; text-align: center; display: flex; flex-direction: column;">
                <img src="{{ asset('storage/tshirt_images/' . $tshirt->image_url) }}" alt="{{ $tshirt->name }}" style="width: 100%; height: 220px; object-fit: contain;">
                
                <h3>{{ $tshirt->name }}</h3>
                
                <p style="height: 60px; overflow: hidden; font-size: 14px; color: #555; margin-bottom: 5px;">
                    {{ Str::limit($tshirt->description, 90) }}
                </p>
                
                <p style="font-size: 18px; font-weight: bold; color: #0056b3; margin: 5px 0 0 0;">
                    {{ number_format($price->unit_price_catalog, 2, ',', ' ') }} €
                </p>
                
                <p style="font-size: 11px; color: #28a745; font-weight: bold; margin: 0 0 10px 0;">
                    Leva {{ $price->qty_discount }} ou mais por {{ number_format($price->unit_price_catalog_discount, 2, ',', ' ') }} € cada!
                </p>
                
                <form action="{{ route('cart.add', $tshirt->id) }}" method="POST" style="margin-top: auto;">
                    @csrf

                    <div style="display: flex; gap: 5px; margin-bottom: 8px;">
                        <select name="size" required style="width: 50%; padding: 6px;">
                            <option value="">Tam.</option>
                            <option>S</option>
                            <option>M</option>
                            <option>L</option>
                            <option>XL</option>
                            <option>XXL</option>
                        </select>

                        <select name="color" required style="width: 50%; padding: 6px;">
                            <option value="">Cor</option>
                            <option>Preto</option>
                            <option>Branco</option>
                            <option>Azul</option>
                            <option>Vermelho</option>
                            <option>Verde</option>
                        </select>
                    </div>

                    <input type="number" name="quantity" value="1" min="1"
                        style="width: 100%; padding: 6px; margin-bottom: 8px;">

                    <button type="submit" style="background-color: #0056b3; color: white; font-weight: bold; padding: 8px 12px; border: none; border-radius: 4px; cursor: pointer; width: 100%; font-size: 14px;">
                        Adicionar ao Carrinho
                    </button>
                </form>
            </div>
        @endforeach
    </div>
    <div style="margin-top: 25px;">
        {{ $tshirts->links() }}
    </div>
@endsection