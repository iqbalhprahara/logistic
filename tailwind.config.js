const defaultTheme = require('tailwindcss/defaultTheme')
/** @type {import('tailwindcss').Config} */
export default {
    presets: [
        require('./vendor/wireui/wireui/tailwind.config.js'),
    ],
    content: [
        "./vendor/wireui/wireui/resources/**/*.blade.php",
        "./vendor/wireui/wireui/ts/**/*.ts",
        "./vendor/wireui/wireui/src/View/**/*.php",
        "./resources/views/**/landing/*.blade.php",
        "./resources/views/**/landing/*.js",
        "./node_modules/flowbite/**/*.js"
    ],
    safelist: [
        'w-64',
        'w-1/2',
        'rounded-l-lg',
        'rounded-r-lg',
        'bg-gray-200',
        'grid-cols-4',
        'grid-cols-7',
        'h-6',
        'leading-6',
        'h-9',
        'leading-9',
        'shadow-lg',
        'bg-opacity-50',
    ],
    darkMode: 'false',
    theme: {
        extend: {
            fontFamily: {
                'sans': ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'system-ui', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'Noto Sans', 'sans-serif', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'],
                'body': ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'system-ui', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'Noto Sans', 'sans-serif', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'],
                'mono': ['ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', 'Liberation Mono', 'Courier New', 'monospace']
            },
            colors: {
                'primary': {
                    '50': '#effbfc',
                    '100': '#d6f3f7',
                    '200': '#b2e6ef',
                    '300': '#7dd3e3',
                    '400': '#41b7cf',
                    '500': '#259bb5',
                    '600': '#227d98',
                    '700': '#246c84',
                    '800': '#245466',
                    '900': '#224757',
                    '950': '#112e3b',
                },
                'secondary': {
                    '50': '#f5f8f7',
                    '100': '#dee9e7',
                    '200': '#bcd3cd',
                    '300': '#93b5af',
                    '400': '#6a928c',
                    '500': '#527a74',
                    '600': '#40615d',
                    '700': '#364f4c',
                    '800': '#2e4140',
                    '900': '#293837',
                    '950': '#141f1e',
                },
                'accent': {
                    '50': '#fefaec',
                    '100': '#fcf0c9',
                    '200': '#fae39b',
                    '300': '#f6c953',
                    '400': '#f4b42b',
                    '500': '#ee9312',
                    '600': '#d26f0d',
                    '700': '#af4d0e',
                    '800': '#8e3c12',
                    '900': '#753212',
                    '950': '#431805',
                },
                "pg-primary":  {
                    '50': '#effbfc',
                    '100': '#d6f3f7',
                    '200': '#b2e6ef',
                    '300': '#7dd3e3',
                    '400': '#41b7cf',
                    '500': '#259bb5',
                    '600': '#227d98',
                    '700': '#246c84',
                    '800': '#245466',
                    '900': '#224757',
                    '950': '#112e3b',
                },
            },
            transitionProperty: {
                'width': 'width'
            },
            textDecoration: ['active'],
            minWidth: {
                'kanban': '28rem'
            },
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
        require("@tailwindcss/forms")({
            strategy: 'class',
        }),
        require('@tailwindcss/aspect-ratio'),
        require('flowbite/plugin')
    ],
}

