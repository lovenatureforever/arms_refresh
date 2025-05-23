<div class="mb-4 grid grid-cols-1 gap-6">
    <div class="">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h4 class="card-title">Company List</h4>
                    <div class="flex items-center gap-2">
                        <a class="btn bg-primary text-white" type="button" href="{{ route('companies.create') }}">Create</a>
                    </div>
                </div>
            </div>

            <div class="p-6">
                {{-- @livewire('shared.datatable.company-list-datatable') --}}
                @livewire('tenant.components.company-list')
            </div>
        </div>

    </div>
</div>

@push('script')
@vite('resources/js/pages/dashboard.js')
@endpush
