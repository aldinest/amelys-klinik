@extends('layouts.applte')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold text-dark">Edit Data Pasien</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            
            {{-- Alert Success/Error --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible shadow-sm border-0">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                    {{ session('success') }}
                </div>
            @endif

            {{-- CARD 1: FORM DATA PASIEN --}}
            <div class="card card-outline card-warning shadow-sm">
                <div class="card-header bg-warning">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-user-edit mr-2"></i> Informasi Profil Pasien
                    </h3>
                </div>

                <form action="{{ route('admin.patients.update', $patient->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="row">
                            {{-- Nama --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted text-uppercase small font-weight-bold">Nama Pasien</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $patient->name) }}"
                                        placeholder="Nama lengkap" required>
                                </div>
                            </div>

                            {{-- No Telepon --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted text-uppercase small font-weight-bold">No. Telepon</label>
                                    <input type="text" name="phone" class="form-control"
                                        value="{{ old('phone', $patient->phone) }}"
                                        placeholder="08xxxxxxxxxx" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Jenis Kelamin --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted text-uppercase small font-weight-bold">Jenis Kelamin</label>
                                    <select name="gender" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="L" {{ old('gender', $patient->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('gender', $patient->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Tanggal Lahir --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted text-uppercase small font-weight-bold">Tanggal Lahir</label>
                                    <input type="date" name="date_of_birth" class="form-control"
                                        value="{{ old('date_of_birth', $patient->date_of_birth) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Alamat --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="text-muted text-uppercase small font-weight-bold">Alamat</label>
                                    <textarea name="address" class="form-control" rows="3"
                                        placeholder="Alamat lengkap">{{ old('address', $patient->address) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light"> 
                        <div class="d-flex justify-content-start" style="gap: 10px;"> 
                            <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary"> 
                                <i class="fas fa-arrow-left mr-1"> </i> Kembali </a> 
                                <button type="submit" class="btn btn-warning px-4"> 
                                    <i class="fas fa-save mr-1"></i> Update 
                                </button> 
                            </div> 
                        </div>
                </form>
            </div>

        {{-- CARD 2: AKUN LOGIN PASIEN (Desain Rapi & Sejajar) --}}
        <div class="card card-outline card-info shadow-sm mt-4">
            <div class="card-header bg-info">
                <h3 class="card-title font-weight-bold text-white">
                    <i class="fas fa-user-lock mr-2"></i> Akun Login Pasien
                </h3>
            </div>

            <div class="card-body">
                @if(!$patient->user_id)
                    <div class="callout callout-warning border-left shadow-sm py-2 mb-4">
                        <p class="small mb-0 text-muted">
                            <i class="fas fa-info-circle mr-1 text-warning"></i> 
                            Pasien ini belum memiliki akun aplikasi. Masukkan email untuk aktivasi akun.
                        </p>
                    </div>

                    <form action="{{ route('admin.patients.create-account', $patient->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <label class="small font-weight-bold text-muted text-uppercase">Email Akun Pasien</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0">
                                            <i class="fas fa-envelope text-info"></i>
                                        </span>
                                    </div>
                                    <input type="email" name="email" class="form-control border-left-0" placeholder="pasien@email.com" style="height: 46px;" required>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-success font-weight-bold px-4 shadow-sm" style="height: 46px;">
                                            <i class="fas fa-user-plus mr-2"></i> Buat Akun
                                        </button>
                                    </div>
                                </div>
                                <p class="small text-muted italic mt-2 ml-1">
                                    <i class="fas fa-key mr-1"></i> Password default: <strong class="text-danger">nama depan + tgl lahir</strong> 
                                    <span class="text-secondary">(Contoh: budi12051995)</span>
                                </p>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="py-2">
                        <div class="d-inline-flex align-items-center p-3 border rounded bg-light shadow-none">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3 shadow-sm" style="width: 45px; height: 45px;">
                                <i class="fas fa-check fa-lg"></i>
                            </div>
                            <div class="text-left">
                                <h6 class="font-weight-bold mb-0 text-success">Akun Pasien Aktif</h6>
                                {{-- Menampilkan email yang terdaftar --}}
                                <div class="text-dark font-weight-bold mt-1">{{ $patient->user->email ?? '-' }}</div>
                                <small class="text-muted">Pasien sudah bisa menggunakan email ini untuk masuk ke aplikasi Amelys Klinik.</small>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        </div>
    </section>
</div>

<style>
    /* Styling Group Input agar menyatu presisi */
    .input-group-text {
        border-top-left-radius: 4px !important;
        border-bottom-left-radius: 4px !important;
    }
    .input-group .form-control {
        border-radius: 0;
    }
    .input-group .btn {
        border-top-right-radius: 4px !important;
        border-bottom-right-radius: 4px !important;
    }
    .italic { font-style: italic; }
</style>
@endsection