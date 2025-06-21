<x-app-layout>
    <x-hero/>
    <div class="wrapper">

        <div x-data="{ input: 'Foo!', copied: false }">
            <button
                x-clipboard="input"
                @click="$clipboard(input).then(() => copied = true)"
            >
                Copy to Clipboard
            </button>

            <span x-show="copied" x-transition.duration.1000ms style="margin-left: 10px; color: green;">
        Copied!
    </span>
        </div>

        <x-block.prism language="javascript" code="hello world"/>

        <x-block.prism language="php">
            function hello() {
            echo "Hello World!";
            }
        </x-block.prism>


        <x-block.prism language="javascript">
            console.log('hello, world')
        </x-block.prism>


    </div>

</x-app-layout>
