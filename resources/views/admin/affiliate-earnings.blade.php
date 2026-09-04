@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto p-4">
    <div class="bg-card rounded-xl p-6 mb-6">
        <h2 class="text-xl font-bold text-white">{{ $affiliate->user->name }}'s Earnings</h2>
        <p class="text-text-secondary">Commission and earnings summary</p>
    </div>
    
    <div class="grid grid-cols-1 gap-4">
        <div class="bg-card rounded-xl p-6">
            <h3 class="text-white">Commission Summary</h3>
            <p>Commission Rate: {{ $affiliate->commission_rate * 100 }}%</p>
            <p>Total Sales: {{ $affiliate->total_sales }}</p>
            <p>Total Earnings: ${{ number_format($affiliate->total_earnings, 2) }}</p>
        </div>
        
        <div class="bg-card rounded-xl p-6">
            <h3 class="text-white">Recent Transactions</h3>
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="text-left text-white">Amount</th>
                        <th class="text-left text-white">Type</th>
                        <th class="text-left text-white">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentTransactions as $tx)
                    <tr class="border-b border-gray-700 hover:bg-gray-900">
                        <td class="text-yellow-400">{{ number_format($tx->amount, 2) }}</td>
                        <td class="text-cyan">{{ ucfirst($tx->type) }}</td>
                        <td class="text-xs text-text-secondary">{{ $tx->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection