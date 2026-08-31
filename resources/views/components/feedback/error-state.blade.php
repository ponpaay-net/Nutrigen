@props(['title' => 'Ups, Koneksi Terputus', 'message' => 'Gagal menarik data terbaru. Pastikan internet menyala ya Ibu.', 'class' => ''])

<div {{ $attributes->merge(['class' => "bg-red-50 rounded-[28px] p-6 text-center shadow-soft border border-red-100 flex flex-col items-center mx-5 mt-10 $class"]) }}>
    <div class="text-[5xl] mb-4 filter drop-shadow-sm">🔌</div>
    <h2 class="text-lg font-black text-red-800 mb-2">{{ $title }}</h2>
    <p class="text-[13px] text-red-600 mb-6 font-bold leading-relaxed max-w-[200px] mx-auto">{{ $message }}</p>
    <x-ui.button variant="danger" class="max-w-[200px] py-3 rounded-full">
        Coba Muat Ulang
    </x-ui.button>
</div>
