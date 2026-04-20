@extends('layouts.applte')

@section('content')
<div class="content-wrapper">
    {{-- Header Halaman Tetap Mengikuti Standar --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Data Dokter</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            {{-- Card dengan Header Biru (Primary) --}}
            <div class="card shadow-sm">
                <div class="card-header bg-primary">
                    <h3 class="card-title">
                        <i class="fas fa-user-md mr-2"></i> Tambah Dokter
                    </h3>
                </div>

                <form action="{{ route('admin.doctors.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            {{-- Baris 1: Nama & No HP --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Nama Dokter</label>
                                    <input type="text" name="name" class="form-control" placeholder="Nama lengkap" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">No. Telepon</label>
                                    <input type="text" name="phone_number" class="form-control" placeholder="08xxxxxxxxxx">
                                </div>
                            </div>

                            {{-- Baris 2: Spesialis & Tanggal Lahir --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Spesialis</label>
                                    <input type="text" name="specialist" class="form-control" placeholder="Contoh: Spesialis Anak" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Tanggal Lahir</label>
                                    <input type="date" name="date_of_birth" class="form-control">
                                </div>
                            </div>

                            {{-- Baris 3: Gender & Status --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Jenis Kelamin</label>
                                    <select name="gender" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="aktif">Aktif</option>
                                        <option value="non-aktif">Non Aktif</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Baris 4: Alamat (Full Width) --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">Alamat</label>
                                    <textarea name="address" class="form-control" rows="3" placeholder="Alamat lengkap dokter"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer: Tombol Rata Kiri --}}
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-start" style="gap: 10px;">
                            <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection