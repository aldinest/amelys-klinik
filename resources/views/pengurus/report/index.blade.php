@extends('layouts.app_pengurus')

@section('content')
{{-- Custom Style untuk handling Modal & Badge --}}
<style>
    .badge-success-soft { background-color: rgba(40, 167, 69, 0.1); color: #28a745; }
    .modal-xl { max-width: 90%; }
    #previewPdfModal { z-index: 1060 !important; }
    .modal-backdrop { z-index: 1050 !important; }
    .progress-bar { transition: width 0.6s ease; }
</style>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div>
                    <h1 class="m-0 font-weight-bold text-dark">Laporan Riwayat Tahunan</h1>
                    <p class="text-muted small mb-0">Overview performa klinik periode {{ $year }}</p>
                </div>
                
                <div class="d-none d-md-block">
                    <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                        {{-- Ganti ke Button untuk menghindari void(0) --}}
                        <button type="button" 
                                onclick="previewPdf('{{ route('pengurus.report.pdf', ['year' => $year, 'doctor_id' => $doctorId]) }}')" 
                                class="btn btn-sm btn-white text-danger border-right bg-white">
                            <i class="fas fa-file-pdf mr-1"></i> PDF
                        </button>
                        <a href="#" class="btn btn-sm btn-white text-success bg-white">
                            <i class="fas fa-file-excel mr-1"></i> Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            
            {{-- Filter Area --}}
            <div class="card shadow-sm border-0 mb-4 rounded-lg">
                <div class="card-body p-3">
                    <form action="{{ route('pengurus.report.index') }}" method="GET" id="filterForm">
                        <div class="row align-items-center">
                            <div class="col-md-9 col-12 d-flex flex-wrap align-items-center" style="gap: 12px;">
                                <div class="input-group input-group-sm border rounded-pill px-3 bg-light" style="width: auto;">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-transparent border-0 text-primary"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <select name="year" class="form-control form-control-sm border-0 bg-transparent font-weight-bold select-hybrid" style="width: 80px;">
                                        @php $currentYear = date('Y'); @endphp
                                        @for($y = $currentYear; $y >= $currentYear - 5; $y--)
                                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="input-group input-group-sm border rounded-pill px-3 bg-light" style="width: auto; min-width: 200px;">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-transparent border-0 text-primary"><i class="fas fa-user-md"></i></span>
                                    </div>
                                    <select name="doctor_id" class="form-control form-control-sm border-0 bg-transparent font-weight-bold select-hybrid">
                                        <option value="all" {{ $doctorId == 'all' ? 'selected' : '' }}>Semua Dokter</option>
                                        @foreach($doctors as $dr)
                                            <option value="{{ $dr->id }}" {{ $doctorId == $dr->id ? 'selected' : '' }}>{{ $dr->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm d-none d-md-inline-block">
                                    <i class="fas fa-sync-alt mr-1"></i> Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Stats Box --}}
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="card shadow-sm border-0 rounded-lg overflow-hidden mb-4">
                        <div class="card-body p-0 d-flex align-items-stretch">
                            <div class="bg-info p-4 d-flex align-items-center"><i class="fas fa-calendar-check fa-2x text-white-50"></i></div>
                            <div class="p-3">
                                <span class="text-uppercase small text-muted font-weight-bold">Total Reservasi</span>
                                <h3 class="font-weight-bold mb-0 text-info">{{ collect($monthlySummary)->sum('total_reservations') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="card shadow-sm border-0 rounded-lg overflow-hidden mb-4">
                        <div class="card-body p-0 d-flex align-items-stretch">
                            <div class="bg-success p-4 d-flex align-items-center"><i class="fas fa-user-md fa-2x text-white-50"></i></div>
                            <div class="p-3">
                                <span class="text-uppercase small text-muted font-weight-bold">Pasien Terperiksa</span>
                                <h3 class="font-weight-bold mb-0 text-success">{{ collect($monthlySummary)->sum('total_checked') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Chart --}}
            <div class="card shadow-sm border-0 mb-4 rounded-lg">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="font-weight-bold text-dark mb-0"><i class="fas fa-chart-bar mr-2 text-primary"></i>Grafik Performa Bulanan</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div style="height: 300px; position: relative;">
                        <canvas id="yearlyChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="card shadow-sm border-0 mb-4 rounded-lg">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="font-weight-bold text-dark mb-0"><i class="fas fa-table mr-2 text-primary"></i>Ringkasan Performa Tiap Bulan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 border-0 text-uppercase small font-weight-bold">Bulan</th>
                                    <th class="text-center border-0 text-uppercase small font-weight-bold">Reservasi</th>
                                    <th class="text-center border-0 text-uppercase small font-weight-bold">Selesai</th>
                                    <th class="text-center border-0 text-uppercase small font-weight-bold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlySummary as $row)
                                <tr>
                                    <td class="px-4 font-weight-bold text-dark">{{ $row['month_name'] }}</td>
                                    <td class="text-center"><span class="badge badge-light border px-3 py-2">{{ $row['total_reservations'] }}</span></td>
                                    <td class="text-center"><span class="badge badge-success-soft text-success px-3 py-2 font-weight-bold">{{ $row['total_checked'] }}</span></td>
                                    <td class="text-center">
                                        @if($row['total_checked'] > 0)
                                            <button type="button" 
                                                    onclick="previewPdf('{{ route('pengurus.report.export_monthly_pdf', ['month' => sprintf('%02d', $row['month_num']), 'year' => $year, 'doctor_id' => $doctorId]) }}')" 
                                                    class="btn btn-sm btn-outline-danger px-3 rounded-pill">
                                                <i class="fas fa-file-pdf mr-1"></i> PDF
                                            </button>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

{{-- Modal Preview --}}
<div class="modal fade" id="previewPdfModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-file-pdf mr-2"></i> Preview Laporan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" onclick="closeModalManual()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0 bg-dark">
                <div id="loader" class="text-center py-5 text-white">
                    <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                    <p>Memuat Dokumen...</p>
                </div>
                <iframe id="pdfFrame" src="" style="width: 100%; height: 75vh; border: none; display: none;" onload="this.style.display='block'; document.getElementById('loader').style.display='none';"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Fungsi Global dengan Fallback jika jQuery Error
    window.previewPdf = function(url) {
        console.log("Request PDF: " + url);
        const frame = document.getElementById('pdfFrame');
        const loader = document.getElementById('loader');
        const modalEl = document.getElementById('previewPdfModal');

        if (frame && modalEl) {
            loader.style.display = 'block';
            frame.style.display = 'none';
            frame.src = url;

            // Trigger Modal (Support jQuery & Vanilla JS)
            if (window.jQuery && typeof jQuery.fn.modal === 'function') {
                $(modalEl).modal('show');
            } else {
                modalEl.classList.add('show');
                modalEl.style.display = 'block';
                document.body.classList.add('modal-open');
            }
        }
    };

    window.closeModalManual = function() {
        const modalEl = document.getElementById('previewPdfModal');
        if (window.jQuery && typeof jQuery.fn.modal === 'function') {
            $(modalEl).modal('hide');
        } else {
            modalEl.classList.remove('show');
            modalEl.style.display = 'none';
            document.body.classList.remove('modal-open');
        }
        document.getElementById('pdfFrame').src = '';
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Auto Update Filter
        const selects = document.querySelectorAll('.select-hybrid');
        selects.forEach(s => {
            s.addEventListener('change', () => document.getElementById('filterForm').submit());
        });

        // Initialize Chart
        const ctx = document.getElementById('yearlyChart');
        if (ctx) {
            new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Reservasi',
                            data: {!! json_encode($chartDataReservations) !!},
                            backgroundColor: 'rgba(23, 162, 184, 0.2)',
                            borderColor: '#17a2b8',
                            borderWidth: 2,
                            borderRadius: 5
                        },
                        {
                            label: 'Selesai Periksa',
                            data: {!! json_encode($chartDataChecked) !!},
                            backgroundColor: '#28a745',
                            borderColor: '#28a745',
                            borderWidth: 1,
                            borderRadius: 5
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    });

    // Cleanup Iframe on modal hide
    $(document).on('hidden.bs.modal', '#previewPdfModal', function () {
        $('#pdfFrame').attr('src', '');
    });
</script>
@endpush