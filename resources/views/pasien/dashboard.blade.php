@extends('layouts.app_pasien')

@section('content')
<div class="content-wrapper">
    {{-- HEADER --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold text-dark">Portal Pasien Amelys Klinik</h1>
                    <p class="text-muted small">Cek jadwal, reservasi mandiri, dan riwayat medis Anda di sini.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <div class="card card-outline card-primary shadow-sm mb-4">
                <div class="card-header border-0 bg-white">
                    <h3 class="card-title font-weight-bold text-primary">
                        <i class="fas fa-project-diagram mr-2"></i> Panduan Layanan Online
                    </h3>
                </div>
                <div class="card-body pt-0">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded h-100 bg-light shadow-none border-primary">
                                <div class="badge badge-primary mb-2 px-3">1. Buat Reservasi</div>
                                <h6 class="font-weight-bold">Pilih Dokter & Jadwal</h6>
                                <p class="small text-muted mb-0">Pilih dokter, cek jadwal praktik harian yang tersedia, lalu isi detail tindakan medis Anda.</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded h-100 bg-light shadow-none border-info">
                                <div class="badge badge-info mb-2 px-3 text-white">2. Reservasi Saya</div>
                                <h6 class="font-weight-bold">Status Reservasi</h6>
                                <p class="small text-muted mb-0">Lihat daftar reservasi Anda di menu <strong>Reservasi Saya</strong>. Tersedia opsi pembatalan jika berhalangan.</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded h-100 bg-light shadow-none border-success">
                                <div class="badge badge-success mb-2 px-3">3. Riwayat Medis</div>
                                <h6 class="font-weight-bold">Hasil Periksa & Cetak</h6>
                                <p class="small text-muted mb-0">Akses rekam medis Anda setelah selesai periksa, serta <strong>cetak hasil riwayat</strong> medis per kunjungan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PANDUAN LAYANAN ONLINE --}}
<div class="card card-outline card-primary shadow-sm mb-4">
    </div>

{{-- INFORMASI KHUSUS ANTRIAN --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-left: 5px solid #ffc107 !important; background-color: #fff9e6;">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle text-warning fa-2x"></i>
                        </div>
                        <div class="col">
                            <h6 class="font-weight-bold text-dark mb-1">
                                PENTING: Prosedur Antrian drg. Agus (Poli Gigi)
                            </h6>
                            <p class="small text-muted mb-0">
                                Khusus untuk layanan <strong>drg. Agus</strong>, urutan masuk ruangan pemeriksaan <strong>berdasarkan nomor antrian fisik</strong> yang diambil di <strong>Apotek Amelys</strong> setelah Anda melakukan reservasi online, bukan berdasarkan urutan daftar di aplikasi.
                            </p>
                        </div>
                        <div class="col-md-auto mt-2 mt-md-0">
                            <span class="badge badge-warning px-3 py-2 text-uppercase">Wajib Ambil Antrian Fisik</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-outline card-primary shadow-sm mb-3">
                        <div class="card-header border-0 bg-white">
                            <h3 class="card-title font-weight-bold text-primary">
                                <i class="fas fa-id-card mr-2"></i> Informasi Pasien
                            </h3>
                        </div>
                        <div class="card-body box-profile pt-0">
                            <div class="row">
                                {{-- Kolom Kiri --}}
                                <div class="col-md-6">
                                    <ul class="list-group list-group-unbordered mb-0">
                                        <li class="list-group-item border-top-0 py-2">
                                            <b class="text-muted small text-uppercase">Nama Lengkap</b> 
                                            <span class="d-block font-weight-bold text-dark text-uppercase mt-1">{{ $patient->name }}</span>
                                        </li>
                                        <li class="list-group-item border-0 py-2">
                                            <b class="text-muted small text-uppercase">Jenis Kelamin</b> 
                                            <span class="d-block text-dark mt-1">{{ $patient->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                        </li>
                                    </ul>
                                </div>
                                {{-- Kolom Kanan --}}
                                <div class="col-md-6 border-left">
                                    <ul class="list-group list-group-unbordered mb-0">
                                        <li class="list-group-item border-top-0 py-2">
                                            <b class="text-muted small text-uppercase">No. Rekam Medis (RM)</b> 
                                            <span class="d-block font-weight-bold text-danger mt-1">{{ $patient->medical_record_number ?? 'B-000' }}</span>
                                        </li>
                                        <li class="list-group-item border-0 py-2">
                                            <b class="text-muted small text-uppercase">No. Telepon / HP</b> 
                                            <span class="d-block text-dark mt-1">{{ $patient->phone }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <hr class="mt-2 mb-1">
                            <ul class="list-group list-group-unbordered mb-0">
                                <li class="list-group-item border-0 pt-1 pb-0">
                                    <b class="text-muted small text-uppercase"><i class="fas fa-history mr-1"></i> Total Kunjungan</b> 
                                    <span class="float-right badge badge-success">{{ $totalMedicalRecords }} Kali Periksa</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- JADWAL TERBARU --}}
                <div class="col-md-6">
                    <div class="card card-outline card-info shadow-sm mb-3">
                        <div class="card-header border-0 bg-white">
                            <h3 class="card-title font-weight-bold text-info">
                                <i class="fas fa-user-md mr-2"></i> Jadwal Dokter Hari Ini
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover table-valign-middle m-0">
                                <thead class="bg-light">
                                    <tr class="small text-uppercase">
                                        <th>Dokter</th>
                                        <th class="text-center">Jam Praktek</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($todaySchedules as $schedule)
                                        <tr>
                                            <td>
                                                <div class="font-weight-bold text-primary">{{ $schedule->doctor->name }}</div>
                                                <small class="text-muted">{{ $schedule->doctor->specialist ?? 'Umum' }}</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-light border">{{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('pasien.reservations.create', ['schedule' => $schedule->id]) }}" class="btn btn-outline-primary btn-sm font-weight-bold">
                                                    Daftar
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center py-4 text-muted small italic text-muted font-weight-bold">Tidak ada jadwal hari ini.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- NEWS / INFORMASI DARI ADMIN --}}
            <div class="row mt-2">
                <div class="col-12">
                    <div class="card card-outline card-primary shadow-sm">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold text-primary">
                                <i class="fas fa-bullhorn mr-2"></i> Informasi Klinik
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($news as $item)
                                <div class="col-md-4 mb-3 border-right">
                                    <div class="post px-2">
                                        <div class="user-block mb-1">
                                            <span class="description ml-0 text-primary font-weight-bold small text-uppercase">
                                                <i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d M Y') }}
                                            </span>
                                        </div>
                                        <h6 class="font-weight-bold text-dark mb-1">{{ $item->title }}</h6>
                                        <p class="small text-muted mb-0">
                                            {{ Str::limit($item->description, 110) }}
                                        </p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<style>
    .content-wrapper { background-color: #f4f6f9 !important; }
    .card { border-radius: 8px; border: none; }
    .card-outline.card-primary { border-top: 3px solid #007bff !important; }
    .box-profile { padding-top: 0rem; }
    .profile-username { font-size: 1.25rem; margin-top: 0px; }
    .border-left { border-left: 1px solid #dee2e6 !important; }
</style>
@endsection