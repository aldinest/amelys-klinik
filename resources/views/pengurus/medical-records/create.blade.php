@extends('layouts.app_pengurus')

@section('title', 'Rekam Medis Pasien')

@section('content')
<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid px-3">
            <div class="row">
                
                {{-- KOLOM KIRI: FORM INPUT (Prioritas Utama) --}}
                <div class="col-lg-7 mb-3">
                    <div class="card card-primary card-outline shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-edit text-primary me-1"></i> 
                                Input Rekam Medis Baru
                            </h3>
                        </div>

                        <form method="POST" action="{{ route('pengurus.medical-records.store') }}">
                            @csrf
                            <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">

                            <div class="card-body">
                                {{-- HEADER INFO PASIEN --}}
                                <div class="row mb-4 bg-light p-3 rounded border mx-0">
                                    <div class="col-sm-8 mb-2 mb-sm-0">
                                        <label class="text-muted mb-0 d-block small uppercase">Nama Pasien</label>
                                        <h5 class="font-weight-bold mb-0 text-dark">{{ $reservation->patient->name }}</h5>
                                    </div>
                                    <div class="col-sm-4 text-sm-right">
                                        <label class="text-muted mb-0 d-block small">Nomor Rekam Medis</label>
                                        <span class="badge badge-primary px-3 py-2">
                                            {{ $reservation->patient->medical_record_number ?? 'PASIEN BARU' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Keluhan Utama</label>
                                    <textarea name="complaint" class="form-control border-primary-soft" rows="3" 
                                              placeholder="Apa yang dikeluhkan pasien saat ini?" required></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Diagnosis Dokter</label>
                                    <textarea name="diagnosis" class="form-control border-primary-soft" rows="3" 
                                              placeholder="Tuliskan diagnosa medis..." required></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Tindakan / Terapi</label>
                                    <textarea name="treatment" class="form-control border-primary-soft" rows="3" 
                                              placeholder="Tindakan yang diberikan atau resep obat..." required></textarea>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="text-muted font-weight-bold">
                                        Catatan Internal <small>(Opsional)</small>
                                    </label>
                                    <textarea name="doctor_notes" class="form-control" rows="2" 
                                              placeholder="Catatan tambahan untuk dokter/perawat..."></textarea>
                                </div>
                            </div>

                            <div class="card-footer bg-light py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('pengurus.reservations.show', $reservation->doctor_schedule_id) }}" 
                                       class="btn btn-link text-muted font-weight-bold">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4 shadow">
                                        <i class="fas fa-check-circle mr-1"></i> Simpan Rekam Medis
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- KOLOM KANAN: HISTORY RM --}}
                <div class="col-lg-5">
                    <div class="card card-info card-outline shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold text-info">
                                <i class="fas fa-history me-1"></i>
                                Riwayat Kunjungan
                            </h3>
                        </div>
                        <div class="card-body p-0" style="max-height: 680px; overflow-y: auto; background-color: #fbfbfb;">
                            @forelse($reservation->patient->medicalRecords->sortByDesc('examined_at') as $history)
                                <div class="history-item p-3 border-bottom bg-white m-2 rounded shadow-sm border-left-info">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-info font-weight-bold small">
                                            <i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($history->examined_at)->format('d/m/Y') }}
                                        </span>
                                        <span class="badge badge-light border text-muted">
                                            <i class="fas fa-user-md"></i> dr. {{ $history->doctor->name ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="bg-light p-2 rounded mb-2">
                                        <small class="d-block text-uppercase font-weight-bold text-muted small-text">Diagnosis</small>
                                        <span class="text-dark">{{ $history->diagnosis }}</span>
                                    </div>
                                    <div class="px-1 mb-2">
                                        <small class="d-block text-uppercase font-weight-bold text-muted small-text">Tindakan</small>
                                        <span class="text-muted italic text-sm">{{ $history->treatment }}</span>
                                    </div>
                                    <div class="px-1 mt-1">
                                        <small class="d-block text-uppercase font-weight-bold text-info small-text">Catatan</small>
                                        <span class="text-dark text-sm">{{ $history->doctor_notes ?? '-' }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="py-5 text-center bg-white">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="opacity-50 mb-3" style="filter: grayscale(1);">
                                    <p class="text-muted px-4">Belum ada riwayat medis terdahulu untuk pasien ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<style>
    .border-left-info { border-left: 4px solid #17a2b8 !important; }
    .border-primary-soft { border: 1px solid #dee2e6; transition: 0.3s; }
    .border-primary-soft:focus { border-color: #007bff; box-shadow: 0 0 0 0.2rem rgba(0,123,255,.1); }
    .small-text { font-size: 10px; letter-spacing: 0.5px; }
    .italic { font-style: italic; }
    .history-item { transition: transform 0.2s; }
    .history-item:hover { transform: translateX(5px); }
    
    @media (max-width: 768px) {
        .btn-block-mobile { width: 100%; margin-bottom: 10px; }
    }
</style>
@endsection