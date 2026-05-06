@extends('layouts.app_pengurus')

@section('content')
<div class="content-wrapper">

    {{-- ALERT MESSAGES - Tambah container-fluid biar ada margin kiri-kanan --}}
    <div class="container-fluid pt-3">
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

    {{-- PAGE HEADER - Rapikan margin bottom --}}
    <section class="content-header pb-2">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold text-dark">Jadwal Reservasi Pasien</h1>
                </div>
                <div class="col-sm-6 d-none d-sm-block">
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
            
            {{-- FILTER CARD - Konsistensi padding & alignment --}}
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
                            <div class="col-md-2 mb-2 mb-md-0">
                                <label class="small font-weight-bold text-muted">Dokter</label>
                                <select name="doctor_id" class="form-control shadow-none">
                                    <option value="">Semua Dokter</option>
                                    @foreach($doctors as $dr)
                                        <option value="{{ $dr->id }}" {{ request('doctor_id') == $dr->id ? 'selected' : '' }}>{{ $dr->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-2 mb-md-0">
                                <label class="small font-weight-bold text-muted">Status</label>
                                <select name="status" class="form-control shadow-none">
                                    <option value="">Semua Status</option>
                                    <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="penuh" {{ request('status') == 'penuh' ? 'selected' : '' }}>Penuh</option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                           {{-- Tombol Search & Reset dengan Jarak --}}
                            <div class="col-md-2">
                                <div class="d-flex">
                                    {{-- Tombol Search --}}
                                    <button type="submit" class="btn btn-primary flex-fill mr-2 shadow-sm" title="Cari">
                                        <i class="fas fa-search mr-1"></i> Cari
                                    </button>
                                    
                                    {{-- Tombol Reset --}}
                                    <a href="{{ route('pengurus.reservations.index') }}" class="btn btn-secondary shadow-sm" title="Reset">
                                        <i class="fas fa-sync-alt"></i>
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
                        <i class="fas fa-calendar-check mr-2 text-primary"></i> Daftar Reservasi Pasien
                    </h6>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase font-weight-bold">
                                <tr>
                                    <th width="60" class="text-center py-3 border-0">No</th>
                                    <th class="py-3 border-0">Dokter & Spesialis</th>
                                    <th class="py-3 border-0">Waktu Praktek</th>
                                    <th class="text-center py-3 border-0">Status Kuota</th>
                                    <th class="text-center py-3 border-0">Ketersediaan</th>
                                    <th width="120" class="text-center py-3 border-0">Aksi</th>
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
                                        <td class="text-center align-middle">{{ $schedules->firstItem() + $loop->index }}</td>
                                        <td class="align-middle">
                                            <div class="font-weight-bold text-dark">{{ $schedule->doctor->name }}</div>
                                            <span class="text-primary small font-weight-bold">{{ strtoupper($schedule->doctor->specialist) }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <div class="font-weight-bold">
                                                <i class="far fa-calendar-alt mr-1 text-muted"></i> 
                                                {{ \Carbon\Carbon::parse($schedule->schedule_date)->locale('id')->translatedFormat('l, d F Y') }}
                                            </div>
                                            <div class="small text-muted"><i class="far fa-clock mr-1"></i> {{ $schedule->start_time }} - {{ $schedule->end_time }}</div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="progress mb-1 mx-auto" style="height: 6px; max-width: 80px;">
                                                @php $percent = ($usedQuota / $schedule->quota) * 100; @endphp
                                                <div class="progress-bar {{ $isFull ? 'bg-danger' : 'bg-success' }}" style="width: {{ $percent }}%"></div>
                                            </div>
                                            <span class="small font-weight-bold {{ $isFull ? 'text-danger' : 'text-dark' }}">{{ $usedQuota }} / {{ $schedule->quota }}</span>
                                        </td>
                                       <td class="text-center align-middle">
                                            @if ($isPast)
                                                <span class="badge badge-secondary px-3 py-2 shadow-sm">Selesai</span>
                                            @elseif ($isFull)
                                                <span class="badge badge-danger px-3 py-2 shadow-sm">Penuh</span>
                                            @else
                                                <span class="badge badge-success px-3 py-2 shadow-sm">Tersedia</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group">
                                                <a href="{{ route('pengurus.reservations.show', $schedule->id) }}" class="btn btn-info btn-sm rounded shadow-sm mr-1"><i class="fas fa-eye"></i></a>
                                                @if ($isPast)
                                                    <button class="btn btn-outline-secondary btn-sm rounded" disabled><i class="fas fa-lock"></i></button>
                                                @elseif (!$isFull)
                                                    <a href="{{ route('pengurus.reservations.create', ['schedule' => $schedule->id]) }}" class="btn btn-primary btn-sm rounded shadow-sm"><i class="fas fa-plus"></i></a>
                                                @else
                                                    <button class="btn btn-outline-danger btn-sm rounded" disabled><i class="fas fa-ban"></i></button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-5 text-muted">Tidak ada jadwal ditemukan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-white py-3 border-top-0">
                    <div class="row align-items-center text-center text-md-left">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <span class="small text-muted">Menampilkan <strong>{{ $schedules->firstItem() ?? 0 }}</strong>-<strong>{{ $schedules->lastItem() ?? 0 }}</strong> dari <strong>{{ $schedules->total() ?? 0 }}</strong> jadwal</span>
                        </div>
                        <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
                            {{ $schedules->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection