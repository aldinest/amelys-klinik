@extends('layouts.app_pengurus')

@section('title', 'Detail Reservasi')

@section('content')
<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid px-2 px-md-4"> {{-- Padding lebih kecil di HP --}}

            {{-- ================= INFO JADWAL ================= --}}
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card card-info shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-alt me-1"></i> Informasi Jadwal Dokter
                            </h3>
                        </div>

                        <div class="card-body">
                            @php
                                $approvedCount = $reservations->whereIn('status', ['approved', 'completed'])->count();
                                $sisaSlot = $schedule->quota - $approvedCount;
                            @endphp

                            <div class="row">
                                {{-- Menggunakan col-6 agar di HP tampil 2 kolom menyamping, bukan 1 baris penuh --}}
                                <div class="col-6 col-md-3 mb-3">
                                    <small class="text-muted d-block">Dokter</small>
                                    <h6 class="mb-0 font-weight-bold text-truncate">{{ $schedule->doctor->name }}</h6>
                                </div>

                                <div class="col-6 col-md-3 mb-3">
                                    <small class="text-muted d-block">Tanggal</small>
                                    <h6 class="mb-0">{{ \Carbon\Carbon::parse($schedule->schedule_date)->format('d M Y') }}</h6>
                                </div>

                                <div class="col-6 col-md-3 mb-3">
                                    <small class="text-muted d-block">Jam Praktik</small>
                                    <h6 class="mb-0">{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</h6>
                                </div>

                                <div class="col-6 col-md-3 mb-3">
                                    <small class="text-muted d-block">Kuota</small>
                                    <h6 class="mb-0">{{ $approvedCount }} / {{ $schedule->quota }}</h6>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <small class="text-muted d-block">Sisa Slot</small>
                                    @if ($sisaSlot > 0)
                                        <span class="badge bg-success px-3">{{ $sisaSlot }} Tersedia</span>
                                    @else
                                        <span class="badge bg-danger px-3">Penuh</span>
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
                                <i class="fas fa-users me-1"></i> Daftar Pasien Reservasi
                            </h3>
                        </div>

                        <div class="card-body table-responsive p-0"> {{-- table-responsive tetap menjaga tabel bisa di-scroll di HP --}}
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-light text-nowrap">
                                    <tr>
                                        <th width="50" class="text-center">No</th>
                                        <th>Nama Pasien</th>
                                        <th class="d-none d-md-table-cell">No RM</th> {{-- Sembunyikan No RM di HP agar ringkas --}}
                                        <th>Tindakan</th>
                                        <th class="text-center">Status</th>
                                        <th width="150" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse ($reservations as $reservation)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-nowrap">
                                            <div class="font-weight-bold">{{ $reservation->patient->name }}</div>
                                            <small class="d-md-none text-muted">RM: {{ $reservation->patient->medical_record_number ?? '-' }}</small>
                                        </td>
                                        <td class="d-none d-md-table-cell">{{ $reservation->patient->medical_record_number ?? '-' }}</td>
                                        <td><span class="text-muted small">{{ $reservation->action ?? '-' }}</span></td>
                                        <td class="text-center">
                                            @switch($reservation->status)
                                                @case('approved') <span class="badge bg-primary px-2">Disetujui</span> @break
                                                @case('completed') <span class="badge bg-success px-2">Selesai</span> @break
                                                @default <span class="badge bg-danger px-2">Dibatalkan</span>
                                            @endswitch
                                        </td>
                                        <td class="text-center">
                                            {{-- Bungkus dalam flex-nowrap agar tombol tidak turun ke bawah (stacking) di HP --}}
                                            <div class="d-flex justify-content-center gap-1 flex-nowrap px-2">
                                                
                                                {{-- Tombol Detail --}}
                                                <a href="{{ route('pengurus.patients.show', $reservation->patient->id) }}" class="btn btn-info btn-sm" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                {{-- Tombol WA --}}
                                                <button type="button" 
                                                    class="btn btn-success btn-sm btn-kirim-wa" 
                                                    data-toggle="modal" 
                                                    data-target="#modalWA"
                                                    data-nama="{{ $reservation->patient->name }}"
                                                    data-phone="{{ $reservation->patient->phone }}"
                                                    data-email="{{ $reservation->patient->user->email ?? '-' }}"
                                                    data-tgl="{{ \Carbon\Carbon::parse($schedule->schedule_date)->format('d-m-Y') }}"
                                                    data-jam="{{ substr($schedule->start_time, 0, 5) }}">
                                                    <i class="fab fa-whatsapp"></i>
                                                </button>

                                                {{-- Tombol Periksa / Lihat RM --}}
                                                @if ($reservation->status === 'approved' && !$reservation->medicalRecord)
                                                    <a href="{{ route('pengurus.medical-records.create', ['reservation_id' => $reservation->id]) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-stethoscope"></i>
                                                    </a>
                                                @elseif ($reservation->medicalRecord)
                                                    <a href="{{ route('pengurus.medical-records.show', $reservation->medicalRecord->id) }}" class="btn btn-sm btn-success">
                                                        <i class="fas fa-notes-medical"></i>
                                                    </a>
                                                @endif

                                                {{-- Tombol Cancel --}}
                                                @if ($reservation->status === 'approved')
                                                    <form action="{{ route('pengurus.reservations.cancel', $reservation->id) }}" method="POST" onsubmit="return confirm('Batalkan?')" class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-sm btn-danger px-2"><i class="fas fa-times"></i></button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Tidak ada data reservasi</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer">
                            <a href="{{ route('pengurus.reservations.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- MODAL WA --}}
<div class="modal fade" id="modalWA" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white py-2">
                <h5 class="modal-title small font-weight-bold">Kirim WhatsApp</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0">
                <div class="p-3 text-center border-bottom bg-light">
                    <small class="text-muted">Penerima:</small>
                    <div id="wa-name" class="font-weight-bold"></div>
                </div>
                <div class="list-group list-group-flush">
                    {{-- Pilihan 1: Pengingat Jadwal --}}
                    <a href="#" id="wa-link-akun" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <span><i class="fas fa-bell text-success mr-2"></i> Kirim Pengingat Jadwal</span>
                        <i class="fas fa-chevron-right text-muted small"></i>
                    </a>
                    
                    {{-- Pilihan 2: Jadwal Dipindahkan --}}
                    <a href="#" id="wa-link-jadwal" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <span><i class="fas fa-calendar-alt text-warning mr-2"></i> Kabari Jadwal Dipindahkan</span>
                        <i class="fas fa-chevron-right text-muted small"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.btn-kirim-wa').forEach(btn => {
    btn.addEventListener('click', function() {
        const d = this.dataset;
        document.getElementById('wa-name').innerText = d.nama;

        // Logika Perbaikan Nomor Telepon (+62)
        let phone = d.phone.replace(/[^0-9]/g, ''); // Ambil angka saja
        if (phone.startsWith('0')) {
            phone = '62' + phone.substring(1);
        } else if (phone.startsWith('8')) {
            phone = '62' + phone;
        }

        // 1. Template Pengingat Jadwal
        const msgReminder = encodeURIComponent(
            `Halo *${d.nama}*,\n\n` +
            `Kami dari *Praktek Dokter Amelys* ingin mengingatkan jadwal pemeriksaan Anda pada:\n` +
            `📅 Tanggal: *${d.tgl}*\n` +
            `⏰ Jam: *${d.jam} WIB*\n\n` +
            `Mohon hadir 15 menit lebih awal. Jika ada kendala, silakan hubungi kami kembali. Terima kasih.`
        );

        // 2. Template Jadwal Dipindahkan
        const msgReschedule = encodeURIComponent(
            `Halo *${d.nama}*,\n\n` +
            `Mohon maaf mengganggu waktunya. Kami dari *Praktek Dokter Amelys* menginformasikan bahwa dikarenakan adanya kendala teknis yang mendesak, jadwal pemeriksaan Anda pada ${d.tgl} *harus dipindahkan*.\n\n` +
            `Kami akan segera menghubungi Anda kembali untuk mengatur ulang jadwal pengganti yang tersedia. Mohon maaf atas ketidaknyamanan ini. Terima kasih.`
        );

        // Update Link ke Modal
        document.getElementById('wa-link-akun').href = `https://wa.me/${phone}?text=${msgReminder}`;
        document.getElementById('wa-link-jadwal').href = `https://wa.me/${phone}?text=${msgReschedule}`;
    });
});
</script>

<style>
    .gap-1 { gap: 0.25rem; }
    @media (max-width: 768px) {
        .btn-sm { padding: 0.25rem 0.4rem; font-size: 0.75rem; }
        .table td { vertical-align: middle; padding: 0.5rem !important; }
    }
</style>
@endsection