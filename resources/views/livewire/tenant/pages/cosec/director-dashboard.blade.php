<div>
    {{-- Header --}}
    <div class="mb-6">
        <h4 class="text-xl font-semibold text-gray-800">Director Dashboard</h4>
        <p class="text-gray-500 mt-1">Manage your COSEC orders and company documents.</p>
    </div>

    @if($director)
        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            {{-- Credit Balance Card --}}
            <div class="card">
                <div class="card-body p-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Credit Balance</p>
                            <p class="text-lg font-bold text-gray-800">{{ number_format(auth()->user()->credit ?? 0, 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Signature Status Card --}}
            <div class="card">
                <div class="card-body p-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 {{ $hasSignature ? 'bg-green-100' : 'bg-yellow-100' }} rounded-lg">
                            @if($hasSignature)
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Signature</p>
                            <p class="text-lg font-bold {{ $hasSignature ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ $hasSignature ? 'Uploaded' : 'Not Uploaded' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Designation Card --}}
            <div class="card">
                <div class="card-body p-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Designation</p>
                            <p class="text-lg font-bold text-gray-800">{{ $director->designation ?? 'Director' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Company Info Cards --}}
        @if($company)
        <div class="mb-6">
            <h5 class="text-lg font-semibold text-gray-800 mb-4">My Company</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="card">
                    <div class="card-body p-4">
                        <p class="text-xs text-gray-500 uppercase">Company Name</p>
                        <p class="font-semibold text-gray-800">{{ $company->name }}</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-4">
                        <p class="text-xs text-gray-500 uppercase">Registration No</p>
                        <p class="font-semibold text-gray-800">{{ $company->registration_no }}</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-4">
                        <p class="text-xs text-gray-500 uppercase">Current Year</p>
                        <p class="font-semibold text-gray-800">{{ $company->current_year }}</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-4">
                        <p class="text-xs text-gray-500 uppercase">Status</p>
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded {{ $company->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $company->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Main Content --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Signature Preview Card --}}
            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <h5 class="card-title mb-0">Your Signature</h5>
                    @if($hasSignature && $currentSignature)
                        <a href="{{ route('profile') }}" class="btn bg-gray-100 text-gray-700 text-sm">
                            Change
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($hasSignature && $currentSignature)
                        <div class="flex items-center justify-center">
                            <div class="border rounded p-4 bg-gray-50 inline-block">
                                <img src="/tenancy/assets/{{ $currentSignature->signature_path }}"
                                     alt="Your Signature"
                                     class="h-16 max-w-[200px] object-contain">
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                            <p class="text-sm text-gray-500 mb-3">No signature uploaded</p>
                            <a href="{{ route('profile') }}" class="btn bg-primary text-white text-sm">
                                Upload Signature
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Quick Actions Card --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <a href="{{ route('director.cosec.place-order') }}" class="flex items-center gap-3 p-3 rounded-lg border hover:bg-gray-50 transition">
                            <div class="p-2 bg-primary/10 rounded">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Place New Order</p>
                                <p class="text-xs text-gray-500">Request a new COSEC document</p>
                            </div>
                        </a>
                        <a href="{{ route('director.cosec.my-orders') }}" class="flex items-center gap-3 p-3 rounded-lg border hover:bg-gray-50 transition">
                            <div class="p-2 bg-blue-100 rounded">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">My Orders</p>
                                <p class="text-xs text-gray-500">View order history & download PDFs</p>
                            </div>
                        </a>
                        <a href="{{ route('profile.credit-history') }}" class="flex items-center gap-3 p-3 rounded-lg border hover:bg-gray-50 transition">
                            <div class="p-2 bg-green-100 rounded">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Credit History</p>
                                <p class="text-xs text-gray-500">View your credit transactions</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if(!$hasSignature)
        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>
                    <p class="font-medium text-yellow-800">Signature Required</p>
                    <p class="text-sm text-yellow-700">Please upload your signature from your profile to enable document signing.</p>
                </div>
            </div>
        </div>
        @endif
    @else
        {{-- Not Linked Warning --}}
        <div class="card">
            <div class="card-body p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-yellow-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <h5 class="text-lg font-semibold text-gray-800 mb-2">Account Not Linked</h5>
                <p class="text-gray-500">Your account is not linked to a company director profile.</p>
                <p class="text-sm text-gray-400 mt-2">Please contact your administrator to link your account.</p>
            </div>
        </div>
    @endif
</div>
