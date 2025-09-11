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
                cream: {
                    50: '#fefdf8',
                    100: '#fdf8e8',
                    200: '#f9edc4',
                    300: '#f4dfa0',
                    400: '#eecb7c',
                    500: '#e8b758',
                    600: '#d4a347',
                    700: '#b8913d',
                    800: '#9c7f33',
                    900: '#806d29',
                },
                coffee: {
                    50: '#f7f3f0',
                    100: '#ede4dc',
                    200: '#d4baa8',
                    300: '#bb9074',
                    400: '#a26640',
                    500: '#8b4513',
                    600: '#7a3f12',
                    700: '#693911',
                    800: '#583310',
                    900: '#472d0f',
                },
                latte: {
                    50: '#faf8f5',
                    100: '#f5f0ea',
                    200: '#e8d5c4',
                    300: '#dbba9e',
                    400: '#ce9f78',
                    500: '#c18452',
                    600: '#a8724a',
                    700: '#8f6042',
                    800: '#764e3a',
                    900: '#5d3c32',
                }
            },
            animation: {
                'float': 'float 3s ease-in-out infinite',
                'fade-in-up': 'fadeInUp 0.6s ease-out',
                'slide-in-left': 'slideInLeft 0.5s ease-out',
                'pulse-soft': 'pulse-soft 2s ease-in-out infinite',
            },
            backdropBlur: {
                xs: '2px',
            }
        },
    },

    plugins: [forms],
};
