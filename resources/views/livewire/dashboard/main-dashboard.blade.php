<div>

    <div class="mb-6 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
        <div class="col-span-1">
            <div class="card">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="me-3 flex-shrink-0">
                            <div class="flex h-12 w-12 items-center justify-center rounded bg-primary/25 text-primary">
                                <i class="mgc_department_line text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-grow">
                            <h5 class="mb-1">Active Tenant</h5>
                            <p>{{ $totalTenant }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Grid End -->

    <div class="grid gap-6 md:grid-cols-2 2xl:grid-cols-4">
        <div class="col-span-1">
            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <h4 class="card-title">Latest 5 Tenants Registered</h4>
                </div>

                <div class="py-6">
                    <div class="px-6" data-simplebar style="max-height: 304px;">
                        <div class="space-y-6">

                            @foreach ($newFiveTenant as $tenant)
                                <div class="flex items-center">
                                    <div class="w-full overflow-hidden">
                                        <h5 class="font-semibold"><a class="text-gray-600 dark:text-gray-400" href="{{ route('show.tenant', $tenant->id) }}">{{ $tenant->firmName }}</a></h5>
                                        <div class="flex items-center gap-2">
                                            <div>{{ $tenant->firmNo }}</div>
                                            <i class="mgc_round_fill text-[5px]"></i>
                                            <div>{{ Carbon\Carbon::parse($tenant->created_at)->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Grid End -->
</div>

@push('script')
    @vite('resources/js/pages/dashboard.js')
@endpush
