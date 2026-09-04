@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4">
    <div class="bg-card rounded-xl p-6 mb-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
            <!-- Clicks -->
            <div>
                <h3 class="text-sm text-text-secondary uppercase">Clicks</h3>
                <p class="text-3xl font-bold text-cyan">{{ $stats['clicks'] }}</p>
            </div>
            <!-- Sales -->
            <div>
                <h3 class="text-sm text-text-secondary uppercase">Sales</h3>
                <p class="text-3xl font-bold text-green-400">{{ $stats['sales'] }}</p>
            </div>
            <!-- Conversion Rate -->
            <div>
                <h3 class="text-sm text-text-secondary uppercase">Conversion Rate</h3>
                <p class="text-3xl font-bold">{{ $stats['conversion_rate'] }}%</p>
            </div>
            <!-- Earnings -->
            <div>
                <h3 class="text-sm text-text-secondary uppercase">Earnings</h3>
                <p class="text-3xl font-bold text-yellow-400">${{ number_format($stats['earnings'], 2) }}</p>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Affiliate Links -->
        <div class="bg-card rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">My Affiliate Links</h2>
            @if(count($links) > 0)
            <div class="space-y-3">
                @foreach ($links as $link)
                <div class="flex items-center justify-between border rounded p-3">
                    <div class="flex items-center space-x-2">
                        @if($link->product)
                            <span class="text-text-secondary">{{ $link->product->name }}</span>
                        @else
                            <span class="text-text-secondary">All Products</span>
                        @endif
                    </div>
                    <span class="text-cyan text-sm">{{ $link->code }}</span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-text-secondary">No links yet. Create one below.</p>
            @endif
        </div>
        
        <!-- Conversion Stats -->
        <div class="bg-card rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Conversion Stats</h2>
            <a href="{{ route('affiliate.stats') }}" class="text-cyan hover:underline">View detailed stats</a>
        </div>
    </div>
</div>
@endsection