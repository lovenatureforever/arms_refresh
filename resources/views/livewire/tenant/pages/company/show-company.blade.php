<div>
    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        <div class="lg:col-span-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Company Overview</h6>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-4 gap-6">
                        <div>
                            <p class="mb-3 text-sm font-medium uppercase underline">
                                Company Name
                            </p>
                            <h5 class="text-base font-medium text-gray-700 dark:text-gray-300">{{ $name }}</h5>
                        </div>

                        <div>
                            <p class="mb-3 text-sm font-medium uppercase underline">
                                Registration No
                            </p>
                            <h5 class="text-base font-medium text-gray-700 dark:text-gray-300">New: {{ $registrationNo }}</h5>
                            <h5 class="text-base font-medium text-gray-700 dark:text-gray-300">Old: {{ $registrationNoOld }}</h5>
                        </div>

                        <div>
                            <p class="mb-3 text-sm font-medium uppercase underline">
                                Current Year
                            </p>
                            <h5 class="text-base font-medium text-gray-700 dark:text-gray-300">{{ $currentYear }}</h5>
                        </div>

                        <div>
                            <p class="mb-3 text-sm font-medium uppercase underline">
                                Status
                            </p>
                            <div class="inline-flex items-center gap-1.5 py-1 px-3 rounded text-xs font-medium {{ $is_active ? 'bg-success/90' : 'bg-dark/80' }} text-white">{{ $is_active ? 'Active' : 'Inactive' }}</div>
                            {{-- <h5 class="text-base font-medium text-gray-700 dark:text-gray-300">
                                {{ $is_active }}
                            </h5> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3 mt-4">
        <div class="card">
            <div class="flex flex-col">
                <div class="px-6 py-3">
                    <h5 class="my-2">
                        <a class="text-slate-900 dark:text-slate-200" href="#">Corporate Info</a>
                    </h5>
                    <p class="mb-9 text-sm text-gray-500">Explain Here</p>
                </div>

                <div class="border-t border-gray-300 p-5 dark:border-gray-700">
                    <div class="grid gap-4 lg:grid-cols-2">
                        <div class="flex items-center gap-2">
                            <a class="btn w-full bg-primary text-white" href="{{ route('corporate.yearend', ['id' => $id]) }}">View</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex flex-col">
                <div class="px-6 py-3">
                    <h5 class="my-2">
                        <a class="text-slate-900 dark:text-slate-200" href="#">Data Upload & Report</a>
                    </h5>
                    <p class="mb-9 text-sm text-gray-500">Explain Here</p>
                </div>

                <div class="border-t border-gray-300 p-5 dark:border-gray-700">
                    <div class="grid gap-4 lg:grid-cols-2">
                        <div class="flex items-center gap-2">
                            <a class="btn w-full bg-primary text-white" href="{{ route('datamigration.index', ['id' => $id]) }}">View</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="flex flex-col">
                <div class="px-6 py-3">
                    <h5 class="my-2">
                        <a class="text-slate-900 dark:text-slate-200" href="#">Report Configuration</a>
                    </h5>
                    <p class="mb-9 text-sm text-gray-500">Explain Here</p>
                </div>

                <div class="border-t border-gray-300 p-5 dark:border-gray-700">
                    <div class="grid gap-4 lg:grid-cols-2">
                        <div class="flex items-center gap-2">
                            <a class="btn w-full bg-primary text-white" href="{{ route('reportconfig.director', ['id' => $id]) }}">View</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex flex-col">
                <div class="px-6 py-3">
                    <h5 class="my-2">
                        <a class="text-slate-900 dark:text-slate-200" href="#">Cosec Request Order</a>
                    </h5>
                    <p class="mb-9 text-sm text-gray-500">Explain Here</p>
                </div>

                <div class="border-t border-gray-300 p-5 dark:border-gray-700">
                    <div class="grid gap-4 lg:grid-cols-2">
                        <div class="flex items-center gap-2">
                            <button class="btn w-full bg-primary text-white" type="button" wire:click="cosec">Request</button>
                        </div>

                        <div class="flex items-center gap-2">
                            <button class="btn w-full bg-primary text-white" type="button" wire:click="cosecOrder">View</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="card">
        <div class="flex flex-col">
            <div class="px-6 py-3">
                <h5 class="my-2">
                    <a class="text-slate-900 dark:text-slate-200" href="#">Financial Report</a>
                </h5>
                <p class="mb-9 text-sm text-gray-500">Explain Here</p>
            </div>

            <div class="border-t border-gray-300 p-5 dark:border-gray-700">
                <div class="grid gap-4 lg:grid-cols-2">
                    <div class="flex items-center gap-2">
                        <button class="btn w-full bg-primary text-white" type="button" wire:click="financialReport">View</button>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    </div>
</div>
