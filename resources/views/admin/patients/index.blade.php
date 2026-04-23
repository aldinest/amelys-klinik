@extends('layouts.applte')

@section('content')
<div class="content-wrapper">

    {{-- ALERT MESSAGES --}}
    <div class="mx-3 pt-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                {{ session('success') }}
                <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <h5><i class="icon fas fa-ban"></i> Error!</h5>
                {{ session('error') }}
                <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">&times;</button>
            </div>
        @endif
    </div>

    {{-- PAGE HEADER --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Data Pasien</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Data Pasien</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    {{-- Diperbaiki agar responsif di HP --}}
                    <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
                        
                        {{-- Tombol Utama & Action Buttons --}}
                        <div class="d-flex flex-wrap align-items-center" style="gap: 10px;">
                            <a href="{{ route('admin.patients.create') }}" class="btn btn-primary shadow-sm">
                                <i class="fas fa-plus-circle mr-1"></i> Tambah Pasien
                            </a>

                            <a href="{{ route('admin.patients.export') }}" class="btn btn-success shadow-sm">
                                <i class="fas fa-file-excel mr-1"></i> Export Excel
                            </a>

                            <form action="{{ route('admin.patients.import') }}" method="POST" enctype="multipart/form-data" class="d-inline">
                                @csrf
                                <input type="file" name="file" id="fileImport" class="d-none" required onchange="this.form.submit()">
                                <button type="button" class="btn btn-info shadow-sm text-white" onclick="document.getElementById('fileImport').click()">
                                    <i class="fas fa-upload mr-1"></i> Import Excel
                                </button>
                            </form>
                        </div>

                        {{-- Search Form --}}
                        <div class="flex-grow-1 flex-md-grow-0">
                            <form action="{{ route('admin.patients.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Cari nama pasien..." value="{{ request('search') }}">
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
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 50px" class="text-center">No</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Nomor HP</th>
                                    <th style="width: 250px" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($patients as $patient)
                                    <tr>
                                        <td class="text-center align-middle">
                                            {{ $loop->iteration + ($patients->currentPage() - 1) * $patients->perPage() }}
                                        </td>
                                        <td class="align-middle font-weight-bold">{{ $patient->name }}</td>
                                        <td class="align-middle">{{ $patient->address }}</td>

                                        {{-- 1. BAGIAN JENIS KELAMIN (DENGAN BADGE AGAR LEBIH RAPI) --}}
                                        <td class="align-middle text-center">
                                            @if($patient->gender == 'L')
                                                <span class="badge badge-info px-2 py-1">Laki-laki</span>
                                            @else
                                                <span class="badge badge-danger px-2 py-1" style="background-color: #e83e8c;">Perempuan</span>
                                            @endif
                                        </td>

                                        {{-- 2. BAGIAN NOMOR HP (DENGAN LOGIKA WHATSAPP) --}}
                                        <td class="align-middle font-weight-bold">
                                            @php
                                                // Membersihkan karakter non-angka dan mengubah 0 ke 62
                                                $nomorBersih = preg_replace('/[^0-9]/', '', $patient->phone);
                                                if (substr($nomorBersih, 0, 1) === '0') {
                                                    $nomorWA = '62' . substr($nomorBersih, 1);
                                                } else {
                                                    $nomorWA = $nomorBersih;
                                                }
                                            @endphp
                                            <a href="https://wa.me/{{ $nomorWA }}" target="_blank" class="text-dark">
                                                <i class="fab fa-whatsapp text-success mr-1"></i>{{ $patient->phone }}
                                            </a>
                                        </td>

                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center text-nowrap" style="gap: 5px;">
                                                <a href="{{ route('admin.patients.show', $patient->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                                <a href="{{ route('admin.patients.edit', $patient->id) }}" class="btn btn-warning btn-sm text-white">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('admin.patients.destroy', $patient->id) }}" method="POST"
                                                    onsubmit="return confirm('Yakin mau hapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm" type="submit">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fas fa-users fa-3x mb-3 opacity-25"></i><br>
                                            Data pasien tidak ditemukan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- FOOTER / PAGINATION --}}
                <div class="card-footer bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col-sm-12 col-md-6 text-center text-md-left mb-3 mb-md-0">
                            <p class="mb-0 small text-muted">
                                Menampilkan <strong>{{ $patients->firstItem() ?? 0 }}</strong> sampai 
                                <strong>{{ $patients->lastItem() ?? 0 }}</strong> dari 
                                <strong>{{ $patients->total() ?? 0 }}</strong> data
                            </p>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            {{-- Wrapper Scrollable untuk Pagination --}}
                            <div class="pagination-responsive-wrapper">
                                {{ $patients->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    /* Menangani Pagination agar bisa di-scroll ke samping pada layar kecil */
    .pagination-responsive-wrapper {
        display: flex;
        justify-content: center;
    }

    @media (min-width: 768px) {
        .pagination-responsive-wrapper {
            justify-content: flex-end;
        }
    }

    @media (max-width: 767.98px) {
        .pagination-responsive-wrapper {
            overflow-x: auto;
            display: block;
            white-space: nowrap;
            padding: 5px 0;
            -webkit-overflow-scrolling: touch;
        }
        
        .pagination-responsive-wrapper .pagination {
            display: inline-flex;
            margin-bottom: 0;
        }

        .page-link {
            padding: 0.4rem 0.6rem !important;
            font-size: 0.75rem !important;
        }
    }

    /* Memastikan card-header rapi di mobile */
    @media (max-width: 576px) {
        .card-header .btn {
            width: 100%; /* Tombol jadi full width di layar sangat kecil */
            margin-bottom: 2px;
        }
        .input-group {
            width: 100% !important;
        }
    }
</style>
@endsection