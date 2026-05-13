<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Tahunan Amelys Klinik {{ $year }}</title>
    <style>
        @page { margin: 0.8cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.4; margin: 0; padding: 0; font-size: 11px; }
        
        /* Header / Kop Klinik */
        .header { border-bottom: 2px solid #3498db; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { width: 50px; height: auto; float: left; margin-right: 15px; }
        .clinic-name { color: #2c3e50; font-size: 20px; font-weight: bold; margin: 0; text-transform: uppercase; letter-spacing: 1px; }
        .clinic-sub { color: #e74c3c; font-size: 11px; font-weight: bold; margin: 2px 0 0; text-transform: uppercase; }
        .clear { clear: both; }
        
        /* Metadata Section */
        .meta-container { background-color: #fcfcfc; border: 1px solid #eee; padding: 12px; margin-bottom: 20px; border-left: 4px solid #3498db; }
        .meta-table { width: 100%; border-collapse: collapse; }
        .label { color: #7f8c8d; width: 100px; font-size: 10px; }
        .value { font-weight: bold; color: #2c3e50; }

        /* Main Data Table */
        table.main-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.main-table th { 
            background-color: #3498db; 
            color: white; 
            font-weight: bold; 
            text-transform: uppercase; 
            font-size: 10px; 
            padding: 10px; 
            border: 1px solid #2980b9; 
        }
        table.main-table td { padding: 10px; border: 1px solid #dfe6e9; vertical-align: middle; }
        .row-alt { background-color: #f9fbff; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        /* Total Row */
        .total-row { background-color: #2c3e50 !important; color: white; font-weight: bold; }
        .total-row td { border: 1px solid #2c3e50; padding: 12px 10px; font-size: 11px; }
        
        /* Badge Persentase */
        .percent-badge { background: #e8f4fd; color: #2980b9; padding: 4px 8px; border-radius: 10px; font-weight: bold; font-size: 10px; border: 1px solid #d1e9f7; }

        .note-section { margin-top: 30px; padding: 10px; background: #fffcf5; border: 1px solid #f39c12; border-radius: 4px; }
        .footer { position: fixed; bottom: 10px; width: 100%; font-size: 9px; color: #bdc3c7; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('dist/img/logoamelys.png') }}" class="logo">
        <div class="clinic-info">
            <h1 class="clinic-name">Praktek Dokter Amelys</h1>
            <p class="clinic-sub">Laporan Rekapitulasi Performa Tahunan Klinik</p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="meta-container">
        <table class="meta-table">
            <tr>
                <td class="label">Tahun Laporan</td>
                <td width="10">:</td>
                <td class="value">{{ $year }}</td>
                <td class="label text-right">Dokter Spesialis :</td>
                <td width="10"></td>
                <td class="value">{{ strtoupper($doctor) }}</td>
            </tr>
            <tr>
                <td class="label">Periode Data</td>
                <td>:</td>
                <td class="value">Januari - Desember {{ $year }}</td>
                <td class="label text-right">Status :</td>
                <td></td>
                <td class="value" style="color: #27ae60;">Terverifikasi Sistem</td>
            </tr>
        </table>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th width="35%">Bulan Operasional</th>
                <th class="text-center">Total Reservasi</th>
                <th class="text-center">Selesai Periksa (RM)</th>
                <th class="text-center">Persentase Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $grandTotalRes = 0; 
                $grandTotalChecked = 0; 
            @endphp
            
            @foreach($data as $index => $row)
                @php
                    $grandTotalRes += $row['total_reservations'];
                    $grandTotalChecked += $row['total_checked'];
                    $percent = $row['total_reservations'] > 0 
                        ? round(($row['total_checked'] / $row['total_reservations']) * 100) 
                        : 0;
                @endphp
                <tr class="{{ $index % 2 == 0 ? '' : 'row-alt' }}">
                    <td class="font-bold" style="color: #2c3e50;">{{ $row['month_name'] }}</td>
                    <td class="text-center">{{ number_format($row['total_reservations'], 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($row['total_checked'], 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="percent-badge">{{ $percent }}%</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td>TOTAL AKUMULASI TAHUNAN</td>
                <td class="text-center">{{ number_format($grandTotalRes, 0, ',', '.') }}</td>
                <td class="text-center">{{ number_format($grandTotalChecked, 0, ',', '.') }}</td>
                <td class="text-center">
                    @php 
                        $totalPercent = $grandTotalRes > 0 ? round(($grandTotalChecked / $grandTotalRes) * 100) : 0;
                    @endphp
                    {{ $totalPercent }}%
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="note-section">
        <p style="margin: 0 0 5px 0;"><strong>Catatan Laporan:</strong></p>
        <ul style="margin: 0; padding-left: 20px;">
            <li>Data di atas mencakup seluruh kunjungan pasien yang terdaftar dalam sistem reservasi.</li>
            <li>"Selesai Periksa" diverifikasi berdasarkan pengisian data Rekam Medis oleh dokter.</li>
            <li>Laporan ini merupakan dokumen internal Amelys Klinik.</li>
        </ul>
    </div>

    <div class="footer">
        Dokumen ini dihasilkan secara otomatis oleh Amelys Klinik System pada {{ date('d/m/Y H:i') }}.
    </div>
</body>
</html>