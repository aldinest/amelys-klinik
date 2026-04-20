@extends('layouts.app_pengurus')

@section('content')
<div class="content-wrapper">

    {{-- ALERT --}}
    <div class="mx-3 pt-3">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0">
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
                    {{-- Font weight bold hanya untuk judul utama halaman --}}
                    <h1 class="font-weight-bold">Tambah Jadwal Dokter</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('pengurus.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pengurus.doctor_schedules.index') }}">Jadwal</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            <div class="card shadow-sm border-0">
                {{-- Header Card: Menggunakan font standar (tidak bold semua) agar elegan --}}
                <div class="card-header bg-primary">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-plus mr-2"></i> Form Jadwal Praktik
                    </h3>
                </div>

                <form action="{{ route('pengurus.doctor_schedules.store') }}" method="POST">
                    @csrf
                    <div class="card-body text-dark">
                        <div class="row">
                            {{-- Pilih Dokter --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Nama Dokter</label>
                                    <select name="doctor_id" class="form-control select2bs4" required>
                                        <option value="">-- Pilih Dokter --</option>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
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
                                    <input type="date" name="schedule_date" value="{{ old('schedule_date') }}" class="form-control" required>
                                </div>
                            </div>

                            {{-- Jam Mulai --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Jam Mulai</label>
                                    <input type="time" name="start_time" value="{{ old('start_time') }}" class="form-control" required>
                                </div>
                            </div>

                            {{-- Jam Selesai --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Jam Selesai</label>
                                    <input type="time" name="end_time" value="{{ old('end_time') }}" class="form-control" required>
                                </div>
                            </div>

                            {{-- Kuota --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Kuota Pasien</label>
                                    <input type="number" name="quota" value="{{ old('quota') }}" class="form-control" min="1" placeholder="Misal: 20" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Card: Tombol Menggunakan Teks Putih Bersih --}}
                    <div class="card-footer bg-white border-top">
                        <div class="d-flex justify-content-start" style="gap: 10px;">
                            <a href="{{ route('pengurus.doctor_schedules.index') }}" class="btn btn-secondary text-white px-3 shadow-sm">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary text-white px-4 shadow-sm">
                                <i class="fas fa-save mr-1"></i> Simpan Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection