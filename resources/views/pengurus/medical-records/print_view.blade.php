<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Resume Medis - {{ $medicalRecord->reservation->patient->name }}</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 14px; color: #333; margin: 0; padding: 20px; background-color: #f4f7f6; }
        .page { background: #fff; width: 210mm; margin: auto; padding: 40px; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 5px; }
        
        /* Header Klinik */
        .header { display: flex; align-items: center; border-bottom: 2px solid #007bff; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { width: 90px; margin-right: 20px; }
        .clinic-info h1 { margin: 0; color: #007bff; text-transform: uppercase; }
        .clinic-info p { margin: 5px 0 0; color: #555; line-height: 1.4; }

        /* Document Title */
        .doc-title { background: #007bff; color: white; padding: 10px; border-radius: 4px; text-align: center; font-weight: bold; margin-bottom: 20px; }

        /* Grid Layout */
        .grid-container { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .box { border: 1px solid #dee2e6; padding: 15px; border-radius: 6px; background: #fafafa; }
        .label { font-size: 11px; font-weight: bold; color: #888; text-transform: uppercase; margin-bottom: 5px; }
        .value { font-weight: 600; font-size: 15px; }

        /* Table */
        .table-klinis { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table-klinis th { text-align: left; width: 30%; color: #555; padding: 12px; border-bottom: 1px solid #eee; }
        .table-klinis td { padding: 12px; border-bottom: 1px solid #eee; font-weight: 500; }
        .badge { background: #dc3545; color: white; padding: 4px 10px; border-radius: 4px; font-size: 13px; }

        /* Footer */
        .footer { margin-top: 40px; text-align: right; border-top: 1px solid #eee; padding-top: 20px; color: #777; }
    </style>
</head>
<body onload="window.print()">

    <div class="page">
        {{-- Header dengan Logo & Alamat --}}
        <div class="header">
            <img src="{{ asset('dist/img/logoamelys.png') }}" alt="Logo Amelys" class="logo">
            <div class="clinic-info">
                <h1>Amelys Klinik</h1>
                <p>Jl. Jend. Ahmad Yani No.118, Krajan, Surodikraman, Ponorogo<br>
                Telp: +62 823-3548-3854 | Email: klinikapotekamelys118@gmail.com</p>
            </div>
        </div>

        <div class="doc-title">RESUME MEDIS DIGITAL</div>

        {{-- Info Pasien & Dokter --}}
        <div class="grid-container">
            <div class="box">
                <div class="label">Informasi Pasien</div>
                <div class="value">{{ $medicalRecord->reservation->patient->name }}</div>
                <small>No. RM: {{ $medicalRecord->reservation->patient->medical_record_number }}</small>
            </div>
            <div class="box">
                <div class="label">Dokter Pemeriksa</div>
                <div class="value">dr. {{ $medicalRecord->reservation->doctorSchedule->doctor->name }}</div>
                <small>{{ $medicalRecord->reservation->doctorSchedule->doctor->specialization ?? 'Dokter Umum' }}</small>
            </div>
        </div>

        {{-- Detail Pemeriksaan --}}
        <h3 style="color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 5px; margin-top: 30px; font-size: 1.1rem;">Riwayat Pemeriksaan & Pengobatan</h3>
        <table class="table-klinis">
            <thead>
                <tr style="background-color: #f8f9fa;">
                    <th style="width: 15%; padding: 12px;">Tanggal</th>
                    <th style="width: 20%; padding: 12px;">Dokter</th>
                    <th style="width: 20%; padding: 12px;">Keluhan</th>
                    <th style="width: 15%; padding: 12px; text-align: center;">Diagnosa</th>
                    <th style="width: 30%; padding: 12px;">Tindakan & Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($history as $row)
                <tr style="vertical-align: middle;"> <!-- Pastikan semua isi baris rata tengah secara vertikal -->
                    <td style="font-size: 12px; padding: 12px;">{{ $row->created_at->format('d/m/Y') }}</td>
                    <td style="font-size: 12px; padding: 12px;">dr. {{ $row->reservation->doctorSchedule->doctor->name }}</td>
                    <td style="font-size: 12px; padding: 12px;">{{ $row->complaint }}</td>
                    
                    <td style="text-align: center; padding: 12px;">
                        <span style="background: #dc3545; color: white; padding: 5px 12px; border-radius: 4px; font-size: 12px; display: inline-block;">
                            {{ $row->diagnosis }}
                        </span>
                    </td>
                    
                    <td style="font-size: 12px; padding: 12px;">
                        <strong>{{ $row->treatment }}</strong><br>
                        <small style="color: #666;"><em>{{ $row->doctor_notes ?? '-' }}</em></small>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Footer Verifikasi --}}
        <div class="footer">
            <p>Dokumen ini dicetak secara sah oleh sistem pada: {{ date('d/m/Y H:i') }}</p>
            <!-- <p><strong>Verifikasi sistem:</strong> Terjamin & Akurat</p> -->
        </div>
    </div>

</body>
</html>