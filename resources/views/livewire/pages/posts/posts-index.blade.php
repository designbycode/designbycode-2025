<div class="wrapper my-6">
    <h1 class="text-5xl tracking-tight font-bold mb-4 text-balance text-foreground">
        Tutorials
    </h1>

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

</div>
