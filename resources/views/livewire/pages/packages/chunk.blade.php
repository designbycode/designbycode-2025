<div>
    @foreach($packages as $index => $package)
        <x-packages.card style="animation-delay: {{ $index * 0.1 }}s" :$package :key="$package->id"/>
    @endforeach
</div>
