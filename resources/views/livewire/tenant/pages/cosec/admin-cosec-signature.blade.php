<div>
    <div class="mb-6">
        <h3 class="text-lg font-semibold mb-4">Manage Director Signatures</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-2">Select Director</label>
                <select wire:model="selectedDirector" class="w-full border rounded px-3 py-2">
                    <option value="">Choose Director</option>
                    @foreach($directors as $director)
                        <option value="{{ $director->id }}">{{ $director->name }}</option>
                    @endforeach
                </select>
                @error('selectedDirector') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Signature Image</label>
                <input type="file" wire:model="signatureFile" accept="image/png" class="w-full border rounded px-3 py-2">
                <div wire:loading wire:target="signatureFile">Validating file...</div>
                @error('signatureFile') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- <button wire:click="uploadSignature" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            wire:loading.attr="disabled">
            Upload Signature
        </button> --}}
        <div class="mt-4" wire:loading.remove wire:target="signatureFile">
            <button class="text-white rounded-lg btn bg-primary disabled:opacity-60 disabled:cursor-not-allowed" wire:click="uploadSignature">
                <div class="mr-2 inline-block h-4 w-4 animate-spin rounded-full border-[3px] border-current border-t-transparent text-white"
                    role="status" aria-label="loading" wire:loading>
                    <span class="sr-only">Loading...</span>
                </div>
                <span wire:loading>Uploading...</span>
                <span wire:loading.remove>Upload Signature</span>
            </button>
        </div>
    </div>

    <div class="mb-6">
        <h3 class="text-lg font-semibold mb-4">Current Signatures</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($directors as $director)
                <div class="border rounded p-4">
                    <h4 class="font-medium">{{ $director->name }}</h4>
                    @if($director->defaultSignature)
                        <img src="{{tenant_asset('' . $director->defaultSignature->signature_path) }}"
                             alt="Signature" class="mt-2 max-w-full object-contain border">
                        <p class="text-sm text-gray-500 mt-1">
                            Uploaded: {{ $director->defaultSignature->created_at->format('Y-m-d') }}
                        </p>
                        {{-- <button wire:click="deleteSignature({{ $director->id }})" class="btn bg-danger text-white text-sm">Delete</button> --}}
                    @else
                        <p class="text-gray-500 mt-2">No signature uploaded</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
