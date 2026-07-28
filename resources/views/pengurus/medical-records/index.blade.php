@extends('layouts.app_pengurus')

@section('title', 'Rekam Medis')

@section('content')
<div class="content-wrapper">
    {{-- HEADER --}}
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="font-weight-bold">Rekam Medis Pasien</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('pengurus.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Rekam Medis</li>
            </ol>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            
            {{-- TOMBOL ACTION & TAMBAH DADAKAN --}}
            <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <a href="{{ route('pengurus.patients.export') }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#importModal">
                        <i class="fas fa-upload"></i> Import Excel
                    </button>
                </div>
                <div>
                    {{-- TOMBOL TAMBAH RM DADAKAN --}}
                    <button class="btn btn-danger font-weight-bold" data-toggle="modal" data-target="#addEmergencyRMModal">
                        <i class="fas fa-plus-circle"></i> Tambah RM Dadakan (Walk-in)
                    </button>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h3 class="card-title font-weight-bold text-muted">Daftar Rekam Medis</h3>
                        
                        <div class="card-tools">
                            <form method="GET" action="{{ route('pengurus.medical-records.index') }}">
                                <div class="input-group" style="width: 250px;">
                                    <input type="text" name="search" class="form-control" placeholder="Cari pasien..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-secondary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- TABLE BODY (Desktop) --}}
                <div class="card-body p-0 d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 60px" class="text-center">No</th>
                                    <th>Nama Pasien</th>
                                    <th>No. RM</th>
                                    <th>Dokter</th>
                                    <th style="width: 120px" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($medicalRecords as $record)
                                    <tr>
                                        <td class="text-center align-middle text-muted">{{ $medicalRecords->firstItem() + $loop->index }}</td>
                                        <td class="align-middle font-weight-bold">{{ $record->patient->name ?? '-' }}</td>
                                        <td class="align-middle text-center">
                                            <span class="badge badge-light border w-100 py-2">{{ $record->patient->medical_record_number ?? '-' }}</span>
                                        </td>
                                        <td class="align-middle text-muted"><i class="fas fa-user-md mr-1"></i> {{ $record->doctor->name ?? '-' }}</td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('pengurus.medical-records.show', $record->id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-4 text-muted font-weight-bold">Data tidak ditemukan</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- MOBILE VIEW (Cards) --}}
                <div class="card-body p-3 d-md-none" style="background-color: #f4f6f9;">
                    @forelse ($medicalRecords as $record)
                        <div class="card shadow-sm border-0 mb-3" style="border-radius: 8px;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="font-weight-bold mb-0">{{ $record->patient->name ?? '-' }}</h6>
                                        <small class="text-muted">{{ $record->patient->medical_record_number ?? '-' }}</small>
                                    </div>
                                    <a href="{{ route('pengurus.medical-records.show', $record->id) }}" class="btn btn-info btn-xs px-2">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </div>
                                <hr class="my-2">
                                <div class="small">
                                    <i class="fas fa-user-md text-info mr-1"></i> 
                                    <strong>Dokter:</strong> {{ $record->doctor->name ?? '-' }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">Data tidak ditemukan</div>
                    @endforelse
                </div>

                {{-- FOOTER --}}
                <div class="card-footer bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-center text-md-left mb-3 mb-md-0">
                            <small class="text-muted">
                                Menampilkan <strong>{{ $medicalRecords->firstItem() ?? 0 }}</strong> sampai <strong>{{ $medicalRecords->lastItem() ?? 0 }}</strong> dari <strong>{{ $medicalRecords->total() ?? 0 }}</strong> data
                            </small>
                        </div>
                        <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
                            {{ $medicalRecords->appends(request()->query())->links('pagination::simple-bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- MODAL IMPORT EXCEL --}}
<div class="modal fade" id="importModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('pengurus.patients.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Import Rekam Medis</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>File Excel</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Unggah</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH RM DADAKAN (WALK-IN) --}}
<div class="modal fade" id="addEmergencyRMModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('pengurus.medical-records.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-user-plus mr-1"></i> Tambah Rekam Medis Dadakan (Walk-in)</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Pilih Pasien dengan Select2 (Searchable) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Pilih Pasien <span class="text-danger">*</span></label>
                                <select name="patient_id" id="emergency-patient-select" class="form-control select2bs4" style="width: 100%;" required>
                                    <option value=""></option>
                                    @isset($patients)
                                        @foreach($patients as $patient)
                                            <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->medical_record_number ?? 'Belum ada No. RM' }})</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                        </div>
                        
                        <!-- Pilih Jadwal Dokter -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Pilih Jadwal Dokter <span class="text-danger">*</span></label>
                                <select name="doctor_schedule_id" id="emergency-doctor-schedule-select" class="form-control select2bs4" style="width: 100%;" required>
                                    <option value=""></option>
                                    @isset($doctorSchedules)
                                        @foreach($doctorSchedules as $schedule)
                                            <option value="{{ $schedule->id }}">
                                                {{ $schedule->doctor->name ?? '-' }} — ({{ \Carbon\Carbon::parse($schedule->schedule_date)->format('d M Y') }} | {{ $schedule->start_time }} - {{ $schedule->end_time }})
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Keluhan Pasien</label>
                        <textarea name="complaint" class="form-control" rows="2" placeholder="Masukkan keluhan pasien..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Diagnosa</label>
                        <textarea name="diagnosis" class="form-control" rows="2" placeholder="Masukkan diagnosa..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Tindakan</label>
                        <textarea name="treatment" class="form-control" rows="2" placeholder="Masukkan tindakan medis..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Catatan Dokter</label>
                        <textarea name="doctor_notes" class="form-control" rows="2" placeholder="Catatan tambahan (opsional)..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Simpan Rekam Medis</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script inisialisasi Select2 khusus di dalam Modal Bootstrap -->
@push('js')
<script>
    $(function () {
        $('#emergency-patient-select').select2({
            theme: 'bootstrap4',
            dropdownParent: $('#addEmergencyRMModal'),
            placeholder: '-- Pilih Pasien --',
            allowClear: true
        });

        $('#emergency-doctor-schedule-select').select2({
            theme: 'bootstrap4',
            dropdownParent: $('#addEmergencyRMModal'),
            placeholder: '-- Pilih Dokter & Tanggal Praktek --',
            allowClear: true
        });
    });
</script>
@endpush
@endsection