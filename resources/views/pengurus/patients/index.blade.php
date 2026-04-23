@extends('layouts.app_pengurus')

@section('content')
<div class="content-wrapper">

    {{-- ALERT MESSAGES --}}
    <div class="mx-3 pt-3">
        @foreach (['success' => 'success', 'error' => 'danger'] as $key => $type)
            @if (session($key))
                <div class="alert alert-{{ $type }} alert-dismissible fade show shadow-sm">
                    <h5><i class="icon fas fa-{{ $type == 'success' ? 'check' : 'ban' }}"></i> 
                        {{ $type == 'success' ? 'Berhasil!' : 'Error!' }}
                    </h5>
                    {{ session($key) }}
                    <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">&times;</button>
                </div>
            @endif
        @endforeach
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
                        <li class="breadcrumb-item"><a href="{{ route('pengurus.dashboard') }}">Dashboard</a></li>
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
                
                {{-- CARD HEADER DENGAN SEARCH --}}
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
                        <h6 class="mb-0 font-weight-bold text-muted"><i class="fas fa-users mr-1"></i> Daftar Pasien Terdaftar</h6>
                        
                        <div class="flex-grow-1 flex-md-grow-0">
                            <form method="GET" action="{{ route('pengurus.patients.index') }}">
                                <div class="input-group" style="min-width: 250px;">
                                    <input type="search" name="search" value="{{ request('search') }}"
                                           class="form-control" placeholder="Cari nama pasien...">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="submit">
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
                        <table class="table table-bordered table-striped table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 60px" class="text-center">No</th>
                                    <th>Nama Pasien</th>
                                    <th>Alamat</th>
                                    <th class="text-center">Gender</th>
                                    <th>No. HP</th>
                                    <th style="width: 120px" class="text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($patients as $patient)
                                    <tr>
                                        <td class="text-center align-middle">
                                            {{ $patients->firstItem() + $loop->index }}
                                        </td>
                                        <td class="align-middle font-weight-bold">{{ $patient->name }}</td>
                                        <td class="align-middle text-sm">{{ $patient->address }}</td>
                                        <td class="text-center align-middle">
                                            @if($patient->gender == 'L')
                                                <span class="badge badge-info px-2">Laki-laki</span>
                                            @else
                                                <span class="badge badge-danger px-2" style="background-color: #e83e8c;">Perempuan</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
    @php
        // 1. Bersihkan semua karakter non-angka
        $nomorBersih = preg_replace('/[^0-9]/', '', $patient->phone);

        // 2. Jika diawali angka 0, ganti dengan 62
        if (substr($nomorBersih, 0, 1) === '0') {
            $nomorWA = '62' . substr($nomorBersih, 1);
        } else {
            $nomorWA = $nomorBersih;
        }
    @endphp

    <a href="https://wa.me/{{ $nomorWA }}" target="_blank" class="text-dark font-weight-bold">
        <i class="fab fa-whatsapp text-success mr-1"></i>
        {{ $patient->phone }}
    </a>
</td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('pengurus.patients.show', $patient->id) }}"
                                               class="btn btn-info btn-sm shadow-sm">
                                                <i class="fas fa-eye mr-1"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fas fa-user-injured fa-3x mb-3 opacity-25"></i><br>
                                            <strong>Data pasien tidak ditemukan</strong>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- CARD FOOTER / PAGINATION --}}
                <div class="card-footer bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col-sm-6 text-center text-sm-left mb-3 mb-sm-0">
                            <p class="mb-0 small text-muted">
                                Menampilkan <strong>{{ $patients->firstItem() ?? 0 }}</strong> - 
                                <strong>{{ $patients->lastItem() ?? 0 }}</strong> dari 
                                <strong>{{ $patients->total() ?? 0 }}</strong> pasien
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex justify-content-center justify-content-sm-end">
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
    /* Mengatur style pagination agar lebih rapi di mobile */
    @media (max-width: 576px) {
        .pagination { font-size: 0.8rem; flex-wrap: wrap; justify-content: center; }
        .input-group { width: 100% !important; }
    }
    .table thead th { border-top: 0; }
</style>
@endsection