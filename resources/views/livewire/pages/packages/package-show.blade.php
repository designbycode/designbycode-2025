<div class="wrapper my-6 space-y-3">
    <x-filament::breadcrumbs class="flex shrink-0" :breadcrumbs="[
            '/' => 'Home',
            '/packages' => 'Packages',
        ]"/>
    <div @class(['prose prose-p:text-foreground prose-code:bg-background-darker prose-code:p-0.5 prose-code:rounded-md prose-headings:text-foreground-darker
        prose-strong:text-foreground-darker
        min-w-full space-y-6
        prose-h1:text-2xl
        md:prose-h1:text-4xl'])>
        <h1 class="text-5xl tracking-tight font-bold mb-4 text-balance text-foreground">
            {{ $package->name }}
        </h1>

        <p class="text-foreground">
            Published: {{ $package->created_at->diffForHumans() }}
        </p>

        <p class="text-foreground/25">Estimate time to read is {{ $package->estimatedReadTime }} {{ Str::plural('minute', $package->estimatedReadTime) }}</p>
        <x-block :content="$package->content" :model="$package"/>
    </div>
</div>
