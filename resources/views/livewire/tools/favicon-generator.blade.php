<div x-data="imageUploader()" class="space-y-3">
    <div class="px-2 py-3 bg-background rounded-xl shadow-sm  border border-foreground/5">
        <form wire:submit.prevent="convertImage">
            <div
                    x-ref="dropzone"
                    class="dropzone-area  border-2 border-dashed border-foreground/15 rounded-md p-4 min-h-30 grid place-items-center"
                    :class="{ 'dragover border-primary': isDragging }"
                    @dragover.prevent="isDragging = true"
                    @dragleave.prevent="isDragging = false"
                    @drop.prevent="handleDrop($event)"
                    @click="$refs.fileInput.click()"
            >
                <template x-if="!image">
                    <span class="text-gray-500 text-center px-4 py-2">Drag & drop an image or click to browse</span>
                </template>
                <template x-if="image">
                    <img :src="image" alt="Preview" class="max-h-full max-w-full object-contain">
                </template>
            </div>

            <!-- Hidden file input -->
            <input type="file" x-ref="fileInput" accept="image/*" @change="handleInputChange" class="hidden" wire:model="image">

            <div class="mt-4 flex justify-between">
                <button x-show="image" class="px-4 py-2 rounded-md text-white bg-primary hover:bg-primary/90" type="submit">Convert to Favicon</button>
                <button
                        x-show="image"
                        x-cloak
                        @click="removeImage"
                        type="button"
                        class="px-4 py-2 rounded-md text-white bg-red-500 hover:bg-red-500/90 "
                >
                    Remove Image
                </button>
            </div>
            @error('image') <span class="error">{{ $message }}</span> @enderror
        </form>
    </div>

    @if ($convertedImages)
        <div class="bg-foreground/5 rounded-lg p-4 animate-slide-down">
            <div class="flex flex-wrap gap-6">
                @foreach ($convertedImages as $img)
                    <div class="flex flex-col items-center space-y-2">
                        <img src="{{ $img['url'] }}" alt="{{ $img['name'] }}" class="w-16 h-16 object-contain border rounded-md shadow">
                        <div class="text-xs text-center">{{ $img['name'] }}<br>{{ $img['size'] }}</div>
                        <a href="{{ $img['url'] }}" download="{{ $img['name'] }}"
                           class="bg-primary text-white rounded px-3 py-1 text-xs hover:bg-primary/90">Download</a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div x-show="image" class="prose dark:prose-invert min-w-full animate-slide-down">
        @markdown
        ### Add the following code to the `head` of html document
        ```html
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="shortcut icon" href="/favicon.ico">
        ```
        @endmarkdown
    </div>
</div>
