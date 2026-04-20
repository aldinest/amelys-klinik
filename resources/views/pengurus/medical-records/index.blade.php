@extends('layouts.app_pengurus')

@section('title', 'Rekam Medis')

@section('content')
<div class="content-wrapper">
    {{-- HEADER --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Rekam Medis Pasien</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('pengurus.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Rekam Medis</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            
            {{-- TOMBOL EKSPORT/IMPORT (Sesuai gaya di foto) --}}
            <div class="mb-3">
                <a href="{{ route('pengurus.patients.export') }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
                <button class="btn btn-primary" data-toggle="modal" data-target="#importModal">
                    <i class="fas fa-upload"></i> Import Excel
                </button>
            </div>

            <div class="card shadow-sm">
                <div class="card-header border-0 bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        {{-- Judul Tabel --}}
                        <h3 class="card-title text-muted font-weight-bold">Daftar Rekam Medis</h3>
                        
                        {{-- SEARCH BOX (Sesuai posisi di foto) --}}
                        <div class="card-tools">
                            <form method="GET" action="{{ route('pengurus.medical-records.index') }}">
                                <div class="input-group" style="width: 250px;">
                                    <input type="text" name="search" class="form-control" placeholder="Cari nama pasien..." value="{{ request('search') }}">
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

                {{-- TABLE BODY --}}
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
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
                                        <td class="text-center align-middle">{{ $medicalRecords->firstItem() + $loop->index }}</td>
                                        <td class="align-middle">
                                            <span class="font-weight-bold">{{ $record->patient->name ?? '-' }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge badge-light border text-primary">
                                                {{ $record->patient->medical_record_number ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <i class="fas fa-user-md text-muted mr-1"></i> 
                                            {{ $record->doctor->name ?? '-' }}
                                        </td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('pengurus.medical-records.show', $record->id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            Data rekam medis tidak ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                    <p class="mb-0 small text-muted">
                        Menampilkan {{ $medicalRecords->firstItem() ?? 0 }} - {{ $medicalRecords->lastItem() ?? 0 }} dari {{ $medicalRecords->total() ?? 0 }} data
                    </p>
                    <div>
                        {{ $medicalRecords->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection