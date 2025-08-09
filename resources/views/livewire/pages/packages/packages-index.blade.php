<div class="wrapper my-6">
    <h1 class="text-5xl tracking-tight font-bold mb-6 text-balance text-foreground">
        Packages
    </h1>

    <div class="grid gap-6 grid-cols-2">
        @if($packages->count())
            @foreach($packages as $package )
                <div class="bg-foreground/5 p-4 relative slide-in rounded-lg space-y-2 group isolate transition-all duration-150">
                    <h3 class="text-md font-semibold text-balance">{{ $package->name }}</h3>
                    <p class="text-foreground/50 text-sm line-clamp-3">{{ $package->description }}</p>
                    <a wire:navigate.hover href="{{ route('packages.show', $package) }}" class="text-white text-sm rounded-md px-4 py-2   bg-primary
                hover:bg-primary/75
                        inline-flex
                        items-center ">
                        <span>Read more</span>
                    </a>
                </div>
            @endforeach
        @else
            <div>
                <h2 class="text-2xl font-bold">Sorry no Packages found.</h2>
                <p>Don't worry come back later for some awesome packages.</p>
            </div>

        @endif
    </div>

</div>
