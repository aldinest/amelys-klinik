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
    <section class="content-header pb-2">
        <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap">
            <h1 class="fw-bold mb-0">Jadwal Reservasi Pasien</h1>

            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('pengurus.dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Reservasi</li>
            </ol>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">

            <div class="card shadow-sm">

                {{-- CARD HEADER --}}
                <div class="card-header bg-white py-2">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <strong class="text-muted text-uppercase small">
                            Daftar Jadwal Dokter
                        </strong>

                        {{-- FILTER TANGGAL --}}
                        <form method="GET"
                              action="{{ route('pengurus.reservations.index') }}"
                              class="d-flex gap-2">

                                <div class="d-flex align-items-center gap-2">
                                    <input type="date" name="date" class="form-control" style="max-width: 200px;" value="{{ request('date') }}">
                                    <button class="btn btn-secondary text-white">
                                        <i class="bi bi-funnel"></i> Filter
                                    </button>
                                </div>

                            @if(request('date'))
                                <a href="{{ route('pengurus.reservations.index') }}"
                                   class="btn btn-sm btn-light border ml-2">
                                    Reset
                                </a>
                            @endif
                        </form>

                    </div>
                </div>

                {{-- CARD BODY --}}
                <div class="card-body py-3">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="50" class="text-center">No</th>
                                    <th>Dokter</th>
                                    <th>Tanggal</th>
                                    <th>Jam Praktik</th>
                                    <th class="text-center">Kuota</th>
                                    <th class="text-center">Ketersediaan</th>
                                    <th width="140" class="text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($schedules as $schedule)
                                    @php
                                        $usedQuota = $schedule->reservations
                                            ->whereIn('status', ['approved', 'completed'])
                                            ->count();

                                        $isFull = $usedQuota >= $schedule->quota;

                                        // LOGIKA BARU: Cek apakah tanggal sudah lewat hari ini
                                        $isPast = \Carbon\Carbon::parse($schedule->schedule_date)->isPast() && 
                                                  !\Carbon\Carbon::parse($schedule->schedule_date)->isToday();
                                    @endphp

                                    <tr>
                                        <td class="text-center">
                                            {{ $schedules->firstItem() + $loop->index }}
                                        </td>

                                        <td>
                                            <strong class="text-dark">{{ $schedule->doctor->name }}</strong><br>
                                            <small class="text-muted text-uppercase">
                                                {{ $schedule->doctor->specialist }}
                                            </small>
                                        </td>

                                        <td>
                                            {{ \Carbon\Carbon::parse($schedule->schedule_date)->translatedFormat('d F Y') }}
                                        </td>

                                        <td>
                                            {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                        </td>

                                        <td class="text-center">
                                            {{ $usedQuota }} / {{ $schedule->quota }}
                                        </td>

                                        <td class="text-center">
                                            @if ($isPast)
                                                <span class="badge bg-secondary text-white">Selesai</span>
                                            @elseif ($isFull)
                                                <span class="badge bg-danger text-white">Penuh</span>
                                            @else
                                                <span class="badge bg-success text-white">Tersedia</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <div class="d-inline-flex gap-1">

                                                {{-- DETAIL --}}
                                                <a href="{{ route('pengurus.reservations.show', $schedule->id) }}"
                                                   class="btn btn-outline-info btn-sm"
                                                   title="Lihat Reservasi">
                                                     <i class="fas fa-eye"></i>
                                                </a>

                                                {{-- TAMBAH RESERVASI (Dicek Past Date & Full) --}}
                                                @if ($isPast)
                                                    <button class="btn btn-outline-secondary btn-sm" disabled title="Jadwal sudah lewat">
                                                        <i class="fas fa-lock"></i>
                                                    </button>
                                                @elseif (!$isFull)
                                                    <a href="{{ route('pengurus.reservations.create', ['schedule' => $schedule->id]) }}"
                                                       class="btn btn-outline-primary btn-sm"
                                                       title="Tambah Reservasi">
                                                        <i class="fas fa-plus"></i>
                                                    </a>
                                                @else
                                                    <button class="btn btn-outline-secondary btn-sm" disabled title="Kuota Penuh">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <strong>Tidak ada jadwal pada tanggal ini</strong>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION --}}
                    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                        <small class="text-muted text-sm">
                            Menampilkan
                            <strong>{{ $schedules->firstItem() }}</strong> –
                            <strong>{{ $schedules->lastItem() }}</strong>
                            dari <strong>{{ $schedules->total() }}</strong> data
                        </small>

                        {{ $schedules->links() }}
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

{{-- CSS Tambahan agar tombol beneran putih teksnya --}}
<style>
    .btn-secondary, .btn-primary, .badge {
        color: #ffffff !important;
    }
</style>
@endsection