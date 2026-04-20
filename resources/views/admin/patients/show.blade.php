@extends('layouts.applte')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold text-dark">Detail Data Pasien</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    {{-- CARD 1: INFORMASI PROFIL PASIEN --}}
                    <div class="card card-outline card-primary shadow-sm mb-4">
                        <div class="card-header bg-primary">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-user mr-2"></i> Profil Lengkap Pasien
                            </h3>
                        </div>

                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <tbody>
                                    <tr>
                                        <th width="30%" class="bg-light text-muted small text-uppercase">Nama Lengkap</th>
                                        <td class="font-weight-bold text-dark">{{ $patient->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-muted small text-uppercase">Jenis Kelamin</th>
                                        <td>{{ $patient->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-muted small text-uppercase">Tanggal Lahir</th>
                                        <td>
                                            {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d M Y') }}
                                            <span class="text-muted small ml-1">({{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} tahun)</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-muted small text-uppercase">No. Telepon</th>
                                        <td>{{ $patient->phone ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-muted small text-uppercase">Alamat</th>
                                        <td>{{ $patient->address ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer bg-white border-top">
                            <div class="d-flex justify-content-start">
                                <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary mr-2 px-3 shadow-sm">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                                </a>
                                <a href="{{ route('admin.patients.edit', $patient->id) }}" class="btn btn-warning font-weight-bold px-4 shadow-sm text-dark">
                                    <i class="fas fa-edit mr-1"></i> Edit Data
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- CARD 2: STATUS AKUN LOGIN --}}
                    <div class="card card-outline card-info shadow-sm">
                        <div class="card-header bg-info">
                            <h3 class="card-title font-weight-bold text-white">
                                <i class="fas fa-user-lock mr-2"></i> Status Akun Aplikasi
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($patient->user_id)
                                <div class="d-flex align-items-center">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3 shadow-sm" style="width: 42px; height: 42px;">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 font-weight-bold text-success">Akun Pasien Aktif</h6>
                                        <small class="text-muted text-italic">Pasien ini sudah terdaftar dan dapat mengakses rekam medis via aplikasi.</small>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex align-items-center">
                                    <div class="bg-gray text-white rounded-circle d-flex align-items-center justify-content-center mr-3 shadow-sm" style="width: 42px; height: 42px;">
                                        <i class="fas fa-user-slash"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 font-weight-bold text-muted">Belum Memiliki Akun</h6>
                                        <small class="text-muted italic">Klik <strong>Edit Data</strong> di atas untuk mendaftarkan email pasien ini.</small>
                                    </div>
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
    /* Styling agar tabel konsisten dengan screenshot */
    .table th {
        vertical-align: middle;
        border-right: 1px solid #dee2e6;
        padding-left: 1.25rem !important;
    }
    .table td {
        vertical-align: middle;
        padding-left: 1.25rem !important;
    }
    .text-italic { font-style: italic; }
</style>
@endsection