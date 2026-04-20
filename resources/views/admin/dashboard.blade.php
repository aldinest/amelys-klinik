@extends('layouts.applte')

@section('content')
<div class="content-wrapper">
    {{-- Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard Admin</h1>
                    <p class="text-muted">Selamat Datang, {{ auth()->user()->name }}</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- Main content --}}
    <section class="content">
        <div class="container-fluid">
            
            {{-- Statistik Berwarna (Style Awal) --}}
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info shadow-sm">
                        <div class="inner">
                            <h3>{{ $countDokter }}</h3>
                            <p>Data Dokter</p>
                        </div>
                        <div class="icon"><i class="fas fa-user-md"></i></div>
                        <a href="{{ route('admin.doctors.index') }}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success shadow-sm">
                        <div class="inner">
                            <h3>{{ $countPasien }}</h3>
                            <p>Data Pasien</p>
                        </div>
                        <div class="icon"><i class="fas fa-users"></i></div>
                        <a href="{{ route('admin.patients.index') }}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning shadow-sm">
                        <div class="inner">
                            <h3>{{ $countAkunUser }}</h3>
                            <p>Data Akun User</p>
                        </div>
                        <div class="icon"><i class="fas fa-users-cog"></i></div>
                        <a href="{{ route('admin.users.index') }}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger shadow-sm">
                        <div class="inner">
                            <h3>{{ $countJadwal }}</h3>
                            <p>Jadwal Dokter</p>
                        </div>
                        <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                        <a href="{{ route('admin.doctor-display-schedules.index') }}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

          {{-- Notes Section dengan Tombol Minimize --}}
          <div class="card card-default shadow-sm">
              <div class="card-header border-bottom">
                  <h3 class="card-title font-weight-bold">
                      <i class="fas fa-project-diagram mr-2 text-primary"></i> SOP Operasional Admin Amelys Klinik
                  </h3>
                  <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-minus"></i>
                      </button>
                  </div>
              </div>
              <div class="card-body">
                  {{-- Header Callout --}}
                  <div class="callout callout-info mb-4">
                      <h5><i class="fas fa-shield-alt mr-2"></i> Keamanan & Sinkronisasi Data</h5>
                      <p>Sistem menggunakan <strong>Single Identity System</strong>. Pastikan email yang digunakan pada Data Master sama dengan akun login agar rekam medis terpanggil dengan benar.</p>
                  </div>

                  {{-- BAGIAN LANGKAH-LANGKAH DETAIL DENGAN ICON KOTAK KECIL --}}
                  <div class="row">
                      {{-- Step 1 --}}
                      <div class="col-md-6 mb-3">
                          <div class="d-flex align-items-center p-3 border rounded bg-light h-100 shadow-none">
                              <div class="bg-info text-white rounded d-flex align-items-center justify-content-center mr-3 shadow-sm" style="width: 60px; height: 60px; flex-shrink: 0;">
                                  <i class="fas fa-database fa-lg"></i>
                              </div>
                              <div>
                                  <h6 class="font-weight-bold mb-1 text-uppercase small text-primary">Langkah 1: Input Data Master</h6>
                                  <p class="small text-muted mb-0">Masukkan profil lengkap di menu <strong>Data Dokter</strong> atau <strong>Data Pasien</strong>. Data ini adalah fondasi utama untuk seluruh riwayat rekam medis pasien di sistem.</p>
                              </div>
                          </div>
                      </div>

                      {{-- Step 2 --}}
                      <div class="col-md-6 mb-3">
                          <div class="d-flex align-items-center p-3 border rounded bg-light h-100 shadow-none">
                              <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center mr-3 shadow-sm" style="width: 60px; height: 60px; flex-shrink: 0;">
                                  <i class="fas fa-user-check fa-lg"></i>
                              </div>
                              <div>
                                  <h6 class="font-weight-bold mb-1 text-uppercase small text-primary">Langkah 2: Aktivasi Akun</h6>
                                  <p class="small text-muted mb-0">Buka halaman <strong>Edit</strong> pada data master tadi, lalu buatkan akun login dengan menggunakan email mereka dan password default: <code class="text-danger">password123</code>.</p>
                              </div>
                          </div>
                      </div>

                      {{-- Step 3 --}}
                      <div class="col-md-6 mb-3">
                          <div class="d-flex align-items-center p-3 border rounded bg-light h-100 shadow-none">
                              <div class="bg-success text-white rounded d-flex align-items-center justify-content-center mr-3 shadow-sm" style="width: 60px; height: 60px; flex-shrink: 0;">
                                  <i class="fas fa-desktop fa-lg"></i>
                              </div>
                              <div>
                                  <h6 class="font-weight-bold mb-1 text-uppercase small text-primary">Langkah 3: Jadwal Display</h6>
                                  <p class="small text-muted mb-0">Update <strong>Jadwal Dokter (Display)</strong> untuk informasi publik di halaman Welcome awal. Ini terpisah dari jadwal reservasi internal yang ada di aplikasi.</p>
                              </div>
                          </div>
                      </div>

                      {{-- Step 4 --}}
                      <div class="col-md-6 mb-3">
                          <div class="d-flex align-items-center p-3 border rounded bg-light h-100 shadow-none">
                              <div class="bg-warning text-dark rounded d-flex align-items-center justify-content-center mr-3 shadow-sm" style="width: 60px; height: 60px; flex-shrink: 0;">
                                  <i class="fas fa-bullhorn fa-lg"></i>
                              </div>
                              <div>
                                  <h6 class="font-weight-bold mb-1 text-uppercase small text-primary">Langkah 4: Pengumuman</h6>
                                  <p class="small text-muted mb-0">Gunakan menu <strong>Info & Pengumuman</strong> untuk menyebarkan informasi penting ke Dashboard Pengurus, Pasien, dan juga halaman Welcome Page.</p>
                              </div>
                          </div>
                      </div>
                  </div>

                  {{-- Detail Tambahan Bawah --}}
                  <div class="row mt-2">
                      <div class="col-md-6">
                          <div class="card card-outline card-info shadow-none border h-100">
                              <div class="card-header"><h3 class="card-title text-sm font-weight-bold">Ketentuan Manajemen User</h3></div>
                              <div class="card-body py-2 px-3 small text-muted">
                                  <ul class="mb-0 pl-3">
                                      <li>Menu <strong>Manajemen User</strong> hanya untuk menambahkan Admin atau Pegurus baru.</li>
                                      <li>Dilarang membuat akun Dokter/Pasien langsung di Manajemen User agar relasi database tidak rusak.</li>
                                      <li>Edit Akun User dilakukan di sini jika hanya ingin mengubah password atau status aktif.</li>
                                  </ul>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="card card-outline card-success shadow-none border h-100">
                              <div class="card-header"><h3 class="card-title text-sm font-weight-bold">Alur Informasi Jadwal</h3></div>
                              <div class="card-body py-2 px-3 small text-muted">
                                  <ul class="mb-0 pl-3">
                                      <li><strong>Jadwal Internal:</strong> Digunakan untuk sistem reservasi pasien di aplikasi.</li>
                                      <li><strong>Jadwal Display:</strong> Murni untuk tampilan di Welcome Page agar pengunjung tahu jadwal praktek.</li>
                                      <li>Pastikan Data Dokter sudah ada sebelum mengatur jadwal display.</li>
                                  </ul>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="card-footer py-2 bg-white d-flex justify-content-between">
                  <span class="text-primary small font-weight-bold">By Admin ALDINEST</span>
              </div>
          </div>

        </div>
    </section>
</div>

<style>
    .text-wrap { white-space: normal !important; }
    .italic { font-style: italic; }
</style>
@endsection