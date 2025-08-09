<div class="wrapper my-6">
    <h1 class="text-5xl tracking-tight font-bold mb-4 text-balance text-foreground">
        Articles
    </h1>
    <div class="flex justify-end mt-2 items-center">
        <x-heroicon-s-magnifying-glass class="size-5 translate-x-8 pointer-events-none"/>
        <input class="pl-10 pr-2 py-2 my-2 bg-background-lighter rounded-md flex border border-primary focus:outline-primary focus:border-primary" type="text"
               wire:model.live="search">
    </div>
    @if(count($this->chunks) > 0)
        <x-posts>
            @foreach($this->getCurrentChunks() as $chunkIds)
                <livewire:pages.posts.chunk :ids="$chunkIds" :key="md5(implode(',', $chunkIds))"/>
                <x-posts.ads style="animation-delay: {{ ($chunkSize + 1 ) * 0.1 }}s"/>
            @endforeach

            @if($this->hasMorePages())
                <x-posts.card-skeleton x-intersect.margin.300px="$wire.loadMore"/>
            @endif
        </x-posts>
    @else
        <h2 class="text-2xl font-bold">Sorry no posts found.</h2>
        <p>Don't worry come back later for some awesome content.</p>
    @endif
</div>
