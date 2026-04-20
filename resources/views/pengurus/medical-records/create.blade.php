@extends('layouts.app_pengurus')

@section('title', 'Rekam Medis Pasien')

@section('content')
<div class="content-wrapper">
    <section class="content pt-2">
        <div class="container-fluid px-3">

            <div class="card card-primary shadow-sm">
                <div class="card-header py-2">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-notes-medical me-1"></i>
                        Form Rekam Medis
                    </h3>
                </div>

                <form method="POST"
                      action="{{ route('pengurus.medical-records.store') }}">
                    @csrf

                    <input type="hidden"
                           name="reservation_id"
                           value="{{ $reservation->id }}">

                    <div class="card-body py-3">

                        {{-- INFO PASIEN --}}
                        <div class="mb-2">
                            <small class="text-muted">Pasien</small><br>
                            <strong>{{ $reservation->patient->name }}</strong>
                        </div>

                        <div class="mb-2">
                            <label class="form-label mb-1">Keluhan</label>
                            <textarea name="complaint"
                                      class="form-control form-control-sm"
                                      rows="2"
                                      required></textarea>
                        </div>

                        <div class="mb-2">
                            <label class="form-label mb-1">Diagnosis</label>
                            <textarea name="diagnosis"
                                      class="form-control form-control-sm"
                                      rows="2"
                                      required></textarea>
                        </div>

                        <div class="mb-2">
                            <label class="form-label mb-1">Tindakan / Terapi</label>
                            <textarea name="treatment"
                                      class="form-control form-control-sm"
                                      rows="2"
                                      required></textarea>
                        </div>

                        <div class="mb-2">
                            <label class="form-label mb-1">
                                Catatan Tambahan <span class="text-muted">(opsional)</span>
                            </label>
                            <textarea name="doctor_notes"
                                      class="form-control form-control-sm"
                                      rows="2"></textarea>
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="card-footer py-2">
                        <div class="d-flex gap-2">
                            <a href="{{ route('pengurus.reservations.show',
                                $reservation->doctor_schedule_id) }}"
                               class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Kembali
                            </a>

                            <button class="btn btn-sm btn-primary">
                                <i class="fas fa-save"></i>
                                Simpan
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </section>
</div>
@endsection
