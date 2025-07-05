<div class="p-4 md:p-6 max-w-2xl mx-auto bg-white rounded-xl shadow-lg">
    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6 text-center">Favicon Generator</h2>

    <form wire:submit.prevent="generateFavicons" class="space-y-6">
        <div>
            <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Upload Image (PNG, JPG, WebP)</label>
            <input type="file" id="photo" wire:model="photo" accept="image/png, image/jpeg, image/webp"
                   class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">

            <div wire:loading wire:target="photo" class="mt-2 text-sm text-gray-500">
                Uploading image...
            </div>

            @error('photo')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        @if ($photo && !$errors->has('photo'))
            <div class="mt-4 text-center">
                <img src="{{ $photo->temporaryUrl() }}" alt="Image Preview"
                     class="inline-block max-w-[100px] max-h-[100px] border-2 border-gray-200 rounded-md shadow-sm">
            </div>
        @endif

        @if ($feedbackMessage)
            <div role="alert"
                 class="p-3 rounded-md text-sm mt-4 @if(session('favicon_generation_complete') && !$errors->any()) bg-green-50 text-green-700 @elseif($errors->any() || Str::contains(strtolower($feedbackMessage), ['error', 'failed', 'must', 'not found'])) bg-red-50 text-red-700 @else bg-blue-50 text-blue-700 @endif">
                {{ $feedbackMessage }}
            </div>
        @endif

        @if (session()->has('message') && !$feedbackMessage)
            <div class="p-3 rounded-md text-sm mt-4 bg-green-50 text-green-700">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error') && !$feedbackMessage)
             <div class="p-3 rounded-md text-sm mt-4 bg-red-50 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div>
            <button type="submit" wire:loading.attr="disabled" wire:target="generateFavicons, photo"
                    class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:bg-gray-400 transition duration-150 ease-in-out">
                <span wire:loading.remove wire:target="generateFavicons, photo">
                    Generate Favicons
                </span>
                <span wire:loading wire:target="generateFavicons, photo">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Generating...
                </span>
            </button>
        </div>
    </form>

    @if (session('favicon_generation_complete'))
        <div class="mt-6">
            <button wire:click="downloadFaviconZip" wire:loading.attr="disabled" wire:target="downloadFaviconZip"
                    class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:bg-gray-400 transition duration-150 ease-in-out">
                <span wire:loading.remove wire:target="downloadFaviconZip">
                    Download ZIP
                </span>
                <span wire:loading wire:target="downloadFaviconZip">
                     <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Preparing Download...
                </span>
            </button>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:load', function () {
            // Optional: If you need to do something specific when generation is complete.
            // Livewire's conditional rendering (@if) handles showing/hiding the download button.
            window.addEventListener('generationComplete', event => {
                console.log('Favicon generation complete event received.');
                // Example: Scroll to download button if it's out of view
                // const downloadButton = document.querySelector('button[wire\\:click="downloadFaviconZip"]');
                // if (downloadButton) {
                //    downloadButton.scrollIntoView({ behavior: 'smooth', block: 'center' });
                // }
            });
        });
    </script>
</div>
