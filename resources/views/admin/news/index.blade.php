@extends('layouts.applte')

@section('content')
<div class="content-wrapper">

    {{-- ALERT --}}
    <div class="mx-3 mt-3">
        @foreach (['success' => 'success', 'error' => 'danger'] as $key => $type)
            @if (session($key))
                <div class="alert alert-{{ $type }} alert-dismissible fade show">
                    {{ session($key) }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif
        @endforeach
    </div>

    {{-- HEADER --}}
    <section class="content-header pb-2">
        <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap">
            <h1 class="fw-bold mb-0">Manajemen Info Terbaru</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Info Terbaru</li>
            </ol>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">
                
                {{-- CARD HEADER --}}
                <div class="card-header bg-white py-2">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <strong class="text-muted">Daftar Artikel & Berita</strong>
                        <a href="{{ route('admin.news.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Info
                        </a>
                    </div>
                </div>

                {{-- CARD BODY --}}
                <div class="card-body py-3">
                    
                    {{-- TABEL DESKTOP --}}
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="50" class="text-center">No</th>
                                    <th>Tanggal</th>
                                    <th>Judul</th>
                                    <th>Deskripsi Singkat</th>
                                    <th>Penulis</th>
                                    <th width="120" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($news as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td style="white-space: nowrap;"><i class="far fa-calendar-alt text-muted mr-1"></i> {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d M Y') }}</td>
                                        <td><strong>{{ $item->title }}</strong></td>
                                        <td><small class="text-muted">{{ Str::limit($item->description, 70) }}</small></td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span>{{ $item->author_name }}</span>
                                                <small class="badge badge-light border">{{ $item->author_role }}</small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-inline-flex gap-1">
                                                <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-outline-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                                <form method="POST" action="{{ route('admin.news.destroy', $item->id) }}" onsubmit="return confirm('Hapus info ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted py-4"><strong>Belum ada info terbaru yang diterbitkan.</strong></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- TAMPILAN MOBILE --}}
                    <div class="d-md-none">
                        @forelse ($news as $item)
                            <div class="card mb-3 border">
                                <div class="card-body p-3">
                                    <h6 class="font-weight-bold mb-1">{{ $item->title }}</h6>
                                    <p class="text-muted small mb-2"><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d M Y') }} | <strong>{{ $item->author_name }}</strong></p>
                                    <p class="mb-2">{{ Str::limit($item->description, 100) }}</p>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i> Edit</a>
                                        <form method="POST" action="{{ route('admin.news.destroy', $item->id) }}" onsubmit="return confirm('Hapus info ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i> Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-3">Belum ada info terbaru.</div>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
@endsection