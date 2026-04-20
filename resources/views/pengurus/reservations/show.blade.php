@extends('layouts.app_pengurus')

@section('title', 'Detail Reservasi')

@section('content')
<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid px-4">

            {{-- ================= INFO JADWAL ================= --}}
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card card-info shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Informasi Jadwal Dokter
                            </h3>
                        </div>

                        <div class="card-body">
                            @php
                                // LOGIKA PERBAIKAN: Hitung yang approved DAN yang sudah selesai (completed)
                                // agar kuota tidak terbuka lagi setelah diperiksa
                                $approvedCount = $reservations
                                    ->whereIn('status', ['approved', 'completed'])
                                    ->count();
                                    
                                $sisaSlot = $schedule->quota - $approvedCount;
                            @endphp

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <small class="text-muted">Dokter</small>
                                    <h6 class="mb-0">{{ $schedule->doctor->name }}</h6>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <small class="text-muted">Tanggal</small>
                                    <h6 class="mb-0">
                                        {{ \Carbon\Carbon::parse($schedule->schedule_date)->format('d M Y') }}
                                    </h6>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <small class="text-muted">Jam Praktik</small>
                                    <h6 class="mb-0">
                                        {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                    </h6>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <small class="text-muted">Kuota</small>
                                    <h6 class="mb-0">
                                        {{ $approvedCount }} / {{ $schedule->quota }}
                                    </h6>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <small class="text-muted">Sisa Slot</small><br>
                                    @if ($sisaSlot > 0)
                                        <span class="badge bg-success">{{ $sisaSlot }} Tersedia</span>
                                    @else
                                        <span class="badge bg-danger">Penuh</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- ================= TABEL RESERVASI ================= --}}
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-users me-1"></i>
                                Daftar Pasien Reservasi
                            </h3>
                        </div>

                        <div class="card-body table-responsive p-0">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">No</th>
                                        <th>Nama Pasien</th>
                                        <th>No RM</th>
                                        <th>Tindakan</th>
                                        <th>Status</th>
                                        <th width="120" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse ($reservations as $reservation)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $reservation->patient->name }}</td>
                                        <td>{{ $reservation->patient->medical_record_number ?? '-' }}</td>
                                        <td>
                                            <span class="text-muted">
                                                {{ $reservation->action ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            @switch($reservation->status)
                                                @case('approved')
                                                    <span class="badge bg-primary">Disetujui</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge bg-success">Selesai</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-danger">Dibatalkan</span>
                                            @endswitch
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1 flex-wrap">

                                                {{-- PERIKSA --}}
                                                @if ($reservation->status === 'approved' && !$reservation->medicalRecord)
                                                    <a href="{{ route('pengurus.medical-records.create', [
                                                        'reservation_id' => $reservation->id
                                                    ]) }}"
                                                       class="btn btn-sm btn-primary">
                                                        <i class="fas fa-stethoscope"></i>
                                                        Periksa
                                                    </a>
                                                @endif

                                                @if ($reservation->status === 'approved')
                                                    <form action="{{ route('pengurus.reservations.cancel', $reservation->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Yakin mau cancel reservasi pasien ini?')"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button class="btn btn-sm btn-danger">
                                                            <i class="fas fa-times"></i>
                                                            Cancel
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- LIHAT RM --}}
                                                @if ($reservation->medicalRecord)
                                                    <a href="{{ route(
                                                        'pengurus.medical-records.show',
                                                        $reservation->medicalRecord->id
                                                    ) }}"
                                                       class="btn btn-sm btn-success">
                                                        <i class="fas fa-notes-medical"></i>
                                                        Lihat RM
                                                    </a>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            class="text-center text-muted py-4">
                                            Tidak ada data reservasi
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer">
                            <a href="{{ route('pengurus.reservations.index') }}"
                               class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Kembali
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection