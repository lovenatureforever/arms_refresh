<div class="grid grid-cols-1 gap-6">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">My Signature</h4>
        </div>
        <div class="card-body">
            @if($director)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h5 class="font-semibold mb-3">Current Signature</h5>
                        @if($currentSignature)
                            <div class="border rounded-lg p-4">
                                <img src="{{ tenant_asset($currentSignature->signature_path) }}"
                                     alt="Your Signature"
                                     class="max-w-full h-32 object-contain border mb-3">
                                <p class="text-sm text-gray-500">
                                    Uploaded: {{ $currentSignature->created_at->format('Y-m-d H:i') }}
                                </p>
                                <button wire:click="deleteSignature"
                                        class="btn bg-danger text-white text-sm mt-2"
                                        wire:confirm="Are you sure you want to delete your signature?">
                                    Delete Signature
                                </button>
                            </div>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <p class="text-gray-600">No signature uploaded yet.</p>
                            </div>
                        @endif
                    </div>

                    <div>
                        <h5 class="font-semibold mb-3">Upload New Signature</h5>
                        <div class="border rounded-lg p-4">
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Signature Image</label>
                                <input type="file"
                                       wire:model="signatureFile"
                                       accept="image/png,image/jpeg,.png,.jpg,.jpeg"
                                       class="w-full border rounded px-3 py-2 @error('signatureFile') border-red-500 bg-red-50 @enderror">
                                <div wire:loading wire:target="signatureFile" class="text-sm text-blue-600 mt-1">
                                    <span class="inline-flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Uploading file...
                                    </span>
                                </div>
                                @error('signatureFile')
                                    <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg">
                                        <p class="text-red-600 text-sm font-medium flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                        <p class="text-red-500 text-xs mt-1">Please select a PNG or JPG image file only.</p>
                                    </div>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">
                                    Accepted formats: PNG, JPG. Max size: 2MB.
                                </p>
                            </div>

                            @if($signatureFile)
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Preview</label>
                                    <img src="{{ $signatureFile->temporaryUrl() }}"
                                         alt="Preview"
                                         class="max-w-full h-24 object-contain border">
                                </div>
                            @endif

                            <button wire:click="uploadSignature"
                                    class="btn bg-primary text-white w-full"
                                    wire:loading.attr="disabled">
                                <span wire:loading wire:target="uploadSignature">Uploading...</span>
                                <span wire:loading.remove wire:target="uploadSignature">
                                    {{ $currentSignature ? 'Replace Signature' : 'Upload Signature' }}
                                </span>
                            </button>
                        </div>

                        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h6 class="font-medium text-blue-800 mb-2">Tips for a good signature:</h6>
                            <ul class="text-sm text-blue-700 list-disc list-inside">
                                <li>Use a white background</li>
                                <li>Sign with a dark pen (black or blue)</li>
                                <li>Ensure good lighting when taking photo</li>
                                <li>Crop to show only the signature</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-yellow-800">Your director profile is not linked. Please contact your company secretary.</p>
                </div>
            @endif
        </div>
    </div>
</div>
