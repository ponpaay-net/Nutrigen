<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan_Hasil_Pengukuran_Posyandu_{{ str_replace(' ', '_', $periode) }}.pdf</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm 12mm 12mm 12mm;
        }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #0f172a;
            line-height: 1.35;
            margin: 0;
            padding: 8px;
            background-color: #fff;
            font-size: 10.5px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        /* Official Kop Surat Dinas Kesehatan */
        .kop-container {
            display: table;
            width: 100%;
            border-bottom: 3px double #0f172a;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }
        .kop-logo {
            display: table-cell;
            width: 70px;
            vertical-align: middle;
            text-align: center;
        }
        .kop-text {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
            padding: 0 15px;
        }
        .kop-instansi-1 {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #0f172a;
            margin: 0;
        }
        .kop-instansi-2 {
            font-size: 15px;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #0f766e;
            margin: 2px 0;
        }
        .kop-unit {
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #0f172a;
            margin: 0;
        }
        .kop-alamat {
            font-size: 9.5px;
            color: #475569;
            margin-top: 3px;
            margin-bottom: 0;
        }

        /* Title Area */
        .report-title-box {
            text-align: center;
            margin: 10px 0 14px;
        }
        .report-title {
            font-size: 13.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #0f172a;
            margin: 0;
        }
        .report-subtitle {
            font-size: 10.5px;
            color: #334155;
            margin-top: 3px;
            font-weight: 600;
        }

        /* Summary Stats Ribbon */
        .summary-ribbon {
            display: table;
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background-color: #f8fafc;
            margin-bottom: 14px;
            table-layout: fixed;
        }
        .summary-cell {
            display: table-cell;
            padding: 6px 10px;
            text-align: center;
            border-right: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .summary-cell:last-child {
            border-right: none;
        }
        .summary-num {
            font-size: 15px;
            font-weight: 800;
            display: block;
            color: #0f172a;
        }
        .summary-label {
            font-size: 8.5px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        /* Main Data Table */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5px;
            margin-top: 4px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #94a3b8;
            padding: 5px 6px;
            vertical-align: middle;
        }
        table.data-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 9px;
            text-align: center;
            letter-spacing: 0.02em;
        }
        table.data-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: 700; }
        
        .status-badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 8.5px;
        }
        .status-normal { background: #dcfce7; color: #166534; }
        .status-warning { background: #fef3c7; color: #92400e; }
        .status-danger { background: #fee2e2; color: #991b1b; }

        /* Signatures Section */
        .signature-section {
            margin-top: 24px;
            width: 100%;
            display: table;
            table-layout: fixed;
            page-break-inside: avoid;
        }
        .signature-col {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .sign-space {
            height: 50px;
        }
        .sign-name {
            font-weight: 800;
            font-size: 10.5px;
            text-decoration: underline;
            color: #0f172a;
            margin-bottom: 2px;
        }
        .sign-role {
            font-size: 9.5px;
            color: #475569;
            margin: 0;
        }

        /* Screen Header Bar */
        .action-bar {
            background: #0f172a;
            color: #fff;
            padding: 10px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 8px;
            margin-bottom: 14px;
        }
        .btn-action {
            color: #fff;
            border: none;
            padding: 7px 16px;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            font-size: 11.5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-print { background: #0f766e; }
        .btn-excel { background: #15803d; }
        .btn-back {
            color: #94a3b8;
            text-decoration: none;
            font-weight: 600;
            font-size: 11.5px;
        }
        .btn-back:hover { color: #fff; }

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

    <!-- Screen Navigation Bar (Hidden during Print) -->
    <div class="action-bar no-print">
        <div>
            <strong>Laporan Resmi Posyandu</strong> &bull; {{ $posyanduName }} &bull; Periode {{ $periode }}
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="{{ route('laporan.index') }}" class="btn-back">&larr; Kembali ke Portal</a>
            <a href="{{ route('laporan.export.excel', ['periode' => request('periode')]) }}" class="btn-action btn-excel">Export Excel (.xls)</a>
            <button onclick="window.print()" class="btn-action btn-print">Cetak Laporan (PDF)</button>
        </div>
    </div>

    <!-- Official Kop Surat -->
    <div class="kop-container">
        <div class="kop-logo">
            <svg viewBox="0 0 24 24" width="46" height="46" fill="none" stroke="#0f766e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
        </div>
        <div class="kop-text">
            <p class="kop-instansi-1">PEMERINTAH KABUPATEN / KOTA</p>
            <p class="kop-instansi-2">DINAS KESEHATAN &bull; {{ strtoupper($puskesmasName) }}</p>
            <p class="kop-unit">{{ strtoupper($posyanduName) }} &bull; DESA/KEL. {{ strtoupper($desa) }}</p>
            <p class="kop-alamat">Alamat: {{ $alamat }} &bull; Format Standar Buku KIA / KMS Terintegrasi NutriGen</p>
        </div>
        <div class="kop-logo" style="width: 70px;"></div>
    </div>

    <!-- Report Title -->
    <div class="report-title-box">
        <h1 class="report-title">LAPORAN HASIL PENIMBANGAN & PEMANTAUAN TUMBUH KEMBANG BALITA</h1>
        <p class="report-subtitle">Periode Pelaksanaan: {{ $periode }} &bull; Tempat: {{ $posyanduName }}</p>
    </div>

    <!-- Summary Stats Ribbon -->
    <div class="summary-ribbon">
        <div class="summary-cell">
            <span class="summary-num">{{ $totalBalita }}</span>
            <span class="summary-label">Total Balita (S)</span>
        </div>
        <div class="summary-cell">
            <span class="summary-num" style="color: #0f766e;">{{ $sudahDiukur }}</span>
            <span class="summary-label">Balita Terukur (D)</span>
        </div>
        <div class="summary-cell">
            <span class="summary-num" style="color: #0284c7;">{{ $persentase }}%</span>
            <span class="summary-label">Cakupan (D/S)</span>
        </div>
        <div class="summary-cell">
            <span class="summary-num" style="color: #d97706;">{{ $perluPerhatian }}</span>
            <span class="summary-label">Pantauan Gizi</span>
        </div>
        <div class="summary-cell">
            <span class="summary-num" style="color: #be123c;">{{ $berisiko }}</span>
            <span class="summary-label">Konfirmasi Puskesmas</span>
        </div>
    </div>

    <!-- Main Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 105px;">NIK Balita</th>
                <th>Nama Balita</th>
                <th style="width: 30px;">L/P</th>
                <th style="width: 65px;">Tgl Lahir</th>
                <th style="width: 45px;">Umur</th>
                <th>Nama Ibu / Ortu</th>
                <th style="width: 65px;">Tgl Ukur</th>
                <th style="width: 45px;">BB (kg)</th>
                <th style="width: 45px;">TB (cm)</th>
                <th style="width: 45px;">LK (cm)</th>
                <th style="width: 35px;">ASI</th>
                <th style="width: 35px;">KMS</th>
                <th style="width: 110px;">Status / Diagnosa</th>
                <th style="width: 110px;">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($balitas as $index => $b)
                @php 
                    $m = $b->pengukurans->first();
                    $statusGizi = $m ? $m->status_gizi : '-';
                    $statusValidasi = $m ? $m->status_validasi : null;
                    
                    // Medical protocol formatting
                    $displayStatus = '-';
                    $badgeClass = 'status-normal';
                    if ($m) {
                        if ($statusValidasi === 'approved') {
                            $displayStatus = match(strtolower($statusGizi)) {
                                'stunting' => 'Stunting',
                                'risiko' => 'Risiko Stunting',
                                'kurang', 'gizi kurang' => 'Gizi Kurang',
                                'normal', 'gizi baik' => 'Normal',
                                default => ucfirst($statusGizi)
                            };
                            $badgeClass = match(strtolower($statusGizi)) {
                                'stunting' => 'status-danger',
                                'risiko', 'kurang', 'gizi kurang' => 'status-warning',
                                default => 'status-normal'
                            };
                        } elseif ($statusValidasi === 'rejected') {
                            $displayStatus = 'Perlu Revisi';
                            $badgeClass = 'status-danger';
                        } else {
                            $displayStatus = match(strtolower($statusGizi)) {
                                'stunting', 'pendek' => 'TB Rendah (Menunggu Validasi)',
                                'risiko', 'kurang' => 'Pantauan (Menunggu Validasi)',
                                'normal', 'gizi baik' => 'Gizi Baik (KMS)',
                                default => 'Menunggu Validasi'
                            };
                            $badgeClass = match(strtolower($statusGizi)) {
                                'stunting', 'pendek' => 'status-warning',
                                'risiko', 'kurang' => 'status-warning',
                                default => 'status-normal'
                            };
                        }
                    }
                    $catatan = $m ? ($m->catatan_kader ?? ($m->catatan_validator ?? '-')) : '-';
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center font-bold">{{ $b->nik ?? '-' }}</td>
                    <td class="font-bold">{{ $b->nama }}</td>
                    <td class="text-center">{{ $b->jenis_kelamin }}</td>
                    <td class="text-center">{{ $b->tanggal_lahir ? \Carbon\Carbon::parse($b->tanggal_lahir)->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">{{ $m ? $m->umur_bulan . ' bln' : '-' }}</td>
                    <td>{{ $b->orangTua->nama_ibu ?? '-' }}</td>
                    <td class="text-center">{{ $m ? \Carbon\Carbon::parse($m->tanggal_ukur)->format('d/m/Y') : '-' }}</td>
                    <td class="text-center font-bold">{{ $m ? number_format((float)$m->berat_badan, 2) : '-' }}</td>
                    <td class="text-center font-bold">{{ $m ? number_format((float)$m->tinggi_badan, 1) : '-' }}</td>
                    <td class="text-center">{{ ($m && $m->lingkar_kepala) ? number_format((float)$m->lingkar_kepala, 1) : '-' }}</td>
                    <td class="text-center">{{ $m ? ($m->asi_eksklusif ? 'Ya' : 'Tdk') : '-' }}</td>
                    <td class="text-center font-bold">{{ $m ? ($m->status_kenaikan ?? '-') : '-' }}</td>
                    <td class="text-center">
                        <span class="status-badge {{ $badgeClass }}">{{ $displayStatus }}</span>
                    </td>
                    <td>{{ $catatan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="15" class="text-center" style="padding: 18px; color: #64748b;">
                        Tidak ada data balita yang diukur pada periode {{ $periode }}.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Official Dual Signatures Section -->
    <div class="signature-section">
        <div class="signature-col">
            <p>Mengetahui,</p>
            <p class="sign-role">Petugas Gizi / Bidan Pembina {{ $puskesmasName }}</p>
            <div class="sign-space"></div>
            <p class="sign-name">( .................................................... )</p>
            <p class="sign-role">NIP. .............................................</p>
        </div>
        <div class="signature-col">
            <p>{{ $desa }}, {{ now()->translatedFormat('d F Y') }}</p>
            <p class="sign-role">Pelaksana Kader {{ $posyanduName }}</p>
            <div class="sign-space"></div>
            <p class="sign-name"><strong><u>{{ $kaderName }}</u></strong></p>
            <p class="sign-role">Kader Penanggung Jawab</p>
        </div>
    </div>

</body>
</html>
