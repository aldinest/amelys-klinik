<!DOCTYPE html>
<html>
<head>
    <title>Laporan Bulanan - {{ $monthName }} {{ $year }}</title>
    <style>
        @page { margin: 0.7cm; }
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 10px; color: #333; line-height: 1.2; }
        
        .header-table { width: 100%; border-bottom: 2px solid #3498db; padding-bottom: 10px; margin-bottom: 15px; }
        .logo { width: 45px; height: auto; }
        .clinic-name { font-size: 18px; font-weight: bold; color: #2c3e50; margin: 0; }
        .report-title { font-size: 11px; color: #e74c3c; font-weight: bold; text-transform: uppercase; margin: 2px 0 0; }
        
        .meta-container { background-color: #fcfcfc; border: 1px solid #eee; padding: 10px; margin-bottom: 15px; border-left: 4px solid #3498db; }
        .meta-table { width: 100%; border-collapse: collapse; }
        .label { color: #7f8c8d; width: 85px; font-size: 9px; }
        .value { font-weight: bold; color: #2c3e50; font-size: 10px; }

        /* Tabel Super Compact */
        table.data-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        table.data-table th { 
            background-color: #3498db; 
            color: white; 
            padding: 8px 2px; 
            font-size: 8.5px; 
            text-align: center;
            border: 1px solid #2980b9;
        }
        table.data-table td { 
            padding: 7px 4px; 
            border: 1px solid #dfe6e9; 
            vertical-align: middle; 
            word-wrap: break-word;
        }
        
        .row-alt { background-color: #f9fbff; }
        .text-center { text-align: center; }
        .text-dark-bold { color: #2c3e50; font-weight: bold; }
        
        .patient-name { font-weight: bold; color: #2c3e50; text-transform: uppercase; font-size: 9px; }
        .footer { margin-top: 15px; font-size: 8px; color: #95a5a6; text-align: right; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td width="55">
                <img src="{{ public_path('dist/img/logoamelys.png') }}" class="logo">
            </td>
            <td>
                <div class="clinic-name">PRAKTEK DOKTER AMELYS</div>
                <div class="report-title">Laporan Rekapitulasi Pasien Terperiksa</div>
            </td>
            <td align="right" valign="bottom" style="font-size: 9px; color: #7f8c8d;">
                Periode: <strong>{{ strtoupper($monthName) }} {{ $year }}</strong>
            </td>
        </tr>
    </table>

    <div class="meta-container">
        <table class="meta-table">
            <tr>
                <td class="label">Nama Dokter</td>
                <td width="10">:</td>
                <td class="value">{{ strtoupper($doctor) }}</td>
            </tr>
            <tr>
                <td class="label">Total Terperiksa</td>
                <td>:</td>
                <td class="value" style="color: #e74c3c;">{{ $records->count() }} Orang</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="20">NO</th>
                <th width="140">NAMA PASIEN</th>
                <th width="25">JK</th>
                <th width="65">TGL LAHIR</th>
                <th width="40">UMUR</th>
                <th>DIAGNOSA</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $index => $record)
                <tr class="{{ $index % 2 == 0 ? '' : 'row-alt' }}">
                    <td class="text-center text-dark-bold">{{ $index + 1 }}</td>
                    <td>
                        <div class="patient-name">{{ $record->patient->name ?? 'N/A' }}</div>
                        <span style="font-size: 8px; color: #95a5a6;">RM: {{ $record->patient->medical_record_number ?? '-' }}</span>
                    </td>
                    <td class="text-center">{{ ($record->patient->gender ?? '-') == 'L' ? 'L' : 'P' }}</td>
                    <td class="text-center">
                        {{ $record->patient && $record->patient->date_of_birth ? \Carbon\Carbon::parse($record->patient->date_of_birth)->format('d/m/Y') : '-' }}
                    </td>
                    <td class="text-center">
                        @if($record->patient && $record->patient->date_of_birth)
                            @php
                                $tglLahir = \Carbon\Carbon::parse($record->patient->date_of_birth);
                                $tglPeriksa = \Carbon\Carbon::parse($record->examined_at);
                                $umur = (int) $tglLahir->diffInYears($tglPeriksa);
                            @endphp
                            {{ $umur }} Thn
                        @else
                            -
                        @endif
                    </td>
                    <td style="color: #444; font-size: 9px; line-height: 1.3;">{{ $record->diagnosis ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 15px;">Data tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i') }} | Amelys Klinik System
    </div>

</body>
</html>