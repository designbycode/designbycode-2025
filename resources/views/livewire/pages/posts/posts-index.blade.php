<div class="wrapper my-6">
    <h1 class="text-5xl tracking-tight font-bold mb-4 text-balance text-foreground">
        Tutorials
    </h1>
    <div class="flex justify-end mt-2 items-center">
        <x-heroicon-s-magnifying-glass class="size-5 translate-x-8 pointer-events-none"/>
        <input class="pl-10 pr-2 py-2 my-2 bg-background-lighter rounded-md flex border border-primary focus:outline-primary focus:border-primary" type="text"
               wire:model.live="search">
    </div>
    @if($posts->count())
        <x-posts>
            @foreach($posts as $post)
                @if($loop->iteration % 5 == 0)
                    <x-posts.ads/>
                @endif
                <x-posts.card :$post/>
            @endforeach
        </x-posts>
    @else
        <div class="grid place-content-center min-h-32 ">
            <p class="text-lg">Not results found for
                <mark>{{ $search }}</mark>
            </p>
        </div>
    @endif
</div>
