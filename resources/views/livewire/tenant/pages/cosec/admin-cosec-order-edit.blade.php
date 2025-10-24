<div class="mb-4">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Order: {{ $order->form_name }}</h3>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <label class="mb-2 inline-block text-sm font-medium text-gray-800">Document Content</label>
                {{-- <textarea id="document-content-editor" class="form-input" wire:model="documentContent" rows="15"></textarea> --}}
                <livewire:quill-text-editor class="form-input" wire:model="documentContent" theme="bubble" />
                @error('documentContent') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="mb-2 inline-block text-sm font-medium text-gray-800">Custom Credit Cost (leave empty to use template default)</label>
                <input type="number" class="form-input w-1/4" wire:model="customCreditCost" min="0" placeholder="{{ $order->template ? $order->template->credit_cost : 'N/A' }}">
                @error('customCreditCost') <span class="text-red-500">{{ $message }}</span> @enderror
                <small class="text-gray-600">Template default: {{ $order->template ? $order->template->credit_cost : 'N/A' }} credits</small>
            </div>

            <div class="flex gap-2">
                <button wire:click="save" class="btn bg-success text-white">Save Changes</button>
                <a href="{{ route('admin.cosec.index') }}" class="btn bg-gray-500 text-white">Back to Orders</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
document.addEventListener('livewire:loaded', function () {
    CKEDITOR.replace('document-content-editor');
    CKEDITOR.instances['document-content-editor'].on('change', function() {
        @this.set('documentContent', CKEDITOR.instances['document-content-editor'].getData());
    });
});
</script>
