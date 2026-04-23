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
                    <button type="button" class="close text-white" data-dismiss="alert">&times;</button>
                </div>
            @endif
        @endforeach
    </div>

    {{-- PAGE HEADER --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold text-dark">Jadwal Reservasi Pasien</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('pengurus.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Reservasi</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            <div class="card shadow-sm border-0">

                {{-- CARD HEADER DENGAN FILTER --}}
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
                        <div>
                            <h6 class="mb-0 font-weight-bold text-muted text-uppercase small">
                                <i class="fas fa-calendar-check mr-1"></i> Daftar Jadwal Dokter
                            </h6>
                        </div>

                        <form method="GET" action="{{ route('pengurus.reservations.index') }}" class="form-inline">
                            <div class="input-group shadow-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0"><i class="fas fa-filter text-muted"></i></span>
                                </div>
                                <input type="date" name="date" class="form-control border-left-0" 
                                       value="{{ request('date') }}" style="max-width: 180px;">
                                <div class="input-group-append">
                                    <button class="btn btn-secondary px-3" type="submit">Filter</button>
                                    @if(request('date'))
                                        <a href="{{ route('pengurus.reservations.index') }}" class="btn btn-light border" title="Reset">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- TABLE BODY --}}
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase font-weight-bold">
                                <tr>
                                    <th width="50" class="text-center">No</th>
                                    <th>Dokter & Spesialis</th>
                                    <th>Waktu Praktek</th>
                                    <th class="text-center">Status Kuota</th>
                                    <th class="text-center">Ketersediaan</th>
                                    <th width="120" class="text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($schedules as $schedule)
                                    @php
                                        $usedQuota = $schedule->reservations
                                            ->whereIn('status', ['approved', 'completed'])
                                            ->count();
                                        $isFull = $usedQuota >= $schedule->quota;
                                        $isPast = \Carbon\Carbon::parse($schedule->schedule_date)->isPast() && 
                                                !\Carbon\Carbon::parse($schedule->schedule_date)->isToday();
                                    @endphp

                                    {{-- Menghilangkan opacity, hanya pakai bg-light jika sudah lewat --}}
                                    <tr class="{{ $isPast ? 'bg-light' : '' }}">
                                        <td class="text-center align-middle text-dark">{{ $schedules->firstItem() + $loop->index }}</td>
                                        
                                        <td class="align-middle">
                                            {{-- Teks dokter dibuat tetap hitam pekat --}}
                                            <div class="font-weight-bold text-dark" style="font-size: 1.1rem;">{{ $schedule->doctor->name }}</div>
                                            <span class="text-primary small font-weight-bold">{{ strtoupper($schedule->doctor->specialist) }}</span>
                                        </td>

                                        <td class="align-middle text-dark">
                                            <div class="font-weight-bold"><i class="far fa-calendar-alt mr-1 text-primary"></i> {{ \Carbon\Carbon::parse($schedule->schedule_date)->translatedFormat('d F Y') }}</div>
                                            <div class="small"><i class="far fa-clock mr-1 text-muted"></i> {{ $schedule->start_time }} - {{ $schedule->end_time }}</div>
                                        </td>

                                        <td class="text-center align-middle">
                                            <div class="progress mb-1" style="height: 8px; background-color: #dee2e6;">
                                                @php $percent = ($usedQuota / $schedule->quota) * 100; @endphp
                                                <div class="progress-bar {{ $isFull ? 'bg-danger' : 'bg-success' }}" 
                                                    role="progressbar" style="width: {{ $percent }}%"></div>
                                            </div>
                                            <span class="font-weight-bold {{ $isFull ? 'text-danger' : 'text-dark' }}">
                                                {{ $usedQuota }} / {{ $schedule->quota }}
                                            </span>
                                        </td>

                                        <td class="text-center align-middle">
                                            @if ($isPast)
                                                <span class="badge badge-secondary px-3 py-2 shadow-sm" style="opacity: 1 !important;">Selesai</span>
                                            @elseif ($isFull)
                                                <span class="badge badge-danger px-3 py-2 shadow-sm">Penuh</span>
                                            @else
                                                <span class="badge badge-success px-3 py-2 shadow-sm">Tersedia</span>
                                            @endif
                                        </td>

                                        <td class="text-center align-middle">
                                            <div class="btn-group">
                                                <a href="{{ route('pengurus.reservations.show', $schedule->id) }}"
                                                class="btn btn-info btn-sm shadow-sm" title="Lihat Reservasi">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>

                                                @if ($isPast)
                                                    <button class="btn btn-outline-secondary btn-sm ml-1" disabled>
                                                        <i class="fas fa-lock"></i>
                                                    </button>
                                                @elseif (!$isFull)
                                                    <a href="{{ route('pengurus.reservations.create', ['schedule' => $schedule->id]) }}"
                                                    class="btn btn-primary btn-sm ml-1 shadow-sm" title="Tambah Reservasi">
                                                        <i class="fas fa-plus"></i> Tambah
                                                    </a>
                                                @else
                                                    <button class="btn btn-outline-danger btn-sm ml-1" disabled>
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    {{-- ... bagian empty tetap sama ... --}}
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>

                {{-- CARD FOOTER --}}
                <div class="card-footer bg-white py-3 border-top-0">
                    <div class="row align-items-center text-center text-sm-left">
                        <div class="col-sm-6 mb-2 mb-sm-0">
                            <span class="small text-muted">
                                Data <strong>{{ $schedules->firstItem() ?? 0 }}</strong> - 
                                <strong>{{ $schedules->lastItem() ?? 0 }}</strong> dari 
                                <strong>{{ $schedules->total() ?? 0 }}</strong> jadwal
                            </span>
                        </div>
                        <div class="col-sm-6 d-flex justify-content-center justify-content-sm-end">
                            {{ $schedules->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<style>
    .progress { background-color: #f1f1f1; }
    .opacity-75 { opacity: 0.75; }
    .table thead th { border-top: 0; letter-spacing: 0.5px; }
    .badge-pill { font-weight: 600; font-size: 85%; }
    @media (max-width: 576px) {
        .input-group { width: 100% !important; }
        .form-inline { width: 100%; }
    }
</style>
@endsection