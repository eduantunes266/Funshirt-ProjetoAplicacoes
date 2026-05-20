@extends('layouts.app')

@section('content')
    <div style="max-width: 600px; margin: 40px auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;">
        <h1 style="font-size: 24px; font-weight: bold; color: #333; margin-bottom: 20px; text-align: center;">Cria a tua T-Shirt Personalizada</h1>

        <form action="{{ route('custom.store') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 20px;">
            @csrf
            
            <div>
                <label for="image" style="display: block; font-weight: bold; margin-bottom: 8px; color: #4a5568;">Upload da Imagem</label>
                <input type="file" name="image" id="image" accept="image/*" style="width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 4px; background-color: #f7fafc;">
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

            <div style="margin-top: 10px;">
                <button type="submit" style="width: 100%; background-color: #0056b3; color: white; font-weight: bold; padding: 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
                    Adicionar ao Carrinho
                </button>
            </div>
        </form>
    </div>
@endsection