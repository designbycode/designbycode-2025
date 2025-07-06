<div class="wrapper my-6">
    <h1 class="text-5xl tracking-tight font-bold mb-4 text-balance text-foreground">
        Tutorials
    </h1>
    <div class="flex justify-end mt-2 items-center">
        <x-heroicon-s-magnifying-glass class="size-5 translate-x-8 pointer-events-none"/>
        <input class="pl-10 pr-2 py-2 my-2 bg-background-lighter rounded-md flex border border-primary focus:outline-primary focus:border-primary" type="text"
               wire:model.live="search">
    </div>
    @unless(empty($chunks))
        <x-posts>
            @for($chunk = 0; $chunk < $page; $chunk++)
                <livewire:posts.posts-chunk :ids="$chunks[$chunk]" :key="$chunk"/>
                <x-posts.ads/>
            @endfor

            @if($this->hasMorePages())
                <x-posts.card-skeleton x-intersect.margin.300px="$wire.loadMore"/>
            @endif
        </x-posts>
    @endunless
    @if(empty($chunks) && !empty($search))
        <div class="grid place-content-center min-h-32 ">
            <p class="text-lg">No results found for
                <mark class="bg-primary/20 text-primary font-semibold">{{ $search }}</mark>
                .
            </p>
        </div>
    @elseif(empty($chunks) && empty($search))
        <div class="grid place-content-center min-h-32 ">
            <p class="text-lg">No posts available at the moment.</p>
        </div>
    @endif
</div>
