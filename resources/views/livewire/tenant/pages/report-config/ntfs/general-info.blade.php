<div>
    @foreach($items as $item)
    <div wire:key='{{ $item->id }}'>
        <div class="flex flex-row justify-start mb-1">
            <div class="text-lg font-medium">{{ $item->title }}</div>
            <button
                class="btn"
                type="button"
                title="Edit"
                wire:click="$dispatch('openModal', {component: 'tenants.modal.report.config.update-ntfs-general-title-modal', arguments: { id: {{ $item->id }} }})"
            >
                <i class="text-base text-info mgc_edit_3_line"></i>
            </button>
            <button
                class="btn"
                type="button"
                title="Show in page"
                wire:click="updateDisplay({{ $item->id }}, {{ $item->is_active ? 0 : 1 }})"
            >
                <i class="text-base {{ $item->is_active ? 'mgc_eye_2_line' : 'mgc_eye_close_line text-gray-300' }}"></i>
                {{-- <i class="text-base text-gray-300 mgc_eye_close_line"></i> --}}
            </button>
        </div>
        <div class="relative overflow-x-auto border rounded-lg mb-7">
            <div class="grid grid-cols-2 text-xs font-bold text-center text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <div class="flex items-center justify-center p-1 border-r">
                    Default Content
                    <input type='checkbox' {{ $item->is_default_content ? 'checked' : '' }} wire:click="setDefaultContent({{ $item->id }}, 'true')"  class="w-4 h-4 ml-4 bg-white border-gray-300 rounded text-slate-600 focus:ring-slate-500 focus:ring-2" />
                </div>
                <div class="flex items-center justify-center p-1">
                    Custom Content
                    <input type='checkbox' {{ !$item->is_default_content ? 'checked' : '' }} wire:click="setDefaultContent({{ $item->id }}, 'false')" class="w-4 h-4 ml-4 bg-white border-gray-300 rounded text-slate-600 focus:ring-slate-500 focus:ring-2" />

                    <button
                        class="btn"
                        type="button"
                        title="Edit"
                        wire:click="$dispatch('openModal', {component: 'tenants.modal.report.config.update-ntfs-general-content-modal', arguments: { id: {{ $item->id }} }})"
                    >
                        <i class="text-base text-info mgc_edit_3_line"></i>
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-2">
                <div class="p-4 border-r"><pre class="text-wrap">{{ $item->default_content }}</pre></div>
                <div class="p-4"><pre class="text-wrap">{{ $item->content }}</pre></div>
            </div>
        </div>
    </div>
    @endforeach

</div>
