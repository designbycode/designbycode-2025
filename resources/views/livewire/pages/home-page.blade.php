<div x-data>
    <x-hero/>

    <div class="wrapper text-6xl font-black grid grid-cols-3">
        <div x-countup="{end: 2000}">0</div>
        <div x-countup="{end: 1000}">0</div>
        <span x-countup="{
        end: 100,
        suffix: '%',
        decimals: 0,
        tolerance: 0
    }"></span>
    </div>
</div>
