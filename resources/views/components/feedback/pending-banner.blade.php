@props(['message' => 'Sedang ditinjau.', 'class' => ''])

<div {{ $attributes->merge(['class' => "bg-amber-50 border border-amber-100 rounded-[20px] p-3 flex items-start space-x-3 shadow-sm transform transition-all duration-300 $class"]) }}>
    <div class="bg-amber-200 text-amber-700 rounded-full p-1 mt-0.5 flex-shrink-0">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    </div>
    <p class="text-[12.5px] font-bold text-amber-900 leading-snug">
        {{ $message }}
    </p>
</div>
