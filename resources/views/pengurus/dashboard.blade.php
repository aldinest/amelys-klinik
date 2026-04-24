@extends('layouts.app_pengurus')

@section('content')
<div class="content-wrapper">
    {{-- HEADER --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold text-dark">Dashboard Pengurus Klinik</h1>
                    <p class="text-muted">Kelola data pasien dan reservasi harian secara real-time.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            
            {{-- STATISTIC BOXES --}}
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary shadow-sm">
                        <div class="inner">
                            <h3>{{ $pasienHariIni ?? 0 }}</h3>
                            <p>Reservasi Hari Ini</p>
                        </div>
                        <div class="icon"><i class="fas fa-calendar-check"></i></div>
                        <a href="{{ route('pengurus.reservations.index')}}" class="small-box-footer">Lihat Antrean <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success shadow-sm">
                        <div class="inner">
                            <h3>{{ $totalPasien ?? 0 }}</h3>
                            <p>Total Master Pasien</p>
                        </div>
                        <div class="icon"><i class="fas fa-users"></i></div>
                        <a href="{{ route('pengurus.patients.index') }}" class="small-box-footer">Kelola Pasien <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info shadow-sm">
                        <div class="inner">
                            <h3>{{ $totalDokter ?? 0 }}</h3>
                            <p>Jadwal Praktik</p>
                        </div>
                        <div class="icon"><i class="fas fa-user-md"></i></div>
                        <a href="{{ route('pengurus.doctor_schedules.index')}}" class="small-box-footer">Cek Jadwal <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning shadow-sm">
                        <div class="inner text-white">
                            <h3 class="text-white">RM</h3>
                            <p class="text-white">Rekam Medis</p>
                        </div>
                        <div class="icon"><i class="fas fa-file-medical"></i></div>
                        <a href="{{ route('pengurus.medical-records.index')}}" class="small-box-footer" style="color:rgba(255,255,255,0.8) !important">Cetak Data <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            {{-- STEP FLOW OPERASIONAL (Berdasarkan penjelasan lo) --}}
            <div class="card card-outline card-primary shadow-sm mb-4">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold text-primary">
                        <i class="fas fa-project-diagram mr-2"></i> Alur Kerja Operasional Pengurus
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="p-3 border rounded h-100 bg-light shadow-none">
                                <div class="badge badge-primary mb-2">1. Data Master</div>
                                <h6 class="font-weight-bold">List Semua Pasien</h6>
                                <p class="small text-muted mb-0">Kelola semua pasien & gunakan fitur <strong>chat whatsapp</strong> untuk menghubungi pasien.</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 border rounded h-100 bg-light shadow-none">
                                <div class="badge badge-info mb-2">2. Scheduling</div>
                                <h6 class="font-weight-bold">Jadwal & Kuota</h6>
                                <p class="small text-muted mb-0">Atur jadwal praktek harian beserta <strong>kuota maksimal</strong> dokter agar antrean tertib.</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 border rounded h-100 bg-light shadow-none">
                                <div class="badge badge-success mb-2">3. Reservasi</div>
                                <h6 class="font-weight-bold">Aksi & Otomasi</h6>
                                <p class="small text-muted mb-0">Daftarkan pasien. Aksi akan <strong>otomatis terkunci</strong> jika kuota penuh atau hari terlampaui.</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 border rounded h-100 bg-light shadow-none">
                                <div class="badge badge-warning mb-2 text-white">4. Rekam Medis</div>
                                <h6 class="font-weight-bold">Input & Cetak</h6>
                                <p class="small text-muted mb-0">Input hasil periksa dokter ke RM pasien dan <strong>cetak hasil medis</strong> per dokter.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- TABLE RESERVASI TERBARU --}}
                <div class="col-lg-7">
                    <div class="card card-outline card-success shadow-sm" style="min-height: 400px;">
                        <div class="card-header border-0">
                            <h3 class="card-title font-weight-bold">Aktivitas Reservasi Hari Ini</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Pasien</th>
                                        <th>Status</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reservasiTerbaru ?? [] as $res)
                                    <tr>
                                        <td>#{{ $res->id }}</td>
                                        <td class="font-weight-bold text-dark">{{ $res->patient->name }}</td>
                                        <td><span class="badge badge-success">Selesai</span></td>
                                        <td><i class="far fa-clock mr-1 small text-muted"></i> {{ $res->created_at->format('H:i') }} WIB</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted italic">Belum ada aktivitas hari ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-white text-center">
                            <a href="{{ route('pengurus.reservations.index') }}" class="font-weight-bold">Lihat Semua Antrean <i class="fas fa-chevron-right ml-1 small"></i></a>
                        </div>
                    </div>
                </div>

                {{-- INFO CARD --}}
                <div class="col-lg-5">
                    <div class="card card-outline card-info shadow-sm">
                        <div class="card-header border-0">
                            <h3 class="card-title font-weight-bold">Info Singkat Sistem</h3>
                        </div>
                        <div class="card-body">
                            <div class="callout callout-warning py-2 mb-0">
                                <h6 class="font-weight-bold text-warning font-sm"><i class="fas fa-lock mr-2"></i> Fitur Auto-Lock</h6>
                                <p class="small text-muted mb-0">Sistem otomatis menutup tombol "Periksa" jika kuota dokter di hari tersebut sudah terpenuhi atau tanggal sudah lewat.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection