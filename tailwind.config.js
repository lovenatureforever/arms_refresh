/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "node_modules/@frostui/tailwindcss/**/*.js",
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        // './vendor/rappasoft/laravel-livewire-tables/resources/views/**/*.blade.php',
    ],
    darkMode: ['class', '[data-mode="dark"]'],
    theme: {
        container: {
            center: true,
        },
        fontFamily: {
            'base': ['Inter', 'sans-serif'],
        },
        extend: {
            colors: {
                'primary': '#3073F1',
                'secondary': '#68625D',
                'success': '#1CB454',
                'warning': '#E2A907',
                'info': '#0895D8',
                'danger': '#E63535',
                'light': '#eef2f7',
                'dark': '#313a46',
            },
        },
    },
    safelist: [
        "sm:max-w-sm",
        "sm:max-w-md",
        "md:max-w-lg",
        "md:max-w-xl",
        "lg:max-w-2xl",
        "lg:max-w-3xl",
        "xl:max-w-4xl",
        "xl:max-w-5xl",
        "2xl:max-w-6xl",
        "2xl:max-w-7xl",
    ],

    plugins: [
        require('@frostui/tailwindcss/plugin'),
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
    ],
}

