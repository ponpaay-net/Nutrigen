@props(['child'])

<div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm mb-6">
    <h3 class="text-xs font-bold tracking-tight text-slate-800 uppercase tracking-widest mb-3 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-slate-400">
            <path fill-rule="evenodd" d="M4.25 12a.75.75 0 01.75-.75h14a.75.75 0 010 1.5H5a.75.75 0 01-.75-.75zm0-4.5a.75.75 0 01.75-.75h14a.75.75 0 010 1.5H5a.75.75 0 01-.75-.75zm0 9a.75.75 0 01.75-.75h14a.75.75 0 010 1.5H5a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
        </svg>
        Catatan Validator
    </h3>
    <textarea rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-sm focus:ring-teal-500 focus:border-teal-500 shadow-inner transition-colors focus:bg-white" placeholder="Tulis instruksi klinis atau umpan balik untuk kader terkait {{ $child['name'] }}..."></textarea>
</div>
