@extends('layouts.app_pengurus')

@section('content')
<div class="content-wrapper">

    {{-- ALERT --}}
    <div class="mx-3 pt-3">
        @foreach (['success' => 'success', 'error' => 'danger'] as $key => $type)
            @if (session($key))
                <div class="alert alert-{{ $type }} alert-dismissible fade show shadow-sm">
                    <h5><i class="icon fas fa-{{ $type == 'success' ? 'check' : 'ban' }}"></i> 
                        {{ $type == 'success' ? 'Berhasil!' : 'Error!' }}
                    </h5>
                    {{ session($key) }}
                    <button type="button" class="close text-white" data-dismiss="alert">&times;</button>
                </div>
            @endif
        @endforeach
    </div>

    {{-- HEADER --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Jadwal Praktek Dokter</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('pengurus.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Jadwal Praktek</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">

                {{-- CARD HEADER --}}
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
                        
                        <a href="{{ route('pengurus.doctor_schedules.create') }}" class="btn btn-primary shadow-sm">
                            <i class="fas fa-plus-circle mr-1"></i> Tambah Jadwal
                        </a>

                        <form method="GET" action="{{ route('pengurus.doctor_schedules.index') }}" class="flex-grow-1 flex-md-grow-0">
                            <div class="input-group" style="min-width: 250px;">
                                <input type="search" name="search" value="{{ request('search') }}"
                                       class="form-control" placeholder="Cari nama dokter...">
                                <div class="input-group-append">
                                    <button class="btn btn-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

                {{-- CARD BODY --}}
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 50px" class="text-center">No</th>
                                    <th>Nama Dokter</th>
                                    <th>Spesialis</th>
                                    <th>Tanggal Praktek</th>
                                    <th>Jam</th>
                                    <th class="text-center">Kuota</th>
                                    <th class="text-center">Status</th>
                                    <th style="width: 120px" class="text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($schedules as $schedule)
                                    <tr>
                                        <td class="text-center align-middle">
                                            {{ $schedules->firstItem() + $loop->index }}
                                        </td>
                                        <td class="align-middle font-weight-bold">{{ $schedule->doctor->name }}</td>
                                        <td class="align-middle text-muted">{{ $schedule->doctor->specialist }}</td>
                                        <td class="align-middle">
                                            <span class="text-dark font-weight-bold">
                                                <i class="far fa-calendar-alt mr-1 text-muted"></i>
                                                {{ \Carbon\Carbon::parse($schedule->schedule_date)->translatedFormat('d F Y') }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <code class="text-primary">{{ $schedule->start_time }} - {{ $schedule->end_time }}</code>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-light border px-2">{{ $schedule->quota }} Pasien</span>
                                        </td>
                                        <td class="text-center align-middle">
                                            @if($schedule->status == 'active')
                                                <span class="badge badge-pill badge-success px-3">Aktif</span>
                                            @else
                                                <span class="badge badge-pill badge-secondary px-3">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center" style="gap: 5px;">
                                                <a href="{{ route('pengurus.doctor_schedules.edit', $schedule->id) }}"
                                                   class="btn btn-warning btn-sm text-white shadow-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('pengurus.doctor_schedules.destroy', $schedule->id) }}"
                                                      method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm shadow-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <i class="fas fa-calendar-times fa-3x mb-3 opacity-25"></i><br>
                                            <strong>Jadwal tidak ditemukan</strong>
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
                                Menampilkan <strong>{{ $schedules->firstItem() ?? 0 }}</strong> - 
                                <strong>{{ $schedules->lastItem() ?? 0 }}</strong> dari 
                                <strong>{{ $schedules->total() ?? 0 }}</strong> data
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex justify-content-center justify-content-sm-end">
                                {{ $schedules->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<style>
    .table thead th { border-top: 0; }
    code { font-size: 90%; }
    @media (max-width: 576px) {
        .input-group { width: 100% !important; }
        .card-header .btn { width: 100%; }
    }
</style>
@endsection