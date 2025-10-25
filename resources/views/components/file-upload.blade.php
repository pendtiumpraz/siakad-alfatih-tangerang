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
        x-ref="fileInput"
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
            @php
                // Check if currentFile is already a full URL
                $fileUrl = filter_var($currentFile, FILTER_VALIDATE_URL)
                    ? $currentFile
                    : asset('storage/' . $currentFile);
                $displayName = filter_var($currentFile, FILTER_VALIDATE_URL)
                    ? 'File di Google Drive'
                    : basename($currentFile);
            @endphp
            <a href="{{ $fileUrl }}" target="_blank" class="text-green-600 hover:text-green-800 underline inline-flex items-center gap-1">
                {{ $displayName }}
                @if(filter_var($currentFile, FILTER_VALIDATE_URL) && str_contains($currentFile, 'drive.google.com'))
                    <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.01 1.485c-2.082 0-3.754.02-3.743.047.011.024 1.793 3.099 3.959 6.833l3.938 6.78-2.258 3.905c-1.242 2.148-2.25 3.919-2.241 3.936.013.024 1.653.042 3.646.042H19l2.24-3.878c1.232-2.133 2.231-3.906 2.22-3.942-.013-.036-1.802-3.138-3.975-6.894zm-2.555 5.894c-1.425-2.479-2.613-4.521-2.638-4.537-.038-.023-1.715 2.851-5.088 8.721l-1.067 1.859 2.255 3.904c1.241 2.148 2.259 3.893 2.263 3.879.004-.014 1.212-2.063 2.683-4.553l2.675-4.527z"/>
                    </svg>
                @endif
            </a>
        </p>
    </div>
    @endif
</div>
