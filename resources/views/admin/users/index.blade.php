@extends('layouts.applte')

@section('content')
<div class="content-wrapper">

    {{-- ALERT MESSAGES --}}
    <div class="mx-3 pt-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                {{ session('success') }}
                <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <h5><i class="icon fas fa-ban"></i> Error!</h5>
                {{ session('error') }}
                <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">&times;</button>
            </div>
        @endif
    </div>

    {{-- PAGE HEADER --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Data User</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Data User</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary shadow-sm">
                            <i class="fas fa-plus-circle mr-1"></i> Tambah User
                        </a>

                        <div class="flex-grow-1 flex-md-grow-0">
                            <form action="{{ route('admin.users.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Cari nama atau email..." value="{{ request('search') }}">
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
                        <table class="table table-bordered table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 50px" class="text-center">No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th class="text-center">Role</th>
                                    <th style="width: 200px" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $i => $user)
                                    <tr>
                                        <td class="text-center align-middle">{{ $users->firstItem() + $i }}</td>
                                        <td class="align-middle font-weight-bold">{{ $user->name }}</td>
                                        <td class="align-middle">{{ $user->email }}</td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'pengurus' ? 'primary' : 'secondary') }} px-3 py-2">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center" style="gap: 5px;">
                                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm text-white"><i class="fas fa-edit"></i> Edit</a>
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-danger btn-sm" type="submit"><i class="fas fa-trash"></i> Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-5 text-muted">Data user tidak ditemukan</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- CARD VIEW (Mobile) --}}
                <div class="card-body p-3 d-md-none">
                    @forelse ($users as $user)
                        <div class="card mb-3 border shadow-none">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="font-weight-bold mb-0">{{ $user->name }}</h6>
                                    <span class="badge badge-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'pengurus' ? 'primary' : 'secondary') }} px-2 py-1">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </div>
                                <p class="text-muted small mb-2"><i class="fas fa-envelope mr-1"></i> {{ $user->email }}</p>
                                <div class="d-flex" style="gap: 5px;">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-warning btn-sm flex-fill">Edit</a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="flex-fill" onsubmit="return confirm('Yakin?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm w-100" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted">Data user tidak ditemukan</div>
                    @endforelse
                </div>

                {{-- FOOTER --}}
                <div class="card-footer bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col-sm-12 col-md-6 text-center text-md-left mb-3 mb-md-0">
                            <p class="mb-0 small text-muted">Menampilkan <strong>{{ $users->firstItem() ?? 0 }}</strong> - <strong>{{ $users->lastItem() ?? 0 }}</strong> dari <strong>{{ $users->total() ?? 0 }}</strong> data</p>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="pagination-responsive-wrapper">{{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .pagination-responsive-wrapper { display: flex; justify-content: center; }
    @media (min-width: 768px) { .pagination-responsive-wrapper { justify-content: flex-end; } }
    @media (max-width: 767.98px) {
        .pagination-responsive-wrapper { overflow-x: auto; display: block; white-space: nowrap; padding: 5px 0; }
        .pagination-responsive-wrapper .pagination { display: inline-flex; margin-bottom: 0; }
    }
    @media (max-width: 576px) {
        .card-header .btn { width: 100%; margin-bottom: 5px; }
        .input-group { width: 100% !important; }
    }
</style>
@endsection