<div x-data>
    <x-hero/>
    <div class="wrapper">
        <div x-countup="{end: 2000}">0</div>
        <div x-countup="{end: 1000}">0</div>
        <span x-countup="{
        start: 50,
        end: 5100,
        duration: 2500,
        prefix: '$',
        suffix: ' USD',
        decimals: 2,
        tolerance: 150
    }"></span>
    </div>
</div>
