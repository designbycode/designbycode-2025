<div x-data="imageEditor()" class="py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-slate-800 mb-2">Image Filter Studio</h1>
            <p class="text-slate-600">Upload an image and apply beautiful filters in real-time</p>
        </div>

        <!-- Upload Section -->
        <div x-show="!$wire.image" class="bg-white rounded-2xl shadow-xl p-8 text-center">
            <div
                    class="border-3 border-dashed border-slate-300 rounded-xl p-12 hover:border-blue-400 hover:bg-blue-50 transition-all duration-300 cursor-pointer group"
                    @click="$refs.fileInput.click()"
                    @dragover.prevent
                    @drop.prevent="handleDrop($event)"
            >
                <x-heroicon-o-cloud-arrow-down name="upload" class="w-16 h-16 mx-auto text-slate-400 group-hover:text-blue-500 mb-4 transition-colors"/>
                <h3 class="text-xl font-semibold text-slate-700 mb-2">Upload Your Image</h3>
                <p class="text-slate-500 mb-4">Click to browse or drag and drop your image here</p>
                <p class="text-sm text-slate-400">Supports JPG, PNG, GIF up to 10MB</p>
            </div>
            <input
                    x-ref="fileInput"
                    type="file"
                    accept="image/*"
                    wire:model="image"
                    class="hidden"
            />
        </div>

        <!-- Editor Section -->
        <div x-show="$wire.image" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Image Preview -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-slate-800">Preview</h2>
                        <div class="flex gap-2">
                            <button
                                    @click="showOriginal = !showOriginal"
                                    class="flex items-center gap-2 px-3 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors text-sm font-medium text-slate-700"
                            >
                                <x-heroicon-o-eye name="eye-off" class="w-4 h-4" x-show="showOriginal"/>
                                <x-heroicon-o-eye-slash name="eye" class="w-4 h-4" x-show="!showOriginal"/>
                                <span x-text="showOriginal ? 'Hide Original' : 'Show Original'"></span>
                            </button>
                            <button
                                    @click="$wire.download()"
                                    class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm font-medium"
                            >
                                <x-heroicon-o-arrow-down-tray name="download" class="w-4 h-4"/>
                                Download
                            </button>
                        </div>
                    </div>

                    <div class="relative bg-slate-50 rounded-xl overflow-hidden">
                        <img
                                x-ref="previewImage"
                                :src="$wire.image ? $wire.image.temporaryUrl() : ''"
                                alt="Preview"
                                class="w-full h-auto max-h-96 object-contain mx-auto transition-all duration-300"
                                :class="{ 'pixelated': $wire.pixelateValue > 0 && !showOriginal }"
                                :style="showOriginal ? '' : getFilterStyle()"
                        />
                        <div
                                x-show="$wire.pixelateValue > 0 && !showOriginal"
                                class="absolute inset-0 bg-black bg-opacity-10 flex items-center justify-center"
                        >
                            <span class="bg-white bg-opacity-90 px-3 py-1 rounded-full text-sm font-medium text-slate-700">
                                Pixelated <span x-text="$wire.pixelateValue"></span>%
                            </span>
                        </div>
                    </div>

                    <p class="text-sm text-slate-500 mt-2">File: <span x-text="$wire.image ? $wire.image.name : ''"></span></p>
                    <script>
                        document.addEventListener('livewire:initialized', () => {
                        @this.on('image-updated', (event) => {
                            const image = document.querySelector('[x-ref="previewImage"]');
                            image.src = event.image;
                        })

                        });
                    </script>
                </div>
            </div>

            <!-- Controls Panel -->
            <div class="space-y-6">
                <!-- Filters -->
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-slate-800">Filters</h2>
                        <button
                                @click="resetFilters()"
                                class="flex items-center gap-2 px-3 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors text-sm font-medium text-slate-700"
                        >
                            <x-heroicon-o-arrow-path name="rotate-ccw" class="w-4 h-4"/>
                            Reset
                        </button>
                    </div>

                    <div class="space-y-6">
                        <!-- Sepia -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Sepia (<span x-text="$wire.sepiaValue"></span>%)
                            </label>
                            <input
                                    type="range"
                                    min="0"
                                    max="100"
                                    wire:model.live="sepiaValue"
                                    class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer slider"
                            />
                        </div>

                        <!-- Grayscale -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Grayscale (<span x-text="$wire.grayscaleValue"></span>%)
                            </label>
                            <input
                                    type="range"
                                    min="0"
                                    max="100"
                                    wire:model.live="grayscaleValue"
                                    class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer slider"
                            />
                        </div>

                        <!-- Blur -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Blur (<span x-text="$wire.blurValue"></span>px)
                            </label>
                            <input
                                    type="range"
                                    min="0"
                                    max="20"
                                    wire:model.live="blurValue"
                                    class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer slider"
                            />
                        </div>

                        <!-- Brightness -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Brightness (<span x-text="$wire.brightnessValue"></span>%)
                            </label>
                            <input
                                    type="range"
                                    min="0"
                                    max="200"
                                    wire:model.live="brightnessValue"
                                    class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer slider"
                            />
                        </div>

                        <!-- Contrast -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Contrast (<span x-text="$wire.contrastValue"></span>%)
                            </label>
                            <input
                                    type="range"
                                    min="0"
                                    max="200"
                                    wire:model.live="contrastValue"
                                    class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer slider"
                            />
                        </div>

                        <!-- Saturation -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Saturation (<span x-text="$wire.saturateValue"></span>%)
                            </label>
                            <input
                                    type="range"
                                    min="0"
                                    max="200"
                                    wire:model.live="saturateValue"
                                    class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer slider"
                            />
                        </div>

                        <!-- Hue Rotate -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Hue Rotate (<span x-text="$wire.hueRotateValue"></span>°)
                            </label>
                            <input
                                    type="range"
                                    min="0"
                                    max="360"
                                    wire:model.live="hueRotateValue"
                                    class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer slider"
                            />
                        </div>

                        <!-- Pixelate -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Pixelate (<span x-text="$wire.pixelateValue"></span>%)
                            </label>
                            <input
                                    type="range"
                                    min="0"
                                    max="90"
                                    wire:model.live="pixelateValue"
                                    class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer slider"
                            />
                        </div>
                    </div>
                </div>

                <!-- Quick Presets -->
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Quick Presets</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <button
                                @click="applyPreset('vintage')"
                                class="p-3 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg text-sm font-medium text-amber-800 transition-colors"
                        >
                            Vintage
                        </button>
                        <button
                                @click="applyPreset('bw')"
                                class="p-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-sm font-medium text-slate-800 transition-colors"
                        >
                            B&W Classic
                        </button>
                        <button
                                @click="applyPreset('vivid')"
                                class="p-3 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-lg text-sm font-medium text-rose-800 transition-colors"
                        >
                            Vivid
                        </button>
                        <button
                                @click="applyPreset('dreamy')"
                                class="p-3 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg text-sm font-medium text-blue-800 transition-colors"
                        >
                            Dreamy
                        </button>
                    </div>
                </div>

                <!-- Download Settings -->
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Download Settings</h3>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-3">
                            File Format
                        </label>
                        <div class="grid grid-cols-3 gap-2">
                            <button
                                    @click="$wire.set('downloadFormat', 'png')"
                                    :class="$wire.downloadFormat === 'png' ? 'bg-blue-100 text-blue-800 border-2 border-blue-300' : 'bg-slate-50 text-slate-700 border-2 border-slate-200 hover:bg-slate-100'"
                                    class="p-2 rounded-lg text-xs font-medium transition-colors"
                            >
                                PNG
                            </button>
                            <button
                                    @click="$wire.set('downloadFormat', 'jpeg')"
                                    :class="$wire.downloadFormat === 'jpeg' ? 'bg-blue-100 text-blue-800 border-2 border-blue-300' : 'bg-slate-50 text-slate-700 border-2 border-slate-200 hover:bg-slate-100'"
                                    class="p-2 rounded-lg text-xs font-medium transition-colors"
                            >
                                JPEG
                            </button>
                            <button
                                    @click="$wire.set('downloadFormat', 'webp')"
                                    :class="$wire.downloadFormat === 'webp' ? 'bg-blue-100 text-blue-800 border-2 border-blue-300' : 'bg-slate-50 text-slate-700 border-2 border-slate-200 hover:bg-slate-100'"
                                    class="p-2 rounded-lg text-xs font-medium transition-colors"
                            >
                                WebP
                            </button>
                        </div>
                        <p class="text-xs text-slate-500 mt-2">
                            <span x-show="$wire.downloadFormat === 'png'">Best quality, larger file size</span>
                            <span x-show="$wire.downloadFormat === 'jpeg'">Good quality, smaller file size</span>
                            <span x-show="$wire.downloadFormat === 'webp'">Modern format, excellent compression</span>
                        </p>
                    </div>
                </div>

                <!-- Upload New -->
                <button
                        @click="$wire.resetConverter()"
                        class="w-full bg-slate-600 hover:bg-slate-700 text-white py-3 px-4 rounded-xl font-medium transition-colors"
                >
                    Upload New Image
                </button>
            </div>
        </div>

        <!-- Hidden canvas for download functionality -->
        <canvas x-ref="downloadCanvas" class="hidden"></canvas>
    </div>

    @push('scripts')
        <script src="{{ asset('js/image-editor.js') }}"></script>
        <script>
            function imageEditor() {
                return {
                    showOriginal: false,

                    handleDrop(event) {
                        const file = event.dataTransfer.files[0];
                        if (file && file.type.startsWith('image/')) {
                        @this.upload('image', file)
                        }
                    },

                    resetFilters() {
                    @this.set('sepiaValue', 0)

                    @this.set('grayscaleValue', 0)

                    @this.set('blurValue', 0)

                    @this.set('brightnessValue', 100)

                    @this.set('contrastValue', 100)

                    @this.set('saturateValue', 100)

                    @this.set('hueRotateValue', 0)

                    @this.set('pixelateValue', 0)

                    },

                    getFilterStyle() {
                        const filterString = [
                            `sepia(${@this.get('sepiaValue')}%)`,
                            `grayscale(${@this.get('grayscaleValue')}%)`,
                            `blur(${@this.get('blurValue')}px)`,
                            `brightness(${@this.get('brightnessValue')}%)`,
                            `contrast(${@this.get('contrastValue')}%)`,
                            `saturate(${@this.get('saturateValue')}%)`,
                            `hue-rotate(${@this.get('hueRotateValue')}deg)`
                        ].join(' ');

                        let style = `filter: ${filterString};`;

                        if (${@this.get('pixelateValue')
                        }
                    >
                        0
                    )
                        {
                            const scale = Math.max(0.1, 1 - ($
                            {@this.get('pixelateValue')
                            }
                            / 100));
                            style += ` transform: scale(${scale}); transform-origin: top left;`;
                        }

                        return style;
                    },

                    applyPreset(preset) {
                        switch (preset) {
                            case 'vintage':
                            @this.set('sepiaValue', 80)

                            @this.set('brightnessValue', 110)

                            @this.set('contrastValue', 120)

                                break;
                            case 'bw':
                            @this.set('grayscaleValue', 100)

                            @this.set('contrastValue', 130)

                                break;
                            case 'vivid':
                            @this.set('saturateValue', 150)

                            @this.set('contrastValue', 120)

                            @this.set('brightnessValue', 110)

                                break;
                            case 'dreamy':
                            @this.set('blurValue', 3)

                            @this.set('brightnessValue', 120)

                            @this.set('saturateValue', 80)

                                break;
                        }
                    },
                }
            }
        </script>
    @endpush
</div>
