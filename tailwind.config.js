const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './node_modules/flowbite/**/*.js'
    ],

    theme: {
        extend: {
            colors: {
                'rk-yellow': '[linear-gradient(#ffd370 0%, #f7ad07 50%)]',
                'rk-red': '#FF1296',
                'rk-blue-light': '#150035',
                'rk-blue-dark': '#0B001B',
                'rk-yellow-light': '#E6BF1C',
            },
            backgroundImage: (theme) => ({
                'rk-gradient-yellow': "linear-gradient(to bottom, #E6BF1C 30%, #f7ac03 70%)",
            }),
        },
        fontFamily: {
            'Poppins': ['Poppins', 'sans-serif'],
            'Nunito': ['Nunito', 'sans-serif'],
        },
        container: {
            center: true,
            padding: "1rem",
            screens: {
                lg: "1140px",
                xl: "1140px",
                "2xl": "1140px",
            }
        },
    },

    plugins: [require('@tailwindcss/forms'), require('flowbite/plugin')],
};
