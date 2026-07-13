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
                    <h1 class="font-weight-bold">Data Dokter</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Data Dokter</li>
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
                    <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
                        <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary shadow-sm">
                            <i class="fas fa-plus-circle mr-1"></i> Tambah Dokter
                        </a>

                        <div class="flex-grow-1 flex-md-grow-0">
                            <form action="{{ route('admin.doctors.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Cari nama dokter..." value="{{ request('search') }}">
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

                {{-- TABLE BODY (Desktop - d-none d-md-block) --}}
                <div class="card-body p-0 d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 50px" class="text-center">No</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>Spesialis</th>
                                    <th style="width: 100px" class="text-center">Status</th>
                                    <th style="width: 220px" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($doctors as $doctor)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration + ($doctors->currentPage() - 1) * $doctors->perPage() }}</td>
                                        <td class="align-middle font-weight-bold">{{ $doctor->name }}</td>
                                        <td class="align-middle">{{ $doctor->address }}</td>
                                        <td class="align-middle">{{ $doctor->specialist }}</td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-pill {{ $doctor->status === 'aktif' ? 'badge-success' : 'badge-secondary' }} px-3">
                                                {{ ucfirst($doctor->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center text-nowrap" style="gap: 5px;">
                                                <a href="{{ route('admin.doctors.show', $doctor->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</a>
                                                <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-warning btn-sm text-white"><i class="fas fa-edit"></i> Edit</a>
                                                <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus data ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-danger btn-sm" type="submit"><i class="fas fa-trash"></i> Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-5 text-muted">Data dokter tidak ditemukan</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- CARD VIEW (Mobile - d-md-none) --}}
                <div class="card-body p-3 d-md-none">
                    @forelse ($doctors as $doctor)
                        <div class="card mb-3 border shadow-none">
                            <div class="card-body p-3">
                                <h6 class="font-weight-bold mb-1">{{ $doctor->name }}</h6>
                                <p class="text-muted small mb-2">{{ $doctor->specialist }} | {{ $doctor->address }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge badge-pill {{ $doctor->status === 'aktif' ? 'badge-success' : 'badge-secondary' }} px-3">
                                        {{ ucfirst($doctor->status) }}
                                    </span>
                                    <div class="d-flex" style="gap: 5px;">
                                        <a href="{{ route('admin.doctors.show', $doctor->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-warning btn-sm text-white"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm" type="submit"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted">Data dokter tidak ditemukan</div>
                    @endforelse
                </div>

                {{-- FOOTER / PAGINATION --}}
                <div class="card-footer bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col-sm-12 col-md-6 text-center text-md-left mb-3 mb-md-0">
                            <p class="mb-0 small text-muted">
                                Menampilkan <strong>{{ $doctors->firstItem() ?? 0 }}</strong> sampai 
                                <strong>{{ $doctors->lastItem() ?? 0 }}</strong> dari 
                                <strong>{{ $doctors->total() ?? 0 }}</strong> data
                            </p>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="pagination-responsive-wrapper">
                                {{ $doctors->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .pagination-responsive-wrapper { display: flex; justify-content: center; }
    @media (min-width: 768px) { .pagination-responsive-wrapper { justify-content: flex-end; } }
    @media (max-width: 767.98px) {
        .pagination-responsive-wrapper { overflow-x: auto; display: block; white-space: nowrap; padding: 5px 0; }
        .pagination-responsive-wrapper .pagination { display: inline-flex; margin-bottom: 0; }
    }
    @media (max-width: 576px) {
        .card-header .btn { width: 100%; margin-bottom: 5px; }
        .input-group { width: 100% !important; }
    }
</style>
@endsection