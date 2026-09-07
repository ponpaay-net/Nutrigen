<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pemantauan Gizi Nasional — {{ $monthLabel }} {{ $currentYear }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 15mm 20mm 15mm;
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
            font-size: 11pt;
            line-height: 1.4;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            padding: 20px;
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

        /* Kop Surat */
        .kop-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            border-bottom: 2.5px solid #0f172a;
            padding-bottom: 14px;
            margin-bottom: 18px;
            position: relative;
        }

        .kop-logo {
            width: 68px;
            height: 68px;
            border-radius: 12px;
            background: linear-gradient(135deg, #0d9488, #10b981);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 26px;
            flex-shrink: 0;
        }

        .kop-text {
            text-align: center;
        }

        .kop-text h2 {
            font-size: 14pt;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kop-text h3 {
            font-size: 12pt;
            font-weight: 700;
            color: #0d9488;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .kop-text p {
            font-size: 9pt;
            color: #64748b;
            margin-top: 3px;
        }

        /* Title */
        .report-title-box {
            text-align: center;
            margin-bottom: 20px;
        }

        .report-title-box h1 {
            font-size: 13pt;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .report-title-box p {
            font-size: 10pt;
            font-weight: 600;
            color: #0d9488;
            margin-top: 4px;
        }

        /* KPI Cards */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .kpi-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 14px;
            text-align: center;
        }

        .kpi-label {
            font-size: 8.5pt;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .kpi-value {
            font-size: 15pt;
            font-weight: 800;
            color: #0f172a;
        }

        .kpi-highlight {
            color: #e11d48;
        }

        /* Tables */
        .section-title {
            font-size: 11pt;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
            font-size: 9.5pt;
        }

        th, td {
            border: 1px solid #cbd5e1;
            padding: 7px 10px;
            text-align: left;
        }

        th {
            background-color: #f1f5f9 !important;
            font-weight: 700;
            color: #334155;
            text-align: center;
            font-size: 9pt;
            text-transform: uppercase;
        }

        tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: 700; }

        .badge-danger {
            color: #e11d48;
            font-weight: 700;
        }
        .badge-success {
            color: #0d9488;
            font-weight: 700;
        }

        /* Signatures */
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 35px;
            page-break-inside: avoid;
        }

        .sign-box {
            text-align: center;
            width: 220px;
        }

        .sign-title {
            font-size: 9.5pt;
            color: #64748b;
            margin-bottom: 60px;
        }

        .sign-name {
            font-size: 10pt;
            font-weight: 700;
            color: #0f172a;
            border-bottom: 1px solid #94a3b8;
            padding-bottom: 4px;
        }

        .sign-nip {
            font-size: 8.5pt;
            color: #64748b;
            margin-top: 3px;
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

    <!-- Bar Aksi (Hanya tampil di layar, tersembunyi saat dicetak) -->
    <div class="no-print">
        <div style="font-size: 13px; font-weight: 600; color: #334155;">
            📄 Pratinjau Dokumen Resmi PDF — Periode: <strong>{{ $monthLabel }} {{ $currentYear }}</strong>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn btn-primary">
                🖨️ Cetak / Simpan PDF
            </button>
            <button onclick="window.close(); if(!window.close()) window.history.back();" class="btn btn-secondary">
                Kembali
            </button>
        </div>
    </div>

    <!-- Kop Surat Resmi -->
    <div class="kop-header">
        <div class="kop-logo">
            +
        </div>
        <div class="kop-text">
            <h2>KEMENTERIAN KESEHATAN REPUBLIK INDONESIA</h2>
            <h3>DIREKTORAT GIZI DAN KESEHATAN IBU DAN ANAK</h3>
            <p>Sistem Pemantauan Status Gizi Balita Terpadu (NutriGen National Health Command)</p>
        </div>
    </div>

    <!-- Judul Laporan -->
    <div class="report-title-box">
        <h1>REKAPITULASI PEMANTAUAN STATUS GIZI BALITA NASIONAL</h1>
        <p>PERIODE PENGUKURAN: {{ strtoupper($monthLabel) }} {{ $currentYear }}</p>
    </div>

    <!-- KPI Cards Ringkasan -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-label">Puskesmas Terdaftar</div>
            <div class="kpi-value">{{ $totalPuskesmas }}</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Posyandu Aktif</div>
            <div class="kpi-value">{{ $totalPosyandu }}</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Balita Terverifikasi</div>
            <div class="kpi-value">{{ $totalMeasured }} <span style="font-size: 9pt; font-weight: normal; color: #64748b;">/ {{ $totalBalita }}</span></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Prevalensi Stunting</div>
            <div class="kpi-value {{ $prevalensiNasional > 14 ? 'kpi-highlight' : '' }}">{{ $prevalensiNasional }}%</div>
        </div>
    </div>

    <!-- Tabel 1: Sebaran Status Gizi Nasional -->
    <div class="section-title">I. Ringkasan Distribusi Status Gizi Nasional</div>
    <table>
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Kategori Status Gizi</th>
                <th style="width: 120px;">Jumlah Balita</th>
                <th style="width: 120px;">Persentase</th>
                <th>Keterangan Tindak Lanjut</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td><strong>Gizi Baik (Normal)</strong></td>
                <td class="text-center font-bold">{{ $statusCounts['normal'] }}</td>
                <td class="text-center">{{ $totalMeasured > 0 ? round(($statusCounts['normal'] / $totalMeasured) * 100, 1) : 0 }}%</td>
                <td>Pemantauan rutin posyandu bulanan berlanjut</td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td><strong class="badge-danger">Stunting (Pendek / Sangat Pendek)</strong></td>
                <td class="text-center font-bold badge-danger">{{ $statusCounts['stunting'] }}</td>
                <td class="text-center font-bold badge-danger">{{ $totalMeasured > 0 ? round(($statusCounts['stunting'] / $totalMeasured) * 100, 1) : 0 }}%</td>
                <td>Intervensi spesifik PMT & rujukan spesialis anak</td>
            </tr>
            <tr>
                <td class="text-center">3</td>
                <td><strong>Wasting / Gizi Kurang</strong></td>
                <td class="text-center font-bold">{{ $statusCounts['wasting'] }}</td>
                <td class="text-center">{{ $totalMeasured > 0 ? round(($statusCounts['wasting'] / $totalMeasured) * 100, 1) : 0 }}%</td>
                <td>Pemberian makanan tambahan pemulihan</td>
            </tr>
            <tr>
                <td class="text-center">4</td>
                <td><strong>Risiko Gizi Lebih / Obesitas</strong></td>
                <td class="text-center font-bold">{{ $statusCounts['overweight'] }}</td>
                <td class="text-center">{{ $totalMeasured > 0 ? round(($statusCounts['overweight'] / $totalMeasured) * 100, 1) : 0 }}%</td>
                <td>Edukasi pola asuh & diet seimbang keluarga</td>
            </tr>
        </tbody>
    </table>

    <!-- Tabel 2: Rekapitulasi per Puskesmas -->
    <div class="section-title">II. Rekapitulasi Kinerja Wilayah Puskesmas</div>
    <table>
        <thead>
            <tr>
                <th style="width: 35px;">No</th>
                <th>Nama Puskesmas</th>
                <th>Kode Faskes</th>
                <th>Wilayah Kerja</th>
                <th style="width: 70px;">Posyandu</th>
                <th style="width: 80px;">Terukur</th>
                <th style="width: 70px;">Normal</th>
                <th style="width: 70px;">Stunting</th>
                <th style="width: 85px;">Prevalensi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($puskesmasList as $index => $p)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-bold">{{ $p['nama'] }}</td>
                    <td class="text-center" style="font-family: monospace;">{{ $p['kode'] }}</td>
                    <td>{{ $p['wilayah'] }}</td>
                    <td class="text-center">{{ $p['posyandu_count'] }}</td>
                    <td class="text-center font-bold">{{ $p['total_ukur'] }}</td>
                    <td class="text-center badge-success">{{ $p['normal_count'] }}</td>
                    <td class="text-center {{ $p['stunting_count'] > 0 ? 'badge-danger font-bold' : '' }}">{{ $p['stunting_count'] }}</td>
                    <td class="text-center font-bold {{ $p['prevalensi'] > 15 ? 'badge-danger' : '' }}">{{ $p['prevalensi'] }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 16px; color: #64748b;">
                        Belum ada faskes terdaftar dalam sistem.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Bagian Tanda Tangan Resmi -->
    <div class="signature-section">
        <div class="sign-box">
            <div class="sign-title">
                Mengetahui,<br>
                Koordinator Wilayah Faskes
            </div>
            <div class="sign-name">TIM VERIFIKATOR GIZI</div>
            <div class="sign-nip">NutriGen Data Center</div>
        </div>

        <div class="sign-box">
            <div class="sign-title">
                Jakarta, {{ now()->translatedFormat('d F Y') }}<br>
                Administrator Sistem Kemenkes
            </div>
            <div class="sign-name">SUPER ADMIN NASIONAL</div>
            <div class="sign-nip">NIP. 19850714 201012 1 002</div>
        </div>
    </div>

</body>
</html>
