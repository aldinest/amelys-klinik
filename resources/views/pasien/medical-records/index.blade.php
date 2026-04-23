@extends('layouts.app_pasien')
@section('title', 'Riwayat Medis Saya')

@section('content')
<div class="content-wrapper">
    {{-- HEADER --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold text-dark"><i class="fas fa-file-medical-alt mr-2 text-info"></i>Riwayat Kesehatan</h1>
                    <p class="text-muted mb-0">Daftar seluruh hasil pemeriksaan medis Anda.</p>
                </div>
                <div class="col-sm-6 d-none d-sm-block">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('pasien.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Rekam Medis</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            
            {{-- SEARCH & FILTER CARD --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('pasien.medical-records.index') }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="search" name="search" value="{{ request('search') }}" 
                                           class="form-control form-control-lg border-right-0" 
                                           placeholder="Cari diagnosa atau nama dokter...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary px-4" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @if(request('search'))
                            <div class="col-md-2 mt-2 mt-md-0">
                                <a href="{{ route('pasien.medical-records.index') }}" class="btn btn-light btn-block border">
                                    <i class="fas fa-undo mr-1"></i> Reset
                                </a>
                            </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- DAFTAR REKAM MEDIS --}}
            <div class="row">
                @forelse ($medicalRecords as $record)
                    <div class="col-md-6 col-lg-4">
                        <div class="card card-outline card-info shadow-sm h-100 transition-card">
                            <div class="card-header bg-white border-bottom-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <span class="text-xs text-muted font-weight-bold text-uppercase d-block">Tanggal Periksa</span>
                                        <h5 class="font-weight-bold text-dark mb-0">
                                            {{ \Carbon\Carbon::parse($record->examined_at)->translatedFormat('d F Y') }}
                                        </h5>
                                    </div>
                                    <!-- <span class="badge badge-light p-2 border text-info">
                                        <i class="far fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($record->examined_at)->format('H:i') }}
                                    </span> -->
                                </div>
                            </div>
                            
                            <!-- <div class="card-body py-2">
                                <div class="p-3 bg-light rounded shadow-none border-0 mb-3">
                                    <label class="text-xs text-muted text-uppercase font-weight-bold mb-1 d-block">Hasil Diagnosa</label>
                                    <h6 class="font-weight-bold text-danger mb-0">
                                        <i class="fas fa-stethoscope mr-2"></i>{{ $record->diagnosis }}
                                    </h6>
                                </div> -->

                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td width="30%" class="text-muted small">Dokter</td>
                                        <td class="small font-weight-bold text-dark">: {{ $record->doctor->name ?? 'Dokter' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted small">Tindakan</td>
                                        <td class="small text-dark">: {{ Str::limit($record->complaint, 70) }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- <div class="card-footer bg-white border-top-0 pt-0 pb-3">
                                <a href="{{ route('pasien.medical-records.show', $record->id) }}" 
                                   class="btn btn-outline-info btn-sm btn-block font-weight-bold py-2 rounded-pill shadow-none">
                                    Lihat Detail Pemeriksaan <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div> -->
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="card border-0 shadow-sm py-5 rounded-lg">
                            <div class="card-body">
                                <div class="mb-4">
                                    <i class="fas fa-file-medical fa-4x text-muted opacity-25"></i>
                                </div>
                                <h4 class="text-dark font-weight-bold">Belum Ada Catatan Medis</h4>
                                <p class="text-muted mb-4 px-md-5">Riwayat pemeriksaan Anda di Klinik Amelys akan muncul di sini secara otomatis setelah pemeriksaan selesai.</p>
                                <a href="{{ route('pasien.dashboard') }}" class="btn btn-primary btn-lg rounded-pill px-5">
                                    <i class="fas fa-calendar-plus mr-2"></i> Buat Reservasi
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $medicalRecords->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>

        </div>
    </section>
</div>

<style>
    /* AdminLTE Enhancements */
    .content-wrapper { background-color: #f8f9fb !important; }
    .card { border-radius: 12px; }
    .card-info.card-outline { border-top: 4px solid #17a2b8; border-radius: 12px; }
    
    .text-xs { font-size: 0.75rem; letter-spacing: 0.05rem; }
    .rounded-lg { border-radius: 15px; }
    
    /* Card Animation */
    .transition-card { 
        transition: transform 0.2s ease, box-shadow 0.2s ease; 
    }
    .transition-card:hover { 
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }

    /* Custom Buttons */
    .btn-outline-info {
        border-width: 1.5px;
        transition: all 0.3s;
    }
    .btn-outline-info:hover {
        background-color: #17a2b8;
        color: #fff !important;
    }

    /* Pagination Styling */
    .pagination .page-item.active .page-link {
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
</style>
@endsection