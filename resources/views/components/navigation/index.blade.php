<div class="fixed top-0 inset-x-1 md:inset-x-10 z-50 backdrop-blur-sm bg-gray-800/80 border-b border-gray-700">
    <nav class="wrapper py-3 flex items-center justify-between transition-all ">
        <a class="text-2xl font-semibold hover:text-primary-500  duration-150" wire:navigate href="{{ route('home') }}">{{ config('app.name') }}</a>
        <div class="flex space-x-4 items-center text-sm text-neutral-200 group  duration-150">
            @foreach($menu as $key => $item)
                <a wire:navigate
                   @class([
                    'whitespace-nowrap font-semibold duration-150  text-shadow-black/50
                     hover:text-primary-200 text-shadow-sm text-shadow-black/50',
                    'text-primary-500' => request()->routeIs($item->active),
                        ])
                   href="{{ route($item->route) }}">{{$item->name }}</a>
            @endforeach
        </div>
    </nav>
</div>
