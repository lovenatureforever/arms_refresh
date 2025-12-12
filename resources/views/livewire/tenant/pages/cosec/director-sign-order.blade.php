<div class="grid grid-cols-1 gap-6">
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">Sign Document</h4>
                <a href="{{ route('director.cosec.signatures') }}" class="btn border-secondary text-secondary">
                    Back to List
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($order && $director)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h5 class="font-semibold mb-3">Document Details</h5>
                        <table class="w-full text-sm">
                            <tr>
                                <td class="py-2 text-gray-600">Document:</td>
                                <td class="py-2 font-medium">{{ $order->template->name ?? $order->form_name }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-gray-600">Company:</td>
                                <td class="py-2 font-medium">{{ $order->company_name }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-gray-600">Requested:</td>
                                <td class="py-2 font-medium">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-gray-600">Status:</td>
                                <td class="py-2">
                                    @if($order->status == 1)
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Approved</span>
                                    @elseif($order->status == 0)
                                        <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Pending</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Rejected</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div>
                        <h5 class="font-semibold mb-3">Your Signature Status</h5>
                        @if($signatureRequest)
                            @if($signatureRequest->signature_status === 'signed')
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <p class="text-green-800 font-medium">You have already signed this document.</p>
                                    <p class="text-green-600 text-sm mt-1">Signed at: {{ $signatureRequest->signed_at->format('Y-m-d H:i') }}</p>
                                </div>
                            @else
                                @if($hasSignature)
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                        <p class="text-blue-800">Your signature is ready. Click the button below to sign this document.</p>
                                    </div>
                                    <button wire:click="signOrder" class="btn bg-success text-white w-full">
                                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Sign Document
                                    </button>
                                @else
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                        <p class="text-yellow-800">You need to upload your signature before signing documents.</p>
                                    </div>
                                    <a href="{{ route('director.cosec.my-signatures') }}" class="btn bg-primary text-white w-full">
                                        Upload Signature
                                    </a>
                                @endif
                            @endif
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <p class="text-gray-600">No signature request found for this document.</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if($order->template && $order->template->template_file)
                    <div class="mt-6">
                        <h5 class="font-semibold mb-3">Document Preview</h5>
                        <iframe src="{{ route('director.cosec.template.preview', $order->template->id) }}"
                                class="w-full h-96 border rounded"></iframe>
                    </div>
                @endif
            @else
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-red-800">Document not found or you don't have access to sign it.</p>
                </div>
            @endif
        </div>
    </div>
</div>
