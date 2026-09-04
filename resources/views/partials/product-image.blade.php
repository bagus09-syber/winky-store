@props(['product', 'class' => '', 'imgClass' => ''])

@php
    $imageFile = $product->image ? public_path('images/products/' . $product->image) : null;
    $hasImage = $imageFile && file_exists($imageFile);
@endphp

<img
    src="{{ $hasImage ? asset('images/products/' . $product->image) : asset('images/products/no-image.png') }}"
    alt="{{ $product->name }}"
    class="product-image {{ $class }} {{ $imgClass }}"
    loading="lazy"
>
