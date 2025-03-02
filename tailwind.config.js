import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: "var(--color-primary)",
                secondary: "var(--color-secondary)",
                accent: "var(--color-accent)",
            },

            screens: {
                'xs': '475px', // For smaller screens
                'sm': '640px', // Small devices (default)
                'md': '768px', // Medium devices
                'lg': '1024px', // Large devices
                'xl': '1280px', // Extra large devices
                '2xl': '1536px', // Double extra large devices
                '3xl': '1600px', // For very large screens
            },
            
        },
    },
    safelist: [
        {
           pattern: /max-w-(sm|md|lg|xl|2xl|3xl|4xl|5xl|6xl|7xl)/,
           variants: ['sm', 'md', 'lg', 'xl', '2xl'],
        },
     ],
     

    plugins: [forms],
};
