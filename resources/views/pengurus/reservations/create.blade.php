@extends('layouts.app_pengurus')

@section('content')

@push('css')
<style>
    .select2-container { width: 100% !important; }
    .select2-selection--single { height: 38px !important; padding: 6px 12px; }
    .select2-selection__rendered { line-height: 24px !important; }
</style>
@endpush

<div class="content-wrapper">
    {{-- ALERT --}}
    <div class="mx-3 pt-3">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                <h5><i class="icon fas fa-ban"></i> Error!</h5>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close text-white" data-dismiss="alert">&times;</button>
            </div>
        @endif
    </div>

    {{-- HEADER --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Tambah Reservasi Pasien</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('pengurus.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pengurus.reservations.index') }}">Reservasi</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">
                {{-- Header Card Biru --}}
                <div class="card-header bg-primary">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-plus mr-2"></i> Form Reservasi
                    </h3>
                </div>

                <form action="{{ route('pengurus.reservations.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        {{-- Data Jadwal (Read Only) --}}
                        <div class="row bg-light p-3 mb-4 border rounded">
                            <input type="hidden" name="doctor_schedule_id" value="{{ $schedule->id }}">
                            
                            <div class="col-md-6 border-right">
                                <label class="text-muted small mb-1">Informasi Dokter</label>
                                <p class="mb-1"><strong>Dokter:</strong> {{ $schedule->doctor->name }}</p>
                                <p class="mb-0"><strong>Spesialis:</strong> {{ $schedule->doctor->specialist }}</p>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">Informasi Jadwal</label>
                                <p class="mb-1"><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($schedule->schedule_date)->translatedFormat('d F Y') }} ({{ $schedule->start_time }} - {{ $schedule->end_time }})</p>
                                <p class="mb-0"><strong>Sisa Kuota:</strong> 
                                    <span class="badge badge-info">{{ $schedule->quota - $schedule->reservations->where('status','approved')->count() }} Pasien</span>
                                </p>
                            </div>
                        </div>

                        {{-- Form Input --}}
                        <div class="row">
                            {{-- Pilih Pasien --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pasien</label>
                                    <select name="patient_id" class="form-control select2bs4" required>
                                        <option value="">-- Cari & Pilih Pasien --</option>
                                        @foreach ($patients as $patient)
                                            <option value="{{ $patient->id }}">
                                                {{ $patient->medical_record_number ?? '-' }} - {{ $patient->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Tindakan / Keluhan --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Tindakan / Keluhan</label>
                                    <input type="text" name="action" class="form-control" placeholder="Contoh: Konsultasi rutin, Cek darah" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer: Tombol Rata Kiri --}}
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-start" style="gap: 10px;">
                            <a href="{{ route('pengurus.reservations.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save mr-1"></i> Simpan Reservasi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

@push('js')
<script>
$(function () {
    $('.select2bs4').select2({
        theme: 'bootstrap4',
        placeholder: '-- Cari & Pilih Pasien --',
        allowClear: true
    });
});
</script>
@endpush

@endsection