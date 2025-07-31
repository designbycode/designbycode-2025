<div class="max-w-4xl mx-auto p-6 bg-background rounded-lg shadow-lg" x-data="{ dragOver: false }">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-foreground mb-2">Image Converter</h1>
        <p class="text-foreground-lighter">Convert your images to different formats with ease</p>
    </div>

    <!-- File Upload Area -->
    <div class="mb-8">
        <div
                class="relative border-2 border-dashed rounded-lg p-8 text-center transition-colors duration-200"
                :class="dragOver ? 'border-primary bg-primary/10' : 'border-background-darker bg-background-lighter'"
                @dragover.prevent="dragOver = true"
                @dragleave.prevent="dragOver = false"
                @drop.prevent="dragOver = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change'))"
        >
            @if(!$image)
                <svg class="mx-auto h-12 w-12 text-foreground-lighter mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>

                <h3 class="text-lg font-medium text-foreground mb-2">Upload your image</h3>
                <p class="text-foreground-lighter mb-4">Drag and drop your image here, or click to browse</p>

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
                        class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/90 transition-colors duration-200"
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
            <div class="inline-flex items-center text-primary">
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
        <div class="bg-background-lighter rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-foreground mb-4">Original Image Information</h3>

            <!-- Image Preview -->
            <div class="mb-6 text-center" x-data="{
                sepiaValue: @entangle('sepiaValue'),
                grayscaleValue: @entangle('grayscaleValue'),
                pixelateValue: @entangle('pixelateValue'),
                blurValue: @entangle('blurValue'),
                getStyle() {
                    let filterStyle = '';
                    if (this.sepiaValue > 0) {
                        filterStyle += `sepia(${this.sepiaValue / 100}) `;
                    }
                    if (this.grayscaleValue > 0) {
                        filterStyle += `grayscale(${this.grayscaleValue / 100}) `;
                    }
                    if (this.blurValue > 0) {
                        filterStyle += `blur(${this.blurValue}px) `;
                    }
                    if (this.pixelateValue > 0) {
                        // Not a standard CSS filter, handled by canvas
                    }
                    return `max-width: 400px; filter: ${filterStyle.trim()};`;
                }
            }">
                <div class="inline-block bg-background p-4 rounded-lg border border-background-darker shadow-sm">
                    <canvas x-ref="canvas" class="max-w-full max-h-64 object-contain rounded"></canvas>
                    <img src="{{ $image->temporaryUrl() }}"
                         alt="Preview"
                         class="hidden"
                         x-ref="image"
                         @load="
                            const image = $refs.image;
                            const canvas = $refs.canvas;
                            const ctx = canvas.getContext('2d');

                            function updatePreview() {
                                canvas.width = image.naturalWidth;
                                canvas.height = image.naturalHeight;
                                ctx.filter = getStyle().replace('max-width: 400px;', '').replace('filter:', '');
                                ctx.drawImage(image, 0, 0, image.naturalWidth, image.naturalHeight);
                                if ($wire.pixelateValue > 0) {
                                    const pixelation = $wire.pixelateValue / 100 * 30;
                                    ctx.imageSmoothingEnabled = false;
                                    ctx.drawImage(canvas, 0, 0, canvas.width / pixelation, canvas.height / pixelation);
                                    ctx.drawImage(canvas, 0, 0, canvas.width / pixelation, canvas.height / pixelation, 0, 0, canvas.width, canvas.height);
                                }
                            }

                            updatePreview();

                            $watch('$wire.sepiaValue', () => updatePreview());
                            $watch('$wire.grayscaleValue', () => updatePreview());
                            $watch('$wire.pixelateValue', () => updatePreview());
                            $watch('$wire.blurValue', () => updatePreview());
                         "
                    >
                </div>
            </div>

            <!-- Image Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-background p-4 rounded-lg border border-background-darker">
                    <div class="text-sm text-foreground-lighter mb-1">Format</div>
                    <div class="text-lg font-semibold text-foreground">{{ $originalFormat }}</div>
                </div>
                <div class="bg-background p-4 rounded-lg border border-background-darker">
                    <div class="text-sm text-foreground-lighter mb-1">File Size</div>
                    <div class="text-lg font-semibold text-foreground">{{ $originalSize }}</div>
                </div>
                <div class="bg-background p-4 rounded-lg border border-background-darker">
                    <div class="text-sm text-foreground-lighter mb-1">Dimensions</div>
                    <div class="text-lg font-semibold text-foreground">{{ $originalDimensions }}</div>
                </div>
            </div>
            <div class="text-center mt-4">
                <button wire:click="resetConverter" class="text-sm text-red-500 hover:underline">Reset Image</button>
            </div>
        </div>

        <!-- Conversion Options -->
        <div class="bg-background-lighter border border-background-darker rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-foreground mb-6">Conversion Settings</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Target Format -->
                <div>
                    <label class="block text-sm font-medium text-foreground-lighter mb-2">Target Format</label>
                    <select wire:model="targetFormat"
                            class="w-full border border-background-darker rounded-lg px-3 py-2 bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="jpg">JPG</option>
                        <option value="png">PNG</option>
                        <option value="webp">WebP</option>
                        <option value="gif">GIF</option>
                        <option value="bmp">BMP</option>
                    </select>
                </div>

                <!-- Quality Setting -->
                <div x-data="{ quality: @entangle('quality') }" x-show="['jpg', 'jpeg', 'webp'].includes('{{ $targetFormat }}')">
                    <label for="quality" class="block text-sm font-medium text-foreground-lighter mb-2">
                        Quality <span x-text="`(${quality}%)`"></span>
                    </label>
                    <input
                            type="range"
                            id="quality"
                            x-model="quality"
                            min="1"
                            max="100"
                            class="w-full h-2 bg-background-darker rounded-lg appearance-none cursor-pointer slider"
                    >
                    <div class="flex justify-between text-xs text-foreground-lighter mt-1">
                        <span>Low</span>
                        <span>High</span>
                    </div>
                </div>
                <!-- Filter Sliders -->
                <div class="col-span-2 grid grid-cols-2 gap-4">
                    <div>
                        <label for="sepia" class="block text-sm font-medium text-foreground-lighter mb-2">Sepia</label>
                        <input type="range" id="sepia" wire:model.live="sepiaValue" min="0" max="100"
                               class="w-full h-2 bg-background-darker rounded-lg appearance-none cursor-pointer slider">
                    </div>
                    <div>
                        <label for="grayscale" class="block text-sm font-medium text-foreground-lighter mb-2">Grayscale</label>
                        <input type="range" id="grayscale" wire:model.live="grayscaleValue" min="0" max="100"
                               class="w-full h-2 bg-background-darker rounded-lg appearance-none cursor-pointer slider">
                    </div>
                    <div>
                        <label for="pixelate" class="block text-sm font-medium text-foreground-lighter mb-2">Pixelate</label>
                        <input type="range" id="pixelate" wire:model.live="pixelateValue" min="0" max="100"
                               class="w-full h-2 bg-background-darker rounded-lg appearance-none cursor-pointer slider">
                    </div>
                    <div>
                        <label for="blur" class="block text-sm font-medium text-foreground-lighter mb-2">Blur</label>
                        <input type="range" id="blur" wire:model.live="blurValue" min="0" max="100"
                               class="w-full h-2 bg-background-darker rounded-lg appearance-none cursor-pointer slider">
                    </div>
                </div>
            </div>

            @error('targetFormat')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Convert Button -->
        <div class="text-center mb-8" x-data="{ targetFormat: @entangle('targetFormat') }">
            <button
                    wire:click="convertImage"
                    :disabled="$wire.isProcessing"
                    class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors duration-200"
            >
                <span wire:loading.remove wire:target="convertImage" x-text="`Convert to ${targetFormat.toUpperCase()}`"></span>
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
            <div class="bg-green-500/10 border border-green-500/20 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="h-8 w-8 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <div>
                            <h4 class="text-lg font-semibold text-green-500">Conversion Complete!</h4>
                            <p class="text-green-500/80">Your image has been successfully converted to <span x-text="$wire.targetFormat.toUpperCase()"></span>
                                format.</p>
                        </div>
                    </div>
                    <button
                            wire:click="download"
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
        <div class="bg-red-500/10 border border-red-500/20 rounded-lg p-4 mt-4">
            <p class="text-sm text-red-500">{{ $message }}</p>
        </div>
        @enderror
    @endif
</div>
