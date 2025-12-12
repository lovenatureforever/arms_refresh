<div class="grid grid-cols-1 gap-6">
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">Pending Signatures</h4>
                <div class="flex gap-2">
                    <button wire:click="setFilter('pending')" class="btn {{ $filter === 'pending' ? 'bg-primary text-white' : 'border-primary text-primary' }}">
                        Pending
                    </button>
                    <button wire:click="setFilter('signed')" class="btn {{ $filter === 'signed' ? 'bg-success text-white' : 'border-success text-success' }}">
                        Signed
                    </button>
                    <button wire:click="setFilter('all')" class="btn {{ $filter === 'all' ? 'bg-secondary text-white' : 'border-secondary text-secondary' }}">
                        All
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($director)
                @if($signatures->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Document</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requested</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($signatures as $sig)
                                    <tr>
                                        <td class="px-6 py-4 text-sm">
                                            {{ $sig->order->template->name ?? $sig->order->form_name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            {{ $sig->order->company_name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            {{ $sig->created_at->format('Y-m-d H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            @if($sig->signature_status === 'signed')
                                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Signed</span>
                                                @if($sig->signed_at)
                                                    <br><small class="text-gray-500">{{ $sig->signed_at->format('Y-m-d H:i') }}</small>
                                                @endif
                                            @elseif($sig->signature_status === 'rejected')
                                                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Rejected</span>
                                            @else
                                                <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm">
                                            @if($sig->signature_status === 'pending')
                                                <a href="{{ route('director.cosec.sign', $sig->cosec_order_id) }}" class="btn bg-primary text-white text-sm">
                                                    Sign Now
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>No {{ $filter === 'all' ? '' : $filter }} signature requests found.</p>
                    </div>
                @endif
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-yellow-800">Your director profile is not linked. Please contact your company secretary.</p>
                </div>
            @endif
        </div>
    </div>
</div>
