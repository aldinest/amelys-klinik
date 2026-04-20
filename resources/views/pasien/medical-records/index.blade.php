@extends('layouts.app_pasien')
@section('title', 'Riwayat Medis Saya')

@section('content')
<div class="content-wrapper">
    {{-- HEADER --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold text-dark">Riwayat Kesehatan</h1>
                </div>
                <div class="col-sm-6">
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
            <div class="card card-default shadow-none border">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('pasien.medical-records.index') }}">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input type="search" name="search" value="{{ request('search') }}" 
                                           class="form-control" placeholder="Cari diagnosa atau nama dokter...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search mr-1"></i> Cari
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @if(request('search'))
                            <div class="col-md-2">
                                <a href="{{ route('pasien.medical-records.index') }}" class="btn btn-default">Reset</a>
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
                        {{-- Card dengan border-left tebal khas AdminLTE Info/Primary --}}
                        <div class="card card-outline card-info shadow-none border">
                            <div class="card-header border-bottom-0">
                                <h3 class="card-title text-muted font-weight-bold">
                                    <i class="fas fa-calendar-day mr-1"></i> 
                                    {{ \Carbon\Carbon::parse($record->examined_at)->translatedFormat('d F Y') }}
                                </h3>
                                <div class="card-tools">
                                    <span class="badge badge-light border">{{ \Carbon\Carbon::parse($record->examined_at)->format('H:i') }} WIB</span>
                                </div>
                            </div>
                            
                            <div class="card-body py-2">
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <th width="100px" class="text-muted small uppercase">Dokter</th>
                                        <td>: <strong class="text-dark">{{ $record->doctor->name ?? 'Dokter' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted small uppercase">Diagnosa</th>
                                        <td>: <span class="badge badge-danger">{{ $record->diagnosis }}</span></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted small uppercase">Keluhan</th>
                                        <td class="small">: {{ Str::limit($record->complaint, 80) }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="card-footer bg-transparent border-top-0 pt-0 pb-3">
                                <a href="{{ route('pasien.medical-records.show', $record->id) }}" 
                                   class="btn btn-info btn-sm btn-block shadow-sm">
                                    <i class="fas fa-eye mr-1"></i> Lihat Detail Rekam Medis
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="card border shadow-none py-5">
                            <div class="card-body">
                                <i class="fas fa-notes-medical fa-4x text-muted mb-3 opacity-25"></i>
                                <h4 class="text-muted font-weight-bold">Belum Ada Riwayat Medis</h4>
                                <p class="text-muted">Data pemeriksaan Anda akan tampil di sini secara otomatis.</p>
                                <a href="{{ route('pasien.dashboard') }}" class="btn btn-primary px-4">
                                    <i class="fas fa-plus mr-1"></i> Buat Reservasi Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $medicalRecords->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>

        </div>
    </section>
</div>

<style>
    /* AdminLTE Contrast Fix */
    .card-outline.card-info { border-top: 3px solid #17a2b8; }
    .uppercase { text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.7rem; }
    .table-sm th, .table-sm td { padding: 0.4rem 0; }
    .content-wrapper { background-color: #f4f6f9 !important; }
    
    /* Tombol lebih tegas */
    .btn-info { background-color: #17a2b8; border-color: #17a2b8; color: #fff !important; }
    .btn-info:hover { background-color: #138496; }
</style>
@endsection