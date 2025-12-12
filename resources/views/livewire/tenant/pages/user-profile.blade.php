<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Profile Information Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Profile Information</h3>
            </div>
            <div class="card-body p-6">
                <form wire:submit="updateProfile">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
                            <input type="text" wire:model="name" class="form-input w-full" placeholder="Your name">
                            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                            <input type="email" wire:model="email" class="form-input w-full" placeholder="Your email">
                            @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">User Type</label>
                            <input type="text" value="{{ ucfirst($user->user_type) }}" class="form-input w-full bg-gray-50 dark:bg-gray-700 cursor-not-allowed" disabled>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Member Since</label>
                            <input type="text" value="{{ $user->created_at->format('d M Y') }}" class="form-input w-full bg-gray-50 dark:bg-gray-700 cursor-not-allowed" disabled>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t dark:border-gray-700">
                        <button type="submit" class="btn bg-primary text-white">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password Card -->
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h3 class="card-title">Change Password</h3>
                <button wire:click="togglePasswordForm" class="btn btn-sm {{ $showPasswordForm ? 'bg-gray-500' : 'bg-primary' }} text-white">
                    {{ $showPasswordForm ? 'Cancel' : 'Change Password' }}
                </button>
            </div>
            @if($showPasswordForm)
            <div class="card-body p-6">
                <form wire:submit="updatePassword">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Password</label>
                            <input type="password" wire:model="currentPassword" class="form-input w-full" placeholder="Enter current password">
                            @error('currentPassword') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Password</label>
                            <input type="password" wire:model="newPassword" class="form-input w-full" placeholder="Enter new password">
                            @error('newPassword') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm Password</label>
                            <input type="password" wire:model="newPasswordConfirmation" class="form-input w-full" placeholder="Confirm new password">
                            @error('newPasswordConfirmation') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t dark:border-gray-700">
                        <button type="submit" class="btn bg-primary text-white">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>

        <!-- Signature Card (Directors Only) -->
        @if($user->user_type === 'director')
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">My Signature</h3>
            </div>
            <div class="card-body p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Current Signature -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Current Signature</label>
                        @if($currentSignature)
                            <div class="border dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-800 text-center">
                                <img src="/tenancy/assets/{{ $currentSignature->signature_path }}" alt="Your Signature" class="max-h-20 mx-auto">
                            </div>
                            <button wire:click="deleteSignature" wire:confirm="Are you sure you want to delete your signature?" class="btn bg-red-500 text-white text-sm mt-4 w-full">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Signature
                            </button>
                        @else
                            <div class="border dark:border-gray-700 rounded-lg p-6 bg-gray-50 dark:bg-gray-800 text-center">
                                <svg class="w-10 h-10 mx-auto text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                                <p class="text-sm text-gray-500 dark:text-gray-400">No signature uploaded</p>
                            </div>
                        @endif
                    </div>

                    <!-- Upload/Update Signature -->
                    <div>
                        <form wire:submit="uploadSignature">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ $currentSignature ? 'Update Signature' : 'Upload Signature' }}</label>
                                <input type="file" wire:model="signatureFile" accept="image/png,image/jpeg,image/jpg" class="form-input w-full text-sm">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">PNG or JPG, max 2MB</p>

                                <!-- File Upload Loading Indicator -->
                                <div wire:loading wire:target="signatureFile" class="mt-2">
                                    <div class="flex items-center text-primary text-sm">
                                        <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Uploading file...
                                    </div>
                                </div>

                                @error('signatureFile') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            @if($signatureFile)
                            <div class="mt-4" wire:loading.remove wire:target="signatureFile">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preview</label>
                                <div class="border dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-800 text-center">
                                    <img src="{{ $signatureFile->temporaryUrl() }}" alt="Preview" class="max-h-16 mx-auto">
                                </div>
                            </div>
                            @endif

                            <button type="submit" class="btn bg-primary text-white w-full mt-4" wire:loading.attr="disabled" wire:target="uploadSignature" @if(!$signatureFile) disabled @endif>
                                <!-- Loading State -->
                                <span wire:loading wire:target="uploadSignature" class="flex items-center justify-center">
                                    <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Saving...
                                </span>

                                <!-- Normal State -->
                                <span wire:loading.remove wire:target="uploadSignature">
                                    @if($currentSignature)
                                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Update Signature
                                    @else
                                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                        </svg>
                                        Upload Signature
                                    @endif
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Right Column - Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Credit Balance Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Credit Balance</h3>
            </div>
            <div class="card-body p-6 text-center">
                <div class="py-2">
                    <div class="text-4xl font-bold text-primary mb-1">RM {{ number_format($user->credit ?? 0, 0) }}</div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Available Credits</p>
                </div>
                <a href="{{ route('profile.credit-history') }}" class="btn bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 w-full text-sm mt-4">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    View Credit History
                </a>
            </div>
        </div>

        <!-- Order Stats Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Order Statistics</h3>
            </div>
            <div class="card-body p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
                        <span class="text-gray-600 dark:text-gray-400">Total Orders</span>
                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $totalOrders }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
                        <span class="text-gray-600 dark:text-gray-400">Pending</span>
                        <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300 rounded-full text-xs font-medium">{{ $pendingOrders }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">Approved</span>
                        <span class="px-2.5 py-1 bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 rounded-full text-xs font-medium">{{ $approvedOrders }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Transactions</h3>
            </div>
            <div class="card-body p-0">
                @forelse($recentTransactions as $transaction)
                    <div class="px-6 py-3 border-b dark:border-gray-700 last:border-b-0 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <div class="flex justify-between items-center">
                            <div class="flex-1 min-w-0 mr-3">
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">{{ $transaction->description }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $transaction->created_at->format('d M Y') }}</p>
                            </div>
                            <span class="text-sm font-semibold whitespace-nowrap {{ $transaction->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->type === 'credit' ? '+' : '-' }} RM {{ number_format($transaction->amount, 0) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        <svg class="w-10 h-10 mx-auto text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-sm text-gray-500 dark:text-gray-400">No transactions yet</p>
                    </div>
                @endforelse
            </div>
            @if($recentTransactions->count() > 0)
            <div class="card-footer p-4 text-center border-t dark:border-gray-700">
                <a href="{{ route('profile.credit-history') }}" class="text-primary text-sm font-medium hover:underline">View All Transactions</a>
            </div>
            @endif
        </div>
    </div>
</div>
