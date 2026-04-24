@extends('layouts.app_pengurus')

@section('content')
<div class="content-wrapper">

    {{-- ALERT MESSAGES --}}
    <div class="mx-3 pt-3">
        @foreach (['success' => 'success', 'error' => 'danger'] as $key => $type)
            @if (session($key))
                <div class="alert alert-{{ $type }} alert-dismissible fade show shadow-sm">
                    <h5><i class="icon fas fa-{{ $type == 'success' ? 'check' : 'ban' }}"></i> 
                        {{ $type == 'success' ? 'Berhasil!' : 'Error!' }}
                    </h5>
                    {{ session($key) }}
                    <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">&times;</button>
                </div>
            @endif
        @endforeach
    </div>

    {{-- PAGE HEADER --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Data Pasien</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('pengurus.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Pasien</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">
                
                {{-- CARD HEADER DENGAN SEARCH --}}
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
                        <h6 class="mb-0 font-weight-bold text-muted"><i class="fas fa-users mr-1"></i> Daftar Pasien Terdaftar</h6>
                        
                        <div class="flex-grow-1 flex-md-grow-0">
                            <form method="GET" action="{{ route('pengurus.patients.index') }}">
                                <div class="input-group" style="min-width: 250px;">
                                    <input type="search" name="search" value="{{ request('search') }}"
                                           class="form-control" placeholder="Cari nama pasien...">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- TABLE BODY --}}
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 60px" class="text-center">No</th>
                                    <th>Nama Pasien</th>
                                    <th>Alamat</th>
                                    <th class="text-center">Gender</th>
                                    <th>No. HP</th>
                                    <th style="width: 120px" class="text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($patients as $patient)
                                    <tr>
                                        <td class="text-center align-middle">
                                            {{ $patients->firstItem() + $loop->index }}
                                        </td>
                                        <td class="align-middle font-weight-bold">{{ $patient->name }}</td>
                                        <td class="align-middle text-sm">{{ $patient->address }}</td>
                                        <td class="text-center align-middle">
                                            @if($patient->gender == 'L')
                                                <span class="badge badge-info px-2">Laki-laki</span>
                                            @else
                                                <span class="badge badge-danger px-2" style="background-color: #e83e8c;">Perempuan</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @php
                                                $num = preg_replace('/[^0-9]/', '', $patient->phone);
                                                $wa = (substr($num, 0, 1) === '0') ? '62' . substr($num, 1) : $num;
                                                
                                                $namaDepan = strtolower(explode(' ', trim($patient->name))[0]);
                                                $tglLahir = $patient->date_of_birth 
                                                    ? \Carbon\Carbon::parse($patient->date_of_birth)->format('dmY') 
                                                    : '123456';
                                                $pwd = $namaDepan . $tglLahir;
                                            @endphp

                                            <a href="https://wa.me/{{ $wa }}" target="_blank" class="text-dark font-weight-bold">
                                                <i class="fab fa-whatsapp text-success mr-1"></i>
                                                {{ $patient->phone }}
                                            </a>
                                        </td>
                                        <td class="text-center align-middle">
                                            {{-- Container tombol dengan flex-row di desktop, flex-column di mobile --}}
                                            <div class="d-flex flex-md-row flex-column justify-content-center align-items-center" style="gap: 8px;">
                                                
                                                {{-- Tombol Detail --}}
                                                <a href="{{ route('pengurus.patients.show', $patient->id) }}"
                                                class="btn btn-info btn-sm shadow-sm border-0 w-100" 
                                                style="min-width: 80px;">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>

                                                @php
                                                    $latestReservation = $patient->reservations()->latest()->first();
                                                    $tglReservasi = $latestReservation ? \Carbon\Carbon::parse($latestReservation->doctorSchedule->date)->format('d-m-Y') : '-';
                                                    $jamReservasi = $latestReservation ? substr($latestReservation->doctorSchedule->start_time, 0, 5) : '-';
                                                    $layanan = $latestReservation->action ?? 'Konsultasi';
                                                @endphp

                                                {{-- Tombol Chat --}}
                                                <button type="button" 
                                                    class="btn btn-success btn-sm shadow-sm border-0 btn-kirim-wa w-100" 
                                                    style="min-width: 100px;"
                                                    data-toggle="modal" 
                                                    data-target="#modalWA"
                                                    data-nama="{{ $patient->name }}"
                                                    data-phone="{{ $wa }}"
                                                    data-email="{{ $patient->user->email ?? '-' }}"
                                                    data-pwd="{{ $pwd }}"
                                                    data-layanan="{{ $layanan }}"
                                                    data-tgl-jadwal="{{ $tglReservasi }}"
                                                    data-jam-jadwal="{{ $jamReservasi }}">
                                                    <i class="fab fa-whatsapp"></i> Chat
                                                </button>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fas fa-user-injured fa-3x mb-3 opacity-25"></i><br>
                                            <strong>Data pasien tidak ditemukan</strong>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- CARD FOOTER --}}
                <div class="card-footer bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col-sm-6 text-center text-sm-left mb-3 mb-sm-0">
                            <p class="mb-0 small text-muted">
                                Menampilkan <strong>{{ $patients->firstItem() ?? 0 }}</strong> - <strong>{{ $patients->lastItem() ?? 0 }}</strong> dari <strong>{{ $patients->total() ?? 0 }}</strong> pasien
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex justify-content-center justify-content-sm-end">
                                {{ $patients->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- MODAL (WAJIB DI DALAM SECTION CONTENT ATAU SECTION KHUSUS) --}}
<div class="modal fade" id="modalWA" tabindex="-1" role="dialog" aria-labelledby="modalWALabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalWALabel"><i class="fab fa-whatsapp mr-2"></i> Pilih Template Pesan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-light">
                <div class="text-center mb-3">
                    <p class="text-muted small">Mengirim pesan ke: <br><strong id="wa-target-name"></strong> (<span id="wa-target-phone"></span>)</p>
                </div>
                
                <div class="list-group">
                    <a href="#" id="link-akses-akun" target="_blank" class="list-group-item list-group-item-action mb-2 shadow-sm border-left-success">
                        <h6 class="mb-1 font-weight-bold text-success">1. Detail Akses Akun</h6>
                        <p class="mb-1 small text-muted">Kirim Email & Password agar pasien bisa login.</p>
                    </a>

                    <a href="#" id="link-pengingat-jadwal" target="_blank" class="list-group-item list-group-item-action mb-2 shadow-sm border-left-primary">
                        <h6 class="mb-1 font-weight-bold text-primary">2. Pengingat Jadwal</h6>
                        <p class="mb-1 small text-muted">Ingatkan jadwal konsultasi mendatang.</p>
                    </a>

                    <a href="#" id="link-konfirmasi-data" target="_blank" class="list-group-item list-group-item-action shadow-sm border-left-warning">
                        <h6 class="mb-1 font-weight-bold text-warning">3. Konfirmasi Data Diri</h6>
                        <p class="mb-1 small text-muted">Konfirmasi data untuk aktivasi akun.</p>
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 576px) {
        .pagination { font-size: 0.8rem; flex-wrap: wrap; justify-content: center; }
        .input-group { width: 100% !important; }
    }
    .border-left-success { border-left: 4px solid #28a745 !important; }
    .border-left-primary { border-left: 4px solid #007bff !important; }
    .border-left-warning { border-left: 4px solid #ffc107 !important; }
</style>

{{-- JAVASCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    $('.btn-kirim-wa').on('click', function() {
        const d = $(this).data();
        
        $('#wa-target-name').text(d.nama);
        $('#wa-target-phone').text(d.phone);

        // --- 1. DETAIL AKSES AKUN (Format Original) ---
        const pesan1 = encodeURIComponent(
            `Halo, Customer Amelys(^_^)> *${d.nama}*\n\n` +
            `Akun pasien Anda di *Praktek Dokter Amelys* telah aktif. Berikut adalah detail akun Anda:\n\n` +
            `*Email:* ${d.email}\n` +
            `*Password:* ${d.pwd}\n\n` +
            `Silakan login untuk melihat jadwal dokter & reservasi dokter melalui link berikut:\n` +
            `https://praktekdokteramelys.my.id\n\n` +
            `*Info tambahan:* Anda dapat mengubah Email atau Password secara mandiri melalui menu *Profil* setelah berhasil login.\n\n` +
            `Simpan data ini baik-baik. Terima kasih.`
        );
        $('#link-akses-akun').attr('href', `https://wa.me/${d.phone}?text=${pesan1}`);

        // --- 2. PENGINGAT JADWAL ---
        const pesan2 = encodeURIComponent(
            `Halo, Kak *${d.nama}*.\n\n` +
            `Kami mengonfirmasi jadwal kunjungan Anda di *Praktek Dokter Amelys*:\n\n` +
            `*Layanan:* ${d.layanan}\n` +
            `*Tanggal:* ${d.tglJadwal}\n` +
            `*Jam:* ${d.jamJadwal} WIB\n\n` +
            `Mohon hadir 15 menit sebelum jadwal ya Kak. Jika ada kendala kehadiran, silakan hubungi kami kembali atau bisa cancel mandiri lewat web:\n` + 
            `https://praktekdokteramelys.my.id\n\n` +
            `Terima kasih (^_^)`
        );
        $('#link-pengingat-jadwal').attr('href', `https://wa.me/${d.phone}?text=${pesan2}`);

        // --- 3. KONFIRMASI DATA DIRI (Pertanyaan Kosong) ---
        const pesan3 = encodeURIComponent(
            `Halo, Kak *${d.nama}*.\n\n` +
            `Mohon lengkapi data diri berikut untuk proses pendaftaran akun di website *Praktek Dokter Amelys*:\n\n` +
            `- Nama Lengkap:\n` +
            `- Alamat:\n` +
            `- Tgl Lahir:\n` +
            `- Jenis Kelamin:\n` +
            `- No. HP:\n` +
            `- Email:\n\n` +
            `Data ini akan kami gunakan untuk membuatkan akun login Kakak. Terima kasih!`
        );
        $('#link-konfirmasi-data').attr('href', `https://wa.me/${d.phone}?text=${pesan3}`);
    });
});
</script>

@endsection