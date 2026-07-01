@extends('layouts.app_pengurus')

@section('content')
<style>
    .btn-floating-pending {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
    border-radius: 50px;
    padding: 15px 20px;
    }   
</style>
<div class="content-wrapper">

    {{-- ALERT MESSAGES --}}
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

    {{-- PAGE HEADER --}}
    <section class="content-header pb-2">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold text-dark">Jadwal Reservasi</h1>
                </div>
                <div class="col-sm-6">
                    {{-- Tombol Akses Cepat ke Pending Reservasi --}}
                    <button type="button" class="btn btn-warning float-sm-right shadow-sm font-weight-bold" data-toggle="modal" data-target="#modalPending">
                        <i class="fas fa-bell mr-1"></i> Perlu Konfirmasi ({{ \App\Models\Reservation::where('status', 'pending')->count() }})
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            
            {{-- FILTER CARD --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body py-3">
                    <form method="GET" action="{{ route('pengurus.reservations.index') }}">
                        <div class="row align-items-end">
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label class="small font-weight-bold text-muted">Dari Tanggal</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label class="small font-weight-bold text-muted">Sampai Tanggal</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label class="small font-weight-bold text-muted">Dokter</label>
                                <select name="doctor_id" class="form-control">
                                    <option value="">Semua Dokter</option>
                                    @foreach($doctors as $dr)
                                        <option value="{{ $dr->id }}" {{ request('doctor_id') == $dr->id ? 'selected' : '' }}>{{ $dr->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary shadow-sm"><i class="fas fa-search mr-1"></i> Cari</button>
                                <a href="{{ route('pengurus.reservations.index') }}" class="btn btn-secondary shadow-sm"><i class="fas fa-sync-alt"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- TABLE CARD --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="mb-0 font-weight-bold text-dark text-uppercase small">
                        <i class="fas fa-calendar-check mr-2 text-primary"></i> Daftar Reservasi Pasien
                    </h6>
                </div>

                <div class="card-body p-0">
                    {{-- DESKTOP: Tabel --}}
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase font-weight-bold">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Dokter & Spesialis</th>
                                    <th>Waktu Praktek</th>
                                    <th class="text-center">Status Kuota</th>
                                    <th class="text-center">Ketersediaan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($schedules as $schedule)
                                    @php
                                        $usedQuota = $schedule->reservations->whereIn('status', ['approved', 'completed'])->count();
                                        $isFull = $usedQuota >= $schedule->quota;
                                        $isPast = \Carbon\Carbon::parse($schedule->schedule_date)->isPast() && !\Carbon\Carbon::parse($schedule->schedule_date)->isToday();
                                    @endphp
                                    <tr class="{{ $isPast ? 'bg-light' : '' }}">
                                        <td class="text-center">{{ $schedules->firstItem() + $loop->index }}</td>
                                        <td>
                                            <div class="font-weight-bold text-dark">{{ $schedule->doctor->name }}</div>
                                            <span class="text-primary small font-weight-bold">{{ strtoupper($schedule->doctor->specialist) }}</span>
                                        </td>
                                        <td>
                                            <div><i class="far fa-calendar-alt mr-1 text-muted"></i> {{ \Carbon\Carbon::parse($schedule->schedule_date)->translatedFormat('d M Y') }}</div>
                                            <div class="small text-muted"><i class="far fa-clock mr-1"></i> {{ $schedule->start_time }} - {{ $schedule->end_time }}</div>
                                        </td>
                                        <td class="text-center">
                                            <div class="progress mx-auto" style="height: 6px; max-width: 80px;">
                                                @php $percent = ($usedQuota / $schedule->quota) * 100; @endphp
                                                <div class="progress-bar {{ $isFull ? 'bg-danger' : 'bg-success' }}" style="width: {{ $percent }}%"></div>
                                            </div>
                                            <span class="small font-weight-bold">{{ $usedQuota }}/{{ $schedule->quota }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if ($isPast) <span class="badge badge-secondary">Selesai</span>
                                            @elseif ($isFull) <span class="badge badge-danger">Penuh</span>
                                            @else <span class="badge badge-success">Tersedia</span> @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('pengurus.reservations.show', $schedule->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                            @if (!$isPast && !$isFull)
                                                <a href="{{ route('pengurus.reservations.create', ['schedule' => $schedule->id]) }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-4">Tidak ada jadwal ditemukan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- MOBILE: Card View --}}
                    <div class="d-md-none">
                        @forelse ($schedules as $s)
                            @php 
                                $used = $s->reservations->whereIn('status', ['approved', 'completed'])->count();
                                $isFull = $used >= $s->quota;
                                $isPast = \Carbon\Carbon::parse($s->schedule_date)->isPast() && !\Carbon\Carbon::parse($s->schedule_date)->isToday();
                            @endphp
                            
                            <div class="card mb-2 border-0 shadow-sm mx-2">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="font-weight-bold text-dark mb-0">{{ $s->doctor->name }}</h6>
                                            <small class="text-primary font-weight-bold">{{ strtoupper($s->doctor->specialist) }}</small>
                                        </div>
                                        <span class="badge {{ $isPast ? 'badge-secondary' : ($isFull ? 'badge-danger' : 'badge-success') }} p-2">
                                            {{ $isPast ? 'Selesai' : ($isFull ? 'Penuh' : 'Tersedia') }}
                                        </span>
                                    </div>

                                    <div class="row text-muted small mt-3">
                                        <div class="col-6">
                                            <i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($s->schedule_date)->format('d M y') }}
                                        </div>
                                        <div class="col-6 text-right">
                                            <i class="far fa-clock mr-1"></i> {{ substr($s->start_time, 0, 5) }} - {{ substr($s->end_time, 0, 5) }}
                                        </div>
                                    </div>

                                    <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                                        <span class="text-dark font-weight-bold">
                                            <i class="fas fa-users mr-1 text-secondary"></i> {{ $used }}/{{ $s->quota }} Pasien
                                        </span>
                                        <a href="{{ route('pengurus.reservations.show', $s->id) }}" class="btn btn-sm btn-info px-3">Detail</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">Tidak ada jadwal ditemukan.</div>
                        @endforelse
                    </div>
                </div>
                
                {{-- Pagination Footer (tetap sama) --}}
                <div class="card-footer bg-white">
                    {{ $schedules->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </section>
</div>

{{-- Modal untuk list pending --}}
<div class="modal fade" id="modalPending" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-bell mr-2"></i> Reservasi Perlu Konfirmasi</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="pl-4">Pasien</th>
                                <th>Keluhan</th>
                                <th>Jadwal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $pendings = \App\Models\Reservation::with(['patient', 'schedule'])->where('status', 'pending')->get(); @endphp
                            @forelse($pendings as $res)
                            <tr>
                                <td class="pl-4">
                                    <div class="font-weight-bold text-dark">{{ $res->patient->name }}</div>
                                </td>
                                <td>
                                    <div class="text-muted small" style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $res->complaint }}">
                                        {{ $res->action ?? '-' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-light border">
                                        {{ \Carbon\Carbon::parse($res->schedule->schedule_date)->translatedFormat('d M Y') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <form action="{{ route('pengurus.reservations.approve', $res->id) }}" method="POST" class="mr-1">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" title="Setujui">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('pengurus.reservations.cancel', $res->id) }}" method="POST" onsubmit="return confirm('Tolak reservasi ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Tolak">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Tidak ada reservasi pending.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection