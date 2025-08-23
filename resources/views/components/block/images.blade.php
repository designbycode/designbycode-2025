<div class="not-prose grid grid-cols-1 md:grid-cols-2 gap-4 ">
    @foreach($images as $image)
        <div class="aspect-video w-full">
            <img class="w-full rounded-md shadow-md" src="{{ $image->getFullUrl('main') }}" alt="{{ $alt }}">
        </div>
    @endforeach
</div>
