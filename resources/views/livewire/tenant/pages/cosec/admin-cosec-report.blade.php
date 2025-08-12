<div class="mb-4">
    <div class="card">
        <div class="text-black w-1/2 mx-auto mb-4">
            <div class="flex items-center justify-between border-b mb-6">
                <h2 class="text-center text-xl py-6">
                    {{ $order->form_name }}
                </h2>

                <div>
                    @if ($order->status == 0)
                    <button wire:click="approve" class="btn bg-success text-white mr-4">
                        Approve
                    </button>
                    <button wire:click="deny" class="btn bg-danger text-white">
                        Deny
                    </button>
                    @endif
                </div>
            </div>

            @livewire('tenant.pages.cosec.admin-cosec-report-content', ['order' => $order])
        </div>
    </div>
</div>
