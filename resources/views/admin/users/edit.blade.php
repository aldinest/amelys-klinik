@extends('layouts.applte')

@section('content')
<div class="content-wrapper">
<section class="content pt-3">
<div class="container-fluid">

<div class="card shadow-sm">
    <div class="card-header bg-warning">
        <h3 class="card-title">
            <i class="fas fa-user-edit mr-1"></i> Edit User
        </h3>
    </div>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control"
                               value="{{ $user->name }}" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ $user->email }}" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option value="admin" {{ $user->role=='admin'?'selected':'' }}>Admin</option>
                            <option value="pengurus" {{ $user->role=='pengurus'?'selected':'' }}>Pengurus</option>
                            <option value="pasien" {{ $user->role=='pasien'?'selected':'' }}>Pasien</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" name="password" class="form-control"
                               placeholder="Kosongkan jika tidak diubah">
                        <small class="text-muted">
                            Minimal 6 karakter
                        </small>
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                Kembali
            </a>
            <button class="btn btn-warning float-right">
                Update
            </button>
        </div>
    </form>
</div>

</div>
</section>
</div>
@endsection
