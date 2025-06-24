<div class="fixed top-0 inset-x-1 md:inset-x-10 z-50 backdrop-blur-sm bg-background/90 border-b border-background-darker/10">
    <nav class="wrapper py-3 flex items-center justify-between transition-all ">
        <a class="text-2xl font-semibold hover:text-primary  duration-150" wire:navigate href="{{ route('home') }}">{{ config('app.name') }}</a>
        <div class="flex space-x-4 items-center text-sm group  duration-150">
            @foreach($menu as $key => $item)
                <a wire:navigate
                   @class([
                    'whitespace-nowrap font-semibold duration-150
                     hover:text-primary',
                    'text-primary' => request()->routeIs($item->active),
                        ])
                   href="{{ route($item->route) }}">{{$item->name }}</a>
            @endforeach

            <!-- Theme Selector Dropdown -->
            <div x-data="themeSwitcher()" class="relative">
                <button @click="dropdownOpen = !dropdownOpen"
                        title="Select theme"
                        class="flex items-center justify-center size-8 hover:text-primary focus:outline-none rounded-md
                        hover:bg-background">
                    <template x-if="currentButtonIcon === 'light'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </template>
                    <template x-if="currentButtonIcon === 'dark'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                    </template>
                    <template x-if="currentButtonIcon === 'system'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </template>
                </button>

                <div x-show="dropdownOpen"
                     @click.away="dropdownOpen = false"
                     x-transition:enter="transition ease-out duration-100 transform"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75 transform"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-40 bg-background border border-background-lighter/10 rounded-md shadow-lg py-1 z-50"
                     x-cloak>
                    <a @click.prevent="setTheme('light')" href="#"
                       :class="{'bg-background-lighter/10 text-primary': theme === 'light'}"
                       class="flex items-center px-4 py-2 text-sm hover:bg-background-lighter/10 hover:text-primary">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Light
                    </a>
                    <a @click.prevent="setTheme('dark')" href="#"
                       :class="{'bg-background-lighter/10 text-primary': theme === 'dark'}"
                       class="flex items-center px-4 py-2 text-sm hover:bg-background-lighter/10 hover:text-primary">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                        Dark
                    </a>
                    <a @click.prevent="setTheme('system')" href="#"
                       :class="{'bg-background-lighter/10 text-primary': theme === 'system'}"
                       class="flex items-center px-4 py-2 text-sm hover:bg-background-lighter/10 hover:text-primary">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        System
                    </a>
                </div>
            </div>
            <!-- End Theme Selector Dropdown -->

        </div>
    </nav>
</div>

<script>
    function themeSwitcher() {
        return {
            theme: 'system', // User's selected preference: 'light', 'dark', or 'system'
            dropdownOpen: false,

            init() {
                this.theme = localStorage.getItem('theme') || 'system';
                this.applyThemePreference();

                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                    // Only react to system changes if the user's preference is 'system'
                    if (localStorage.getItem('theme') === 'system') {
                        this._updatePageThemeVisuals(e.matches);
                    }
                });
            },

            // This function updates the actual page visuals (adds/removes .dark class)
            _updatePageThemeVisuals(isSystemDark) {
                if (isSystemDark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            },

            // This function is called on init and when the user changes their preference
            applyThemePreference() {
                if (this.theme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else if (this.theme === 'light') {
                    document.documentElement.classList.remove('dark');
                } else { // 'system'
                    // For system, remove any forced class and let media query drive visuals
                    // The event listener will handle initial state and changes for system.
                    this._updatePageThemeVisuals(window.matchMedia('(prefers-color-scheme: dark)').matches);
                }
            },

            setTheme(newTheme) {
                this.theme = newTheme;
                localStorage.setItem('theme', this.theme);
                this.applyThemePreference();
                this.dropdownOpen = false;
            },

            // Computed property for the button icon display based on user's *preference*
            get currentButtonIcon() {
                return this.theme;
            }
        }
    }
</script>
