export default function themeSwitcher() {
    return {
        theme: 'system', // User's selected preference: 'light', 'dark', or 'system'
        dropdownOpen: false,

        init() {
            this.theme = localStorage.getItem('theme') || 'system';
            this.applyThemePreference();

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (localStorage.getItem('theme') === 'system') {
                    this._updatePageThemeVisuals(e.matches);
                }
            });

            // Ensure the theme is applied correctly on initial load when 'system' is selected
            if (this.theme === 'system') {
                this._updatePageThemeVisuals(window.matchMedia('(prefers-color-scheme: dark)').matches);
            }
        },

        _updatePageThemeVisuals(isSystemDark) {
            if (isSystemDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },

        applyThemePreference() {
            if (this.theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else if (this.theme === 'light') {
                document.documentElement.classList.remove('dark');
            } else { // 'system'
                this._updatePageThemeVisuals(window.matchMedia('(prefers-color-scheme: dark)').matches);
            }
        },

        setTheme(newTheme) {
            this.theme = newTheme;
            localStorage.setItem('theme', this.theme);
            this.applyThemePreference();
            if (typeof this.dropdownOpen !== 'undefined') { // Check if dropdownOpen exists (for desktop)
                this.dropdownOpen = false;
            }
            // No need to close mobile menu here as theme selection in mobile is separate
        },

        get currentButtonIcon() {
            return this.theme;
        }
    }
}
