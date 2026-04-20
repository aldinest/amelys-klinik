@extends('layouts.app_pengurus')

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
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="font-weight-bold">Jadwal Praktek Dokter Amelys</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('pengurus.dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Jadwal Praktek</li>
            </ol>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">

                {{-- CARD HEADER --}}
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        {{-- ACTION --}}
                        <a href="{{ route('pengurus.doctor_schedules.create') }}"
                           class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Jadwal
                        </a>

                        {{-- SEARCH --}}
                        <form method="GET" action="{{ route('pengurus.doctor_schedules.index') }}">
                            <div class="input-group">
                                <input type="search"
                                       name="search"
                                       value="{{ request('search') }}"
                                       class="form-control"
                                       placeholder="Search doctor...">
                                <div class="input-group-append">
                                    <button class="btn btn-secondary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

                {{-- CARD BODY --}}
                <div class="card-body">

                    {{-- TABLE --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width:60px">No</th>
                                    <th>Nama Dokter</th>
                                    <th>Spesialis</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Kuota Pasien</th>
                                    <th>Status</th>
                                    <th style="width:150px">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($schedules as $schedule)
                                    <tr>
                                        <td class="text-center">
                                            {{ $schedules->firstItem() + $loop->index }}
                                        </td>
                                        <td>{{ $schedule->doctor->name }}</td>
                                        <td>{{ $schedule->doctor->specialist }}</td>
                                        <td>{{ \Carbon\Carbon::parse($schedule->schedule_date)->translatedFormat('d F Y') }}</td>
                                        <td>
                                            {{ $schedule->start_time }} -
                                            {{ $schedule->end_time }}
                                        </td>
                                        <td class="text-center">
                                            {{ $schedule->quota }}
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $schedule->status == 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($schedule->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('pengurus.doctor_schedules.edit', $schedule->id) }}"
                                               class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('pengurus.doctor_schedules.destroy', $schedule->id) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Delete this schedule?')"
                                                        class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8"
                                            class="text-center text-muted py-4">
                                            <strong>Data tidak ditemukan 😪</strong>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION --}}
                    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                        <small class="text-muted">
                            Menampilkan
                            <strong>{{ $schedules->firstItem() }}</strong> -
                            <strong>{{ $schedules->lastItem() }}</strong>
                            dari <strong>{{ $schedules->total() }}</strong> data
                        </small>

                        <div>
                            {{ $schedules->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
@endsection
