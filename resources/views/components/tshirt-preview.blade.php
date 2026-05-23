@props([
    'colorCode' => null,
    'image' => null,
    'size' => 'md',
])

@php
    // Tamanhos pre-definidos: md=160px (carrinho), sm=80px (encomenda), lg=320px (detalhe).
    $sizePx = match ($size) {
        'sm' => 80,
        'lg' => 320,
        default => 160,
    };

    $baseImageUrl = $colorCode
        ? asset('storage/tshirt_base/' . $colorCode . '.jpg')
        : asset('storage/tshirt_base/plain_white.png');

    $designUrl = $image ? asset('storage/tshirt_images/' . $image) : null;
@endphp

<div {{ $attributes->merge(['class' => 'tshirt-preview']) }}
     style="position: relative; width: {{ $sizePx }}px; height: {{ $sizePx }}px;">
    <img src="{{ $baseImageUrl }}"
         alt="T-shirt base"
         style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: contain;">
    @if ($designUrl)
        <img src="{{ $designUrl }}"
             alt="Design"
             style="position: absolute; top: 30%; left: 30%; width: 40%; height: 40%; object-fit: contain;">
    @endif
</div>
