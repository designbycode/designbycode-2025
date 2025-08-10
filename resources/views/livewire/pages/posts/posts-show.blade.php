<div class="wrapper my-6 space-y-3">
    <x-filament::breadcrumbs class="flex shrink-0" :breadcrumbs="[
            '/' => 'Home',
            '/articles' => 'Articles',
        ]"/>
    <div @class(['prose prose-p:text-foreground prose-code:bg-background-darker prose-code:p-0.5 prose-code:rounded-md prose-headings:text-foreground-darker
        prose-strong:text-foreground-darker
        min-w-full space-y-6
        prose-h1:text-2xl
        md:prose-h1:text-4xl'])>
        <div
            class="border border-primary/10 mb-3 rounded-lg relative">

            <img class="rounded-lg w-full  m-0! max-w-full block" src="{{ $post->getFirstMediaUrl('posts', 'main') }}" alt="Featured Image for {{ $post->title
            }}">
        </div>


        <h1 class="text-5xl tracking-tight font-bold mb-4 text-balance text-foreground">
            {{ $post->title }}
        </h1>
        <a href="#" class="text-foreground flex space-x-2 items-center">
            <x-avatar :author="$post->author"/>
            <span>
                {{ $post->author->name }}
            </span>
        </a>
        <p class="text-foreground">
            Published: {{ $post->published_at->diffForHumans() }}
        </p>
        <p class="text-foreground/25">Estimate time to read is {{ $post->estimatedReadTime }} {{ Str::plural('minute', $post->estimatedReadTime) }}</p>
        <x-block :content="$post->content"/>
    </div>
</div>
