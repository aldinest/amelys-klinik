@extends('layouts.app_pengurus')

@section('title', 'Rekam Medis')

@section('content')
<div class="content-wrapper">
    {{-- HEADER: Hanya nambah align-items-center supaya sejajar --}}
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="font-weight-bold">Rekam Medis Pasien</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('pengurus.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Rekam Medis</li>
            </ol>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            
            {{-- TOMBOL ACTION: Tetap seperti asli --}}
            <div class="mb-3">
                <a href="{{ route('pengurus.patients.export') }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
                <button class="btn btn-primary" data-toggle="modal" data-target="#importModal">
                    <i class="fas fa-upload"></i> Import Excel
                </button>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h3 class="card-title font-weight-bold text-muted">Daftar Rekam Medis</h3>
                        
                        <div class="card-tools">
                            <form method="GET" action="{{ route('pengurus.medical-records.index') }}">
                                <div class="input-group" style="width: 250px;">
                                    <input type="text" name="search" class="form-control" placeholder="Cari pasien..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-secondary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- TABLE BODY (Desktop) --}}
                <div class="card-body p-0 d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 60px" class="text-center">No</th>
                                    <th>Nama Pasien</th>
                                    <th>No. RM</th>
                                    <th>Dokter</th>
                                    <th style="width: 120px" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($medicalRecords as $record)
                                    <tr>
                                        <td class="text-center align-middle text-muted">{{ $medicalRecords->firstItem() + $loop->index }}</td>
                                        <td class="align-middle font-weight-bold">{{ $record->patient->name ?? '-' }}</td>
                                        <td class="align-middle text-center">
                                            <span class="badge badge-light border w-100 py-2">{{ $record->patient->medical_record_number ?? '-' }}</span>
                                        </td>
                                        <td class="align-middle text-muted"><i class="fas fa-user-md mr-1"></i> {{ $record->doctor->name ?? '-' }}</td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('pengurus.medical-records.show', $record->id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-4 text-muted font-weight-bold">Data tidak ditemukan</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- MOBILE VIEW (Cards) --}}
                <div class="card-body p-3 d-md-none" style="background-color: #f4f6f9;">
                    @forelse ($medicalRecords as $record)
                        <div class="card shadow-sm border-0 mb-3" style="border-radius: 8px;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="font-weight-bold mb-0">{{ $record->patient->name ?? '-' }}</h6>
                                        <small class="text-muted">{{ $record->patient->medical_record_number ?? '-' }}</small>
                                    </div>
                                    <a href="{{ route('pengurus.medical-records.show', $record->id) }}" class="btn btn-info btn-xs px-2">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </div>
                                <hr class="my-2">
                                <div class="small">
                                    <i class="fas fa-user-md text-info mr-1"></i> 
                                    <strong>Dokter:</strong> {{ $record->doctor->name ?? '-' }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">Data tidak ditemukan</div>
                    @endforelse
                </div>

                {{-- FOOTER --}}
                <div class="card-footer bg-white py-3">
                    <div class="row align-items-center">
                        {{-- Info Data di Kiri --}}
                        <div class="col-md-6 text-center text-md-left mb-3 mb-md-0">
                            <small class="text-muted">
                                Menampilkan <strong>{{ $medicalRecords->firstItem() ?? 0 }}</strong> sampai <strong>{{ $medicalRecords->lastItem() ?? 0 }}</strong> dari <strong>{{ $medicalRecords->total() ?? 0 }}</strong> data
                            </small>
                        </div>
                        
                        {{-- Pagination di Kanan --}}
                        <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
                            {{-- Pakai simpleBootstrapFour agar tidak muncul teks 'Showing...' tambahan --}}
                            {{ $medicalRecords->appends(request()->query())->links('pagination::simple-bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- MODAL IMPORT: Tetap seperti asli --}}
<div class="modal fade" id="importModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('pengurus.patients.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Import Rekam Medis</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>File Excel</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Unggah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection