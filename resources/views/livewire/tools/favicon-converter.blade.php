<div class="p-6 max-w-xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Favicon Converter</h1>

    <form wire:submit.prevent="convert" class="mb-4">
        <input type="file" wire:model="image" accept="image/*" class="block w-full mb-2">
        @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

        <button type="submit" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">
            Convert
        </button>
    </form>

    @if ($downloadLink)
        <div class="mt-4">
            <a href="{{ $downloadLink }}" class="text-blue-600 underline" download>Favicon Generated - Download ZIP</a>
        </div>
    @endif

    <div wire:loading wire:target="convert">
        Converting your image...
    </div>
</div>
