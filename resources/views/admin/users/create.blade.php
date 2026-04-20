@extends('layouts.applte')

@section('content')
<div class="content-wrapper">
<section class="content pt-3">
<div class="container-fluid">

<div class="card">
    <div class="card-header bg-primary">
        <h3 class="card-title text-white">
            <i class="fas fa-user-plus"></i> Tambah User
        </h3>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin">Admin</option>
                    <option value="pengurus">Pengurus</option>
                    <option value="pasien">Pasien</option>
                </select>
            </div>

            <div class="alert alert-info">
                Password default: <b>password123</b>
            </div>
        </div>

                    {{-- Footer: Tombol Rata Kiri --}}
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-start" style="gap: 10px;">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
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
