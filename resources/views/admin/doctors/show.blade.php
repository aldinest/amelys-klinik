@extends('layouts.applte')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold text-dark">Detail Data Dokter</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    
                    {{-- CARD 1: INFORMASI PROFIL --}}
                    <div class="card card-outline card-primary shadow-sm mb-4">
                        <div class="card-header bg-primary">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-user-md mr-2"></i> Profil Lengkap Dokter
                            </h3>
                        </div>

                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <tbody>
                                    <tr>
                                        <th width="30%" class="bg-light text-muted small text-uppercase">Nama Lengkap</th>
                                        <td class="font-weight-bold text-dark">{{ $doctor->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-muted small text-uppercase">Spesialisasi</th>
                                        <td><span class="badge badge-info">{{ $doctor->specialist }}</span></td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-muted small text-uppercase">No. Telepon</th>
                                        <td>{{ $doctor->phone_number ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-muted small text-uppercase">Jenis Kelamin</th>
                                        <td>{{ $doctor->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-muted small text-uppercase">Tanggal Lahir</th>
                                        <td>
                                            {{ \Carbon\Carbon::parse($doctor->date_of_birth)->format('d M Y') }}
                                            <span class="text-muted small ml-1">({{ \Carbon\Carbon::parse($doctor->date_of_birth)->age }} tahun)</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-muted small text-uppercase">Alamat</th>
                                        <td>{{ $doctor->address ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-muted small text-uppercase">Status Kerja</th>
                                        <td>
                                            @if($doctor->status === 'aktif')
                                                <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Aktif</span>
                                            @else
                                                <span class="badge badge-secondary">Non-Aktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer bg-white border-top">
                            <div class="d-flex justify-content-start">
                                <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary mr-2 px-3 shadow-sm">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                                </a>
                                <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-warning font-weight-bold px-4 shadow-sm">
                                    <i class="fas fa-edit mr-1"></i> Edit Data
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- CARD 2: INFORMASI AKUN (Sesuai SOP Single Identity) --}}
                    <div class="card card-outline card-info shadow-sm">
                        <div class="card-header bg-info">
                            <h3 class="card-title font-weight-bold text-white">
                                <i class="fas fa-user-lock mr-2"></i> Akses Akun Aplikasi
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($doctor->user_id)
                                <div class="d-flex align-items-center">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 font-weight-bold text-success">Akun Sudah Terhubung</p>
                                        <small class="text-muted">Dokter ini sudah memiliki akses ke sistem dashboard.</small>
                                    </div>
                                </div>
                            @else
                                <div class="callout callout-danger py-2 mb-0">
                                    <h6 class="font-weight-bold text-danger"><i class="fas fa-exclamation-triangle mr-2"></i> Akun Belum Dibuat</h6>
                                    <p class="small text-muted mb-0">Gunakan tombol <strong>Edit Data</strong> di atas untuk melakukan aktivasi email bagi dokter ini.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

<style>
    /* Styling agar tabel terlihat seperti di screenshot "Detail Pasien" */
    .table th {
        vertical-align: middle;
        border-right: 1px solid #dee2e6;
    }
    .table td {
        vertical-align: middle;
    }
</style>
@endsection