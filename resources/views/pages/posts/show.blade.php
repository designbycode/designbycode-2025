<x-app-layout>
    <div class="wrapper my-6">
        <div class="prose prose-invert min-w-full space-y-6 prose-h1:text-2xl md:prose-h1:text-4xl">
            <h1 class="text-balance text-foreground">{{ $post->title }}</h1>
            <a href="#">
                {{ $post->author->name }}
            </a>
            <p class="text-foreground/25">Estimate time to read is {{ $post->estimatedReadTime }} {{ Str::plural('minute', $post->estimatedReadTime) }}</p>
            <x-block :content="$post->content"/>
        </div>
    </div>
</x-app-layout>
