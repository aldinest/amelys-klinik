@extends('layouts.app_pengurus')

@section('title', 'Detail Rekam Medis')

@section('content')
<div class="content-wrapper">
    {{-- HEADER HALAMAN --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Detail Rekam Medis</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <button onclick="window.print()" class="btn btn-dark btn-sm shadow-sm no-print">
                        <i class="fas fa-print mr-1"></i> Cetak Rekam Medis
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            
            {{-- BAGIAN 1: IDENTITAS PASIEN (Header Biru) --}}
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-circle mr-2"></i> Identitas Pasien
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 border-right">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%" class="text-muted">No. Rekam Medis</td>
                                    <td>: <strong>{{ $medicalRecord->reservation->patient->medical_record_number ?? '-' }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Nama Pasien</td>
                                    <td class="text-capitalize">: {{ $medicalRecord->reservation->patient->name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jenis Kelamin</td>
                                    <td>: {{ $medicalRecord->reservation->patient->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%" class="text-muted">Tgl Lahir / Usia</td>
                                    <td>: {{ $medicalRecord->reservation->patient->date_of_birth ?? '-' }} 
                                        <small class="text-muted">({{ \Carbon\Carbon::parse($medicalRecord->reservation->patient->date_of_birth)->age }} Thn)</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">No. Telepon</td>
                                    <td>: {{ $medicalRecord->reservation->patient->phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Alamat</td>
                                    <td class="text-capitalize">: {{ $medicalRecord->reservation->patient->address ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

           {{-- BAGIAN 2: RIWAYAT PEMERIKSAAN --}}
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title text-white">
                        <i class="fas fa-history mr-2 text-white"></i> Riwayat Pemeriksaan & Pengobatan
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr class="text-muted small">
                                    <th class="text-center" style="width: 12%">TANGGAL</th>
                                    <th style="width: 18%">DOKTER</th>
                                    <th style="width: 20%">KELUHAN</th>
                                    <th style="width: 20%">DIAGNOSA</th>
                                    <th style="width: 15%">TINDAKAN</th>
                                    <th style="width: 15%">CATATAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($history as $row)
                                <tr class="align-middle">
                                    <td class="text-center">
                                        <span class="d-block font-weight-bold">{{ $row->created_at->format('d/m/Y') }}</span>
                                        <small class="text-muted">{{ $row->created_at->format('H:i') }}</small>
                                    </td>
                                    <td class="text-dark">dr. {{ $row->reservation->doctorSchedule->doctor->name }}</td>
                                    <td>{{ $row->complaint }}</td>
                                    <td class="text-danger font-weight-bold">{{ $row->diagnosis ?? '-' }}</td>
                                    <td class="text-success font-weight-bold">{{ $row->treatment ?? '-' }}</td>
                                    <td class="small text-muted">{{ $row->notes ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">Belum ada riwayat rekam medis.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                {{-- FOOTER DENGAN TOMBOL WARNA PUTIH --}}
                <div class="card-footer bg-white no-print">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pengurus.medical-records.index') }}" class="btn btn-secondary text-white shadow-sm px-4">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- CSS KHUSUS UNTUK PERAPIAN --}}
<style>
    /* Styling Tabel agar lebih seimbang */
    .table-valign-middle td {
        vertical-align: middle !important;
    }
    .table thead th {
        border-bottom: 2px solid #dee2e6 !important;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Aturan Cetak (Print) */
    @media print {
        .no-print, .main-footer, .content-header, .btn, .breadcrumb {
            display: none !important;
        }
        .content-wrapper {
            margin-left: 0 !important;
            padding-top: 0 !important;
        }
        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
            margin-bottom: 20px !important;
        }
        .card-header.bg-info {
            background-color: #17a2b8 !important;
            color: #fff !important;
            -webkit-print-color-adjust: exact;
        }
    }
</style>
@endsection