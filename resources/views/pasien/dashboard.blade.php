@extends('layouts.app_pasien')

@section('content')
<style>
    .content-wrapper { 
        background-color: #f8fafc !important; 
        margin-left: 0 !important; 
    }
    
    @media (min-width: 769px) {
        .container-custom { padding: 0 8%; }
    }

    @media (max-width: 768px) {
        .content-wrapper { padding-bottom: 90px !important; }
        .container-custom { padding: 0 15px; }
    }

    /* Glassmorphism Alert */
    .announcement-banner {
        background: linear-gradient(135deg, #fff5f5 0%, #fff 100%);
        border: 1px solid #fee2e2;
        border-left: 5px solid #dc3545 !important;
        border-radius: 15px;
    }

    /* Small Action Card */
    .mini-card {
        border-radius: 16px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0 !important;
        background: #fff;
    }
    .mini-card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
    
    .mini-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .step-number {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
</style>

<div class="content-wrapper">
    <div class="container-custom py-4">
        
        {{-- SECTION 1: GREETING --}}
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                @php
                    $hour = \Carbon\Carbon::now()->addHours(7)->format('H');
                    if ($hour >= 5 && $hour < 11) { $sapaan = 'Pagi'; }
                    elseif ($hour >= 11 && $hour < 15) { $sapaan = 'Siang'; }
                    elseif ($hour >= 15 && $hour < 19) { $sapaan = 'Sore'; }
                    else { $sapaan = 'Malam'; }
                @endphp
                <h4 class="font-weight-bold mb-0 text-dark text-capitalize">Selamat {{ $sapaan }}, {{ explode(' ', auth()->user()->name)[0] }}!</h4>
                <p class="text-muted small mb-0">Dashboard kesehatan Amelys Klinik.</p>
            </div>
            <!-- <div class="text-right">
                <span class="badge badge-primary px-3 py-2" style="border-radius: 8px;">
                    RM: {{ $patient->medical_record_number ?? 'BARU' }}
                </span>
            </div> -->
        </div>

        {{-- SECTION 2: PENGUMUMAN (PALING ATAS & JELAS) --}}
        @if(count($news) > 0)
        <div class="alert announcement-banner shadow-sm mb-4 p-3">
            <div class="d-flex align-items-start">
                <div class="mr-3 bg-danger text-white rounded-circle d-flex align-items-center justify-content-center mt-1" style="width: 35px; height: 35px; flex-shrink: 0;">
                    <i class="fas fa-bullhorn fa-sm"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong class="text-danger small font-weight-bold">PENGUMUMAN TERBARU</strong>
                        <span class="text-muted" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($news[0]->date)->diffForHumans() }}</span>
                    </div>
                    <h6 class="font-weight-bold text-dark mb-1">{{ $news[0]->title }}</h6>
                    {{-- Menggunakan nl2br agar enter/baris baru di database muncul sebagai baris baru di tampilan --}}
                    <div class="mb-0 text-muted small" style="line-height: 1.5; white-space: pre-line;">
                        {!! nl2br(e($news[0]->description)) !!}
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- SECTION 3: LANGKAH CEPAT (KOTAK KECIL/MINI) --}}
        <div class="row mb-4">
            {{-- LANGKAH 1 --}}
            <div class="col-6 col-md-4 mb-3">
                <a href="{{ url('/pasien/reservations/create') }}" class="text-decoration-none">
                    <div class="card mini-card shadow-sm h-100 mb-0">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="mini-icon bg-primary text-white mr-2 shadow-sm">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <span class="step-number text-primary">Tahap 1</span>
                            </div>
                            <h6 class="font-weight-bold text-dark mb-1">Daftar</h6>
                            <p class="text-muted mb-0" style="font-size: 0.75rem;">Reservasi jadwal periksa.</p>
                        </div>
                    </div>
                </a>
            </div>

            {{-- LANGKAH 2 --}}
            <div class="col-6 col-md-4 mb-3">
                <a href="{{ url('/pasien/reservations') }}" class="text-decoration-none">
                    <div class="card mini-card shadow-sm h-100 mb-0">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="mini-icon bg-success text-white mr-2 shadow-sm">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                <span class="step-number text-success">Tahap 2</span>
                            </div>
                            <h6 class="font-weight-bold text-dark mb-1">Status</h6>
                            <p class="text-muted mb-0" style="font-size: 0.75rem;">Cek status reservasi.</p>
                        </div>
                    </div>
                </a>
            </div>

            {{-- LANGKAH 3 --}}
            <div class="col-md-4 col-12 mb-3">
                <a href="{{ url('/pasien/medical-records') }}" class="text-decoration-none">
                    <div class="card mini-card shadow-sm h-100 mb-0">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="mini-icon bg-info text-white mr-2 shadow-sm">
                                    <i class="fas fa-file-invoice"></i>
                                </div>
                                <span class="step-number text-info">Tahap 3</span>
                            </div>
                            <h6 class="font-weight-bold text-dark mb-1">Riwayat</h6>
                            <p class="text-muted mb-0" style="font-size: 0.75rem;">History pemeriksaan.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            {{-- JADWAL HARI INI --}}
            <div class="col-md-7 mb-4">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-bold mb-0 text-dark">Jadwal Praktek Hari Ini</h6>
                        <small class="text-muted">{{ \Carbon\Carbon::now()->translatedFormat('d M') }}</small>
                    </div>
                    <div class="card-body px-0 pt-2 pb-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <tbody>
                                    @forelse($todaySchedules as $schedule)
                                    <tr>
                                        <td class="pl-4 align-middle border-0">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user-md text-primary"></i>
                                                </div>
                                                <div>
                                                    <span class="d-block font-weight-bold text-dark small">{{ $schedule->doctor->name }}</span>
                                                    <small class="text-muted" style="font-size: 0.7rem;">{{ $schedule->doctor->specialist ?? 'Umum' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle border-0 text-right pr-4">
                                            <span class="badge badge-light border px-2 py-1 small">
                                                {{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td class="text-center py-4 small text-muted">Tidak ada jadwal hari ini.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KUNJUNGAN TERAKHIR --}}
            <div class="col-md-5 mb-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h6 class="font-weight-bold mb-0 text-dark">Terakhir Periksa</h6>
                    </div>
                    <div class="card-body p-4 text-center">
                        @if(isset($latestMedicalRecord))
                        <div class="mb-3">
                            <i class="fas fa-notes-medical text-primary fa-2x"></i>
                        </div>
                        <h6 class="font-weight-bold mb-1">{{ $latestMedicalRecord->diagnosis }}</h6>
                        <p class="text-muted small mb-3">{{ \Carbon\Carbon::parse($latestMedicalRecord->date)->translatedFormat('d F Y') }}</p>
                        <a href="{{ url('/pasien/medical-records/'.$latestMedicalRecord->id) }}" class="btn btn-outline-primary btn-sm btn-block font-weight-bold" style="border-radius: 10px;">Lihat Hasil</a>
                        @else
                        <div class="py-3">
                            <i class="fas fa-folder-open text-muted mb-2" style="opacity: 0.3;"></i>
                            <p class="small text-muted mb-0">Belum ada riwayat medis.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        {{-- INFO KHUSUS (OPTIONAL FOOTER) --}}
        <div class="text-center p-3">
            <p class="text-muted" style="font-size: 0.7rem;">* Pastikan datang 15 menit sebelum jam praktek dimulai.</p>
        </div>
    </div>
</div>
@endsection