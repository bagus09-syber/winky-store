@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4">
    <div class="bg-card rounded-xl p-6 mb-6">
        <h2 class="text-xl font-bold text-white">Flash Sale</h2>
        <p class="text-text-secondary">Limited time offers with special discounts</p>
    </div>
    
    <div class="grid grid-cols-1 gap-4">
        @foreach ($flashSales as $sale)
        <div class="bg-elevated rounded-lg overflow-hidden">
            <div class="relative h-48">
                <!-- Sale badge -->
                <span class="absolute top-2 left-2 bg-cyan-600 text-white text-xs px-2 py-1 rounded">Flash Sale</span>
                
                <!-- Countdown -->
                <div class="cdigit">
                    <span class="digit" data-digit="1"></span>
                    <span class="digit" data-digit="0"></span>
                    <span class="digit" data-digit=""></span>
                    <span class="digit" data-digit=""></span>
                    <span class="text-colon">:</span>
                    <span class="digit" data-digit="1"></span>
                    <span class="digit" data-digit="0"></span>
                    <span class="digit" data-digit=""></span>
                    <span class="digit" data-digit=""></span>
                    <span class="text-colon">:</span>
                    <span class="digit" data-digit="1"></span>
                    <span class="digit" data-digit="0"></span>
                    <span class="digit" data-digit=""></span>
                    <span class="digit" data-digit=""></span>
                </div>
            </div>
            <div class="p-4">
                <h3 class="text-bold text-white">{{ $sale['name'] }}</h3>
                <p class="text-text-secondary">{{ $sale['product']->name ?? 'Product' }}</p>
                <p class="text-2xl font-bold text-cyan">-${{ $sale['discount_value'] }}%</p>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="mt-8">
        <a href="{{ route('flash-sale') }}" class="btn-glow">View All Flash Sales</a>
    </div>
</div>
@endsection