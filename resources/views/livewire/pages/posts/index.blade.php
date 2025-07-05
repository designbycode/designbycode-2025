<div>
    <div class="flex justify-end mt-2 items-center">
        <x-heroicon-s-magnifying-glass class="size-5 translate-x-8 pointer-events-none"/>
        <input class="pl-10 pr-2 py-2 my-2 rounded-md flex border border-primary focus:outline-primary focus:border-primary" type="text"
               wire:model.live="search">
    </div>

    <x-posts>
        @foreach($posts as $post)
            @if($loop->iteration % 5 == 0)
                <x-posts.ads/>
            @endif
            <x-posts.card :$post/>
        @endforeach
    </x-posts>
</div>
