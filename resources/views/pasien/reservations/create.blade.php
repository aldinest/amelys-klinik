@extends('layouts.app_pasien')

@section('content')
<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                {{-- KOLOM KIRI: PILIH DOKTER & JADWAL --}}
                <div class="col-lg-8">
                    <div class="card card-primary card-outline shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-user-md mr-1"></i> 1. Pilih Dokter</h3>
                        </div>
                        <div class="card-body">
                            <select id="doctorSelect" class="form-control select2">
                                <option value="">-- Cari Nama Dokter --</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">dr. {{ $doctor->name }} ({{ $doctor->specialist ?? 'Umum' }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card card-success card-outline shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-calendar-alt mr-1"></i> 2. Pilih Jadwal</h3>
                            <div class="card-tools">
                                <div class="d-flex align-items-center" style="gap: 8px;">
                                    <select id="filterMonth" class="form-control form-control-sm border-0 shadow-sm">
                                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $key => $month)
                                            <option value="{{ $key }}" {{ date('n')-1 == $key ? 'selected' : '' }}>{{ $month }}</option>
                                        @endforeach
                                    </select>
                                    <select id="filterYear" class="form-control form-control-sm border-0 shadow-sm">
                                        @for($y = date('Y'); $y <= date('Y') + 1; $y++)
                                            <option value="{{ $y }}">{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0" id="customCalendar">
                                    <thead class="bg-light text-center">
                                        <tr>
                                            <th style="width: 14.28%" class="text-danger">Min</th>
                                            <th style="width: 14.28%">Sen</th>
                                            <th style="width: 14.28%">Sel</th>
                                            <th style="width: 14.28%">Rab</th>
                                            <th style="width: 14.28%">Kam</th>
                                            <th style="width: 14.28%">Jum</th>
                                            <th style="width: 14.28%">Sab</th>
                                        </tr>
                                    </thead>
                                    <tbody id="calendarBody">
                                        {{-- Diisi oleh JavaScript --}}
                                    </tbody>
                                </table>
                            </div>

                            <div class="calendar-legend px-3 py-2 bg-light border-top d-flex flex-wrap" style="gap: 15px; font-size: 12px;">
                                <div class="d-flex align-items-center"><span class="legend-box bg-success mr-1"></span> Tersedia</div>
                                <div class="d-flex align-items-center"><span class="legend-box bg-danger mr-1"></span> Penuh</div>
                                <div class="d-flex align-items-center"><span class="legend-box bg-warning mr-1"></span> Terdaftar</div>
                                <div class="d-flex align-items-center"><span class="legend-box bg-past mr-1"></span> Selesai</div>
                                <div class="d-flex align-items-center"><span class="legend-box border border-warning mr-1" style="background:#fff9e6"></span> Hari Ini</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: DETAIL TINDAKAN --}}
                <div class="col-lg-4">
                    <div id="reservationForm" style="display:none;">
                        <div class="card card-success card-outline shadow-sm border-top-3">
                            <div class="card-header"><h3 class="card-title font-weight-bold">3. Detail Tindakan</h3></div>
                            <form id="mainBookingForm" method="POST" action="{{ route('pasien.reservations.store') }}">
                                @csrf
                                <input type="hidden" name="doctor_schedule_id" id="doctor_schedule_id">
                                <div class="card-body">
                                    <div id="selectedScheduleInfo" class="mb-3"></div>
                                    <div class="form-group">
                                        <label>Keluhan / Keperluan Pemeriksaan</label>
                                        <textarea name="action" class="form-control" rows="5" placeholder="Tuliskan keluhan Anda di sini..." required></textarea>
                                        <small class="text-muted italic">*Admin akan memverifikasi reservasi Anda.</small>
                                    </div>
                                </div>
                                <div class="card-footer bg-white">
                                    <button type="submit" class="btn btn-success btn-block py-2 font-weight-bold shadow-sm">
                                        KONFIRMASI RESERVASI
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div id="placeholder" class="card card-outline card-secondary border-dashed text-center py-5 shadow-sm">
                        <div class="card-body">
                            <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                            <h5 class="text-dark font-weight-bold">Mulai Reservasi</h5>
                            <p class="text-muted small">Pilih dokter dan klik pada tanggal yang tersedia di kalender.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    table#customCalendar tbody td { height: 110px; vertical-align: top; padding: 6px; background: #fff; border: 1px solid #eee; }
    .date-number { font-weight: bold; color: #444; display: block; text-align: right; font-size: 14px; margin-bottom: 4px; }
    
    /* Tombol Jadwal Base */
    .schedule-item { 
        display: block; width: 100%; border: none; border-radius: 4px; padding: 4px 6px; 
        font-size: 11px; margin-bottom: 4px; text-align: left; transition: 0.2s; cursor: pointer; color: #fff;
    }
    .schedule-item:hover { opacity: 0.8; transform: translateY(-1px); }
    
    /* Status Warna */
    .bg-success { background-color: #28a745 !important; }
    .bg-danger { background-color: #dc3545 !important; }
    .bg-warning { background-color: #ffc107 !important; color: #212529 !important; }
    .bg-past { background-color: #e9ecef !important; color: #6c757d !important; cursor: not-allowed !important; border: 1px dashed #ced4da; }
    
    .slot-info { display: block; font-size: 9px; opacity: 0.9; border-top: 1px solid rgba(255,255,255,0.3); margin-top: 2px; }
    .bg-past .slot-info { border-top: 1px solid #ced4da; }

    td.today { background-color: #fff9e6 !important; border: 2px solid #ffc107 !important; }
    td.empty-day { background-color: #fafafa !important; }
    td.empty-day .date-number { color: #ccc; }

    .legend-box { width: 12px; height: 12px; border-radius: 2px; display: inline-block; }
    .border-dashed { border: 2px dashed #ddd !important; background: transparent; }
</style>
@endsection

@section('scripts')
{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const doctorSelect = document.getElementById('doctorSelect');
    const calendarBody = document.getElementById('calendarBody');
    const filterMonth = document.getElementById('filterMonth');
    const filterYear = document.getElementById('filterYear');
    const scheduleInput = document.getElementById('doctor_schedule_id');
    const reservationForm = document.getElementById('reservationForm');
    const placeholder = document.getElementById('placeholder');
    const summaryInfo = document.getElementById('selectedScheduleInfo');

    function renderCalendar(month, year, schedules) {
        calendarBody.innerHTML = '';
        month = parseInt(month);
        year = parseInt(year);

        let firstDay = new Date(year, month).getDay();
        let daysInMonth = 32 - new Date(year, month, 32).getDate();
        let date = 1;
        let todayDate = new Date();
        todayDate.setHours(0,0,0,0);

        for (let i = 0; i < 6; i++) {
            let row = document.createElement('tr');
            for (let j = 0; j < 7; j++) {
                let cell = document.createElement('td');
                
                if (i === 0 && j < firstDay) {
                    cell.classList.add('empty-day');
                } else if (date > daysInMonth) {
                    cell.classList.add('empty-day');
                } else {
                    let fMonth = String(month + 1).padStart(2, '0');
                    let fDay = String(date).padStart(2, '0');
                    let fullDateStr = `${year}-${fMonth}-${fDay}`;
                    let currentDateObj = new Date(year, month, date);

                    cell.innerHTML = `<span class="date-number">${date}</span>`;
                    
                    // Highlight Hari Ini
                    if (currentDateObj.getTime() === todayDate.getTime()) {
                        cell.classList.add('today');
                    }

                    let daySchedules = schedules.filter(s => s.start === fullDateStr);

                    if (doctorSelect.value !== "" && daySchedules.length === 0) {
                        cell.classList.add('empty-day');
                    }

                    daySchedules.forEach(sched => {
                        let btn = document.createElement('button');
                        let remaining = sched.extendedProps.remaining ?? 0;
                        let isFull = remaining <= 0;
                        let hasRegistered = sched.extendedProps.has_registered ?? false;
                        let isPast = currentDateObj < todayDate;

                        btn.type = "button";
                        
                        if (isPast) {
                            btn.className = "schedule-item bg-past";
                            btn.innerHTML = `<strong><i class="fas fa-check-circle"></i> Selesai</strong><span class="slot-info">Sudah lewat</span>`;
                            btn.disabled = true;
                        } else if (hasRegistered) {
                            btn.className = "schedule-item bg-warning shadow-sm";
                            btn.innerHTML = `<strong><i class="fas fa-exclamation-circle"></i> Terdaftar</strong><span class="slot-info">Sudah ambil antrean</span>`;
                            btn.onclick = () => Swal.fire('Sudah Terdaftar', 'Anda sudah mendaftar untuk jadwal ini. Cek menu Reservasi Saya.', 'info');
                        } else {
                            btn.className = isFull ? "schedule-item bg-danger" : "schedule-item bg-success shadow-sm";
                            btn.innerHTML = `<strong><i class="far fa-clock"></i> ${sched.title}</strong><span class="slot-info">Sisa ${remaining} Slot</span>`;
                            
                            if (!isFull) {
                                btn.onclick = () => selectSchedule(sched);
                            } else {
                                btn.onclick = () => Swal.fire('Kuota Penuh', 'Maaf, kuota untuk jadwal ini sudah habis.', 'error');
                            }
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

    function loadDoctorSchedules(doctorId) {
        if (!doctorId) { renderCalendar(filterMonth.value, filterYear.value, []); return; }
        
        calendarBody.innerHTML = '<tr><td colspan="7" class="text-center py-5"><i class="fas fa-sync fa-spin fa-2x text-primary"></i><br>Mencari jadwal...</td></tr>';

        const url = "{{ route('pasien.reservations.calendar', ':id') }}".replace(':id', doctorId);
        fetch(url).then(res => res.json()).then(data => {
            renderCalendar(filterMonth.value, filterYear.value, data);
        }).catch(() => {
            calendarBody.innerHTML = '<tr><td colspan="7" class="text-center text-danger py-5">Gagal memuat data.</td></tr>';
        });
    }

    function selectSchedule(sched) {
        scheduleInput.value = sched.id;
        summaryInfo.innerHTML = `
            <div class="alert alert-info border-0 shadow-sm small mb-0">
                <i class="fas fa-calendar-check mr-1"></i> <strong>Jadwal Terpilih:</strong><br>
                dr. ${sched.extendedProps.doctor_name}<br>
                ${sched.extendedProps.date_formatted} (${sched.title})
            </div>`;
        placeholder.style.display = 'none';
        reservationForm.style.display = 'block';
        if(window.innerWidth < 992) reservationForm.scrollIntoView({ behavior: 'smooth' });
    }

    doctorSelect.addEventListener('change', function() { loadDoctorSchedules(this.value); });
    filterMonth.addEventListener('change', function() { loadDoctorSchedules(doctorSelect.value); });
    filterYear.addEventListener('change', function() { loadDoctorSchedules(doctorSelect.value); });

    // Submit Konfirmasi
    document.getElementById('mainBookingForm').onsubmit = function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Reservasi?',
            text: "Pastikan jadwal sudah benar sebelum mendaftar.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Ya, Daftar!',
            cancelButtonText: 'Batal'
        }).then((res) => { if(res.isConfirmed) this.submit(); });
    };

    if(doctorSelect.value) loadDoctorSchedules(doctorSelect.value);
    else renderCalendar(filterMonth.value, filterYear.value, []);
});
</script>
@endsection