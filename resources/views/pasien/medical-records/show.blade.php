@extends('layouts.app_pasien')

@section('title', 'Detail Rekam Medis')

@section('content')
<div class="content-wrapper" style="background-color: #f4f6f9;">
    {{-- HEADER --}}
    <section class="content-header pt-3 pb-2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9 mx-auto d-flex align-items-center justify-content-between px-3 px-md-0">
                    <a href="{{ route('pasien.medical-records.index') }}" class="btn btn-light btn-sm shadow-sm border" style="border-radius: 8px;">
                        <i class="fas fa-chevron-left mr-1 text-primary"></i> Kembali
                    </a>
                    <h5 class="font-weight-bold text-dark mb-0">Detail Pemeriksaan</h5>
                </div>
            </div>
        </div>
    </section>
        
    {{-- CONTENT --}}
    <section class="content pb-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9 mx-auto">
                    
                    {{-- DOKUMEN RESUME MEDIS --}}
                    <div class="card shadow-sm border-0 mb-5" style="border-radius: 20px; overflow: hidden;">
                        <div class="card-header bg-white border-bottom py-3 px-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary-light p-2 rounded mr-3" style="background-color: rgba(13, 138, 188, 0.1);">
                                        <i class="fas fa-file-medical text-primary" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <div>
                                        <h6 class="font-weight-bold text-dark mb-0">RESUME MEDIS DIGITAL</h6>
                                        <small class="text-muted">No: #MR-{{ $record->id }}{{ date('Ymd', strtotime($record->examined_at)) }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4 p-md-5">
                            {{-- KOP SURAT --}}
                            <div class="row mb-4">
                                <div class="col-sm-7">
                                    <h4 class="font-weight-bold text-primary mb-1">AMELYS KLINIK</h4>
                                    <p class="text-dark small mb-0 font-weight-bold">Layanan Kesehatan Terpadu</p>
                                    <p class="text-muted small">Jl. Jend. Ahmad Yani No.118, Krajan, Surodikraman, Ponorogo</p>
                                </div>
                                <div class="col-sm-5 text-sm-right mt-3 mt-sm-0">
                                    <div class="p-3 border rounded shadow-xs bg-light" style="border-style: dashed !important; border-width: 2px !important;">
                                        <small class="d-block text-uppercase font-weight-bold text-muted mb-1">Tanggal Periksa</small>
                                        <span class="h6 font-weight-bold text-dark">{{ \Carbon\Carbon::parse($record->examined_at)->translatedFormat('d F Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            <hr style="border-top: 2px solid #eee;">

                            {{-- INFO PASIEN & DOKTER --}}
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="p-3 rounded bg-light h-100">
                                        <label class="small text-muted text-uppercase font-weight-bold mb-2 d-block">Dokter Pemeriksa</label>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white rounded-circle p-2 shadow-sm mr-3">
                                                <i class="fas fa-user-md text-primary" style="font-size: 1.2rem;"></i>
                                            </div>
                                            <div>
                                                <h6 class="font-weight-bold mb-0 text-dark">Dr. {{ $record->doctor->name ?? 'Tidak Diketahui' }}</h6>
                                                <small class="text-muted">Spesialis Dokter Gigi</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded bg-light h-100 border-left border-primary" style="border-left-width: 4px !important;">
                                        <label class="small text-muted text-uppercase font-weight-bold mb-2 d-block">Informasi Pasien</label>
                                        <h6 class="font-weight-bold mb-0 text-dark text-capitalize">{{ auth()->user()->name }}</h6>
                                        <small class="text-muted font-weight-bold">RM: {{ auth()->user()->patient->medical_record_number }}</small>
                                    </div>
                                </div>
                            </div>

                            {{-- HASIL PEMERIKSAAN --}}
                            <div class="mt-4">
                                <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-notes-medical mr-2 text-primary"></i>Hasil Klinis</h6>
                                <div class="table-responsive rounded shadow-xs border">
                                    <table class="table mb-0">
                                        <tbody>
                                            <tr>
                                                <td width="30%" class="bg-light font-weight-bold text-muted small py-3">KELUHAN</td>
                                                <td class="py-3 text-dark">{{ $record->complaint ?? 'Tidak ada data keluhan.' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="bg-light font-weight-bold text-muted small py-3">DIAGNOSA</td>
                                                <td class="py-3"><span class="badge badge-danger px-3 py-2" style="font-size: 0.9rem;">{{ $record->diagnosis }}</span></td>
                                            </tr>
                                            <tr>
                                                <td class="bg-light font-weight-bold text-muted small py-3">TINDAKAN</td>
                                                <td class="py-3 text-dark font-weight-bold">{{ $record->treatment ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="bg-light font-weight-bold text-muted small py-3">SARAN DOKTER</td>
                                                <td class="py-3 text-muted"><em>{{ $record->doctor_notes ?? 'Tidak ada catatan khusus.' }}</em></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- FOOTER --}}
                        <div class="card-footer bg-white py-4 border-top">
                            <div class="row align-items-center">
                                <div class="col-sm-6 text-center text-sm-left mb-3 mb-sm-0">
                                    <p class="small text-muted mb-0 italic">
                                        <i class="fas fa-check-circle text-success mr-1"></i> Terverifikasi secara sistem pada {{ date('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <div class="col-sm-6 text-center text-sm-right">
                                    <button onclick="window.print()" class="btn btn-primary px-4 shadow-sm font-weight-bold" style="border-radius: 10px;">
                                        <i class="fas fa-print mr-2"></i> Cetak Hasil
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
    /* Styling untuk Detail yang lebih Clean */
    .bg-primary-light { background-color: rgba(13, 138, 188, 0.1); }
    .table td { vertical-align: middle; border-top: 1px solid #eee; }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,.02); }

    @media (max-width: 768px) {
        .content {
            padding-bottom: 100px !important;
        }
        .card {
            margin-bottom: 120px !important;
            border-radius: 0 !important;
            margin-left: -15px;
            margin-right: -15px;
        }
    }

    @media print {
        .btn, .main-footer, .main-sidebar, .content-header, .mobile-nav {
            display: none !important;
        }
        .content-wrapper {
            margin: 0 !important;
            padding: 0 !important;
            background: white !important;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #eee !important;
        }
    }
</style>
@endsection