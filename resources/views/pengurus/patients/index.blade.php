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
        </div>
    </section>
</div>
@endsection
