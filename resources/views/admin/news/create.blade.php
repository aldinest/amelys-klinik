@extends('layouts.applte')

@section('content')
<div class="content-wrapper">

    {{-- HEADER --}}
    <section class="content-header pb-2">
        <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap">
            <h1 class="fw-bold mb-0">Tambah Info Terbaru</h1>

            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}">Info Terbaru</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8"> 
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary">
                            <h3 class="card-title text-white">Formulir Informasi Baru</h3>
                        </div>

                        <form action="{{ route('admin.news.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                
                                {{-- JUDUL --}}
                                <div class="form-group">
                                    <label for="title">Judul Informasi <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" 
                                        class="form-control @error('title') is-invalid @enderror" 
                                        placeholder="Contoh: Jadwal Vaksinasi Minggu Ini" value="{{ old('title') }}" required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="row">
                                    {{-- TANGGAL --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date">Tanggal Terbit <span class="text-danger">*</span></label>
                                            <input type="date" name="date" id="date" 
                                                class="form-control @error('date') is-invalid @enderror" 
                                                value="{{ old('date', date('Y-m-d')) }}" required>
                                            @error('date')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    {{-- NAMA PENULIS --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="author_name">Nama Penulis <span class="text-danger">*</span></label>
                                            <input type="text" name="author_name" id="author_name" 
                                                class="form-control @error('author_name') is-invalid @enderror" 
                                                placeholder="Contoh: Admin Amelys" value="{{ old('author_name') }}" required>
                                            @error('author_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- PERAN PENULIS --}}
                                <div class="form-group">
                                    <label for="author_role">Peran Penulis <span class="text-danger">*</span></label>
                                    <input type="text" name="author_role" id="author_role" 
                                        class="form-control @error('author_role') is-invalid @enderror" 
                                        placeholder="Contoh: Public Relations / Admin Klinik" value="{{ old('author_role') }}" required>
                                    @error('author_role')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- DESKRIPSI --}}
                                <div class="form-group">
                                    <label for="description">Isi Informasi / Deskripsi <span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" rows="6" 
                                        class="form-control @error('description') is-invalid @enderror" 
                                        placeholder="Tuliskan detail informasi di sini..." required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div> {{-- END CARD BODY --}}

                            <div class="card-footer bg-white d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.news.index') }}" class="btn btn-default mr-2">Batal</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane mr-1"></i> Terbitkan Info
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- TIPS CARD (OPSIONAL) --}}
                <div class="col-md-4">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h3 class="card-title">Petunjuk Pengisian</h3>
                        </div>
                        <div class="card-body">
                            <ul class="pl-3">
                                <li><strong>Judul:</strong> Gunakan kalimat yang menarik dan singkat.</li>
                                <li><strong>Tanggal:</strong> Secara otomatis terisi tanggal hari ini.</li>
                                <li><strong>Deskripsi:</strong> Informasi ini akan langsung muncul di halaman depan klinik.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection