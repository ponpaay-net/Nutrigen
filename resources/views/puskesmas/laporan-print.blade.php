<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan - Evaluasi Gizi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm;
        }
        body {
            background-color: white;
            color: black;
            font-family: 'Times New Roman', Times, serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        /* Typography overrides for print */
        .print-title { font-size: 16pt; font-weight: bold; text-align: center; margin-bottom: 5px; }
        .print-subtitle { font-size: 12pt; text-align: center; margin-bottom: 20px; text-transform: uppercase;}
        
        .print-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 11pt;
        }
        .print-table th, .print-table td {
            border: 1px solid #000;
            padding: 8px 12px;
        }
        .print-table th {
            background-color: #f3f4f6 !important;
            font-weight: bold;
            text-align: center;
        }
        .print-table .num-cell { text-align: center; }

        /* Hide anything unwanted */
        @media print {
            .no-print { display: none !important; }
        }
        
        /* Print button style */
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #2563eb;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-family: sans-serif;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border: none;
        }
    </style>
</head>
<body class="bg-white">

    <button onclick="window.print()" class="print-btn no-print">
        🖨️ Cetak PDF Sekarang
    </button>

    <div style="max-width: 800px; margin: 0 auto; padding-top: 20px;">
        
        <!-- KOP SURAT / HEADER -->
        <div style="border-bottom: 3px solid black; padding-bottom: 10px; margin-bottom: 20px; text-align: center;">
            <div style="font-size: 14pt; font-weight: bold; text-transform: uppercase;">
                {{ $puskesmas['nama'] ?? 'PUSKESMAS' }}
            </div>
            <div style="font-size: 11pt;">
                {{ $puskesmas['alamat'] ?? 'Alamat Puskesmas' }}
            </div>
            <div style="font-size: 10pt;">
                Kode Faskes: {{ $puskesmas['kode_registrasi'] ?? '-' }}
            </div>
        </div>

        <div class="print-title">LAPORAN EVALUASI GIZI BALITA</div>
        <div class="print-subtitle">PERIODE: {{ DateTime::createFromFormat('!m', $filters['bulan'])->format('F') }} {{ $filters['tahun'] }}</div>

        <!-- SUMMARY TEXT -->
        <div style="margin-bottom: 20px; line-height: 1.5;">
            <p>Berdasarkan data yang terkumpul pada periode bulan <b>{{ DateTime::createFromFormat('!m', $filters['bulan'])->format('F') }} {{ $filters['tahun'] }}</b>, berikut adalah ringkasan sasaran balita dan status gizinya:</p>
            <ul style="margin-top: 10px; margin-left: 20px;">
                <li>Total Sasaran Balita Terdaftar: <b>{{ number_format($stats['total_balita']) }} anak</b></li>
                <li>Balita dengan Status Gizi Normal: <b>{{ number_format($stats['normal']) }} anak</b></li>
                <li>Balita Berisiko / Stunting: <b>{{ number_format($stats['berisiko']) }} anak</b></li>
            </ul>
        </div>

        <!-- TABLE -->
        <table class="print-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 35%; text-align: left;">Nama Posyandu</th>
                    <th style="width: 15%;">Total Sasaran</th>
                    <th style="width: 15%;">Balita Diukur</th>
                    <th style="width: 15%;">Gizi Normal</th>
                    <th style="width: 15%;">Berisiko</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $index => $row)
                    <tr>
                        <td class="num-cell">{{ $index + 1 }}</td>
                        <td>{{ $row['nama_posyandu'] }}</td>
                        <td class="num-cell">{{ number_format($row['sasaran']) }}</td>
                        <td class="num-cell">{{ number_format($row['diukur']) }}</td>
                        <td class="num-cell">{{ number_format($row['normal']) }}</td>
                        <td class="num-cell">{{ number_format($row['berisiko']) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px;">Data tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- FOOTER / TTD -->
        <div style="margin-top: 60px; display: flex; justify-content: flex-end;">
            <div style="text-align: center; width: 250px;">
                <div>Mengetahui,</div>
                <div style="margin-bottom: 60px;">Kepala {{ $puskesmas['nama'] ?? 'Puskesmas' }}</div>
                <div style="border-bottom: 1px solid black; padding-bottom: 5px; font-weight: bold;">( ......................................... )</div>
                <div style="margin-top: 5px;">NIP. </div>
            </div>
        </div>

    </div>

    <!-- Auto trigger print dialog when loaded -->
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
