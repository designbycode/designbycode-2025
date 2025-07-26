{{--<button onclick="speak()">Listen to Article</button>--}}
{{--<script>--}}
{{--    function speak() {--}}
{{--        const content = document.querySelector('.blog-content').innerText;--}}
{{--        const utterance = new SpeechSynthesisUtterance(content);--}}
{{--        window.speechSynthesis.speak(utterance);--}}
{{--    }--}}
{{--</script>--}}
<div class="prose prose-invert  max-w-full space-y-4 blog-content">
    @foreach($content as $block)
        @switch($block['type'])
            @case('prism')
                <x-dynamic-component
                    component="{{ str('block.'.$block['type'])->lower() }}"
                    :code="$block['data']['content']"
                    :language="$block['data']['language']" class="mt-4 not-prose"/>
                @break
            @case('markdown')
            @case('rich-editor')
                <x-dynamic-component
                    component="{{ str('block.'.$block['type'])->lower() }}"
                    :content="$block['data']['content']"
                    class="mt-4 "/>
                @break
            @case('image')

                <x-dynamic-component
                    component="{{ str('block.'.$block['type'])->lower() }}"
                    :url="$block['data']['url']"
                    :alt="$block['data']['alt']"
                    class="mt-4 "/>
                @break

            @default
                <div>Block not found</div>
        @endswitch
    @endforeach

</div>
