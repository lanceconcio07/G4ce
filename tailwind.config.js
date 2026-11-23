import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Manrope', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50:  '#F0F7FF',
                    100: '#E0EFFF',
                    200: '#BAE0FF',
                    300: '#7CC4FA',
                    400: '#38A3F8',
                    500: '#0E85F3',
                    600: '#0267D2',
                    700: '#0252AA',
                    800: '#06468B',
                    900: '#0B3B73',
                    950: '#08254B',
                },
                navy: {
                    50:  '#F8FAFC',
                    100: '#F1F5F9',
                    200: '#E2E8F0',
                    300: '#CBD5E1',
                    400: '#94A3B8',
                    500: '#64748B',
                    600: '#475569',
                    700: '#334155',
                    800: '#1E293B',
                    900: '#0F172A',
                    950: '#0A0F1D',
                },
            },
            boxShadow: {
                'subtle': '0 1px 2px 0 rgba(15, 23, 42, 0.04)',
                'elevation-low': '0 1px 3px 0 rgba(15, 23, 42, 0.06), 0 1px 2px -1px rgba(15, 23, 42, 0.04)',
                'elevation-md': '0 4px 14px -1px rgba(15, 23, 42, 0.07), 0 2px 6px -2px rgba(15, 23, 42, 0.04)',
                'elevation-lg': '0 12px 28px -4px rgba(15, 23, 42, 0.08), 0 4px 10px -3px rgba(15, 23, 42, 0.04)',
                'elevation-xl': '0 24px 48px -12px rgba(15, 23, 42, 0.12), 0 8px 16px -4px rgba(15, 23, 42, 0.04)',
                'card-hover': '0 10px 25px -3px rgba(2, 103, 210, 0.08), 0 4px 10px -2px rgba(15, 23, 42, 0.04)',
            },
            animation: {
                'fade-in': 'fadeIn 0.25s cubic-bezier(0.16, 1, 0.3, 1)',
                'slide-up': 'slideUp 0.35s cubic-bezier(0.16, 1, 0.3, 1)',
                'pulse-soft': 'pulseSoft 2s ease-in-out infinite',
            },
            keyframes: {
                fadeIn:    { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                slideUp:   { '0%': { opacity: '0', transform: 'translateY(10px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                pulseSoft: { '0%,100%': { opacity: '1' }, '50%': { opacity: '.65' } },
            },
        },
    },

    plugins: [forms, typography],
};
