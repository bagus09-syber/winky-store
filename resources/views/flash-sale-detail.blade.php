@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-4">
    <div class="bg-card rounded-xl p-6 mb-6">
        <h2 class="text-xl font-bold text-white">{{ $promo['name'] }}</h2>
        <p class="text-text-secondary">{{ $promo->description ?? 'Flash sale promotion' }}</p>
        
        <!-- Countdown -->
        <div id="countdown" data-start="{{ $promo->start_at }}" data-end="{{ $promo->end_at }}" class="mt-4">
            <span class="digit" data-digit="1"></span>
            <span class="digit" data-digit="0"></span>
            <!-- ... more digits -->
        </div>
    </div>
    
    <div class="grid grid-cols-1 gap-4">
        <div class="bg-elevated rounded-lg p-6">
            <h3 class="text-white">Discount Details</h3>
            <p class="text-3xl font-bold text-cyan">-${{ $discountedPrice }} (was ${{ $originalPrice }})</p>
            <p class="text-text-secondary">Save {{ $discountPercent }}% on this product</p>
        </div>
        
        <div class="bg-elevated rounded-lg p-6">
            <h3 class="text-white">Stock</h3>
            <p class="text-2xl font-bold">{{ $stock }}</p>
            <p class="text-text-secondary">Limited availability</p>
        </div>
    </div>
    
    <a href="{{ route('products.index') }}" class="mt-6 btn-glow w-full">Continue Shopping</a>
</div>
@endsection