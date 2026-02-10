<div x-data="{
            isDragging: false,
            handleDrop(e) {
                this.isDragging = false;
                const file = e.dataTransfer.files[0];
                if (file) {
                    $refs.input.files = e.dataTransfer.files;
                    $refs.input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }
        }"
         @dragover.prevent="isDragging = true"
         @dragleave.prevent="isDragging = false"
         @drop.prevent="handleDrop($event)"
         :class="{ 'bg-purple-100 border-purple-700': isDragging }"
         class="border-2 border-dashed border-purple-500 p-6 text-center rounded-lg cursor-pointer transition-all"
         @click="$refs.input.click()"
    >
    <input
            type="file"
            wire:model="image"
            x-ref="input"
            class="hidden"
            accept="image/*"
    >
    <p class="text-gray-600">{{ $label ?? __('Drag your image here or click to upload it') }}</p>
    <button type="button"
                class="mt-2 bg-purple-600 text-white px-4 py-2 rounded-md">
            {{ $buttonLabel ?? __('Select Image') }}
    </button>
</div>

@error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

@if ($imagePreview)
    <div class="mt-4">
        <p class="text-gray-700 font-semibold">{{ __('Preview:') }}</p>
        <img src="{{ $imagePreview }}" alt="preview"
             class="w-auto max-w-full h-auto max-h-48 rounded-lg shadow-md mx-auto">
    </div>
@endif

