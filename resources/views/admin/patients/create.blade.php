@extends('layouts.applte')

@section('content')
  <div class="content-wrapper">
      <section class="content pt-3">
        <div class="container-fluid">

<section class="content pt-3">
    <div class="container-fluid px-4">

        <div class="row">
            <div class="col-12">

                <div class="card shadow-sm">
                    <div class="card-header bg-primary">
                        <h3 class="card-title text-white">
                            <i class="fas fa-user-injured mr-2"></i> Tambah Pasien
                        </h3>
                    </div>

                    <form action="{{ route('admin.patients.store') }}" method="POST">
                        @csrf

                        <div class="card-body">
                            <div class="row">

                                {{-- Nama --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Pasien</label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Nama lengkap" required>
                                    </div>
                                </div>

                                {{-- No Telepon --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>No. Telepon</label>
                                        <input type="text" name="phone" class="form-control"
                                            placeholder="08xxxxxxxxxx"  inputmode="numeric" pattern="[0-9]*" required>
                                    </div>
                                </div>

                                {{-- Jenis Kelamin --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jenis Kelamin</label>
                                        <select name="gender" class="form-control" required>
                                            <option value="">-- Pilih --</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Tanggal Lahir --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tanggal Lahir</label>
                                        <input type="date" name="date_of_birth" class="form-control" required>
                                    </div>
                                </div>

                                {{-- Alamat --}}
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <textarea name="address" class="form-control" rows="2"
                                            placeholder="Alamat lengkap pasien"></textarea>
                                    </div>
                                </div>

                                {{-- user_id hidden --}}
                                <input type="hidden" name="user_id" value="">
                            </div>
                        </div>

                    {{-- Footer: Tombol Rata Kiri --}}
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-start" style="gap: 10px;">
                            <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary">
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
        </div>

    </div>
</section>

   </div>
  </section>
</div>
@endsection
