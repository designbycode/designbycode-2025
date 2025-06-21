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
<div aria-hidden="true" class="fixed strips -z-10 left-0 w-2.5 md:w-10 opacity-50 inset-y-0 border-r border-gray-800"></div>
<div aria-hidden="true" class="fixed strips -z-10 right-0 w-2.5 md:w-10 opacity-50 inset-y-0 border-l border-gray-800"></div>
<main id="main">
    {{ $slot }}
</main>


@livewireScriptConfig
@filamentScripts
@stack('scripts')
</body>
</html>
