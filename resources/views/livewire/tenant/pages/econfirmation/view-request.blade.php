@php
    $statusColors = [
        'secondary' => '#6c757d',
        'warning' => '#f59e0b',
        'success' => '#10b981',
        'danger' => '#ef4444',
        'primary' => '#3b82f6',
    ];
@endphp
<div class="grid grid-cols-1 gap-6">
    <!-- Header -->
    <div class="card">
        <div class="card-header flex justify-between items-center">
            <div>
                <h4 class="card-title">E-Confirmation Request</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $request->company->name }}</p>
            </div>
            <div class="flex items-center gap-2">
                @if($request->status === 'draft')
                    <button wire:click="sendToDirectors" wire:confirm="Are you sure you want to send this request to directors?" class="btn bg-primary text-white">
                        <i class="mgc_mail_send_line mr-1"></i> Send to Directors
                    </button>
                @endif
                <a href="{{ route('econfirmation.index') }}" class="btn bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    <i class="mgc_arrow_left_line mr-1"></i> Back
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Request Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Request Details -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Request Details</h5>
                </div>
                <div class="p-6">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Status:</span>
                            <span class="px-2 py-1 text-xs rounded-full text-white" style="background-color: {{ $statusColors[$request->status_badge_color] ?? '#6c757d' }}">
                                {{ $request->status_label }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Year End:</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ $request->year_end_date->format('d M Y') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Created:</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ $request->created_at->format('d M Y H:i') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Created By:</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ $request->creator->name ?? '-' }}
                            </span>
                        </div>
                        @if($request->sent_at)
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Sent At:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ $request->sent_at->format('d M Y H:i') }}
                                </span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Expires:</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ $request->token_expires_at->format('d M Y H:i') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Validity:</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ $request->validity_days }} days
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Progress</h5>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-500 dark:text-gray-400">Banks Completed</span>
                                <span class="font-medium">{{ $request->banks_completed }}/{{ $request->total_banks }}</span>
                            </div>
                            <div class="bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                @php
                                    $bankProgress = $request->total_banks > 0 ? ($request->banks_completed / $request->total_banks) * 100 : 0;
                                @endphp
                                <div class="bg-primary h-2 rounded-full" style="width: {{ $bankProgress }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-500 dark:text-gray-400">Signatures Collected</span>
                                <span class="font-medium">{{ $request->total_signatures_collected }}/{{ $request->total_signatures_required }}</span>
                            </div>
                            <div class="bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-success h-2 rounded-full" style="width: {{ $request->getProgressPercentage() }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Bank PDFs -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Bank Confirmations</h5>
                </div>
                <div class="p-6">
                    @if($request->bankPdfs->isEmpty())
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <i class="mgc_file_line text-4xl mb-2"></i>
                            <p>No bank confirmations added.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($request->bankPdfs as $bankPdf)
                                <div class="border dark:border-gray-700 rounded-lg overflow-hidden">
                                    <!-- Bank Header -->
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800">
                                        <div>
                                            <h6 class="font-medium text-gray-900 dark:text-white">
                                                {{ $bankPdf->bankBranch->bank->bank_name }}
                                            </h6>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $bankPdf->bankBranch->branch_name ?: 'Main Branch' }}
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-1 text-xs rounded-full text-white" style="background-color: {{ $statusColors[$bankPdf->status_badge_color] ?? '#6c757d' }}">
                                                {{ ucfirst($bankPdf->status) }}
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                {{ $bankPdf->signatures_collected }}/{{ $bankPdf->signatures_required }} signatures
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Signatures -->
                                    @if($bankPdf->signatures->isNotEmpty())
                                        <div class="border-t dark:border-gray-700">
                                            <table class="min-w-full">
                                                <thead class="bg-gray-100 dark:bg-gray-700">
                                                    <tr>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Director</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Signed At</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                                    @foreach($bankPdf->signatures as $signature)
                                                        <tr>
                                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                                                {{ $signature->director_name }}
                                                            </td>
                                                            <td class="px-4 py-3">
                                                                <span class="px-2 py-1 text-xs rounded-full text-white" style="background-color: {{ $statusColors[$signature->status_badge_color] ?? '#6c757d' }}">
                                                                    {{ ucfirst($signature->status) }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                                                {{ $signature->signed_at?->format('d M Y H:i') ?? '-' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="p-4 text-sm text-gray-500 dark:text-gray-400 text-center border-t dark:border-gray-700">
                                            No signatures assigned yet.
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
