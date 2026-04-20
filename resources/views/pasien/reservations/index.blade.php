@extends('layouts.app_pasien')

@section('content')
<div class="content-wrapper">

    {{-- HEADER --}}
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1>Reservasi Saya</h1>
            <a href="{{ route('pasien.reservations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Buat Reservasi
            </a>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Dokter</th>
                                <th>Spesialis</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Keperluan</th>
                                <th>Status</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservations as $reservation)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        {{ $reservation->doctorSchedule->doctor->name }}
                                    </td>

                                    <td>
                                        {{ $reservation->doctorSchedule->doctor->specialist }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($reservation->doctorSchedule->schedule_date)->format('d M Y') }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($reservation->doctorSchedule->start_time)->format('H:i') }}
                                        -
                                        {{ \Carbon\Carbon::parse($reservation->doctorSchedule->end_time)->format('H:i') }}
                                    </td>

                                    <td>{{ $reservation->action }}</td>

                                    <td>
                                        @if($reservation->status == 'approved')
                                            <span class="badge badge-success">Disetujui</span>
                                        @elseif($reservation->status == 'completed')
                                            <span class="badge badge-primary">Selesai</span>
                                        @else
                                            <span class="badge badge-danger">Dibatalkan</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($reservation->status == 'approved')
                                            <form action="{{ route('pasien.reservations.destroy', $reservation->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Yakin ingin membatalkan reservasi ini?')"
                                                    class="btn btn-sm btn-danger">
                                                    Batalkan
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        Belum ada reservasi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center">
                        <p class="mb-0 small text-muted">
                            Menampilkan <strong>{{ $reservations->firstItem() }}</strong> - <strong>{{ $reservations->lastItem() }}</strong> dari <strong>{{ $reservations->total() }}</strong> reservasi
                        </p>
                        <div>
                            {{ $reservations->links('pagination::bootstrap-4') }}
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

</div>
@endsection