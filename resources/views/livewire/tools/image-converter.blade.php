<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg" x-data="{ dragOver: false }">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Image Converter</h1>
        <p class="text-gray-600">Convert your images to different formats with ease</p>
    </div>

    <!-- File Upload Area -->
    <div class="mb-8">
        <div
            class="relative border-2 border-dashed rounded-lg p-8 text-center transition-colors duration-200"
            :class="dragOver ? 'border-blue-400 bg-blue-50' : 'border-gray-300 bg-gray-50'"
            @dragover.prevent="dragOver = true"
            @dragleave.prevent="dragOver = false"
            @drop.prevent="dragOver = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change'))"
        >
            @if(!$image)
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>

                <h3 class="text-lg font-medium text-gray-900 mb-2">Upload your image</h3>
                <p class="text-gray-500 mb-4">Drag and drop your image here, or click to browse</p>

                <input
                    type="file"
                    wire:model="image"
                    accept="image/*"
                    class="hidden"
                    x-ref="fileInput"
                >

                <button
                    type="button"
                    onclick="document.querySelector('input[type=file]').click()"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200"
                >
                    Choose File
                </button>
            @else
                <div class="flex items-center justify-center space-x-4">
                    <svg class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-green-700 font-medium">{{ $image->getClientOriginalName() }}</span>
                    <button
                        wire:click="resetConverter"
                        class="text-red-600 hover:text-red-800 transition-colors duration-200"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif
        </div>

        @error('image')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror

        <div wire:loading wire:target="image" class="mt-2 text-center">
            <div class="inline-flex items-center text-blue-600">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Analyzing image...
            </div>
        </div>
    </div>

    <!-- Image Preview and Information -->
    @if($image && $originalFormat)
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Original Image Information</h3>

            <!-- Image Preview -->
            <div class="mb-6 text-center">
                <div class="inline-block bg-white p-4 rounded-lg border shadow-sm">
                    <img src="{{ $image->temporaryUrl() }}"
                         alt="Preview"
                         class="max-w-full max-h-64 object-contain rounded"
                         style="max-width: 400px;">
                </div>
            </div>

            <!-- Image Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-lg border">
                    <div class="text-sm text-gray-500 mb-1">Format</div>
                    <div class="text-lg font-semibold text-gray-900">{{ $originalFormat }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg border">
                    <div class="text-sm text-gray-500 mb-1">File Size</div>
                    <div class="text-lg font-semibold text-gray-900">{{ $originalSize }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg border">
                    <div class="text-sm text-gray-500 mb-1">Dimensions</div>
                    <div class="text-lg font-semibold text-gray-900">{{ $originalDimensions }}</div>
                </div>
            </div>
        </div>

        <!-- Conversion Options -->
        <div class="bg-white border rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Conversion Settings</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Target Format -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Format</label>
                    <select wire:model="targetFormat"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="jpg">JPG</option>
                        <option value="png">PNG</option>
                        <option value="webp">WebP</option>
                        <option value="gif">GIF</option>
                        <option value="bmp">BMP</option>
                    </select>
                </div>

                <!-- Quality Setting -->
                <div x-show="['jpg', 'jpeg', 'webp'].includes('{{ $targetFormat }}')">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quality ({{ $quality }}%)</label>
                    <input
                        type="range"
                        wire:model="quality"
                        min="1"
                        max="100"
                        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider"
                    >
                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                        <span>Low</span>
                        <span>High</span>
                    </div>
                </div>
            </div>

            @error('targetFormat')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Convert Button -->
        <div class="text-center mb-8">
            <button
                wire:click="convertImage"
                :disabled="$wire.isProcessing"
                class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors duration-200"
            >
                <span wire:loading.remove wire:target="convertImage">
                    Convert to {{ strtoupper($targetFormat) }}
                </span>
                <span wire:loading wire:target="convertImage" class="inline-flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Converting...
                </span>
            </button>
        </div>

        @error('conversion')
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                          clip-rule="evenodd"/>
                </svg>
                <p class="ml-3 text-sm text-red-700">{{ $message }}</p>
            </div>
        </div>
        @enderror

        <!-- Download Section -->
        @if($convertedImagePath)
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="h-8 w-8 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <div>
                            <h4 class="text-lg font-semibold text-green-900">Conversion Complete!</h4>
                            <p class="text-green-700">Your image has been successfully converted to <span x-text="$wire.targetFormat.toUpperCase()"></span>
                                format.</p>
                        </div>
                    </div>
                    <button
                        wire:click="downloadConverted"
                        class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center"
                    >
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download
                    </button>
                </div>
            </div>
        @endif

        @error('download')
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mt-4">
            <p class="text-sm text-red-700">{{ $message }}</p>
        </div>
        @enderror
    @endif
</div>
