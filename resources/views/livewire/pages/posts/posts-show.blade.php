<div class="wrapper my-6">
    <div @class(['prose prose-p:text-foreground prose-code:bg-background-darker prose-code:p-0.5 prose-code:rounded-md prose-headings:text-foreground-darker
        prose-strong:text-foreground-darker
        min-w-full space-y-6
        prose-h1:text-2xl
        md:prose-h1:text-4xl'])>
        <h1 class="text-5xl tracking-tight font-bold mb-4 text-balance text-foreground">
            {{ $post->title }}
        </h1>
        <a href="#">
            {{ $post->author->name }}
        </a>
        <p class="text-foreground/25">Estimate time to read is {{ $post->estimatedReadTime }} {{ Str::plural('minute', $post->estimatedReadTime) }}</p>
        <x-block :content="$post->content"/>
    </div>
</div>
