@extends('layouts.applte')

@section('content')
<div class="content-wrapper">

    {{-- HEADER --}}
    <section class="content-header pb-2">
        <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap">
            <h1 class="fw-bold mb-0">Edit Info Terbaru</h1>

            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}">Info Terbaru</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm border-warning">
                        <div class="card-header bg-warning">
                            <h3 class="card-title text-white">Ubah Informasi: <strong>{{ $news->title }}</strong></h3>
                        </div>

                        {{-- Perhatikan Route Update dan Method PUT --}}
                        <form action="{{ route('admin.news.update', $news->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="card-body">
                                
                                {{-- JUDUL --}}
                                <div class="form-group">
                                    <label for="title">Judul Informasi <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" 
                                        class="form-control @error('title') is-invalid @enderror" 
                                        value="{{ old('title', $news->title) }}" required>
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
                                                value="{{ old('date', $news->date) }}" required>
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
                                                value="{{ old('author_name', $news->author_name) }}" required>
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
                                        value="{{ old('author_role', $news->author_role) }}" required>
                                    @error('author_role')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- DESKRIPSI --}}
                                <div class="form-group">
                                    <label for="description">Isi Informasi / Deskripsi <span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" rows="6" 
                                        class="form-control @error('description') is-invalid @enderror" 
                                        required>{{ old('description', $news->description) }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                            <div class="card-footer bg-white d-flex justify-content-end">
                                <a href="{{ route('admin.news.index') }}" class="btn btn-default mr-2">Batal</a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-warning"><i class="fas fa-edit"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Mode Edit</span>
                            <span class="info-box-number">Pembaruan Data</span>
                        </div>
                    </div>
                    <p class="text-muted px-2">
                        <small>Pastikan informasi yang diubah sudah benar sebelum menekan tombol simpan, karena perubahan akan langsung terlihat di halaman depan klinik.</small>
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection