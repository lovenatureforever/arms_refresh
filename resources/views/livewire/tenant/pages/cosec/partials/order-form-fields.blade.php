{{-- Shared Order Form Fields Partial --}}
{{-- Required variables: $customPlaceholders, $formData (wire:model), $requiredSignatures, $selectedDirectors (wire:model), $directors --}}

@php
    // Fields that should be disabled/readonly for directors
    $isDirectorUser = auth()->user()->user_type === 'director';
    $disabledForDirector = [
        'company_name',
        'company_no',
        'company_old_no',
        'secretary_name',
        'secretary_license',
        'secretary_ssm_no',
    ];
@endphp

{{-- Document Details Form --}}
@if(count($customPlaceholders) > 0)
<div class="mb-4">
    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
        <svg class="w-4 h-4 mr-1.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        Document Details
    </h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($customPlaceholders as $placeholder)
            @php
                $isDisabled = $isDirectorUser && in_array($placeholder, $disabledForDirector);
            @endphp
            <div wire:key="field-{{ $placeholder }}" class="{{ str_contains($placeholder, 'address') || str_contains($placeholder, 'description') ? 'md:col-span-2' : '' }}">
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    {{ \App\Models\Tenant\CosecTemplate::placeholderToLabel($placeholder) }}
                    <span class="text-red-500">*</span>
                    @if($isDisabled)
                        <span class="text-gray-400 text-[10px] ml-1">(Auto-filled)</span>
                    @endif
                </label>
                @if(str_contains($placeholder, 'date'))
                    <input type="date" wire:model="formData.{{ $placeholder }}" class="form-input w-full text-sm {{ $isDisabled ? 'bg-gray-100 cursor-not-allowed' : '' }}" {{ $isDisabled ? 'disabled' : '' }}>
                @elseif(str_contains($placeholder, 'address') || str_contains($placeholder, 'description'))
                    <textarea wire:model="formData.{{ $placeholder }}" rows="2" class="form-input w-full text-sm {{ $isDisabled ? 'bg-gray-100 cursor-not-allowed' : '' }}" placeholder="Enter {{ \App\Models\Tenant\CosecTemplate::placeholderToLabel($placeholder) }}" {{ $isDisabled ? 'disabled' : '' }}></textarea>
                @else
                    <input type="text" wire:model="formData.{{ $placeholder }}" class="form-input w-full text-sm {{ $isDisabled ? 'bg-gray-100 cursor-not-allowed' : '' }}" placeholder="Enter {{ \App\Models\Tenant\CosecTemplate::placeholderToLabel($placeholder) }}" {{ $isDisabled ? 'disabled' : '' }}>
                @endif
                @error('formData.' . $placeholder)
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- Director Selection - Hidden for directors, only admin can select signatories --}}
@if($requiredSignatures > 0 && !$isDirectorUser)
<div class="mb-2">
    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
        <svg class="w-4 h-4 mr-1.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
        Select Signatories ({{ $requiredSignatures }} required)
    </h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @for($i = 0; $i < $requiredSignatures; $i++)
            <div wire:key="director-{{ $i }}" class="p-3 border border-gray-200 rounded-lg bg-gray-50">
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    Director {{ $i + 1 }} <span class="text-red-500">*</span>
                </label>
                <select wire:model.live="selectedDirectors.{{ $i }}" class="form-input w-full text-sm">
                    <option value="">-- Select Director --</option>
                    @foreach($directors as $dir)
                        <option value="{{ $dir->id }}">
                            {{ $dir->name }} - {{ $dir->designation }}
                            @if($dir->defaultSignature) (Has Signature) @else (No Signature) @endif
                        </option>
                    @endforeach
                </select>
                @error('selectedDirectors.' . $i)
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror

                @if(isset($selectedDirectors[$i]) && $selectedDirectors[$i])
                    @php
                        $selectedDir = $directors->find($selectedDirectors[$i]);
                    @endphp
                    @if($selectedDir && $selectedDir->defaultSignature)
                        <div class="mt-2 p-2 bg-white rounded border border-gray-200 text-center">
                            <p class="text-xs text-gray-500 mb-1">Signature Preview</p>
                            <img src="/tenancy/assets/{{ $selectedDir->defaultSignature->signature_path }}" alt="Signature" class="max-h-10 mx-auto">
                        </div>
                    @elseif($selectedDir)
                        <div class="mt-2 p-2 bg-yellow-50 rounded border border-yellow-200 text-center">
                            <p class="text-xs text-yellow-700">No signature uploaded</p>
                        </div>
                    @endif
                @endif
            </div>
        @endfor
    </div>
</div>
@endif
