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
                // Design System v2 — Plus Jakarta Sans (bukan Inter)
                sans: ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
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

        'border-emerald-100', 'border-emerald-200',
        'border-amber-100',   'border-amber-200',
        'border-rose-100',    'border-rose-200',
        'border-slate-100',   'border-slate-200',
    ],
}
