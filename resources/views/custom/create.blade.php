@extends('layouts.app')

@section('content')
    <div style="max-width: 600px; margin: 40px auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;">
        <h1 style="font-size: 24px; font-weight: bold; color: #333; margin-bottom: 20px; text-align: center;">Cria a tua T-Shirt Personalizada</h1>

        <div style="margin: 15px 0; padding: 15px; border: 1px solid #ccc; background-color: #f9f9f9; text-align: center;">
            <p style="font-size: 18px; font-weight: bold; color: #0056b3; margin-top: 0;">
                Preço Base: {{ number_format($price->unit_price_own, 2, ',', ' ') }} €
            </p>
            <p style="font-size: 13px; color: #28a745; font-weight: bold; margin-bottom: 0;">
                Leva {{ $price->qty_discount }} ou mais por {{ number_format($price->unit_price_own_discount, 2, ',', ' ') }} € cada!
            </p>
        </div>

        <form action="{{ route('custom.store') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 20px;">
            @csrf
            
            <div>
                <label for="image" style="display: block; font-weight: bold; margin-bottom: 8px; color: #4a5568;">Upload da Imagem</label>
                <input type="file" name="image" id="image" accept="image/*" style="width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 4px; background-color: #f7fafc;">
                @error('image')
                    <p style="color: #dc3545; font-size: 13px; font-weight: bold; margin-top: 5px; margin-bottom: 0;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="color" style="display: block; font-weight: bold; margin-bottom: 8px; color: #4a5568;">Cor Base da T-Shirt</label>
                <select name="color" id="color" style="width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 4px; background-color: #f7fafc;">
                    <option value="white">Branco</option>
                    <option value="black">Preto</option>
                    <option value="gray">Cinzento</option>
                </select>
            </div>

            <div>
                <label for="size" style="display: block; font-weight: bold; margin-bottom: 8px; color: #4a5568;">Tamanho</label>
                <select name="size" id="size" style="width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 4px; background-color: #f7fafc;">
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                </select>
            </div>

            <div>
                <label for="quantity" style="display: block; font-weight: bold; margin-bottom: 8px; color: #4a5568;">Quantidade</label>
                <select name="quantity" id="quantity" style="width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 4px; background-color: #f7fafc;">
                    @for ($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div style="margin-top: 10px;">
                <button type="submit" style="width: 100%; background-color: #0056b3; color: white; font-weight: bold; padding: 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
                    Adicionar ao Carrinho
                </button>
            </div>
        </form>
        
    </div>
@endsection