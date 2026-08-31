@extends('layouts.puskesmas')
@section('page-title', 'Laporan Evaluasi Gizi')
@section('page-mode', 'app')
@section('content')

{{-- Backend Contract:
    Controller: PuskesmasLaporanController@index
    Expected Variables: $stats, $distribution, $trends, $reports, $filters
--}}

<div class="flex-1 overflow-y-auto overflow-x-hidden w-full bg-slate-50">
    <!-- Main Layout Canvas -->
    <div class="flex flex-col w-full max-w-7xl mx-auto px-5 lg:px-8 pt-4 pb-12">
    
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-6">
            <x-page-header 
                breadcrumbs="Portal Puskesmas • Analytics"
                title="Laporan Evaluasi Gizi" 
                subtitle="" 
                class="mb-0"
            />

            <!-- Global Filter Bar placed inline like the mockup -->
            <div class="lg:mb-2">
                <x-report.report-filter-bar :filters="$filters" :posyandus="$posyandus" inline="true" />
            </div>
        </div>

        <!-- Analytics Workspace -->
        <div class="flex flex-col gap-6">
            
            <!-- KPI Summary Cards -->
            <div class="mb-2">
                <x-report.report-summary-card :stats="$stats" />
            </div>
            
            <!-- Main Content Area: Table and Chart -->
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                <!-- Data Recap Table (left) -->
                <div class="xl:col-span-8 bg-white border border-slate-100 rounded-[24px] shadow-sm flex flex-col overflow-hidden">
                    <x-report.report-table :reports="$reports" />
                </div>
                
                <!-- Status Gizi Chart (right) -->
                <div class="xl:col-span-4 bg-white border border-slate-100 rounded-[24px] shadow-sm flex flex-col p-6">
                    <x-report.distribution-chart :distribution="$distribution" />
                </div>
            </div>

            <!-- TAB: TREND (kept at the bottom if needed, but redesigned) -->
            <div class="mt-4">
                <div class="bg-white border border-slate-100 rounded-[24px] p-6 shadow-sm">
                    <x-report.trend-chart :trends="$trends" />
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        /* Hide UI elements not meant for the report */
        header, nav, aside, .sidebar, .navbar, form, button, .print-hidden, [x-data="{ open: false }"] { 
            display: none !important; 
        }
        
        /* Adjust padding on body for clean print margins */
        body {
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Adjust main layout padding if any */
        .pt-4, .py-6, .lg\:py-8, .pb-12 { padding-top: 0 !important; padding-bottom: 0 !important; }

        /* Remove shadows and borders for a clean report look */
        .shadow-sm, .border-slate-100, .bg-white {
            box-shadow: none !important;
            border-color: #e2e8f0 !important;
        }
        
        table {
            width: 100% !important;
            border-collapse: collapse !important;
        }
        
        th, td {
            color: #000 !important;
        }

        /* Ensure charts render nicely */
        canvas {
            max-width: 100% !important;
            height: auto !important;
            page-break-inside: avoid;
        }

        /* Prevent breaks inside sections */
        .xl\:col-span-8, .xl\:col-span-4, .mt-4 {
            page-break-inside: avoid;
            margin-bottom: 2rem !important;
        }

        /* Hide the specific export button container in the table header */
        .relative[x-data] { display: none !important; }
        
        /* Hide SVG icons inside table to save ink (optional) */
        td svg { display: none !important; }
    }
</style>
@endpush

@endsection
