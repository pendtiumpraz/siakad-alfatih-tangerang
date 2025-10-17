@props(['name' => 'file', 'label' => 'Upload File', 'accept' => '*', 'currentFile' => null])

<div x-data="{
    dragging: false,
    fileName: '{{ $currentFile ? basename($currentFile) : '' }}',
    handleFiles(e) {
        const files = e.target.files || e.dataTransfer.files;
        if (files.length) {
            this.fileName = files[0].name;
        }
    }
}">
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $label }}</label>

    <div
        @dragover.prevent="dragging = true"
        @dragleave.prevent="dragging = false"
        @drop.prevent="dragging = false; $refs.fileInput.files = $event.dataTransfer.files; handleFiles($event)"
        :class="dragging ? 'border-green-600 bg-green-50' : 'border-green-300 bg-gray-50'"
        class="border-2 border-dashed rounded-lg p-8 text-center transition-colors duration-200 cursor-pointer hover:border-green-500"
        @click="$refs.fileInput.click()"
    >
        <svg class="mx-auto h-12 w-12 text-green-600" stroke="currentColor" fill="none" viewBox="0 0 48 48">
            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <p class="mt-2 text-sm text-gray-600">
            <span class="font-semibold text-green-600">Klik untuk upload</span> atau drag & drop
        </p>
        <p class="mt-1 text-xs text-gray-500">PDF, PNG, JPG, JPEG (Max. 5MB)</p>

        <p x-show="fileName" x-text="fileName" class="mt-3 text-sm font-medium text-green-700"></p>
    </div>

    <input
        ref="fileInput"
        type="file"
        name="{{ $name }}"
        accept="{{ $accept }}"
        class="hidden"
        @change="handleFiles($event)"
    />

    @if($currentFile)
    <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
        <p class="text-sm text-gray-700">
            <span class="font-medium">File saat ini:</span>
            <a href="{{ asset('storage/' . $currentFile) }}" target="_blank" class="text-green-600 hover:text-green-800 underline">
                {{ basename($currentFile) }}
            </a>
        </p>
    </div>
    @endif
</div>
