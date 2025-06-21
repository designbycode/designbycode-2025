<x-app-layout>
    <div class="wrapper my-6">
        <h1 class="text-2xl font-bold tracking-tighter">Tutorials</h1>
        <div class="relative md:pl-28 md:ml-4 md:border-l md:border-gray-700/25">
            @foreach($posts as $post)
                <div class="post-item my-4 p-4 relative rounded-lg space-y-2 group isolate transition-all duration-150">
                    <h2 class="text-md font-semibold">{{ $post->title }}</h2>
                    <span class="before:size-3 before:hidden italic md:before:inline-block before:rounded-full before:border-2 before:content-[]
                    before:border-primary-500 flex
                    items-center before:mr-2 text-xs md:absolute md:-left-28 md:-translate-x-1.5 md:top-5 pointer-events-none text-gray-500">{{
                    $post->created_at->format('F j, Y') }}</span>
                    <p class="text-gray-500 text-sm line-clamp-4">{{ $post->description }}</p>
                    <div class="flex items-center justify-between text-sm ">
                        <span class="flex items-center space-x-1 text-gray-500 ">
                            <x-heroicon-o-clock class="size-4 "/>
                            <i>Time to read is {{ $post->estimatedReadTime }} {{ Str::plural('minute', $post->estimatedReadTime) }}</i>
                        </span>
                        <a wire:navigate href="{{ route('posts.show', $post) }}" class="text-primary-500 hover:underline inline-block px-2 py-1">
                        <span
                            class="group-hover:border group-hover:border-gray-700/25 group-hover:bg-gray-900/5 absolute inset-0 rounded-lg -z-10 pointer-events-none"></span>
                            Read more
                        </a>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</x-app-layout>
