@extends('layouts.app')

@section('page-title', 'Log Aktivitas — NutriGen')

@section('content')
<div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full min-h-screen" style="background:#F7F8FA; font-family:'Plus Jakarta Sans',sans-serif;">

    {{-- Breadcrumb --}}
    <nav class="flex text-sm font-medium text-slate-500 mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center">
            <li><a href="{{ route('super-admin.dashboard') }}" class="inline-flex items-center hover:text-teal-600 transition-colors"><x-icon name="squares-four" weight="fill" class="text-base mr-1.5" />Dashboard</a></li>
            <li class="flex items-center"><x-icon name="caret-right" weight="bold" class="text-sm mx-1.5" /><span class="text-slate-900 font-bold">Log Aktivitas</span></li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0"><x-icon name="clock-counter-clockwise" weight="fill" class="w-6 h-6" /></div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Log Aktivitas Administrator</h1>
                <p class="text-sm text-slate-500 mt-1">Riwayat perubahan data penting pada sistem nasional.</p>
            </div>
        </div>
        <span class="text-sm font-semibold text-slate-400 self-start md:self-auto">{{ $logs->total() }} aktivitas tercatat</span>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[760px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Waktu</th>
                        <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Pengguna</th>
                        <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                        <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        @php
                            $tone = match($log->action) {
                                'create' => 'bg-emerald-50 text-emerald-700',
                                'update' => 'bg-amber-50 text-amber-700',
                                'delete' => 'bg-rose-50 text-rose-700',
                                default  => 'bg-slate-100 text-slate-600',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-6 text-sm text-slate-600 whitespace-nowrap">{{ $log->created_at?->translatedFormat('d M Y, H:i') }} WIB</td>
                            <td class="py-3.5 px-6">
                                <div class="text-sm font-semibold text-slate-900">{{ $log->user?->name ?? 'Sistem' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $log->user?->email ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-6 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold uppercase tracking-wide {{ $tone }}">{{ $log->action_label }}</span>
                            </td>
                            <td class="py-3.5 px-6 text-sm text-slate-600">{{ $log->description ?? '-' }}</td>
                            <td class="py-3.5 px-6 text-right text-xs font-mono text-slate-400">{{ $log->ip_address ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-400 mb-3"><x-icon name="clock-counter-clockwise" weight="fill" class="text-3xl" /></div>
                            <h3 class="text-base font-bold text-slate-900 mb-1">Belum ada aktivitas</h3>
                            <p class="text-sm text-slate-500">Aktivitas penambahan, perubahan, dan penghapusan data akan tercatat di sini.</p>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">{{ $logs->links() }}</div>
        @endif
    </div>
</div>
@endsection