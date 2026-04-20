@extends('layouts.app_pasien')

@section('title', 'Detail Rekam Medis')

@section('content')
<div class="content-wrapper">
    {{-- HEADER --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                {{-- Bagian ini kuncinya: col-md-9 (atau col-md-10) harus sama dengan lebar kartu kontenmu --}}
                <div class="col-md-9 mx-auto d-flex align-items-center justify-content-between">
                    
                    {{-- Tombol Kembali di Kiri --}}
                    <a href="{{ route('pasien.medical-records.index') }}" class="btn btn-default btn-sm shadow-sm border">
                        <i class="fas fa-chevron-left mr-1"></i> Kembali ke Daftar
                    </a>

                    {{-- Judul di Kanan --}}
                    <h1 class="font-weight-bold text-dark mb-0" style="font-size: 1.8rem;">
                        Detail Pemeriksaan
                    </h1>

                </div>
            </div>
        </div>
    </section>
        
    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9 mx-auto">
                    
                    {{-- DOKUMEN RESUME MEDIS --}}
                    <div class="card card-outline card-primary shadow-none border">
                        <div class="card-header border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="card-title font-weight-bold text-primary">
                                        <i class="fas fa-file-alt mr-2"></i> RESUME MEDIS DIGITAL
                                    </h3>
                                </div>
                                <div class="text-right">
                                    <span class="text-muted small">No. Referensi: #MR-{{ $record->id }}{{ date('Ymd', strtotime($record->examined_at)) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            {{-- KOP SURAT MINI --}}
                            <div class="row mb-4">
                                <div class="col-sm-8">
                                    <h4 class="font-weight-bold mb-1">AMELYS KLINIK</h4>
                                    <p class="text-muted small mb-0">Layanan Kesehatan Terpadu Amelys Klinik</p>
                                    <p class="text-muted small">Jl. Jend. Ahmad Yani No.118, Krajan, Surodikraman, Ponorogo, Jawa Timur</p>
                                </div>
                                <div class="col-sm-4 text-sm-right">
                                    <div class="p-2 border bg-light rounded text-center">
                                        <small class="d-block text-uppercase font-weight-bold text-muted">Tanggal Periksa</small>
                                        <span class="h6 font-weight-bold">{{ \Carbon\Carbon::parse($record->examined_at)->translatedFormat('d F Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            <hr class="mb-4">

                            {{-- INFO PASIEN & DOKTER --}}
                            <div class="row mb-4">
                                <div class="col-md-6 border-right">
                                    <label class="small text-muted text-uppercase font-weight-bold">Informasi Dokter</label>
                                    <div class="d-flex align-items-center mt-2">
                                        <i class="fas fa-user-md fa-2x text-primary mr-3"></i>
                                        <div>
                                            <h6 class="font-weight-bold mb-0">Dr. {{ $record->doctor->name ?? 'Tidak Diketahui' }}</h6>
                                            <small class="text-muted">Dokter Pemeriksa Utama</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 pl-md-4">
                                    <label class="small text-muted text-uppercase font-weight-bold">Informasi Pasien</label>
                                    <div class="mt-2">
                                        <h6 class="font-weight-bold mb-0">{{ strtoupper(auth()->user()->name) }}</h6>
                                        <small class="text-muted">No. RM: {{ auth()->user()->patient->medical_record_number }}</small>
                                    </div>
                                </div>
                            </div>

                            {{-- HASIL PEMERIKSAAN --}}
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th width="30%" class="small font-weight-bold">KATEGORI</th>
                                                    <th class="small font-weight-bold">HASIL PEMERIKSAAN / KETERANGAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="font-weight-bold bg-light"><i class="fas fa-comment-dots mr-2 text-info"></i>Keluhan</td>
                                                    <td class="text-justify">{{ $record->complaint ?? 'Tidak ada data keluhan.' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold bg-light"><i class="fas fa-stethoscope mr-2 text-danger"></i>Diagnosa</td>
                                                    <td><span class="h6 font-weight-bold text-danger">{{ $record->diagnosis }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold bg-light"><i class="fas fa-pills mr-2 text-success"></i>Tindakan / Terapi</td>
                                                    <td>{{ $record->treatment ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold bg-light"><i class="fas fa-sticky-note mr-2 text-warning"></i>Saran Dokter</td>
                                                    <td class="text-italic">{{ $record->doctor_notes ?? 'Tidak ada catatan khusus.' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- FOOTER --}}
                        <div class="card-footer bg-white py-4 border-top">
                            <div class="row align-items-center">
                                <div class="col-sm-6 text-center text-sm-left">
                                    <p class="small text-muted mb-0 italic">
                                        * Dokumen ini sah dan diterbitkan secara digital.
                                    </p>
                                </div>
                                <div class="col-sm-6 text-center text-sm-right mt-3 mt-sm-0">
                                    <button onclick="window.print()" class="btn btn-primary px-4 shadow-sm">
                                        <i class="fas fa-print mr-2"></i> Cetak Hasil Pemeriksaan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .card-outline.card-primary { border-top: 3px solid #007bff; }
    .table-bordered th, .table-bordered td { border: 1px solid #dee2e6 !important; padding: 12px; }
    .bg-light { background-color: #f8f9fa !important; }
    .text-italic { font-style: italic; }

    @media print {
        .btn, .main-footer, .main-sidebar, .content-header, .breadcrumb {
            display: none !important;
        }
        .content-wrapper {
            margin: 0 !important;
            padding: 0 !important;
            background: white !important;
        }
        .card {
            border: 2px solid #000 !important;
            box-shadow: none !important;
        }
        .card-outline { border-top: 5px solid #000 !important; }
    }
</style>
@endsection