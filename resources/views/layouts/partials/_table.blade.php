<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle mb-0">
        <thead class="table-light text-nowrap">
            <tr>
                <th width="50" class="text-center">No</th>
                <th>Nama Pasien</th>
                <th class="d-none d-md-table-cell">No RM</th>
                <th>Tindakan</th>
                <th width="150" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $reservation)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-nowrap">
                        <div class="font-weight-bold">{{ $reservation->patient->name }}</div>
                        <small class="d-md-none text-muted">RM: {{ $reservation->patient->medical_record_number ?? '-' }}</small>
                    </td>
                    <td class="d-none d-md-table-cell">{{ $reservation->patient->medical_record_number ?? '-' }}</td>
                    <td><span class="text-muted small">{{ $reservation->action ?? '-' }}</span></td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1 flex-nowrap px-2">
                            <a href="{{ route('pengurus.patients.show', $reservation->patient->id) }}" class="btn btn-info btn-sm shadow-sm"><i class="fas fa-eye"></i></a>
                            
                            <button type="button" class="btn btn-success btn-sm btn-kirim-wa shadow-sm" 
                                data-toggle="modal" data-target="#modalWA" 
                                data-nama="{{ $reservation->patient->name }}" 
                                data-phone="{{ $reservation->patient->phone }}"
                                data-tgl="{{ \Carbon\Carbon::parse($reservation->schedule->schedule_date)->translatedFormat('d-m-Y') }}"
                                data-jam="{{ substr($reservation->schedule->start_time, 0, 5) }}">
                                <i class="fab fa-whatsapp"></i>
                            </button>

                            @if ($statusType === 'approved')
                                <a href="{{ route('pengurus.medical-records.create', ['reservation_id' => $reservation->id]) }}" class="btn btn-sm btn-primary shadow-sm">
                                    <i class="fas fa-stethoscope"></i>
                                </a>
                                <form action="{{ route('pengurus.reservations.cancel', $reservation->id) }}" method="POST" onsubmit="return confirm('Batalkan?')" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger px-2 shadow-sm"><i class="fas fa-times"></i></button>
                                </form>
                            @elseif ($reservation->medicalRecord)
                                <a href="{{ route('pengurus.medical-records.show', $reservation->medicalRecord->id) }}" class="btn btn-sm btn-success shadow-sm">
                                    <i class="fas fa-notes-medical"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Tidak ada data untuk status ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>