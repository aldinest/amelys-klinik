@extends('layouts.app_pasien')

@section('content')
<div class="content-wrapper bg-white">
    {{-- HEADER --}}
    <section class="content-header pt-3 pb-2">
        <div class="container-fluid">
            <div class="row align-items-end">
                <div class="col-sm-6">
                    <h3 class="m-0 font-weight-bold text-dark" style="letter-spacing: -0.5px; font-size: 1.5rem;">Reservasi Saya</h3>
                    <p class="text-muted mb-0 small">Kelola dan pantau riwayat kunjungan Anda</p>
                </div>
                <div class="col-sm-6 text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('pasien.reservations.create') }}" class="btn btn-primary shadow-sm px-3" style="border-radius: 8px;">
                        <i class="fas fa-plus-circle mr-1"></i> Buat Reservasi
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content mt-4">
        <div class="container-fluid">
            <div class="card shadow-none border-0" style="border-radius: 15px; background: #f8f9fa;">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <h6 class="card-title font-weight-bold text-muted uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">
                        <i class="fas fa-history mr-2 text-primary"></i> RIWAYAT KUNJUNGAN
                    </h6>
                </div>

                <div class="card-body p-0">
                    {{-- TAMPILAN DESKTOP --}}
                    <div class="d-none d-md-block">
                        <div class="table-responsive px-4 pb-4">
                            <table class="table table-borderless bg-white shadow-sm mb-0">
                                <thead style="background-color: #f1f4f9;">
                                    <tr class="text-muted small uppercase">
                                        <th class="text-center py-3" width="60">No</th>
                                        <th class="py-3">Dokter & Spesialis</th>
                                        <th class="py-3">Waktu</th>
                                        <th class="text-center py-3">Status</th>
                                        <th class="text-center py-3" width="120">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reservations as $reservation)
                                        <tr class="border-bottom">
                                            <td class="text-center align-middle text-muted">{{ $loop->iteration }}</td>
                                            <td class="align-middle">
                                                <div class="font-weight-bold text-dark text-capitalize">dr. {{ $reservation->doctorSchedule->doctor->name }}</div>
                                                <small class="text-muted">{{ $reservation->doctorSchedule->doctor->specialist }}</small>
                                            </td>
                                            <td class="align-middle">
                                                <div class="font-weight-bold text-dark">{{ \Carbon\Carbon::parse($reservation->doctorSchedule->schedule_date)->translatedFormat('d M Y') }}</div>
                                                <small class="text-muted">{{ $reservation->doctorSchedule->start_time }} WIB</small>
                                            </td>
                                           <td class="text-center align-middle">
                                                @switch($reservation->status)
                                                    @case('approved')
                                                        <span class="badge badge-pill badge-primary px-3 py-2 shadow-sm">
                                                            <i class="fas fa-check-circle mr-1"></i> Disetujui
                                                        </span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge badge-pill badge-success px-3 py-2 shadow-sm">
                                                            <i class="fas fa-clipboard-check mr-1"></i> Selesai
                                                        </span>
                                                        @break
                                                    @case('pending')
                                                        <span class="badge badge-pill badge-warning px-3 py-2 text-white shadow-sm">
                                                            <i class="fas fa-clock mr-1"></i> Menunggu
                                                        </span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-pill badge-secondary px-3 py-2 shadow-sm">
                                                            <i class="fas fa-times-circle mr-1"></i> Batal
                                                        </span>
                                                @endswitch
                                            </td>
                                            <td class="text-center align-middle">
                                                @if($reservation->status == 'approved')
                                                    <form action="{{ route('pasien.reservations.destroy', $reservation->id) }}" method="POST" class="form-delete">
                                                        @csrf @method('DELETE')
                                                        <button type="button" class="btn btn-link text-danger font-weight-bold p-0 btn-cancel-reservation" data-doctor="dr. {{ $reservation->doctorSchedule->doctor->name }}">
                                                            Batalkan
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada data.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TAMPILAN MOBILE --}}
                    <div class="d-block d-md-none px-3 pb-3">
                        @forelse($reservations as $reservation)
                            <div class="card mb-3 shadow-sm border-0" style="border-radius: 12px;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <span class="text-muted small">DOKTER</span>
                                            <div class="font-weight-bold text-primary text-capitalize">dr. {{ $reservation->doctorSchedule->doctor->name }}</div>
                                            <div class="text-muted small">{{ $reservation->doctorSchedule->doctor->specialist }}</div>
                                        </div>
                                        @if($reservation->status == 'approved')
                                            <span class="badge badge-pill badge-primary px-2 py-1">Disetujui</span>
                                        @elseif($reservation->status == 'completed')
                                            <span class="badge badge-pill badge-success px-2 py-1">Selesai</span>
                                        @else
                                            <span class="badge badge-pill badge-secondary px-2 py-1">Batal</span>
                                        @endif
                                    </div>
                                    
                                    <div class="row bg-light mx-0 py-2 rounded mb-3">
                                        <div class="col-6 border-right">
                                            <small class="text-muted d-block">TANGGAL</small>
                                            <span class="font-weight-bold small text-dark">{{ \Carbon\Carbon::parse($reservation->doctorSchedule->schedule_date)->translatedFormat('d M Y') }}</span>
                                        </div>
                                        <div class="col-6 pl-3">
                                            <small class="text-muted d-block">JAM</small>
                                            <span class="font-weight-bold small text-dark">{{ $reservation->doctorSchedule->start_time }} WIB</span>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="small">
                                            <span class="text-muted">Keperluan:</span>
                                            <span class="text-dark d-block text-truncate" style="max-width: 150px;">{{ $reservation->action }}</span>
                                        </div>
                                        @if($reservation->status == 'approved')
                                            <form action="{{ route('pasien.reservations.destroy', $reservation->id) }}" method="POST" class="form-delete">
                                                @csrf @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger px-3 btn-cancel-reservation" style="border-radius: 8px;" data-doctor="dr. {{ $reservation->doctorSchedule->doctor->name }}">
                                                    Batalkan
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <p class="text-muted">Belum ada riwayat reservasi.</p>
                            </div>
                        @endempty
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="card-footer bg-transparent border-0 pb-4 px-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <p class="mb-0 small text-muted">
                            Menampilkan {{ $reservations->firstItem() ?? 0 }}-{{ $reservations->lastItem() ?? 0 }} dari {{ $reservations->total() ?? 0 }} data
                        </p>
                        <div class="pagination-sm">
                            {{ $reservations->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .table-borderless tbody tr { border-bottom: 1px solid #f2f2f2 !important; }
    .table-borderless tbody tr:last-child { border-bottom: 0 !important; }
    .badge-pill { font-weight: 600; font-size: 11px; letter-spacing: 0.3px; }
    .table-hover tbody tr:hover { background-color: #fcfdfe !important; }
    
    /* Menyesuaikan style font SweetAlert2 agar matching */
    .swal2-popup {
        font-family: 'Poppins', 'Montserrat', 'Segoe UI', sans-serif !important;
        border-radius: 15px !important;
    }
    @media (max-width: 768px) {
    /* Memberikan ruang napas di bawah konten utama agar tidak tertutup navbar bawah */
    .content-wrapper {
        padding-bottom: 80px !important; 
    }
    
    /* Memberi jarak pada footer di dalam kartu reservasi */
    .card-footer {
        padding-top: 20px !important;
        padding-bottom: 20px !important;
    }

    /* Mengatur jarak paginasi agar tidak terlalu rapat dengan teks info */
    .pagination-sm {
        margin-top: 15px;
    }
}
</style>

{{-- SCRIPT SWEETALERT2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Berlaku untuk desktop maupun mobile karena menggunakan class selector yang sama
        const cancelButtons = document.querySelectorAll('.btn-cancel-reservation');
        
        cancelButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('.form-delete');
                const doctorName = this.getAttribute('data-doctor');

                Swal.fire({
                    title: 'Batalkan Reservasi?',
                    text: `Apakah Anda yakin ingin membatalkan jadwal pemeriksaan dengan ${doctorName}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545', // Warna merah Danger Bootstrap
                    cancelButtonColor: '#6c757d',  // Warna abu-abu Secondary Bootstrap
                    confirmButtonText: 'Ya, Batalkan',
                    cancelButtonText: 'Kembali',
                    reverseButtons: true // Tombol 'Kembali' di kiri, 'Ya, Batalkan' di kanan (Lebih user-friendly)
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection