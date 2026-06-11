{{-- Propriedades do Componente --}}
@props([
    'colorCode' => null,
    'image' => null,
    'imageUrl' => null,
    'size' => 'md',
])

@php
    // Tamanhos pre-definidos: md=160px (carrinho), sm=80px (encomenda), lg=320px (detalhe).
    $sizePx = match ($size) {
        'sm' => 80,
        'lg' => 320,
        default => 160,
    };

    // Imagem base da t-shirt
    $baseImageUrl = $colorCode
        ? asset('storage/tshirt_base/' . $colorCode . '.jpg')
        : asset('storage/tshirt_base/plain_white.png');

    // imageUrl (URL completo) tem prioridade; caso contrario assume ficheiro publico do catalogo.
    $designUrl = $imageUrl ?: ($image ? asset('storage/tshirt_images/' . $image) : null);
@endphp

{{-- Contentor da T-Shirt com Design Sobreposto --}}
<div {{ $attributes->merge(['class' => 'tshirt-preview']) }}
     style="position: relative; width: {{ $sizePx }}px; height: {{ $sizePx }}px;">
     
    {{-- T-Shirt Base --}}
    <img src="{{ $baseImageUrl }}"
         alt="T-shirt base"
         style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: contain;">
         
    {{-- Imagem de Design (Sobreposição) --}}
    @if ($designUrl)
        <img src="{{ $designUrl }}"
             alt="Design"
             style="position: absolute; top: 30%; left: 30%; width: 40%; height: 40%; object-fit: contain;">
    @endif
</div>
