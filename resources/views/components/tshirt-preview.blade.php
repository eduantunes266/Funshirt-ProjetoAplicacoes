@props([
    'colorCode' => null,
    'image' => null,
    'size' => 'md',
])

@php
    $sizePx = match ($size) {
        'sm' => 80,
        'lg' => 320,
        default => 160,
    };

    $baseImageUrl = $colorCode
        ? asset('storage/tshirt_base/' . $colorCode . '.jpg')
        : asset('storage/tshirt_base/plain_white.png');

    $designUrl = null;
    if ($image) {
        $designUrl = str_starts_with($image, 'http') ? $image : asset('storage/tshirt_images/' . $image);
    }
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