@props(['message' => '', 'icon' => '🏆'])

<x-ui.card padding="p-4" class="flex items-center space-x-4 bg-gradient-to-r from-blue-50 to-white border-blue-100 relative overflow-hidden">
    <div class="absolute -right-6 -top-6 w-20 h-20 bg-white rounded-full opacity-40"></div>
    <div class="w-12 h-12 rounded-[16px] bg-white shadow-sm flex items-center justify-center text-[22px] flex-shrink-0 border border-blue-50 z-10">
        {{ $icon }}
    </div>
    <p class="text-[13px] font-bold text-blue-900 leading-snug z-10">
        {{ $message }}
    </p>
</x-ui.card>
