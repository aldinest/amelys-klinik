@extends('layouts.app_pasien')

@section('content')
<div class="content-wrapper">
    {{-- HEADER: Diperbaiki agar judul & tombol sejajar rapi --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                {{-- Sisi Kiri: Judul --}}
                <div class="col-sm-6 d-flex align-items-center">
                    <h1 class="m-0 font-weight-bold">Reservasi Saya</h1>
                </div>
                {{-- Sisi Kanan: Tombol --}}
                <div class="col-sm-6">
                    <div class="float-sm-right mt-2 mt-sm-0">
                        <a href="{{ route('pasien.reservations.create') }}" class="btn btn-primary shadow-sm">
                            <i class="fas fa-plus"></i> Buat Reservasi Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h3 class="card-title font-weight-bold text-muted">Riwayat Kunjungan</h3>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 text-nowrap">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th class="text-center" width="60">No</th>
                                    <th>Dokter</th>
                                    <th>Spesialis</th>
                                    <th>Waktu Kedatangan</th>
                                    <th>Keperluan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reservations as $reservation)
                                    <tr>
                                        <td class="text-center align-middle text-muted">{{ $loop->iteration }}</td>
                                        <td class="align-middle font-weight-bold text-dark">
                                            {{ $reservation->doctorSchedule->doctor->name }}
                                        </td>
                                        <td class="align-middle text-muted">
                                            {{ $reservation->doctorSchedule->doctor->specialist }}
                                        </td>
                                        <td class="align-middle text-dark">
                                            <div class="font-weight-bold">{{ \Carbon\Carbon::parse($reservation->doctorSchedule->schedule_date)->translatedFormat('d M Y') }}</div>
                                            <small class="text-muted">{{ $reservation->doctorSchedule->start_time }} - {{ $reservation->doctorSchedule->end_time }} WIB</small>
                                        </td>
                                        <td class="align-middle">{{ $reservation->action }}</td>
                                        <td class="text-center align-middle">
                                            @if($reservation->status == 'approved')
                                                <span class="badge badge-success px-2 py-1">Disetujui</span>
                                            @elseif($reservation->status == 'completed')
                                                <span class="badge badge-primary px-2 py-1">Selesai</span>
                                            @else
                                                <span class="badge badge-danger px-2 py-1">Dibatalkan</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            @if($reservation->status == 'approved')
                                                <form action="{{ route('pasien.reservations.destroy', $reservation->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Yakin batal?')" class="btn btn-sm btn-danger px-3">
                                                        Batal
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">Belum ada riwayat reservasi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- FOOTER / PAGINATION --}}
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <div class="mb-2 mb-md-0">
                            <p class="mb-0 small text-muted">
                                Menampilkan <strong>{{ $reservations->firstItem() ?? 0 }}</strong> sampai 
                                <strong>{{ $reservations->lastItem() ?? 0 }}</strong> dari 
                                <strong>{{ $reservations->total() ?? 0 }}</strong> data
                            </p>
                        </div>
                        <div class="pagination-sm">
                            {{ $reservations->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection