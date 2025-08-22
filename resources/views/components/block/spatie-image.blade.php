<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($images as $image)
        {{ dump($image) }}
        <div>
            <img class="w-full rounded-md shadow-md" src="{{ $image->getUrl('main') }}" alt="{{ $alt }}">

            @if(count($image->custom_properties) > 0)
                <div class="mt-2 text-sm">
                    <p class="font-bold">Custom Properties:</p>
                    <ul>
                        @foreach($image->custom_properties as $key => $value)
                            <li><strong>{{ $key }}:</strong> {{ is_array($value) ? json_encode($value) : $value }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endforeach
</div>
