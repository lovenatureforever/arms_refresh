<div class="mb-4 grid grid-cols-1 gap-6">
    <div class="">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="card-title">Place Order Form</h3>
                    <div class="flex items-center gap-2">
                        <a class="btn bg-primary text-white" type="button" href="{{ route('cosec.cart', $id) }}">
                            View Cart
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="px-12">
                    <div class="m-6">
                        <div class="mb-2">
                            Resolution Template Options
                        </div>
                        <select class="form-input" wire:model.live="selectedOption" x-data>
                            <option value="0">Select an option</option>
                            @foreach ($services as $key => $service)
                                <option value="{{ $key + 1 }}">{{ $service }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div x-data>
                        <div class="p-8 border-t border-b" x-show="$wire.selectedOption == 1">
                            @livewire('tenant.forms.cosec.appointment-director-form')
                        </div>

                        <div class="p-8 border-t border-b" x-show="$wire.selectedOption == 2">
                            @livewire('tenant.forms.cosec.resignation-director-form', ['id' => $this->id])
                        </div>

                        <div class="p-8 border-t border-b" x-show="$wire.selectedOption == 3">
                            @livewire('tenant.forms.cosec.transfer-share-form', ['id' => $this->id])
                        </div>

                        <div class="p-8 border-t border-b" x-show="$wire.selectedOption == 4">
                            @livewire('tenant.forms.cosec.increase-paid-capital-form', ['id' => $this->id])
                        </div>

                        <div class="p-8 border-t border-b" x-show="$wire.selectedOption == 5">
                            @livewire('tenant.forms.cosec.business-address-form', ['id' => $this->id])
                        </div>

                        <div class="p-8 border-t border-b" x-show="$wire.selectedOption == 6">
                            @livewire('tenant.forms.cosec.branch-address-form', ['id' => $this->id])
                        </div>

                        <div class="p-8 border-t border-b" x-show="$wire.selectedOption == 7">
                            @livewire('tenant.forms.cosec.bank-account-form', ['id' => $this->id])
                        </div>

                        <div class="p-8 border-t border-b" x-show="$wire.selectedOption == 8">
                            @livewire('tenant.forms.cosec.bank-signatories-form', ['id' => $this->id])
                        </div>

                        <div class="p-8 border-t border-b" x-show="$wire.selectedOption == 9">
                            @livewire('tenant.forms.cosec.maker-or-checker-form', ['id' => $this->id])
                        </div>
                    </div>

                    <div class="m-6 text-right">
                        <button wire:click="requestData" class="btn bg-success text-white mb-8">
                            Save & Add To Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
