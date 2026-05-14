@extends('layouts.app_pengurus')

@section('title', 'Detail Rekam Medis')

@section('content')
<div class="content-wrapper">
    {{-- Alert Sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle mr-2 fa-lg"></i>
                <div>
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
            </div>
            <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Alert Error (Jika validasi gagal) --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle mr-2 fa-lg"></i>
                <div>
                    <strong>Perhatian!</strong> Mohon periksa kembali inputan Anda.
                </div>
            </div>
            <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <section class="content-header px-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-6">
                    <h1 class="font-weight-bold m-0">Detail Rekam Medis</h1>
                </div>
                <div class="col-6 text-right no-print">
                    <button onclick="window.print()" class="btn btn-dark btn-sm shadow-sm" style="background-color: #212529;">
                        <i class="fas fa-print mr-1"></i> Cetak Rekam Medis
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="content px-3">
        <div class="container-fluid">
            
            {{-- IDENTITAS PASIEN --}}
            <div class="card shadow-sm mb-4" style="border-top: 3px solid #007bff; border-radius: 0;">
                <div class="card-header bg-white py-3">
                    <h3 class="card-title font-weight-bold" style="font-size: 1.1rem; color: #333;">
                        <i class="fas fa-user-circle mr-2"></i> Identitas Pasien
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 border-right">
                            <table class="table table-sm table-borderless mb-0">
                                <tr style="height: 35px;">
                                    <td width="40%" class="text-muted">No. Rekam Medis</td>
                                    <td>: <strong>{{ $medicalRecord->reservation->patient->medical_record_number }}</strong></td>
                                </tr>
                                <tr style="height: 35px;">
                                    <td class="text-muted">Nama Pasien</td>
                                    <td>: {{ $medicalRecord->reservation->patient->name }}</td>
                                </tr>
                                <tr style="height: 35px;">
                                    <td class="text-muted">Jenis Kelamin</td>
                                    <td>: {{ $medicalRecord->reservation->patient->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless mb-0">
                                <tr style="height: 35px;">
                                    <td width="40%" class="text-muted">Tgl Lahir / Usia</td>
                                    <td class="py-2">: {{ \Carbon\Carbon::parse($medicalRecord->reservation->patient->date_of_birth)->format('Y-m-d') }} 
                                        <small class="text-muted">({{ \Carbon\Carbon::parse($medicalRecord->reservation->patient->date_of_birth)->age }} Thn)</small>
                                    </td>
                                </tr>
                                <tr style="height: 35px;">
                                    <td class="text-muted">No. Telepon</td>
                                    <td>: {{ $medicalRecord->reservation->patient->phone }}</td>
                                </tr>
                                <tr style="height: 35px;">
                                    <td class="text-muted">Alamat</td>
                                    <td>: {{ $medicalRecord->reservation->patient->address }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIWAYAT PEMERIKSAAN & PENGOBATAN --}}
            <div class="card shadow-sm border-0" style="border-radius: 0;">
                {{-- Warna Toska dikoreksi ke #3ba2ac --}}
                <div class="card-header py-3" style="background-color: #3ba2ac; color: white;">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-history mr-2"></i> Riwayat Pemeriksaan & Pengobatan
                    </h3>
                </div>
                
                {{-- DESKTOP TABLE --}}
                <div class="card-body p-0 d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="bg-light">
                                <tr class="text-center small font-weight-bold text-muted" style="letter-spacing: 0.5px;">
                                    <th class="py-3">TANGGAL</th>
                                    <th class="py-3">DOKTER</th>
                                    <th class="py-3">KELUHAN</th>
                                    <th class="py-3">DIAGNOSA</th>
                                    <th class="py-3">TINDAKAN</th>
                                    <th class="py-3">CATATAN</th>
                                    <th class="py-3 no-print">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($history as $row)
                                <tr class="text-center" style="font-size: 0.85rem; vertical-align: middle;">
                                    <td class="py-4">
                                        <div class="font-weight-bold">{{ $row->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $row->created_at->format('H:i') }}</small>
                                    </td>
                                    <td class="py-4 text-muted">dr. {{ $row->reservation->doctorSchedule->doctor->name }}</td>
                                    <td class="py-4 text-muted text-left" style="max-width: 200px;">{{ $row->complaint }}</td>
                                    <td class="py-4 font-weight-bold" style="color: #c0392b;">{{ $row->diagnosis }}</td>
                                    <td class="py-4 font-weight-bold" style="color: #27ae60;">{{ $row->treatment }}</td>
                                    <td class="py-4 text-muted small text-left" style="max-width: 200px;">{{ $row->doctor_notes ?? '-' }}</td>
                                    <td class="py-4 no-print">
                                        {{-- Ubah button modal menjadi link ke halaman edit --}}
                                        <a href="{{ route('pengurus.medical-records.edit', $row->id) }}" class="btn btn-warning btn-xs shadow-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- MOBILE CARDS (Data Lengkap) --}}
                <div class="card-body p-3 d-md-none no-print" style="background-color: #f4f6f9;">
                    @foreach ($history as $row)
                        <div class="card shadow-sm border-0 mb-3" style="border-radius: 8px;">
                            <div class="card-body p-3">
                                {{-- Baris Header Card --}}
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge badge-pill px-3 py-2" style="background-color: #e3f2fd; color: #3ba2ac; font-size: 0.8rem;">
                                        <i class="far fa-calendar-alt mr-1"></i> {{ $row->created_at->format('d M Y') }}
                                    </span>
                                        <a href="{{ route('pengurus.medical-records.edit', $row->id) }}" class="btn btn-warning btn-xs shadow-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                </div>

                                {{-- Detail Data --}}
                                <div class="mb-2">
                                    <small class="text-muted d-block uppercase font-weight-bold" style="font-size: 0.65rem;">DOKTER</small>
                                    <span class="text-dark font-weight-bold">{{ $row->reservation->doctorSchedule->doctor->name }}</span>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-6">
                                        <small class="text-muted d-block font-weight-bold" style="font-size: 0.65rem;">DIAGNOSA</small>
                                        <span style="color: #c0392b; font-weight: bold; font-size: 0.9rem;">{{ $row->diagnosis }}</span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block font-weight-bold" style="font-size: 0.65rem;">TINDAKAN</small>
                                        <span style="color: #27ae60; font-weight: bold; font-size: 0.9rem;">{{ $row->treatment }}</span>
                                    </div>
                                </div>

                                <div class="mb-2 p-2 rounded" style="background-color: #f8f9fa; border-left: 3px solid #dee2e6;">
                                    <small class="text-muted d-block font-weight-bold" style="font-size: 0.65rem;">KELUHAN</small>
                                    <span class="text-dark" style="font-size: 0.85rem;">{{ $row->complaint }}</span>
                                </div>

                                @if($row->doctor_notes)
                                <div class="mb-0 p-2 rounded" style="background-color: #fffde7; border-left: 3px solid #ffd54f;">
                                    <small class="text-muted d-block font-weight-bold" style="font-size: 0.65rem;">CATATAN</small>
                                    <span class="text-dark italic" style="font-size: 0.85rem;">{{ $row->doctor_notes }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="card-body pt-0 pb-4">
                    {{-- Tombol Kembali sesuai gambar (Abu-abu gelap) --}}
                    <a href="{{ route('pengurus.medical-records.index') }}" class="btn px-4 py-2 font-weight-bold shadow-sm no-print" style="background-color: #6c757d; color: white;">
                        <i class="fas fa-reply mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .table td { border-top: 1px solid #eee; vertical-align: middle !important; }
    .btn-xs { padding: 0.1rem 0.5rem; font-size: 0.75rem; }
    @media print {
        .no-print { display: none !important; }
        .card-header { background-color: #3ba2ac !important; color: white !important; -webkit-print-color-adjust: exact; }
    }
</style>
@endsection