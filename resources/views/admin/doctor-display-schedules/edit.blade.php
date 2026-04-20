@extends('layouts.applte')

@section('content')
<div class="content-wrapper">

    {{-- HEADER --}}
    <section class="content-header pb-2">
        <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap">
            <h1 class="fw-bold mb-0">Edit Jadwal Dokter</h1>

            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.doctor-display-schedules.index') }}">Jadwal Dokter</a>
                </li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">

            <div class="card shadow-sm">
                <div class="card-header bg-primary">
                    <strong class="text-white">Form Edit Jadwal Dokter (Display)</strong>
                </div>

                <form method="POST"
                      action="{{ route('admin.doctor-display-schedules.update', $schedule->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        {{-- Nama Dokter --}}
                        <div class="form-group">
                            <label>Nama Dokter</label>
                            <input type="text"
                                   name="doctor_name"
                                   class="form-control @error('doctor_name') is-invalid @enderror"
                                   value="{{ old('doctor_name', $schedule->doctor_name) }}"
                                   required>
                            @error('doctor_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Spesialis --}}
                        <div class="form-group">
                            <label>Spesialis</label>
                            <input type="text"
                                   name="specialty"
                                   class="form-control @error('specialty') is-invalid @enderror"
                                   value="{{ old('specialty', $schedule->specialty) }}">
                            @error('specialty')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">

                            {{-- Hari --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Hari Praktik</label>
                                    <select name="day"
                                            class="form-control @error('day') is-invalid @enderror"
                                            required>
                                        @foreach ([
                                            'monday' => 'Senin',
                                            'tuesday' => 'Selasa',
                                            'wednesday' => 'Rabu',
                                            'thursday' => 'Kamis',
                                            'friday' => 'Jumat',
                                            'saturday' => 'Sabtu',
                                            'sunday' => 'Minggu'
                                        ] as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('day', $schedule->day) == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('day')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Jam Mulai --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jam Mulai</label>
                                    <input type="time"
                                           name="start_time"
                                           class="form-control @error('start_time') is-invalid @enderror"
                                           value="{{ old('start_time', $schedule->start_time) }}"
                                           required>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Jam Selesai --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jam Selesai</label>
                                    <input type="time"
                                           name="end_time"
                                           class="form-control @error('end_time') is-invalid @enderror"
                                           value="{{ old('end_time', $schedule->end_time) }}"
                                           required>
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        {{-- Status --}}
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="is_active"
                                       name="is_active"
                                       {{ old('is_active', $schedule->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">
                                    Aktifkan Jadwal
                                </label>
                            </div>
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="card-footer bg-white px-3">
                        <div class="d-flex justify-content-start"> 
                            <a href="{{ route('admin.doctor-display-schedules.index') }}"
                               class="btn btn-secondary mr-2">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </section>
</div>
@endsection