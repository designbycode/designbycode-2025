@props([
    'mobile' => false
])
@if(!$mobile)
    <div x-data="themeSwitcher()" class="relative">
        <button @click="dropdownOpen = !dropdownOpen"
                title="Select theme"
                class="flex items-center justify-center size-8 hover:text-primary focus:outline-none rounded-md hover:bg-background">
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
        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition:enter="transition ease-out duration-100 transform"
             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75 transform" x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="absolute right-0 mt-2 w-40 bg-background border border-background-lighter/10 rounded-md shadow-lg py-1 z-50" x-cloak>
            <button @click.prevent="setTheme('light')" :class="{'bg-background-lighter/10 text-primary': theme === 'light'}"
                    class="flex items-center w-full px-4 py-2 text-sm hover:bg-background-lighter/10 hover:text-primary">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Light
            </button>
            <button @click.prevent="setTheme('dark')" :class="{'bg-background-lighter/10 text-primary': theme === 'dark'}"
                    class="flex items-center w-full px-4 py-2 text-sm hover:bg-background-lighter/10 hover:text-primary">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
                Dark
            </button>
            <button @click.prevent="setTheme('system')" :class="{'bg-background-lighter/10 text-primary': theme === 'system'}"
                    class="flex items-center w-full px-4 py-2 text-sm hover:bg-background-lighter/10 hover:text-primary">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                System
            </button>
        </div>
    </div>

@else
    <div x-data="themeSwitcher()" class="pt-4 mt-4 border-t border-background-darker/10">
        <h3 class="px-3 text-xs font-semibold uppercase text-gray-500 tracking-wider mb-2">Theme</h3>
        <button @click.prevent="setTheme('light')" :class="{'bg-background-lighter/10 text-primary': theme === 'light'}"
                class="flex items-center w-full px-3 py-2 text-sm rounded-md hover:bg-background-lighter/10 hover:text-primary">
            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            Light
        </button>
        <button @click.prevent="setTheme('dark')" :class="{'bg-background-lighter/10 text-primary': theme === 'dark'}"
                class="flex items-center w-full px-3 py-2 text-sm rounded-md hover:bg-background-lighter/10 hover:text-primary">
            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
            </svg>
            Dark
        </button>
        <button @click.prevent="setTheme('system')" :class="{'bg-background-lighter/10 text-primary': theme === 'system'}"
                class="flex items-center w-full px-3 py-2 text-sm rounded-md hover:bg-background-lighter/10 hover:text-primary">
            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            System
        </button>
    </div>

@endif
