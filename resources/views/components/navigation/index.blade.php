<div x-data="navigation()" x-headroom="{ offset: 100, tolerance: 10 }"
     class=" fixed top-0 inset-x-1 md:inset-x-10 z-50 backdrop-blur-sm bg-background/90 border-b border-background-darker/10">
    <nav class="wrapper py-3 flex items-center justify-between transition-all ">
        <a class="text-2xl font-semibold hover:text-primary " wire:navigate href="{{ route('home') }}">{{ config('app.name') }}</a>

        <!-- Desktop Menu -->
        <div class="hidden md:flex space-x-4 items-center text-sm group ">
            @foreach($menu as $key => $item)
                <a wire:navigate.hover
                   @class([
                    'whitespace-nowrap font-semibold  hover:text-primary',
                    'text-primary' => request()->routeIs($item->active),
                   ])
                   href="{{ route($item->route) }}">{{ $item->name }}</a>
            @endforeach
            <!-- Theme Selector Dropdown -->
            <x-navigation.theme-switcher/>
            <!-- End Theme Selector Dropdown -->
        </div>

        <!-- Mobile Menu Button -->
        <div class="md:hidden ">
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-primary focus:outline-none focus:text-primary">
                <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu Panel -->
    <div x-show="mobileMenuOpen"
         @click.away="mobileMenuOpen = false"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-y-0 right-0  min-h-screen w-64 bg-background shadow-xl z-100 p-4 md:hidden"
         x-cloak>
        <div class="flex justify-end items-center mb-6">
            {{--            <span class="text-xl font-semibold">{{ config('app.name') }}</span>--}}
            <button @click="mobileMenuOpen = false" class="text-gray-500 hover:text-primary">
                <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <nav class="flex flex-col space-y-2">
            @foreach($menu as $key => $item)
                <a wire:navigate.hover
                   @click="mobileMenuOpen = false"
                   @class([
                    'px-3 py-2 rounded-md text-base font-medium  hover:text-primary hover:bg-background-lighter/5',
                    'text-primary bg-background-lighter/10' => request()->routeIs($item->active),
                   ])
                   href="{{ route($item->route) }}">{{ $item->name }}</a>
            @endforeach
            <!-- Theme Selector (Simplified for Mobile Menu) -->
            <x-navigation.theme-switcher mobile="true"/>
        </nav>
    </div>


</div>

<script>
    function navigation() {
        return {
            mobileMenuOpen: false,
        }
    }
</script>
