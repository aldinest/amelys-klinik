@extends('layouts.app_pengurus')

@section('content')
<div class="content-wrapper">

    {{-- ALERT --}}
    <div class="mx-3 mt-3">
        @foreach (['success' => 'success', 'error' => 'danger'] as $key => $type)
            @if (session($key))
                <div class="alert alert-{{ $type }} alert-dismissible fade show">
                    {{ session($key) }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif
        @endforeach
    </div>

    {{-- HEADER --}}
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="font-weight-bold">Data Pasien</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('pengurus.dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Data Pasien</li>
            </ol>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">

                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        {{-- ACTION BUTTONS --}}
                        <div class="d-flex gap-2 flex-wrap align-items-center">

                            <a href="{{ route('pengurus.patients.export') }}" 
                               class="btn btn-success">
                               <i class="fas fa-file-excel"></i> Export Excel
                            </a>

                            <form action="{{ route('pengurus.patients.import') }}"
                                method="POST"
                                enctype="multipart/form-data"
                                class="d-inline">
                                @csrf

                                <!-- INPUT FILE DISSEMBUNYIKAN -->
                                <input type="file"
                                    name="file"
                                    id="fileImport"
                                    class="d-none"
                                    required
                                    onchange="this.form.submit()">

                                <!-- TOMBOL KEREN BUAT PILIH FILE -->
                                <button type="button"
                                        class="btn btn-primary"
                                        onclick="document.getElementById('fileImport').click()">
                                    <i class="fas fa-upload"></i> Import Excel
                                </button>
                            </form>

                            <!-- <a href="{{ route('pengurus.patients.pdf') }}" 
                               class="btn btn-danger">
                               <i class="fas fa-file-pdf"></i> Cetak PDF
                            </a> -->
                        </div>

                        {{-- SEARCH --}}
                        <form method="GET" action="{{ route('pengurus.patients.index') }}">
                            <div class="input-group">
                                <input type="search"
                                       name="search"
                                       value="{{ request('search') }}"
                                       class="form-control"
                                       placeholder="Cari nama pasien...">
                                <div class="input-group-append">
                                    <button class="btn btn-secondary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

                <div class="card-body">

                    {{-- TABLE --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 60px">No</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>Jenis Kelamin</th>
                                    <th>No HP</th>
                                    <th style="width: 120px">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($patients as $patient)
                                    <tr>
                                        <td class="text-center">
                                            {{ $patients->firstItem() + $loop->index }}
                                        </td>
                                        <td>{{ $patient->name }}</td>
                                        <td>{{ $patient->address }}</td>
                                        <td>
                                            {{ $patient->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </td>
                                        <td>{{ $patient->phone }}</td>
                                        <td>
                                            <a href="{{ route('pengurus.patients.show', $patient->id) }}"
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <strong>Data tidak ditemukan 😪</strong>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION --}}
                    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                        <small class="text-muted">
                            Menampilkan 
                            <strong>{{ $patients->firstItem() }}</strong> -
                            <strong>{{ $patients->lastItem() }}</strong>
                            dari <strong>{{ $patients->total() }}</strong> data
                        </small>

                        <div>
                            {{ $patients->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
@endsection
