<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Evaluasi Gizi Balita — {{ $puskesmas->nama ?? 'Puskesmas' }} — {{ $monthLabel }} {{ $filters['tahun'] }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 15mm 15mm;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #1e293b;
            background-color: #ffffff;
            font-size: 10pt;
            line-height: 1.4;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            padding: 16px;
        }

        .no-print {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 12px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.03);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.15s ease;
        }

        .btn-primary {
            background-color: #0d9488;
            color: white;
        }
        .btn-primary:hover {
            background-color: #0f766e;
        }

        .btn-secondary {
            background-color: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
        }
        .btn-secondary:hover {
            background-color: #f1f5f9;
        }

        /* Kop Surat Resmi Dinas */
        .kop-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
            padding-bottom: 12px;
            position: relative;
        }

        .kop-logo {
            width: 64px;
            height: 64px;
            object-fit: contain;
            shrink: 0;
        }

        .kop-text {
            text-align: center;
            flex: 1;
        }

        .kop-instansi {
            font-size: 11pt;
            font-weight: 700;
            letter-spacing: 0.8px;
            color: #0f172a;
            text-transform: uppercase;
        }

        .kop-dinas {
            font-size: 12pt;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #0f172a;
            text-transform: uppercase;
        }

        .kop-puskesmas {
            font-size: 14pt;
            font-weight: 800;
            letter-spacing: 1px;
            color: #0d9488;
            text-transform: uppercase;
            margin: 1px 0;
        }

        .kop-detail {
            font-size: 8.5pt;
            color: #475569;
            line-height: 1.35;
        }

        .kop-line-double {
            border-top: 2.5px solid #0f172a;
            border-bottom: 1px solid #0f172a;
            height: 4px;
            margin-bottom: 18px;
        }

        /* Title block */
        .doc-title-box {
            text-align: center;
            margin-bottom: 18px;
        }

        .doc-title {
            font-size: 13pt;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #0f172a;
        }

        .doc-period {
            font-size: 10.5pt;
            font-weight: 700;
            color: #0d9488;
            margin-top: 2px;
            text-transform: uppercase;
        }

        .doc-meta {
            font-size: 8.5pt;
            color: #64748b;
            margin-top: 4px;
        }

        /* KPI Metric Grid */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-bottom: 18px;
        }

        .kpi-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
            background-color: #f8fafc;
            border-left: 4px solid #94a3b8;
        }

        .kpi-card.kpi-teal {
            border-left-color: #0d9488;
            background-color: #f0fdfa;
        }

        .kpi-card.kpi-emerald {
            border-left-color: #10b981;
            background-color: #f0fdf4;
        }

        .kpi-card.kpi-amber {
            border-left-color: #f59e0b;
            background-color: #fffbeb;
        }

        .kpi-card.kpi-rose {
            border-left-color: #e11d48;
            background-color: #fff1f2;
        }

        .kpi-card.kpi-purple {
            border-left-color: #9333ea;
            background-color: #faf5ff;
        }

        .kpi-label {
            font-size: 7.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
        }

        .kpi-value {
            font-size: 15pt;
            font-weight: 800;
            color: #0f172a;
            margin-top: 2px;
            line-height: 1.1;
        }

        .kpi-sub {
            font-size: 8pt;
            color: #64748b;
            margin-top: 2px;
        }

        /* Tables */
        .section-heading {
            font-size: 10pt;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            margin-bottom: 18px;
        }

        .data-table th, 
        .data-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            vertical-align: middle;
        }

        .data-table th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: 700;
            text-align: center;
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: 700; }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 7.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-success { background-color: #dcfce7; color: #15803d; }
        .badge-warning { background-color: #fef3c7; color: #b45309; }
        .badge-danger  { background-color: #ffe4e6; color: #be123c; }

        /* Signatures block */
        .signature-section {
            margin-top: 28px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .signature-box {
            text-align: center;
            width: 220px;
        }

        .signature-title {
            font-size: 9pt;
            color: #475569;
            margin-bottom: 4px;
        }

        .signature-role {
            font-size: 9.5pt;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 58px;
        }

        .signature-name {
            font-size: 9.5pt;
            font-weight: 800;
            color: #0f172a;
            border-bottom: 1.5px solid #0f172a;
            padding-bottom: 3px;
            display: inline-block;
            min-width: 170px;
        }

        .signature-nip {
            font-size: 8.5pt;
            color: #64748b;
            margin-top: 3px;
        }

        /* Document Footer */
        .doc-footer {
            margin-top: 20px;
            border-top: 1px dashed #cbd5e1;
            padding-top: 6px;
            font-size: 7.5pt;
            color: #94a3b8;
            display: flex;
            justify-content: space-between;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <!-- ACTION TOOLBAR (Hidden during print) -->
    <div class="no-print">
        <div style="display: flex; align-items: center; gap: 12px;">
            <a href="{{ route('puskesmas.laporan') }}" class="btn btn-secondary">
                &larr; Kembali ke Laporan
            </a>
            <div>
                <p style="font-size: 13px; font-weight: 700; color: #0f172a;">Pratinjau Cetak Laporan Puskesmas</p>
                <p style="font-size: 11px; color: #64748b;">Periode {{ $monthLabel }} {{ $filters['tahun'] }} &bull; Format A4 Resmi</p>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            <button onclick="window.print()" class="btn btn-primary">
                &#128438; Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- KOP SURAT RESMI -->
    <div class="kop-header">
        <img src="/images/kemenkes_logo.png" alt="Logo Kemenkes" class="kop-logo" onerror="this.style.display='none'">
        <div class="kop-text">
            <div class="kop-instansi">KEMENTERIAN KESEHATAN REPUBLIK INDONESIA</div>
            <div class="kop-dinas">DINAS KESEHATAN {{ strtoupper($puskesmas->kabupaten_kota ?? 'KOTA BANDA ACEH') }}</div>
            <div class="kop-puskesmas">UPTD PUSKESMAS {{ strtoupper($puskesmas->nama ?? 'PUSKESMAS') }}</div>
            <div class="kop-detail">
                {{ $puskesmas->alamat ?? 'Jl. Kesehatan No. 1' }} &bull; Telp: {{ $puskesmas->no_telp ?? '-' }} &bull; Kode Faskes: {{ $puskesmas->kode_faskes ?? '-' }}
            </div>
        </div>
        <img src="/images/logo/logo-nutrigen.png" alt="Logo NutriGen" class="kop-logo" onerror="this.style.display='none'">
    </div>
    <div class="kop-line-double"></div>

    <!-- TITLE BOX -->
    <div class="doc-title-box">
        <h1 class="doc-title">LAPORAN EVALUASI & PEMANTAUAN STATUS GIZI BALITA</h1>
        <div class="doc-period">PERIODE PELAKSANAAN: {{ strtoupper($monthLabel) }} {{ $filters['tahun'] }}</div>
        <div class="doc-meta">
            Cakupan: {{ $filters['posyandu_id'] === 'semua' ? 'Semua Posyandu Wilayah Kerja' : 'Posyandu Terpilih' }} &bull; Sumber Data: Rekapitulasi Penimbangan Terverifikasi &bull; Waktu Unduh: {{ now()->translatedFormat('d F Y, H:i') }} WIB
        </div>
    </div>

    <!-- KPI SUMMARY GRID -->
    <div class="kpi-grid">
        <div class="kpi-card kpi-teal">
            <div class="kpi-label">Sasaran Diukur</div>
            <div class="kpi-value">{{ number_format($stats['total_balita']) }}</div>
            <div class="kpi-sub">Balita terverifikasi</div>
        </div>
        <div class="kpi-card kpi-emerald">
            <div class="kpi-label">Normal (All)</div>
            <div class="kpi-value">{{ number_format($stats['normal']) }}</div>
            <div class="kpi-sub">Sehat 3 Indeks</div>
        </div>
        <div class="kpi-card kpi-amber">
            <div class="kpi-label">Underweight</div>
            <div class="kpi-value">{{ number_format($stats['underweight']) }}</div>
            <div class="kpi-sub">BB Kurang/Sangat Kurang</div>
        </div>
        <div class="kpi-card kpi-rose">
            <div class="kpi-label">Stunting</div>
            <div class="kpi-value">{{ number_format($stats['stunting']) }}</div>
            <div class="kpi-sub">Pendek/Sangat Pendek</div>
        </div>
        <div class="kpi-card kpi-purple">
            <div class="kpi-label">Wasting</div>
            <div class="kpi-value">{{ number_format($stats['wasting']) }}</div>
            <div class="kpi-sub">Gizi Kurang/Buruk</div>
        </div>
    </div>

    <!-- SECTION 1: REKAPITULASI PER POSYANDU -->
    <div class="section-heading">
        <span>I. Rekapitulasi Capaian Gizi per Titik Posyandu</span>
        <span style="font-size: 8pt; font-weight: 500; color: #64748b;">Total {{ count($posyanduSummary) }} Titik Layanan</span>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%; text-align: left;">Nama Posyandu</th>
                <th style="width: 22%; text-align: left;">Desa / Kelurahan</th>
                <th style="width: 10%;">Diukur</th>
                <th style="width: 9%;">Normal</th>
                <th style="width: 9%;">Underweight</th>
                <th style="width: 10%;">Stunting</th>
                <th style="width: 10%;">Wasting</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posyanduSummary as $idx => $row)
                <tr>
                    <td class="text-center font-bold">{{ $idx + 1 }}</td>
                    <td class="font-bold text-left">{{ $row['nama'] }}</td>
                    <td class="text-left" style="color: #475569;">{{ $row['desa'] }}</td>
                    <td class="text-center font-bold">{{ number_format($row['total']) }}</td>
                    <td class="text-center" style="color: #15803d; font-weight: 600;">{{ number_format($row['normal']) }}</td>
                    <td class="text-center" style="color: #b45309; font-weight: 600;">{{ number_format($row['underweight']) }}</td>
                    <td class="text-center font-bold" style="color: {{ $row['stunting'] > 0 ? '#be123c' : '#15803d' }};">
                        {{ number_format($row['stunting']) }}
                        @if($row['total'] > 0)
                            <span style="font-size: 7.5pt; font-weight: normal; color: #64748b;">({{ $row['prevalence'] }}%)</span>
                        @endif
                    </td>
                    <td class="text-center" style="color: #9333ea; font-weight: 600;">{{ number_format($row['wasting']) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 16px; color: #64748b;">Belum ada data posyandu terdaftar.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f1f5f9; font-weight: 800;">
                <td colspan="3" class="text-right" style="padding-right: 12px;">TOTAL KESELURUHAN PUSKESMAS:</td>
                <td class="text-center">{{ number_format($stats['total_balita']) }}</td>
                <td class="text-center" style="color: #15803d;">{{ number_format($stats['normal']) }}</td>
                <td class="text-center" style="color: #b45309;">{{ number_format($stats['underweight']) }}</td>
                <td class="text-center" style="color: #be123c;">{{ number_format($stats['stunting']) }} ({{ $stats['prevalence'] ?? 0 }}%)</td>
                <td class="text-center" style="color: #9333ea;">{{ number_format($stats['wasting']) }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- SECTION 2: DETAIL BALITA -->
    <div class="section-heading" style="margin-top: 14px;">
        <span>II. Rincian Data Pengukuran Balita Terverifikasi</span>
        <span style="font-size: 8pt; font-weight: 500; color: #64748b;">Menampilkan {{ count($pengukurans) }} Data Terverifikasi</span>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 18%; text-align: left;">Nama Balita & NIK</th>
                <th style="width: 15%; text-align: left;">Posyandu</th>
                <th style="width: 18%; text-align: left;">Nama Orang Tua</th>
                <th style="width: 9%;">Umur</th>
                <th style="width: 8%;">BB (kg)</th>
                <th style="width: 8%;">TB (cm)</th>
                <th style="width: 20%;">Status Gizi (WHO)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengukurans as $idx => $row)
                @php
                    $giziLower = strtolower($row->status_gizi);
                    $badgeClass = 'badge-success';
                    if (in_array($giziLower, ['stunting', 'gizi buruk', 'sangat kurus', 'sangat pendek'])) {
                        $badgeClass = 'badge-danger';
                    } elseif (in_array($giziLower, ['risiko', 'kurang', 'kurus', 'pendek'])) {
                        $badgeClass = 'badge-warning';
                    }
                @endphp
                <tr>
                    <td class="text-center font-bold">{{ $idx + 1 }}</td>
                    <td class="text-left">
                        <strong style="color: #0f172a;">{{ $row->balita?->nama ?? '-' }}</strong><br>
                        <span style="font-size: 7.5pt; color: #64748b;">NIK: {{ $row->balita?->nik ?? '-' }}</span>
                    </td>
                    <td class="text-left" style="color: #334155;">{{ $row->balita?->posyandu?->nama ?? '-' }}</td>
                    <td class="text-left" style="color: #334155;">
                        {{ $row->balita?->orangTua?->nama_ibu ?? ($row->balita?->orangTua?->nama_ayah ?? '-') }}
                    </td>
                    <td class="text-center">{{ $row->umur_bulan }} Bln</td>
                    <td class="text-center font-bold">{{ number_format($row->berat_badan, 1) }}</td>
                    <td class="text-center font-bold">{{ number_format($row->tinggi_badan, 1) }}</td>
                    <td class="text-center">
                        <span class="badge {{ $badgeClass }}">{{ strtoupper($row->status_gizi) }}</span>
                        @if($row->rekomendasi_pmt)
                            <span style="font-size: 7.5pt; color: #64748b; font-weight: 600; display: block; margin-top: 4px;">PMT: {{ $row->rekomendasi_pmt }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px; color: #64748b;">
                        Belum ada data pengukuran anak yang terverifikasi pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- SECTION 3: TANDA TANGAN DINAS -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-title">Petugas Pengelola Program Gizi,</div>
            <div class="signature-role">Nutrisionis Pelaksana</div>
            <div class="signature-name">( ........................................ )</div>
            <div class="signature-nip">NIP. ........................................</div>
        </div>

        <div class="signature-box">
            <div class="signature-title">{{ $puskesmas->kecamatan ?? 'Wilayah Kerja' }}, {{ now()->translatedFormat('d F Y') }}</div>
            <div class="signature-role">Kepala UPTD Puskesmas {{ $puskesmas->nama ?? '' }}</div>
            <div class="signature-name">{{ $puskesmas->kepala_puskesmas ?? 'Dr. Hadi Kurniawan' }}</div>
            <div class="signature-nip">NIP. 19780412 200501 1 008</div>
        </div>
    </div>

    <!-- DOCUMENT FOOTER -->
    <div class="doc-footer">
        <span>Dokumen Resmi Sistem Elektronik Pemantauan Gizi NutriGen &bull; Kemenkes RI</span>
        <span>Dicetak secara otomatis &bull; Halaman 1 dari 1</span>
    </div>

</body>
</html>
