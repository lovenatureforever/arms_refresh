<div class="grid grid-cols-1 gap-6">
    <!-- Filters & Stats -->
    <div class="card">
        <div class="card-header flex justify-between items-center">
            <h4 class="card-title">E-Confirmation Requests</h4>
            <a href="{{ route('econfirmation.create') }}" class="btn bg-primary text-white">
                <i class="mgc_add_line mr-1"></i> New Request
            </a>
        </div>
        <div class="p-6">
            <!-- Filters Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-10 gap-4 mb-6">
                {{-- <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company</label>
                    <select wire:model.live="selectedCompany" class="form-select">
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div> --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Year End</label>
                    <select wire:model.live="selectedYearEnd" class="form-select">
                        <option value="">All Year Ends</option>
                        @foreach($yearEndOptions as $yearEnd)
                            <option value="{{ $yearEnd }}">{{ \Carbon\Carbon::parse($yearEnd)->format('d M') }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Year</label>
                    <select wire:model.live="yearFilter" class="form-select">
                        <option value="all">All Years</option>
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select wire:model.live="statusFilter" class="form-select">
                        <option value="all">All Statuses</option>
                        <option value="draft">Draft</option>
                        <option value="pending_signatures">Pending Signatures</option>
                        <option value="completed">Completed</option>
                        <option value="expired">Expired</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bank</label>
                    <select wire:model.live="selectedBank" class="form-select">
                        <option value="">All Banks</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Branch</label>
                    <select wire:model.live="selectedBranch" class="form-select" @if(!$selectedBank) disabled @endif>
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->branch_name ?: 'Main Branch' }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Account No</label>
                    <input type="text" wire:model.live.debounce.300ms="accountNo" class="form-input" placeholder="Search account...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Charge Code</label>
                    <input type="text" wire:model.live.debounce.300ms="chargeCode" class="form-input" placeholder="Search charge code...">
                </div> --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-input" placeholder="Search company...">
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total</div>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-gray-600 dark:text-gray-400">{{ $stats['draft'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Draft</div>
                </div>
                <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['pending'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Pending</div>
                </div>
                <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['completed'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Completed</div>
                </div>
                <div class="text-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['expired'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Expired</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="card">
        <div>
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Company</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Year End</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Banks</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Progress</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Created</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium uppercase text-gray-500" scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($requests as $request)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ $request->company->name }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                            {{ $request->year_end_date->format('d M Y') }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                            {{ $request->total_banks }} {{ Str::plural('bank', $request->total_banks) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                            <div class="flex items-center gap-2">
                                                <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2 min-w-[80px]">
                                                    <div class="bg-primary h-2 rounded-full" style="width: {{ $request->getProgressPercentage() }}%"></div>
                                                </div>
                                                <span class="text-xs whitespace-nowrap">
                                                    {{ $request->total_signatures_collected }}/{{ $request->total_signatures_required }}
                                                    ({{ $request->getProgressPercentage() }}%)
                                                </span>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                                            @php
                                                $statusColors = [
                                                    'secondary' => '#6c757d',
                                                    'warning' => '#f59e0b',
                                                    'success' => '#10b981',
                                                    'danger' => '#ef4444',
                                                    'primary' => '#3b82f6',
                                                ];
                                            @endphp
                                            <span class="px-2 py-1 text-xs rounded-full text-white" style="background-color: {{ $statusColors[$request->status_badge_color] ?? '#6c757d' }}">
                                                {{ $request->status_label }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                            {{ $request->created_at->format('d M Y') }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm">
                                            <div class="flex justify-center gap-2">
                                                <button wire:click="viewRequest({{ $request->id }})" class="btn btn-sm bg-info text-white px-3 py-2 rounded" title="View Details">
                                                    <i class="mgc_eye_line text-base"></i>
                                                </button>
                                                @if($request->status === 'completed')
                                                    <button wire:click="downloadZip({{ $request->id }})" class="btn btn-sm bg-success text-white px-3 py-2 rounded" title="Download ZIP">
                                                        <i class="mgc_download_line text-base"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                            <div class="flex flex-col items-center">
                                                <i class="mgc_file_line text-4xl mb-2 text-gray-400"></i>
                                                <p>No requests found.</p>
                                                <a href="{{ route('econfirmation.create') }}" class="mt-2 text-primary hover:underline">
                                                    Create your first request
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if($requests->hasPages())
        <div class="p-4">
            {{ $requests->links() }}
        </div>
        @endif
    </div>
</div>
