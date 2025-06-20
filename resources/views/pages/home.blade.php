<x-app-layout>
    <x-hero/>
    <div class="wrapper">
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
