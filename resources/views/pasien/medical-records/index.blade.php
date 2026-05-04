@extends('layouts.app_pasien')
@section('title', 'Riwayat Medis Saya')

@section('content')
<div class="content-wrapper">
    {{-- HEADER --}}
    <section class="content-header pt-3 pb-2">
        <div class="container-fluid">
            <div class="row align-items-end">
                <div class="col-sm-6">
                    {{-- Menggunakan h3 agar tidak kebesaran --}}
                    <h3 class="m-0 font-weight-bold text-dark" style="letter-spacing: -0.5px; font-size: 1.5rem;">
                        <i class="fas fa-file-medical-alt mr-2 text-info" style="font-size: 1.3rem;"></i>Riwayat Kesehatan
                    </h3>
                    <p class="text-muted mb-0 small">Daftar seluruh hasil pemeriksaan medis Anda.</p>
                </div>
                <div class="col-sm-6 d-none d-sm-block text-right">
                    <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0 small">
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
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('pasien.medical-records.index') }}">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="input-group">
                                    {{-- form-control ukuran standar lebih rapi untuk dashboard --}}
                                    <input type="search" name="search" value="{{ request('search') }}" 
                                        class="form-control border-right-0" 
                                        style="border-radius: 8px 0 0 8px;"
                                        placeholder="Cari diagnosa atau nama dokter...">
                                    <div class="input-group-append">
                                        <button class="btn btn-info px-4" type="submit" style="border-radius: 0 8px 8px 0;">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @if(request('search'))
                            <div class="col-md-2 mt-2 mt-md-0">
                                <a href="{{ route('pasien.medical-records.index') }}" class="btn btn-light btn-block border-0 shadow-sm" style="border-radius: 8px;">
                                    <i class="fas fa-undo mr-1 text-muted"></i> Reset
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
                    <div class="col-md-6 col-lg-4 mb-4"> {{-- Tambah margin bottom biar gak nempel di HP --}}
                        <div class="card shadow-sm h-100 transition-card border-0" style="border-left: 5px solid #17a2b8;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge badge-light text-info border-0 px-2 py-1" style="font-size: 0.7rem;">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        {{ \Carbon\Carbon::parse($record->examined_at)->translatedFormat('d M Y') }}
                                    </span>
                                    <!-- <small class="text-muted font-weight-bold">{{ \Carbon\Carbon::parse($record->examined_at)->format('H:i') }} WIB</small> -->
                                </div>

                                <!-- <div class="bg-light rounded p-3 mb-3 border-0">
                                    <label class="text-xs text-muted text-uppercase font-weight-bold mb-1 d-block" style="font-size: 0.65rem; letter-spacing: 0.5px;">Diagnosa Utama</label>
                                    <h6 class="font-weight-bold text-dark mb-0">
                                        <i class="fas fa-notes-medical mr-2 text-danger"></i>{{ $record->diagnosis }}
                                    </h6>
                                </div> -->

                                <div class="small px-1">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Dokter:</span>
                                        <span class="font-weight-bold text-dark text-capitalize">{{ $record->doctor->name ?? 'Dokter Klinik' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Tindakan:</span>
                                        <span class="text-dark text-right ml-3 text-truncate" style="max-width: 150px;">{{ $record->complaint }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="card-footer bg-white border-top-0 pt-0 pb-3 px-3">
                                <a href="{{ route('pasien.medical-records.show', $record->id) }}" 
                                class="btn btn-outline-info btn-sm btn-block font-weight-bold py-2 shadow-none"
                                style="border-radius: 8px; border-width: 1.5px;">
                                    Detail Rekam Medis <i class="fas fa-chevron-right ml-1 small"></i>
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