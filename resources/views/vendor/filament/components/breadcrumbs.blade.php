@php
    use Filament\Support\Icons\Heroicon;use Illuminate\View\ComponentAttributeBag;

    use function Filament\Support\generate_href_html;use function Filament\Support\generate_icon_html;
@endphp

@props([
    'breadcrumbs' => [],
])
<div class="mb-3 flex space-x-5 items-center">
    <nav {{ $attributes->class(['fi-breadcrumbs']) }}>
        <ol class="fi-breadcrumbs-list">
            @foreach ($breadcrumbs as $url => $label)
                <li class="fi-breadcrumbs-item">
                    @if (! $loop->first)
                        {{
                            generate_icon_html(Heroicon::ChevronRight, alias: 'breadcrumbs.separator', attributes: (new ComponentAttributeBag)->class([
                                'fi-breadcrumbs-item-separator fi-ltr',
                            ]))
                        }}

                        {{
                            generate_icon_html(Heroicon::ChevronLeft, alias: 'breadcrubs.separator.rtl', attributes: (new ComponentAttributeBag)->class([
                                'fi-breadcrumbs-item-separator fi-rtl',
                            ]))
                        }}
                    @endif

                    @if (is_int($url))
                        <span class="fi-breadcrumbs-item-label">
                        {{ $label }}
                    </span>
                    @else
                        <a
                            {{ generate_href_html($url) }}
                            class="fi-breadcrumbs-item-label"
                            wire:navigate.hover
                        >
                            {{ $label }}
                        </a>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>

    <span class="mask-l-from-20% h-2 flex flex-1 opacity-10 outline-1 border border-foreground rounded-lg strips"></span>
</div>
