<div>
    <style>
        .favicon-generator-container {
            font-family: sans-serif;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            max-width: 500px;
            margin: 40px auto;
            background-color: #f9f9f9;
        }
        .favicon-generator-container h2 {
            text-align: center;
            color: #333;
        }
        .favicon-generator-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .favicon-generator-container input[type="file"] {
            display: block;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: calc(100% - 22px); /* Account for padding and border */
        }
        .favicon-generator-container button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            width: 100%;
        }
        .favicon-generator-container button:hover {
            background-color: #0056b3;
        }
        .favicon-generator-container button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .feedback-message, .error-message, .success-message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }
        .feedback-message {
            background-color: #e6f7ff;
            border: 1px solid #91d5ff;
            color: #0050b3;
        }
        .error-message {
            background-color: #fff1f0;
            border: 1px solid #ffa39e;
            color: #cf1322;
        }
        .success-message {
            background-color: #f6ffed;
            border: 1px solid #b7eb8f;
            color: #389e0d;
        }
        .loading-indicator {
            display: inline-block;
            margin-left: 10px;
            border: 3px solid #f3f3f3; /* Light grey */
            border-top: 3px solid #007bff; /* Blue */
            border-radius: 50%;
            width: 16px;
            height: 16px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <div class="favicon-generator-container">
        <h2>Favicon Generator</h2>

        <form wire:submit.prevent="generateFavicons" id="generateForm">
            <div>
                <label for="photo">Upload Image:</label>
                <input type="file" id="photo" wire:model="photo" accept="image/png, image/jpeg, image/webp">
                @error('photo') <span class="error-message" style="display:block; margin-bottom:10px;">{{ $message }}</span> @enderror
            </div>

            @if ($photo && !$errors->has('photo'))
                <div style="margin-bottom:15px; text-align:center;">
                    <img src="{{ $photo->temporaryUrl() }}" alt="Image Preview" style="max-width: 100px; max-height: 100px; border-radius: 4px; border: 1px solid #ddd;">
                </div>
            @endif

            @if ($feedbackMessage)
                <p class="feedback-message @if(session()->has('error')) error-message @elseif(session()->has('message')) success-message @endif" style="margin-top:10px;">
                    {{ $feedbackMessage }}
                </p>
            @endif

            @if (session()->has('message') && !$feedbackMessage) {{-- Show general session message if no specific feedbackMessage is set --}}
                <div class="success-message" style="margin-top:10px;">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error') && !$feedbackMessage) {{-- Show general session error if no specific feedbackMessage is set --}}
                <div class="error-message" style="margin-top:10px;">
                    {{ session('error') }}
                </div>
            @endif

            <button type="submit" wire:loading.attr="disabled" wire:target="generateFavicons, photo" style="margin-bottom: 10px;">
                <span wire:loading.remove wire:target="generateFavicons, photo">Generate Favicons</span>
                <span wire:loading wire:target="generateFavicons, photo">Generating...</span>
                <div wire:loading wire:target="generateFavicons, photo" class="loading-indicator"></div>
            </button>
            <div wire:loading wire:target="photo" style="text-align:center; margin-top:5px; margin-bottom:10px; font-size:0.9em;">Uploading image...</div>
        </form>

        @if (session('favicon_generation_complete'))
            <button wire:click="downloadFaviconZip" wire:loading.attr="disabled" wire:target="downloadFaviconZip" id="downloadButton">
                <span wire:loading.remove wire:target="downloadFaviconZip">Download ZIP</span>
                <span wire:loading wire:target="downloadFaviconZip">Preparing Download...</span>
                <div wire:loading wire:target="downloadFaviconZip" class="loading-indicator"></div>
            </button>
        @endif
    </div>

    <script>
        document.addEventListener('livewire:load', function () {
            let downloadButton = document.getElementById('downloadButton');

            if (downloadButton && @this.get('favicon_generation_complete')) {
                // Already complete on load
            } else if (downloadButton) {
                // downloadButton.disabled = true; // Managed by @if in blade now
            }

            window.addEventListener('generationComplete', event => {
                // Livewire will re-render the component, and the download button will appear.
                // No specific JS action needed here to enable it due to conditional rendering.
                // If we wanted to auto-click or something more complex, we'd do it here.
                console.log('Favicon generation complete, download button should be visible.');
            });

            // Clear file input after form submission (Livewire handles model, this is for visual reset)
            // This is a bit tricky with Livewire's file handling.
            // A common approach is to use AlpineJS or a component reset.
            // For simplicity, we'll rely on Livewire's model binding.
            // If user selects same file again, updatedPhoto will trigger.
        });
    </script>
</div>
