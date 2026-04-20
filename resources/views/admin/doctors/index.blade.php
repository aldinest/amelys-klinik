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
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle mr-1"></i> Tambah Dokter
                        </a>

                        {{-- SEARCH FORM --}}
                        <div class="card-tools">
                            <form action="{{ route('admin.doctors.index') }}" method="GET">
                                <div class="input-group" style="width: 250px;">
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

                {{-- TABLE BODY --}}
                <div class="card-body p-0">
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
                                        <td class="text-center align-middle">
                                            {{ $loop->iteration + ($doctors->currentPage() - 1) * $doctors->perPage() }}
                                        </td>
                                        <td class="align-middle font-weight-bold">{{ $doctor->name }}</td>
                                        <td class="align-middle">{{ $doctor->address }}</td>
                                        <td class="align-middle">{{ $doctor->specialist }}</td>
                                        <td class="text-center align-middle">
                                            @if ($doctor->status === 'aktif')
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center text-nowrap" style="gap: 5px;">
                                                {{-- Tombol Detail --}}
                                                <a href="{{ route('admin.doctors.show', $doctor->id) }}" 
                                                  class="btn btn-info btn-sm shadow-sm">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>

                                                {{-- Tombol Edit --}}
                                                <a href="{{ route('admin.doctors.edit', $doctor->id) }}" 
                                                  class="btn btn-warning btn-sm text-white shadow-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>

                                                {{-- Tombol Hapus --}}
                                                <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST"
                                                      onsubmit="return confirm('Yakin mau hapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm shadow-sm" type="submit">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fas fa-user-md fa-3x mb-3 opacity-25"></i><br>
                                            Data dokter tidak ditemukan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- FOOTER / PAGINATION --}}
                <div class="card-footer bg-white">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <p class="mb-0 small text-muted">
                                Menampilkan <strong>{{ $doctors->firstItem() ?? 0 }}</strong> sampai 
                                <strong>{{ $doctors->lastItem() ?? 0 }}</strong> dari 
                                <strong>{{ $doctors->total() ?? 0 }}</strong> data
                            </p>
                        </div>
                        <div class="col-sm-6 d-flex justify-content-end">
                            {{ $doctors->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection