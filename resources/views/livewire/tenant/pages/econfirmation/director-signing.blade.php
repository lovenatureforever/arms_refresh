<div class="grid grid-cols-1 gap-6">
    <div class="max-w-4xl mx-auto w-full">

        @if($errorMessage)
            <!-- Error State -->
            <div class="card p-8 text-center">
                <div class="text-red-500 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Unable to Process</h2>
                <p class="text-gray-600 dark:text-gray-400">{{ $errorMessage }}</p>
            </div>
        @else
            <!-- Header -->
            <div class="card p-6 mb-6">
                <div class="text-center">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Bank Confirmation Signing</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">E-Confirmation Request</p>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="text-gray-500 dark:text-gray-400">Company</div>
                        <div class="font-semibold text-gray-900 dark:text-white">{{ $request->company->name }}</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="text-gray-500 dark:text-gray-400">Year End Date</div>
                        <div class="font-semibold text-gray-900 dark:text-white">{{ $request->year_end_date->format('d F Y') }}</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="text-gray-500 dark:text-gray-400">Expires In</div>
                        <div class="font-semibold text-gray-900 dark:text-white">
                            @if($request->daysUntilExpiry() > 0)
                                {{ $request->daysUntilExpiry() }} days
                            @else
                                <span class="text-red-500">Expired</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($director)
                <div class="mt-4 text-center text-sm text-gray-600 dark:text-gray-400">
                    Signing as: <span class="font-semibold">{{ $director->name }}</span>
                </div>
                @endif
            </div>

            <!-- Flash Messages -->
            @if(session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Pending Signatures -->
            @if(count($pendingSignatures) > 0)
                <div class="card mb-6">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Pending Signatures ({{ count($pendingSignatures) }})
                        </h2>
                        @if(count($pendingSignatures) > 1)
                            <button wire:click="signAll" wire:loading.attr="disabled"
                                    class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition">
                                <span wire:loading.remove wire:target="signAll">Sign All</span>
                                <span wire:loading wire:target="signAll">Signing...</span>
                            </button>
                        @endif
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($pendingSignatures as $pending)
                            <div class="px-6 py-4 flex items-center justify-between">
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ $pending['bank_name'] }}
                                    </div>
                                    @if($pending['branch_name'])
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $pending['branch_name'] }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                    <button wire:click="sign({{ $pending['signature_id'] }})"
                                            wire:loading.attr="disabled"
                                            wire:target="sign({{ $pending['signature_id'] }})"
                                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm">
                                        <span wire:loading.remove wire:target="sign({{ $pending['signature_id'] }})">Sign</span>
                                        <span wire:loading wire:target="sign({{ $pending['signature_id'] }})">Signing...</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Completed Signatures -->
            @if(count($completedSignatures) > 0)
                <div class="card">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Completed Signatures ({{ count($completedSignatures) }})
                        </h2>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($completedSignatures as $completed)
                            <div class="px-6 py-4 flex items-center justify-between">
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ $completed['bank_name'] }}
                                    </div>
                                    @if($completed['branch_name'])
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $completed['branch_name'] }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Signed
                                    </span>
                                    @if($completed['signed_at'])
                                        <span class="text-xs text-gray-500">
                                            {{ $completed['signed_at']->format('d/m/Y H:i') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- All Complete Message -->
            @if(count($pendingSignatures) === 0 && count($completedSignatures) > 0)
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6 mt-6 text-center">
                    <div class="text-green-500 mb-3">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-green-800 dark:text-green-200">All Confirmations Signed!</h3>
                    <p class="text-green-600 dark:text-green-400 mt-1">Thank you for completing your signatures.</p>
                </div>
            @endif
        @endif

    </div>
</div>
