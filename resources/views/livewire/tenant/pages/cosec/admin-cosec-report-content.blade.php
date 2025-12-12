<div>
    @if (!empty($order->document_content))
        {{-- Render saved document content --}}
        <div class="prose max-w-none">
            {!! $order->document_content !!}
        </div>
    @else
        {{-- No document content yet --}}
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
            <p class="text-yellow-700">This order has not been processed yet.</p>
        </div>
    @endif
</div>
