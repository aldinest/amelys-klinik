<!DOCTYPE html>
<html>
<head>
    <title>Data Reservasi Pasien</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #333; }
        th { background-color: #f2f2f2; padding: 10px; text-align: center; }
        td { padding: 8px; vertical-align: middle; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; text-align: right; font-style: italic; }
    </style>
</head>
<body>
    <div class="header">
        <h2>CETAK RESERVASI PASIEN</h2>
        <h3>Praktek Dokter Amelys</h3>
    </div>

    <div class="info">
        <table>
            <tr>
                <td style="border:none; width: 100px;">Dokter</td>
                <td style="border:none;">: <strong>{{ $schedule->doctor->name }}</strong></td>
                <td style="border:none; width: 100px;">Tanggal</td>
                <td style="border:none;">: {{ \Carbon\Carbon::parse($schedule->schedule_date)->locale('id')->translatedFormat('l, d F Y') }}</td>
            </tr>
            <tr>
                <td style="border:none;">Jam Praktik</td>
                <td style="border:none;">: {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }} WIB</td>
                <td style="border:none;">Total Pasien</td>
                <td style="border:none;">: {{ $reservations->count() }} Orang</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Nama Pasien</th>
                <th>No. RM</th>
                <th>Tindakan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $key => $row)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>{{ $row->patient->name }}</td>
                <td class="text-center">{{ $row->patient->medical_record_number ?? '-' }}</td>
                <td>{{ $row->action ?? '-' }}</td>
                <td class="text-center">
                    @switch($row->status)
                        @case('approved') 
                            <span style="color: #007bff; font-weight: bold;">Disetujui</span> 
                            @break
                        @case('completed') 
                            <span style="color: #28a745; font-weight: bold;">Selesai</span> 
                            @break
                        @default 
                            <span style="color: #dc3545; font-weight: bold;">Dibatalkan</span>
                    @endswitch
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}
    </div>
</body>
</html>