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
<body class="antialiased bg-neutral-900 d text-neutral-200 min-h-screen overflow-x-clip selection:bg-primary-500 selection:text-primary-50">
<x-navigation/>
<main id="main">
    {{ $slot }}
</main>

@livewireScriptConfig
{{--@filamentScripts--}}
@stack('scripts')
</body>
</html>
