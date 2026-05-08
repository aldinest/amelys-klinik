@extends('layouts.app_pengurus')

@section('title', 'Detail Reservasi')

@section('content')
<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid px-2 px-md-4">

            {{-- ================= INFO JADWAL (Tetap Style Info) ================= --}}
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card card-info shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-alt mr-1"></i> Informasi Jadwal Dokter
                            </h3>
                        </div>
                        <div class="card-body">
                            @php
                                $approvedCount = $reservations->where('status', 'approved')->count();
                                $completedCount = $reservations->where('status', 'completed')->count();
                                $cancelledCount = $reservations->where('status', 'cancelled')->count();
                                $sisaSlot = $schedule->quota - ($approvedCount + $completedCount);
                            @endphp
                            <div class="row">
                                <div class="col-6 col-md-3 mb-3">
                                    <small class="text-muted d-block">Dokter</small>
                                    <h6 class="mb-0 font-weight-bold text-truncate">{{ $schedule->doctor->name }}</h6>
                                </div>
                                <div class="col-6 col-md-3 mb-3">
                                    <small class="text-muted d-block">Tanggal</small>
                                    <h6 class="mb-0">{{ \Carbon\Carbon::parse($schedule->schedule_date)->locale('id')->translatedFormat('l, d M Y') }}</h6>
                                </div>
                                <div class="col-6 col-md-3 mb-3">
                                    <small class="text-muted d-block">Jam Praktik</small>
                                    <h6 class="mb-0">{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</h6>
                                </div>
                                <div class="col-6 col-md-3 mb-3">
                                    <small class="text-muted d-block">Sisa Slot</small>
                                    @if ($sisaSlot > 0)
                                        <span class="badge badge-success px-3 py-2 shadow-sm">{{ $sisaSlot }} Tersedia</span>
                                    @else
                                        <span class="badge badge-danger px-3 py-2 shadow-sm">Penuh</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= TABEL RESERVASI TERPISAH ================= --}}
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline card-outline-tabs shadow-sm">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active font-weight-bold" id="tabs-approved-tab" data-toggle="pill" href="#tabs-approved" role="tab">
                                        <i class="fas fa-clock mr-1 text-primary"></i> Disetujui ({{ $approvedCount }})
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link font-weight-bold" id="tabs-completed-tab" data-toggle="pill" href="#tabs-completed" role="tab">
                                        <i class="fas fa-check-circle mr-1 text-success"></i> Selesai ({{ $completedCount }})
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link font-weight-bold" id="tabs-cancelled-tab" data-toggle="pill" href="#tabs-cancelled" role="tab">
                                        <i class="fas fa-times-circle mr-1 text-danger"></i> Dibatalkan ({{ $cancelledCount }})
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-0">
                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                {{-- TAB: APPROVED --}}
                                <div class="tab-pane fade show active" id="tabs-approved" role="tabpanel">
                                    @include('layouts.partials._table', ['data' => $reservations->where('status', 'approved'), 'statusType' => 'approved'])
                                </div>
                                {{-- TAB: COMPLETED --}}
                                <div class="tab-pane fade" id="tabs-completed" role="tabpanel">
                                    @include('layouts.partials._table', ['data' => $reservations->where('status', 'completed'), 'statusType' => 'completed'])
                                </div>
                                {{-- TAB: CANCELLED --}}
                                <div class="tab-pane fade" id="tabs-cancelled" role="tabpanel">
                                    @include('layouts.partials._table', ['data' => $reservations->where('status', 'cancelled'), 'statusType' => 'cancelled'])
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('pengurus.reservations.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                                </a>
                                <div class="btn-group">
                                    <a href="{{ route('pengurus.reservations.export-pdf', $schedule->id) }}" class="btn btn-sm btn-light border text-danger font-weight-bold">
                                        <i class="fas fa-file-pdf mr-1"></i> PDF
                                    </a>
                                    <a href="{{ route('pengurus.reservations.export-excel', $schedule->id) }}" class="btn btn-sm btn-light border text-success font-weight-bold ml-1">
                                        <i class="fas fa-file-excel mr-1"></i> EXCEL
                                    </a>
                                </div>
                            </div>
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
                    <a href="#" id="wa-link-akun" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <span><i class="fas fa-bell text-success mr-2"></i> Kirim Pengingat Jadwal</span>
                        <i class="fas fa-chevron-right text-muted small"></i>
                    </a>
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

        // Perbaikan Nomor Telepon ke Kode Negara +62
        let phone = d.phone.replace(/[^0-9]/g, '');
        if (phone.startsWith('0')) {
            phone = '62' + phone.substring(1); 
        } else if (phone.startsWith('8')) {
            phone = '62' + phone;
        }

        const msgReminder = encodeURIComponent(
            `Halo *${d.nama}*,\n\n` +
            `Kami dari *Praktek Dokter Amelys* ingin mengingatkan jadwal pemeriksaan Anda pada:\n` +
            `📅 Tanggal: *${d.tgl}*\n` +
            `⏰ Jam: *${d.jam} WIB*\n\n` +
            `Nomor antrian sesuai kedatangan ya kak. Silahkan mengambil nomor antrian yang sudah disediakan di meja 🙏. Terima kasih.`
        );

        const msgReschedule = encodeURIComponent(
            `Halo *${d.nama}*,\n\n` +
            `Mohon maaf mengganggu waktunya. Kami dari *Praktek Dokter Amelys* menginformasikan bahwa dikarenakan kendala teknis, jadwal Anda pada ${d.tgl} *harus dipindahkan*.\n\n` +
            `Kami akan segera menghubungi Anda kembali untuk mengatur ulang jadwal. Terima kasih.`
        );

        document.getElementById('wa-link-akun').href = `https://wa.me/${phone}?text=${msgReminder}`;
        document.getElementById('wa-link-jadwal').href = `https://wa.me/${phone}?text=${msgReschedule}`;
    });
});
</script>

<style>
    .gap-1 { gap: 0.35rem; }
    .badge { border-radius: 4px; } /* Paksa persegi dengan rounded halus sesuai selera lo */
    @media (max-width: 768px) {
        .btn-sm { padding: 0.3rem 0.5rem; font-size: 0.75rem; }
        .table td { padding: 0.5rem !important; }
    }
</style>
@endsection