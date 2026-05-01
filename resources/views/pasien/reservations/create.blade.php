@extends('layouts.app_pasien')

@section('content')
<style>
    /* Global & Layout */
    .content-wrapper { background-color: #f8fafc !important; }
    .container-custom { padding: 0 5%; }
    @media (max-width: 768px) { .container-custom { padding: 0 10px; } }

    /* Card Styling */
    .card-modern { border-radius: 16px; border: none; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); overflow: hidden; }
    .card-modern .card-header { background: #fff; border-bottom: 1px solid #f1f5f9; padding: 1.25rem; }
    
    /* Calendar UI */
    .calendar-table { table-layout: fixed; margin-bottom: 0; }
    .calendar-table th { background: #f8fafc; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; color: #64748b; padding: 12px 0; border: none !important; }
    .calendar-table td { height: 110px; vertical-align: top !important; padding: 6px !important; border: 1px solid #f1f5f9 !important; position: relative; transition: 0.2s; }
    .calendar-table td:hover:not(.empty-day) { background-color: #f8fafc; }
    
    .date-num { font-weight: 700; font-size: 13px; color: #1e293b; display: block; text-align: right; margin-bottom: 6px; }
    .today { background-color: #fffbeb !important; border: 2px solid #fbbf24 !important; }
    .empty-day { background-color: #fbfcfd !important; opacity: 0.6; }

    /* Schedule Pills */
    .sched-btn { 
        width: 100%; border: none; border-radius: 8px; padding: 6px 8px; margin-bottom: 4px;
        font-size: 10px; text-align: left; transition: 0.2s; cursor: pointer; color: #fff;
        display: flex; flex-direction: column; line-height: 1.2;
    }
    .sched-btn:hover { filter: brightness(90%); transform: translateY(-1px); }
    .sched-btn span { font-size: 9px; opacity: 0.8; margin-top: 2px; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 2px; }

    /* Status Colors */
    .btn-available { background: #10b981; box-shadow: 0 2px 4px rgba(16,185,129,0.2); }
    .btn-full { background: #ef4444; cursor: not-allowed; }
    .btn-registered { background: #f59e0b; color: #fff; }
    .btn-past { background: #cbd5e1; color: #64748b; cursor: not-allowed; }

    /* Form Area */
    .placeholder-state { border: 2px dashed #e2e8f0; border-radius: 16px; background: transparent; }
    .selected-box { background: #eff6ff; border-radius: 12px; padding: 15px; border: 1px solid #bfdbfe; }

    /* Custom Select2 Style */
    .select2-container--default .select2-selection--single {
        border-radius: 10px; border: 1px solid #d1d5db; height: 45px; padding: 8px;
    }

    /* Container kartu dokter agar sejajar horizontal */
    .doctor-cards-container {
        display: flex;
        gap: 15px;
        overflow-x: auto;
        padding: 10px 0 20px 0;
    }

    /* Gaya dasar kartu dokter */
    .doc-card {
        min-width: 130px;
        background: #fff;
        border: 2px solid transparent; /* Border default transparan */
        border-radius: 16px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    /* Efek hover */
    .doc-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* Gaya saat kartu dipilih (Active) - Warna hijau sesuai tema Anda */
    .doc-card.active {
        border-color: #28a745;
        background-color: #f8fff9;
    }

    /* Avatar bulat/kotak di dalam kartu */
    .doc-avatar-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-size: 18px;
        font-weight: bold;
        color: #495057;
    }

    /* Warna avatar saat aktif */
    .doc-card.active .doc-avatar-icon {
        background-color: #28a745;
        color: #fff;
    }

    .doc-meta-name { font-size: 12px; font-weight: 700; color: #333; margin-bottom: 2px; }
    .doc-meta-spec { font-size: 10px; color: #777; }

    .selected-box {
    background: #f8f9fa; /* Warna abu sangat muda agar elemen putih di dalamnya kontras */
    border-radius: 16px;
    padding: 20px;
    border: 1px solid #e9ecef;
    }
</style>

<div class="content-wrapper">
    <div class="container-custom py-4">
        <div class="row">
            {{-- KOLOM KIRI: KALENDER --}}
            <div class="col-lg-8">
                <div class="card card-modern mb-4">
                    <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                        <h5 class="font-weight-bold mb-2 mb-md-0"><i class="fas fa-calendar-alt text-primary mr-2"></i>Pilih Jadwal Dokter</h5>
                        <div class="d-flex align-items-center" style="gap: 10px;">
                            <select id="filterMonth" class="form-control form-control-sm custom-select border-0 bg-light">
                                @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $k => $m)
                                    <option value="{{ $k }}" {{ date('n')-1 == $k ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                            <select id="filterYear" class="form-control form-control-sm custom-select border-0 bg-light">
                                @for($y = date('Y'); $y <= date('Y') + 1; $y++)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    
                    <div class="p-3 bg-white border-bottom">
                        <label class="small font-weight-bold text-muted">PILIH DOKTER:</label>
                        
                        <div class="doctor-cards-container" id="doctorCardsArea">
                            
                            <div class="doc-card active p-3" data-id="">
                                <div class="doc-avatar-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="doc-meta-name">Semua</div>
                                <div class="doc-meta-spec">Dokter</div>
                            </div>

                            @foreach($doctors as $doctor)
                            <div class="doc-card p-3" data-id="{{ $doctor->id }}">
                                <div class="doc-avatar-icon">
                                    {{ strtoupper(substr($doctor->name, 0, 2)) }}
                                </div>
                                <div class="doc-meta-name text-truncate">dr. {{ $doctor->name }}</div>
                                <div class="doc-meta-spec">{{ $doctor->specialist ?? 'Umum' }}</div>
                            </div>
                            @endforeach
                        </div>

                        <input type="hidden" id="doctorSelect" value="">
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table calendar-table" id="customCalendar">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-danger">Min</th><th>Sen</th><th>Sel</th><th>Rab</th><th>Kam</th><th>Jum</th><th>Sab</th>
                                    </tr>
                                </thead>
                                <tbody id="calendarBody"></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-light p-3">
                        <div class="d-flex flex-wrap" style="gap: 15px; font-size: 11px;">
                            <div class="d-flex align-items-center"><span class="badge badge-success mr-1" style="width:10px;height:10px;border-radius:2px">&nbsp;</span> Tersedia</div>
                            <div class="d-flex align-items-center"><span class="badge badge-danger mr-1" style="width:10px;height:10px;border-radius:2px">&nbsp;</span> Penuh</div>
                            <div class="d-flex align-items-center"><span class="badge badge-warning mr-1" style="width:10px;height:10px;border-radius:2px">&nbsp;</span> Terdaftar</div>
                            <div class="d-flex align-items-center"><span class="badge badge-secondary mr-1" style="width:10px;height:10px;border-radius:2px">&nbsp;</span> Lewat</div>
                        </div>
                    </div>
                </div>
            </div>

        {{-- KOLOM KANAN: FORM --}}
        <div class="col-lg-4 pb-5"> {{-- Tambah pb-5 supaya gak mentok nav bawah --}}
            <div id="reservationFormArea" style="display:none;">
                <div class="card card-modern border-0 shadow-lg">
                    <div class="card-header bg-success text-white py-3">
                        <h6 class="font-weight-bold mb-0 text-center">Konfirmasi Kunjungan</h6>
                    </div>
                    <form id="mainBookingForm" method="POST" action="{{ url('/pasien/reservations') }}">
                        @csrf
                        <input type="hidden" name="doctor_schedule_id" id="doctor_schedule_id">
                        <div class="card-body">
                            <div id="selectedScheduleSummary" class="selected-box mb-4">
                                {{-- JS akan mengisi ini dengan struktur yang lebih rapi --}}
                            </div>
                            
                            <div class="form-group mb-4">
                                <label class="font-weight-bold small text-muted mb-2">KELUHAN / TUJUAN KEDATANGAN</label>
                                <textarea name="action" class="form-control" rows="5" 
                                    placeholder="Tuliskan keluhan Anda di sini..." 
                                    required style="border-radius: 12px; border: 1.5px solid #e2e8f0; resize: none;"></textarea>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 pb-4">
                            <button type="submit" class="btn btn-success btn-block py-3 font-weight-bold shadow-sm" style="border-radius:12px; letter-spacing: 0.5px;">
                                RESERVASI SEKARANG
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="formPlaceholder" class="placeholder-state text-center py-5 px-3 mb-5">
                <img src="https://cdn-icons-png.flaticon.com/512/2693/2693507.png" width="80" style="opacity: 0.2;" class="mb-3">
                <h6 class="font-weight-bold text-muted">Belum Ada Jadwal</h6>
                <p class="text-muted small">Silakan pilih dokter dan klik jam praktek pada kalender.</p>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    const calendarBody = document.getElementById('calendarBody');
    const filterMonth = document.getElementById('filterMonth');
    const filterYear = document.getElementById('filterYear');
    const scheduleInput = document.getElementById('doctor_schedule_id');
    const formArea = document.getElementById('reservationFormArea');
    const placeholder = document.getElementById('formPlaceholder');
    const summaryBox = document.getElementById('selectedScheduleSummary');
    const hiddenDocInput = $('#doctorSelect');

    // 1. FUNGSI RENDER KALENDER (Tetap Seperti Punya Anda)
    function renderCalendar(month, year, schedules) {
        calendarBody.innerHTML = '';
        month = parseInt(month); year = parseInt(year);
        let firstDay = new Date(year, month).getDay();
        let daysInMonth = 32 - new Date(year, month, 32).getDate();
        let date = 1;
        let today = new Date(); today.setHours(0,0,0,0);

        for (let i = 0; i < 6; i++) {
            let row = document.createElement('tr');
            for (let j = 0; j < 7; j++) {
                let cell = document.createElement('td');
                if (i === 0 && j < firstDay) {
                    cell.classList.add('empty-day');
                } else if (date > daysInMonth) {
                    cell.classList.add('empty-day');
                } else {
                    let curDate = new Date(year, month, date);
                    let dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(date).padStart(2,'0')}`;
                    cell.innerHTML = `<span class="date-num">${date}</span>`;
                    if (curDate.getTime() === today.getTime()) cell.classList.add('today');

                    let daySchedules = schedules.filter(s => s.start === dateStr);
                    daySchedules.forEach(s => {
                        let isPast = curDate < today;
                        let hasReg = s.extendedProps.has_registered;
                        let remains = s.extendedProps.remaining;
                        let btn = document.createElement('button');
                        btn.type = "button";

                        if (isPast) {
                            btn.className = "sched-btn btn-past";
                            btn.innerHTML = `<b>Selesai</b><span>Sudah Lewat</span>`;
                        } else if (hasReg) {
                            btn.className = "sched-btn btn-registered";
                            btn.innerHTML = `<b>Terdaftar</b><span>Sudah Terpilih</span>`;
                            btn.onclick = () => Swal.fire('Info', 'Anda sudah mendaftar di jadwal ini.', 'info');
                        } else if (remains <= 0) {
                            btn.className = "sched-btn btn-full";
                            btn.innerHTML = `<b>Penuh</b><span>Kuota Habis</span>`;
                        } else {
                            btn.className = "sched-btn btn-available";
                            btn.innerHTML = `<b>${s.title}</b><span>Sisa ${remains} Slot</span>`;
                            btn.onclick = () => selectSched(s);
                        }
                        cell.appendChild(btn);
                    });
                    date++;
                }
                row.appendChild(cell);
            }
            calendarBody.appendChild(row);
            if (date > daysInMonth) break;
        }
    }

    // 2. FUNGSI FETCH DATA (Disesuaikan agar bisa dipanggil kartu)
    window.fetchSchedules = function() {
        let docId = hiddenDocInput.val();
        // Jika belum pilih dokter, tampilkan kalender kosong (Permintaan Anda)
        if (!docId) { 
            renderCalendar(filterMonth.value, filterYear.value, []); 
            return; 
        }

        calendarBody.innerHTML = '<tr><td colspan="7" class="text-center py-5"><div class="spinner-border text-primary"></div></td></tr>';
        
        fetch(`{{ url('/pasien/reservations/calendar') }}/${docId}`)
            .then(r => r.json())
            .then(data => renderCalendar(filterMonth.value, filterYear.value, data))
            .catch(() => { 
                calendarBody.innerHTML = '<tr><td colspan="7" class="text-center text-danger py-4">Gagal memuat jadwal.</td></tr>'; 
            });
    }

        // 3. FUNGSI PILIH JADWAL
        function selectSched(s) {
        scheduleInput.value = s.id;
        
        summaryBox.innerHTML = `
            <div class="d-flex align-items-center mb-3">
                <div class="mr-3 d-flex align-items-center justify-content-center bg-white shadow-sm" 
                    style="width: 45px; height: 45px; border-radius: 12px; color: #007bff;">
                    <i class="fas fa-user-md fa-lg"></i>
                </div>
                <div>
                    <p class="mb-0 text-muted small uppercase font-weight-bold" style="letter-spacing: 0.5px;">DOKTER</p>
                    <h6 class="font-weight-bold text-dark mb-0">dr. ${s.extendedProps.doctor_name}</h6>
                </div>
            </div>
            
            <div class="row no-gutters bg-white p-3 shadow-sm" style="border-radius: 12px; border-left: 4px solid #007bff;">
                <div class="col-6 border-right">
                    <p class="mb-1 text-muted small"><i class="far fa-calendar-alt mr-1"></i> TANGGAL</p>
                    <p class="mb-0 font-weight-bold" style="font-size: 13px;">${s.extendedProps.date_formatted}</p>
                </div>
                <div class="col-6 pl-3">
                    <p class="mb-1 text-muted small"><i class="far fa-clock mr-1"></i> JAM</p>
                    <p class="mb-0 font-weight-bold text-primary" style="font-size: 13px;">${s.title} WIB</p>
                </div>
            </div>
        `;

        placeholder.style.display = 'none';
        formArea.style.display = 'block';
        
        if(window.innerWidth < 992) {
            formArea.scrollIntoView({ behavior: 'smooth' });
        }
    }

    // 4. LOGIKA KLIK KARTU DOKTER
    $('.doc-card').click(function() {
        $('.doc-card').removeClass('active');
        $(this).addClass('active');
        
        var doctorId = $(this).attr('data-id');
        hiddenDocInput.val(doctorId);
        
        fetchSchedules(); // Panggil fungsi fetch
    });

    // 5. EVENT LISTENER LAINNYA
    filterMonth.addEventListener('change', fetchSchedules);
    filterYear.addEventListener('change', fetchSchedules);

    document.getElementById('mainBookingForm').onsubmit = function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Konfirmasi?',
            text: "Daftar untuk jadwal yang dipilih?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Daftar!',
            cancelButtonText: 'Batal'
        }).then(res => { if(res.isConfirmed) this.submit(); });
    };

    // Jalankan pertama kali (Kalender tampil tapi kosong jadwal)
    fetchSchedules();
});
</script>
@endsection