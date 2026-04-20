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
            <h1 class="fw-bold mb-0">Jadwal Dokter (Display)</h1>

            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Jadwal Dokter</li>
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

                        <strong class="text-muted">
                            Daftar Jadwal Dokter
                        </strong>

                        <a href="{{ route('admin.doctor-display-schedules.create') }}"
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Jadwal
                        </a>

                    </div>
                </div>

                {{-- CARD BODY --}}
                <div class="card-body py-3">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="50" class="text-center">No</th>
                                    <th>Dokter</th>
                                    <th>Spesialis</th>
                                    <th>Hari</th>
                                    <th>Jam Praktik</th>
                                    <th class="text-center">Status</th>
                                    <th width="150" class="text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($schedules as $schedule)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>
                                            <strong>{{ $schedule->doctor_name }}</strong>
                                        </td>

                                        <td>
                                            {{ $schedule->specialty ?? '-' }}
                                        </td>

                                        <td>
                                            {{-- Mapping EN -> ID --}}
                                            @php
                                                $days = [
                                                    'monday' => 'Senin',
                                                    'tuesday' => 'Selasa',
                                                    'wednesday' => 'Rabu',
                                                    'thursday' => 'Kamis',
                                                    'friday' => 'Jumat',
                                                    'saturday' => 'Sabtu',
                                                    'sunday' => 'Minggu',
                                                ];
                                            @endphp
                                            {{ $days[$schedule->day] ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                        </td>

                                        <td class="text-center">
                                            @if ($schedule->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <div class="d-inline-flex gap-1">

                                                {{-- EDIT --}}
                                                <a href="{{ route('admin.doctor-display-schedules.edit', $schedule->id) }}"
                                                   class="btn btn-outline-warning btn-sm"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                {{-- TOGGLE --}}
                                                <form method="POST"
                                                      action="{{ route('admin.doctor-display-schedules.toggle', $schedule->id) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="btn btn-outline-secondary btn-sm"
                                                            title="Aktif / Nonaktif">
                                                        <i class="fas fa-power-off"></i>
                                                    </button>
                                                </form>

                                                {{-- DELETE --}}
                                                <form method="POST"
                                                      action="{{ route('admin.doctor-display-schedules.destroy', $schedule->id) }}"
                                                      onsubmit="return confirm('Yakin hapus jadwal ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-outline-danger btn-sm"
                                                            title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7"
                                            class="text-center text-muted py-4">
                                            <strong>Belum ada jadwal dokter</strong>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </section>
</div>
@endsection