const defaultTheme = require('tailwindcss/defaultTheme')
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/views/**/*.blade.php",
    "./resources/views/**/*.js",
    "./node_modules/flowbite/**/*.js"
  ],
  darkMode: 'false',
  theme: {
    extend: {
        fontFamily: {
            'inter': ['Inter', ...defaultTheme.fontFamily.sans],
        },
        colors: {
            "primary": "#246c84",
            "secondary": "#6a928c",
            "accent": "#fae39b",
        }
    },
  },
  plugins: [require('flowbite/plugin')],
}

