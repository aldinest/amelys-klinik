@extends('layouts.app_pengurus')

@section('content')
<div class="content-wrapper">
    {{-- ALERT --}}
    <div class="container-fluid pt-3">
        @foreach (['success' => 'success', 'error' => 'danger'] as $key => $type)
            @if (session($key))
                <div class="alert alert-{{ $type }} alert-dismissible fade show shadow-sm">
                    <h5><i class="icon fas fa-{{ $type == 'success' ? 'check' : 'ban' }}"></i> {{ $type == 'success' ? 'Berhasil!' : 'Error!' }}</h5>
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
            </div>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            
            {{-- FILTER CARD --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body py-3">
                    <form method="GET" action="{{ route('pengurus.doctor_schedules.index') }}">
                        <div class="row align-items-end">
                            {{-- Input Tanggal --}}
                            <div class="col-md-2">
                                <label class="small font-weight-bold text-muted">Dari Tanggal</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label class="small font-weight-bold text-muted">Sampai Tanggal</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                            </div>
                            
                            {{-- Input Dokter --}}
                            <div class="col-md-3">
                                <label class="small font-weight-bold text-muted">Dokter</label>
                                <select name="doctor_id" class="form-control">
                                    <option value="">Semua Dokter</option>
                                    @foreach($doctors as $dr)
                                        <option value="{{ $dr->id }}" {{ request('doctor_id') == $dr->id ? 'selected' : '' }}>
                                            {{ $dr->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Area Tombol --}}
                            <div class="col-md-5">
                                <div class="d-flex justify-content-end" style="gap: 5px;">
                                    <button type="submit" class="btn btn-primary shadow-sm px-3">
                                        <i class="fas fa-search mr-1"></i> Cari
                                    </button>
                                    <a href="{{ route('pengurus.doctor_schedules.index') }}" class="btn btn-secondary shadow-sm px-3" title="Reset Filter">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                    <a href="{{ route('pengurus.doctor_schedules.create') }}" class="btn btn-success shadow-sm px-3">
                                        <i class="fas fa-plus mr-1"></i> Tambah Jadwal
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- TABLE CARD --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="mb-0 font-weight-bold text-dark text-uppercase small">
                        <i class="fas fa-calendar-alt mr-2 text-primary"></i> Daftar Jadwal Praktek
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light text-muted small text-uppercase font-weight-bold">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Nama Dokter</th>
                                    <th>Tanggal Praktek</th>
                                    <th>Jam</th>
                                    <th class="text-center">Kuota</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($schedules as $schedule)
                                    <tr>
                                        <td class="text-center align-middle">{{ $schedules->firstItem() + $loop->index }}</td>
                                        <td class="align-middle">
                                            <div class="font-weight-bold text-dark">{{ $schedule->doctor->name }}</div>
                                            <small class="text-primary font-weight-bold">{{ strtoupper($schedule->doctor->specialist) }}</small>
                                        </td>
                                        <td class="align-middle">
                                            {{ \Carbon\Carbon::parse($schedule->schedule_date)->translatedFormat('d F Y') }}
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge badge-light border">{{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                                        </td>
                                        <td class="text-center align-middle">{{ $schedule->quota }} Pasien</td>
                                        <td class="text-center align-middle">
                                            @if($schedule->status == 'active')
                                                <span class="badge badge-pill badge-success px-3">Aktif</span>
                                            @else
                                                <span class="badge badge-pill badge-secondary px-3">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('pengurus.doctor_schedules.edit', $schedule->id) }}" class="btn btn-warning btn-sm text-white"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('pengurus.doctor_schedules.destroy', $schedule->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus jadwal?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center py-5 text-muted">Tidak ada jadwal ditemukan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- FOOTER PAGINATION --}}
                <div class="card-footer bg-white py-3">
                    {{ $schedules->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection