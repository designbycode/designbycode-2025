@props(['post'])

<div {{ $attributes->merge(['class' => 'post-item my-4 p-4 relative slide-in rounded-lg space-y-2 group isolate transition-all duration-150']) }}>
    <h2 class="text-md font-semibold text-balance">
        <a wire:navigate.hover href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
    </h2>
    <span class="before:size-3 before:hidden italic md:before:inline-block before:rounded-full before:border-2 before:content-[]
                    before:border-primary before:bg-background flex
                    items-center before:mr-2 text-xs md:absolute md:-left-28 md:-translate-x-1.5 md:top-5 pointer-events-none text-foreground/50">{{
                    $post->created_at->format('F j, Y') }}</span>
    <p class="text-foreground/50 text-sm line-clamp-4">{{ Str::cleanAiText($post->description) }}</p>
    <div class="flex items-center justify-between text-xs ">
                        <span class="flex items-center space-x-1  text-foreground/50 ">
                            <x-heroicon-o-clock class="size-3 "/>
                            <i>Time to read is {{ $post->estimatedReadTime }} {{ Str::plural('minute', $post->estimatedReadTime) }}</i>
                        </span>
        <span class="text-foreground/50">Unique Views ({{ $post->visit_count_total     }})</span>
        <a wire:navigate.hover href="{{ route('posts.show', $post) }}" class="text-white rounded-md px-4 py-2 bg-primary  hover:bg-primary/75
                        inline-flex
                        items-center ">
                        <span
                            class="group-hover:border group-hover:border-foreground/10 group-hover:bg-foreground/5 absolute inset-0 rounded-lg -z-10
                            pointer-events-none"></span>
            <span>Read more</span>
        </a>
    </div>
</div>
