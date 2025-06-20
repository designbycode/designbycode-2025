<div>
    @foreach($content as $block)
        @switch($block['type'])
            @case('prism')
                <x-dynamic-component
                    component="{{ str('block.'.$block['type'])->lower() }}"
                    :code="$block['data']['content']"
                    :language="$block['data']['language']" class="mt-4"/>
                @break
            @case('markdown')
            @case('rich-editor')
                <x-dynamic-component
                    component="{{ str('block.'.$block['type'])->lower() }}"
                    :content="$block['data']['content']"
                    class="mt-4"/>
                @break
            @default
                <div>Block not found</div>
        @endswitch
    @endforeach

</div>
