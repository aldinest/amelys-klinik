<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle mb-0">
        <thead class="table-light text-nowrap">
            <tr>
                <th width="50" class="text-center">No</th>
                <th>Nama Pasien</th>
                <th class="d-none d-md-table-cell">No RM</th>
                <th class="d-none d-md-table-cell">Dokter</th>
                <th class="d-none d-lg-table-cell">Keluhan</th>
                <th width="150" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $record)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-nowrap">
                        <div class="font-weight-bold">{{ $record->patient->name }}</div>
                        <small class="d-md-none text-muted">RM: {{ $record->patient->medical_record_number ?? '-' }}</small>
                    </td>
                    <td class="d-none d-md-table-cell">{{ $record->patient->medical_record_number ?? '-' }}</td>
                    <td class="d-none d-md-table-cell">dr. {{ $record->doctor->name ?? '-' }}</td>
                    <td class="d-none d-lg-table-cell"><span class="text-muted small">{{ Str::limit($record->complaint, 50, '...') }}</span></td>
                    <td class="text-center">
                        <a href="{{ route('pengurus.medical-records.show', $record->id) }}" class="btn btn-success btn-sm shadow-sm">
                            <i class="fas fa-notes-medical"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Belum ada walk-in di jadwal ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
