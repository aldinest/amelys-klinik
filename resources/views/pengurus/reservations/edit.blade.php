@extends('layouts.app_pengurus')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="content-wrapper">

    {{-- HEADER --}}
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="font-weight-bold">Edit Status Reservasi</h1>

            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('pengurus.dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('pengurus.reservations.index') }}">Reservasi Pasien</a>
                </li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">

            <div class="card shadow-sm">

                <div class="card-header bg-white">
                    <strong>Form Edit Status Reservasi</strong>
                </div>

                <form action="{{ route('pengurus.reservations.update', $schedule->reservations->first()->id) }}"
                      method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="reservation_id" id="reservation_id">

                    <div class="card-body">

                        {{-- INFO DOKTER --}}
                        <div class="form-group">
                            <label>Dokter</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $schedule->doctor->name }}"
                                   disabled>
                        </div>

                        {{-- INFO JADWAL --}}
                        <div class="form-row">

                            <div class="form-group col-md-6">
                                <label>Tanggal</label>
                                <input type="text"
                                       class="form-control"
                                       value="{{ \Carbon\Carbon::parse($schedule->schedule_date)->translatedFormat('d F Y') }}"
                                       disabled>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Jam Praktik</label>
                                <input type="text"
                                       class="form-control"
                                       value="{{ $schedule->start_time }} - {{ $schedule->end_time }}"
                                       disabled>
                            </div>

                        </div>

                        {{-- PILIH PASIEN --}}
                        <div class="form-group">
                            <label>Pilih Pasien</label>

                            <select class="form-control"
                                    onchange="document.getElementById('reservation_id').value=this.value"
                                    required>

                                <option value="">-- Pilih Pasien di Jadwal Ini --</option>

                                @foreach($schedule->reservations as $reservation)
                                    <option value="{{ $reservation->id }}">
                                        {{ $reservation->patient->name }}
                                    </option>
                                @endforeach

                            </select>

                            <small class="text-muted">
                                Pilih pasien yang ingin diubah status reservasinya.
                            </small>
                        </div>

                        {{-- STATUS --}}
                        <div class="form-group">
                            <label>Status Reservasi</label>

                            <select name="status"
                                    class="form-control @error('status') is-invalid @enderror">

                                <option value="approved">Approved</option>
                                <option value="cancel">Cancel (Hapus Reservasi)</option>

                            </select>

                            @error('status')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <small class="text-muted">
                                ⚠️ Jika memilih <strong>Cancel</strong>, data reservasi akan langsung dihapus.
                            </small>
                        </div>

                    </div>

                    <div class="card-footer text-right">
                        <a href="{{ route('pengurus.reservations.index') }}"
                           class="btn btn-secondary">
                            Kembali
                        </a>

                        <button type="submit" class="btn btn-warning">
                            Update Status
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </section>

</div>
@endsection
