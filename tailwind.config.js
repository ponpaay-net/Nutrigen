/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                // Design System v2 — Plus Jakarta Sans with modern fallbacks
                sans: ['"Plus Jakarta Sans"', 'Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', '"Segoe UI"', 'Roboto', 'sans-serif'],
            },
            colors: {
                // Design System v1 alias — dipakai komponen Portal Ibu (legacy).
                // Dipetakan ke palet teal (brand NutriGen) agar konsisten.
                brand: '#0d9488', // teal-600
                mint: {
                    50: '#f0fdfa', 100: '#ccfbf1', 200: '#99f6e4', 300: '#5eead4',
                    400: '#2dd4bf', 500: '#14b8a6', 600: '#0d9488', 700: '#0f766e',
                    800: '#115e59', 900: '#134e4a',
                },
                peach: {
                    50: '#fffbeb', 100: '#fef3c7', 200: '#fde68a', 300: '#fcd34d',
                    400: '#fbbf24', 500: '#f59e0b', 600: '#d97706', 700: '#b45309',
                    800: '#92400e', 900: '#78350f',
                },
            },
            boxShadow: {
                'card-green': '0 8px 24px -4px rgba(13,148,136,0.3)',
            },
            animation: {
                'blob': 'blob 7s infinite',
                'float': 'float 6s ease-in-out infinite',
            },
            keyframes: {
                blob: {
                    '0%': { transform: 'translate(0px, 0px) scale(1)' },
                    '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                    '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                    '100%': { transform: 'translate(0px, 0px) scale(1)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-20px)' },
                }
            },
        },
    },

    plugins: [],

    /*
    |--------------------------------------------------------------------------
    | Safelist — Dynamic Color Classes
    |--------------------------------------------------------------------------
    | Tailwind's JIT engine cannot detect classes built via string interpolation.
    |
    | Design System v2:
    |   - teal (#0d9488): brand/primary
    |   - emerald: sukses  |  amber: perlu tindakan  |  rose: risiko  |  slate: netral
    */
    safelist: [
        'bg-teal-50', 'bg-teal-100', 'bg-teal-500', 'bg-teal-600', 'bg-teal-700',
        'text-teal-600', 'text-teal-700',
        'border-teal-100', 'border-teal-200', 'border-teal-300',
        'from-teal-50', 'to-teal-100',

        'bg-emerald-50', 'bg-emerald-100', 'bg-emerald-500',
        'bg-amber-50',   'bg-amber-100',   'bg-amber-500',
        'bg-rose-50',    'bg-rose-100',    'bg-rose-500',
        'bg-slate-50',   'bg-slate-100',   'bg-slate-500',

        'text-emerald-700', 'text-emerald-600',
        'text-amber-700',   'text-amber-600',
        'text-rose-700',    'text-rose-600',
        'text-slate-700',   'text-slate-600',

        // Dynamic KPI micro-interaction (ring + group-hover)
        'ring-teal-100', 'ring-emerald-100', 'ring-amber-100', 'ring-rose-100',
        'group-hover:bg-teal-50', 'group-hover:bg-emerald-50', 'group-hover:bg-amber-50', 'group-hover:bg-rose-50',
        'group-hover:text-teal-600', 'group-hover:text-emerald-600', 'group-hover:text-amber-600', 'group-hover:text-rose-600',

        'border-emerald-100', 'border-emerald-200',
        'border-amber-100',   'border-amber-200',
        'border-rose-100',    'border-rose-200',
        'border-slate-100',   'border-slate-200',
    ],
}
