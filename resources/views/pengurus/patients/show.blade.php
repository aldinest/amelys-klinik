@extends('layouts.app_pengurus')

@section('content')
<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid px-4">

            <div class="row">
                <div class="col-12">

                    {{-- ================= DETAIL PASIEN ================= --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary">
                            <h3 class="card-title text-white">
                                <i class="fas fa-user mr-2"></i> Detail Pasien
                            </h3>
                        </div>

                        <div class="card-body p-0">
                            <table class="table table-bordered mb-0">
                                <tr>
                                    <th class="bg-light" width="30%">Nama</th>
                                    <td><strong>{{ $patient->name }}</strong></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Jenis Kelamin</th>
                                    <td>{{ $patient->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Tanggal Lahir</th>
                                    <td>
                                        {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d M Y') }}
                                        <span class="text-muted">
                                            ({{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} tahun)
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">No. Telepon</th>
                                    <td>{{ $patient->phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Alamat</th>
                                    <td>{{ $patient->address ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                       <div class="card-footer">
                            <div class="d-flex">
                                <a href="{{ route('pengurus.patients.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- ================= REKAM MEDIS ================= --}}
                    <!-- <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info">
                            <h3 class="card-title text-white">
                                <i class="fas fa-notes-medical mr-2"></i> Rekam Medis
                            </h3>
                        </div>

                        <div class="card-body">
                            @if($patient->medical_records)
                                <p class="mb-0" style="white-space: pre-line;">
                                    {{ $patient->medical_records }}
                                </p>
                            @else
                                <span class="text-muted">Belum ada catatan rekam medis</span>
                            @endif
                        </div>
                    </div> -->

                </div>
            </div>

        </div>
    </section>
</div>
@endsection
