
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        cocoa: {
                            950: '#150a06', // Deep espresso
                            900: '#23120b', // Rich chocolate text
                            800: '#321a10',
                            700: '#48271a',
                            600: '#633726',
                        },
                        gold: {
                            50:  '#fefce8', // Creamy warm yellow background
                            100: '#fef9c3', // Soft yellow card fill
                            200: '#fef08a', // Vibrant pastel yellow
                            300: '#fde047', // Sunlit yellow
                            400: '#facc15', // Donat Menak signature yellow
                            500: '#eab308', // Rich golden amber
                            600: '#ca8a04',
                        }
                    },
                    boxShadow: {
                        'glow-gold': '0 10px 30px -5px rgba(234, 179, 8, 0.35)',
                        'card-yellow': '0 8px 25px -6px rgba(202, 138, 4, 0.18)',
                    }
                }
            }
        }
    