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
                    <div class="d-flex justify-content-between align-items-center">
                        {{-- Tombol Tambah --}}
                        <a href="{{ route('admin.patients.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle mr-1"></i> Tambah Pasien
                        </a>

                        {{-- Search Form --}}
                        <div class="card-tools">
                            <form action="{{ route('admin.patients.index') }}" method="GET">
                                <div class="input-group" style="width: 250px;">
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
                                        <td class="align-middle">{{ $patient->gender }}</td>
                                        <td class="align-middle">{{ $patient->phone }}</td>
                                        <td class="text-center align-middle">
                                            {{-- Container Tombol Berjejer (Persegi Panjang) --}}
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
                <div class="card-footer bg-white">
                    <div class="row align-items-center">
                        <div class="col-sm-6 text-center text-sm-left">
                            <p class="mb-0 small text-muted">
                                Menampilkan <strong>{{ $patients->firstItem() ?? 0 }}</strong> sampai 
                                <strong>{{ $patients->lastItem() ?? 0 }}</strong> dari 
                                <strong>{{ $patients->total() ?? 0 }}</strong> data
                            </p>
                        </div>
                        <div class="col-sm-6 d-flex justify-content-center justify-content-sm-end mt-2 mt-sm-0">
                            {{ $patients->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection