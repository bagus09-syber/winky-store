@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4">
    <div class="bg-card rounded-xl p-6 mb-6">
        <h2 class="text-xl font-bold text-white">Create Affiliate Link</h2>
        <p class="text-text-secondary">Select a product to create a dedicated affiliate link.</p>
        
        <form class="grid grid-cols-1 gap-4">
            <div>
                <label class="block text-white mb-2">Product</label>
                <select class="w-full bg-elevated rounded px-4 py-2 text-white focus:outline-none focus:border-cyan-500">
                    <option value="">All Products</option>
                    @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-glow w-full">Create Link</button>
        </form>
    </div>
    
    <div class="grid grid-cols-1 gap-6">
        @if(count($links) > 0)
        <!-- Existing Links -->
        <div class="bg-card rounded-xl p-6 h-[400px] overflow-y-auto">
            <h2 class="text-xl font-bold text-white mb-4">Existing Links</h2>
            <div class="space-y-3">
                @foreach ($links as $link)
                <div class="flex items-center justify-between border rounded p-2">
                    <span class="text-white">{{ $link->product?->name ?? 'All Products' }}</span>
                    <span class="text-cyan text-sm">{{ $link->code }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <p class="text-text-secondary">No links yet.</p>
        @endif
    </div>
</div>
@endsection