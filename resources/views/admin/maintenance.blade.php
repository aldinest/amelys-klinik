@extends('layouts.applte')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Maintenance Mode</h1>
                    <p class="text-muted">Kelola status perawatan aplikasi Amelys Klinik.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Maintenance</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">Status Maintenance</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-{{ $isDown ? 'danger' : 'success' }}">
                                <h5 class="mb-1">{{ $isDown ? 'Mode maintenance aktif' : 'Mode maintenance nonaktif' }}</h5>
                                <p class="mb-0">
                                    {{ $isDown
                                        ? 'Maintenance saat ini sedang aktif untuk target yang dipilih. Panel admin tetap dapat diakses melalui URL admin.'
                                        : 'Aplikasi berjalan normal. Semua pengunjung dapat mengakses halaman publik.'
                                    }}
                                </p>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <form method="POST" action="{{ route('admin.maintenance.enable') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label for="message">Pesan Maintenance</label>
                                            <textarea id="message" name="message" rows="4" class="form-control" placeholder="Contoh: Sistem sedang dalam pemeliharaan ringan. Silakan kembali dalam 10 menit.">{{ old('message', $message ?? '') }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="target">Target Maintenance</label>
                                            <select id="target" name="target" class="form-control">
                                                <option value="pasien" {{ (old('target', $target ?? 'pengurus_pasien') === 'pasien') ? 'selected' : '' }}>Pasien saja</option>
                                                <option value="pengurus_pasien" {{ (old('target', $target ?? 'pengurus_pasien') === 'pengurus_pasien') ? 'selected' : '' }}>Pengurus + Pasien</option>
                                            </select>
                                            <small class="form-text text-muted">
                                                Pilih apakah maintenance hanya berlaku untuk pasien, atau juga untuk pengurus.
                                                @if($isDown)
                                                    <br><strong>Catatan:</strong> Jika maintenance sedang aktif dan Anda ingin mengubah target dari pasien saja ke pengurus + pasien, atau sebaliknya, Anda harus menonaktifkan maintenance terlebih dahulu, lalu aktifkan lagi dengan target yang baru.
                                                @else
                                                    <br>Ketika maintenance belum aktif, Anda bisa langsung memilih target lalu klik tombol aktifkan.
                                                @endif
                                            </small>
                                        </div>

                                        <button type="submit" class="btn btn-danger btn-block" {{ $isDown ? 'disabled' : '' }}>
                                            Aktifkan Maintenance
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <form method="POST" action="{{ route('admin.maintenance.disable') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-block" {{ $isDown ? '' : 'disabled' }}>
                                            Nonaktifkan Maintenance
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="card card-outline card-secondary mt-3">
                                <div class="card-body">
                                    <p class="mb-0"><strong>Catatan:</strong> Fitur ini hanya menampilkan halaman perawatan di bagian publik sesuai target yang dipilih. Admin tetap dapat masuk. Jika ingin mengubah target dari pasien saja ke pengurus + pasien, atau sebaliknya, matikan maintenance dulu lalu aktifkan kembali dengan target baru.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
