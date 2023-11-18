import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/AppPanel/**/*.php',
        './resources/views/filament/app-panel/**/*.blade.php',
        './resources/views/filament/app-panel/**/*.js',
        './vendor/filament/**/*.blade.php',
        "./vendor/awcodes/filament-badgeable-column/resources/**/*.blade.php",
        "./vendor/filipfonal/filament-log-manager/resources/**/*.blade.php",
    ],
    theme: {
        extend: {
            colors: {
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
            },
        }
    }
}
