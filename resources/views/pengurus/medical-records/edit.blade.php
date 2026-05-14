@extends('layouts.app_pengurus')

@section('title', 'Edit Rekam Medis')

@section('content')
<div class="content-wrapper">
    <section class="content-header px-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold m-0">Edit Rekam Medis</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content px-3">
        <div class="container-fluid">
            <form action="{{ route('pengurus.medical-records.update', $medicalRecord->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- INFORMASI PASIEN (Read Only) --}}
                <div class="card shadow-sm mb-4" style="border-top: 3px solid #007bff; border-radius: 0;">
                    <div class="card-header bg-white py-3">
                        <h3 class="card-title font-weight-bold" style="font-size: 1.1rem; color: #333;">
                            <i class="fas fa-user-circle mr-2"></i> Informasi Pasien & Pemeriksa
                        </h3>
                    </div>
                    <div class="card-body py-2">
                        <div class="row">
                            {{-- Baris 1: Nama Pasien & Dokter --}}
                            <div class="col-md-6 border-right">
                                <div class="form-group mb-2">
                                    <label class="text-muted small mb-0">Nama Pasien</label>
                                    <p class="font-weight-bold mb-0 text-capitalize">{{ $medicalRecord->reservation->patient->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label class="text-muted small mb-0">Dokter Pemeriksa</label>
                                    {{-- Mengambil data nama dokter dari relasi --}}
                                    <p class="font-weight-bold mb-0 text-info">
                                        dr. {{ $medicalRecord->reservation->doctorSchedule->doctor->name }}
                                    </p>
                                </div>
                            </div>

                            {{-- Baris 2: No. RM & Tgl Lahir --}}
                            <div class="col-md-6 border-right mt-2">
                                <div class="form-group mb-0">
                                    <label class="text-muted small mb-0">No. Rekam Medis</label>
                                    <p class="font-weight-bold mb-0">{{ $medicalRecord->reservation->patient->medical_record_number }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="form-group mb-0">
                                    <label class="text-muted small mb-0">Tgl Lahir / Usia</label>
                                    {{-- Format tanpa jam 00:00:00 --}}
                                    <p class="font-weight-bold mb-0">
                                        {{ \Carbon\Carbon::parse($medicalRecord->reservation->patient->date_of_birth)->format('Y-m-d') }}
                                        <small class="text-muted">({{ \Carbon\Carbon::parse($medicalRecord->reservation->patient->date_of_birth)->age }} Thn)</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FORM INPUT REKAM MEDIS --}}
                <div class="card shadow-sm border-0" style="border-radius: 0;">
                    <div class="card-header py-3" style="background-color: #3ba2ac; color: white;">
                        <h3 class="card-title font-weight-bold">
                            <i class="fas fa-edit mr-2"></i> Form Hasil Pemeriksaan
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="font-weight-bold">Keluhan</label>
                                <textarea name="complaint" class="form-control @error('complaint') is-invalid @enderror" rows="3" required placeholder="Masukkan keluhan pasien...">{{ old('complaint', $medicalRecord->complaint) }}</textarea>
                                @error('complaint') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Diagnosa</label>
                                <input type="text" name="diagnosis" class="form-control @error('diagnosis') is-invalid @enderror" value="{{ old('diagnosis', $medicalRecord->diagnosis) }}" required placeholder="Contoh: Periodontitis">
                                @error('diagnosis') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Tindakan</label>
                                <input type="text" name="treatment" class="form-control @error('treatment') is-invalid @enderror" value="{{ old('treatment', $medicalRecord->treatment) }}" required placeholder="Contoh: Exo gigi 24">
                                @error('treatment') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="font-weight-bold">Catatan Dokter (Opsional)</label>
                                <textarea name="doctor_notes" class="form-control" rows="2" placeholder="Tambahkan catatan tambahan jika ada...">{{ old('doctor_notes', $medicalRecord->doctor_notes) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white py-3">
                        <a href="{{ route('pengurus.medical-records.show', $medicalRecord->id) }}" class="btn btn-default px-4 shadow-sm border">
                            <i class="fas fa-times mr-1"></i> Batal
                        </a>

                        <button type="submit" class="btn btn-primary px-4 ml-2 shadow-sm" style="background-color: #3ba2ac; border: none;">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection