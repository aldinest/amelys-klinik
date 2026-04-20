@extends('layouts.app_pengurus')

@section('content')
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
                <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">&times;</button>
            </div>
        @endif
    </div>

    {{-- HEADER HALAMAN --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Edit Jadwal Dokter</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('pengurus.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pengurus.doctor_schedules.index') }}">Jadwal</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">
                {{-- Header Card Warna Orange (Warning) - Teks Normal --}}
                <div class="card-header bg-warning">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i> Form Ubah Jadwal Praktik
                    </h3>
                </div>

                <form action="{{ route('pengurus.doctor_schedules.update', $doctorSchedule->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        <div class="row">
                            {{-- Pilih Dokter --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Dokter</label>
                                    <select name="doctor_id" class="form-control" required>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}" {{ $doctorSchedule->doctor_id == $doctor->id ? 'selected' : '' }}>
                                                {{ $doctor->name }} - {{ $doctor->specialist }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Tanggal Praktik --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Tanggal Praktik</label>
                                    <input type="date" name="schedule_date" value="{{ old('schedule_date', $doctorSchedule->schedule_date) }}" class="form-control" required>
                                </div>
                            </div>

                            {{-- Jam Mulai --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Jam Mulai</label>
                                    <input type="time" name="start_time" value="{{ old('start_time', $doctorSchedule->start_time) }}" class="form-control" required>
                                </div>
                            </div>

                            {{-- Jam Selesai --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Jam Selesai</label>
                                    <input type="time" name="end_time" value="{{ old('end_time', $doctorSchedule->end_time) }}" class="form-control" required>
                                </div>
                            </div>

                            {{-- Kuota --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Kuota Pasien</label>
                                    <input type="number" name="quota" value="{{ old('quota', $doctorSchedule->quota) }}" class="form-control" min="1" required>
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">Status Keaktifan</label>
                                    <select name="status" class="form-control" required>
                                        <option value="active" {{ $doctorSchedule->status == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive" {{ $doctorSchedule->status == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer: Tombol Rata Kiri (Teks Normal) --}}
                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-start" style="gap: 10px;">
                            <a href="{{ route('pengurus.doctor_schedules.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-warning px-4">
                                <i class="fas fa-save mr-1"></i> Perbarui Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection