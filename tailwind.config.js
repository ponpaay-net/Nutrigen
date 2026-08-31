/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],

    theme: {
        extend: {},
    },

    plugins: [],

    /*
    |--------------------------------------------------------------------------
    | Safelist — Dynamic Color Classes
    |--------------------------------------------------------------------------
    |
    | Tailwind's JIT engine cannot detect classes built via string interpolation
    | (e.g. `bg-{{ $colorClass }}-500`). These classes MUST be safelisted or
    | they will be stripped from the production build.
    |
    | Status color palette used across the Kader module:
    |   - emerald: Normal / Sudah Diukur
    |   - amber:   Gizi Kurang / Warning
    |   - rose:    Stunting / Danger
    |   - slate:   Default / Neutral
    |
    */
    safelist: [
        // Background fills
        'bg-emerald-50', 'bg-emerald-100', 'bg-emerald-500',
        'bg-amber-50',   'bg-amber-100',   'bg-amber-500',
        'bg-rose-50',    'bg-rose-100',    'bg-rose-500',
        'bg-slate-50',   'bg-slate-100',   'bg-slate-500',

        // Text colors
        'text-emerald-700', 'text-emerald-600',
        'text-amber-700',   'text-amber-600',
        'text-rose-700',    'text-rose-600',
        'text-slate-700',   'text-slate-600',

        // Border colors
        'border-emerald-100', 'border-emerald-200',
        'border-amber-100',   'border-amber-200',
        'border-rose-100',    'border-rose-200',
        'border-slate-100',   'border-slate-200',
    ],
}