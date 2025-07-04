<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="min-h-screen scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>


    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @filamentStyles
</head>
<body class="antialiased bg-background text-foreground flex flex-col pt-18 min-h-screen overflow-x-clip selection:bg-primary selection:text-white">
<a href="#main" class="sr-only focus:not-sr-only">
    Go to content
</a>
<x-navigation/>
<div aria-hidden="true" class="fixed strips -z-10 left-0 opacity-5 w-1 md:w-10 inset-y-0 border-r border-foreground"></div>
<div aria-hidden="true" class="fixed strips -z-10 right-0 opacity-5 w-1 md:w-10 inset-y-0 border-l border-foreground"></div>
<main id="main" class="flex-1">
    {{ $slot }}
</main>
<x-footer/>

@livewireScriptConfig
@filamentScripts
@stack('scripts')
</body>
</html>
