@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto p-4">
    <div class="bg-card rounded-xl p-6 mb-6">
        <h2 class="text-xl font-bold text-white">Affiliate Management</h2>
        <p class="text-text-secondary">Manage your affiliate program and track earnings</p>
    </div>
    
    <div class="grid grid-cols-1 gap-4">
        <!-- Affiliate Stats Summary -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-card rounded-xl p-6">
                <p class="text-sm text-text-secondary uppercase">Total Affiliates</p>
                <p class="text-3xl font-bold text-cyan">{{ count($affiliates) }}</p>
            </div>
            <div class="bg-card rounded-xl p-6">
                <p class="text-sm text-text-secondary uppercase">Active</p>
                <p class="text-3xl font-bold text-green-400">{{ $activeCount }}</p>
            </div>
        </div>
        
        <div class="bg-card rounded-xl p-6">
            <a href="{{ route('admin.affiliates.create') }}" class="btn-glow w-full mb-4">Add New Affiliate</a>
            <p class="text-text-secondary mt-4">Create new affiliates or approve pending ones.</p>
        </div>
    </div>
    
    <!-- Affiliates Table -->
    <div class="bg-card rounded-xl overflow-hidden">
        <div class="p-6 border-b border-gray-700">
            <h2 class="text-font-bold text-white">Affiliate List</h2>
        </div>
        <div class="p-6">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="text-left text-white">User</th>
                        <th class="text-left text-white">Status</th>
                        <th class="text-left text-white">Commission Rate</th>
                        <th class="text-left text-white">Total Earnings</th>
                        <th class="text-left text-white">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($affiliates as $affiliate)
                    <tr class="border-b border-gray-700 hover:bg-gray-900">
                        <td class="text-white">{{ $affiliate->user->name }}</td>
                        <td>
                            <span class="px-2 py-1 rounded text-xs {{ $affiliate->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $affiliate->status }}</span>
                        </td>
                        <td class="text-cyan">{{ $affiliate->commission_rate * 100 }}%</td>
                        <td class="text-yellow-400">{{ number_format($affiliate->total_earnings, 2) }}</td>
                        <td>
                            <select class="px-2 py-1 rounded text-sm" onchange="window.location='{{ route('admin.affiliates.rate', $affiliate->id) }}?rate=' + this.value">
                                <option value="0.10" {{ $affiliate->commission_rate == 0.10 ? 'selected' : '' }}>10%</option>
                                <option value="0.15" {{ $affiliate->commission_rate == 0.15 ? 'selected' : '' }}>15%</option>
                                <option value="0.20" {{ $affiliate->commission_rate == 0.20 ? 'selected' : '' }}>20%</option>
                            </select>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection