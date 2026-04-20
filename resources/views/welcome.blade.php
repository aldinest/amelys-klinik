<!DOCTYPE html>
<html lang="id">
<head>

     <title>Amelys Klinik</title>
     <link rel="icon" type="image/png" href="{{ asset('dist/img/logoamelys.png') }}">

     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=Edge">
     <meta name="description" content="">
     <meta name="keywords" content="">
     <meta name="author" content="Tooplate">
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

     <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
     <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
     <link rel="stylesheet" href="{{ asset('css/animate.css') }}">
     <link rel="stylesheet" href="{{ asset('css/owl.carousel.css') }}">
     <link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css') }}">

     <link rel="stylesheet" href="{{ asset('css/tooplate-style.css') }}">

     <style>
                /* 1. BRANDING & THEME */
        :root {
            --primary-blue: #007bff;
            --dark-blue: #0056b3;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
        }
        .navbar-brand .logo {
            height: 40px;
            width: auto;
            margin-right: 10px;
        }
        .navbar-brand .brand-text {
            font-size: 20px;
            font-weight: 700;
            color: #454545;
        }
        
        /* Override warna ke Biru */
        :root { --primary-blue: #007bff; }
        header span i, .section-title h2::after, .about-info i, 
        .owl-theme .owl-controls .owl-page.active span,
        .navbar-default .navbar-nav li a:hover,
        .appointment-btn a { color: var(--primary-blue) !important; }
        
        .section-btn, .appointment-btn a, .btn-blue {
            background: var(--primary-blue) !important;
            border-color: var(--primary-blue) !important;
            color: #fff !important;
        }
        .spinner-rotate { border-top-color: var(--primary-blue) !important; }
     </style>

</head>
<body id="top" data-spy="scroll" data-target=".navbar-collapse" data-offset="50">

     <section class="preloader">
          <div class="spinner">
               <span class="spinner-rotate"></span>
          </div>
     </section>


     <header>
          <div class="container">
               <div class="row">

                    <div class="col-md-4 col-sm-5">
                         <p>Selamat Datang di Amelys Klinik</p>
                    </div>
                         
                    <div class="col-md-8 col-sm-7 text-align-right">
                        <span class="phone-icon"><i class="fa fa-phone"></i> +62 823-3548-3854</span>
                        <span class="email-icon"><i class="fa fa-envelope-o"></i> klinikapotekamelys118@gmail.com</span>
                    </div>

               </div>
          </div>
     </header>


     <section class="navbar navbar-default navbar-static-top" role="navigation">
          <div class="container">

               <div class="navbar-header">
                <button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon icon-bar"></span>
                    <span class="icon icon-bar"></span>
                    <span class="icon icon-bar"></span>
                </button>
                <a href="{{ url('/') }}" class="navbar-brand">
                    <img src="{{ asset('dist/img/logoamelys.png') }}" alt="Logo" class="logo">
                    <span class="brand-text">AMELYS KLINIK</span>
                </a>
            </div>

               <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                         <li><a href="#top" class="smoothScroll">Beranda</a></li>
                         <li><a href="#about" class="smoothScroll">Tentang Kami</a></li>
                         <li><a href="#team" class="smoothScroll">Jadwal Dokter</a></li>
                         <li><a href="#news" class="smoothScroll">Info Terbaru</a></li>
                         <li><a href="#google-map" class="smoothScroll">Maps</a></li>
                         <li class="appointment-btn"><a href="{{ route('login') }}">Login Reservasi</a></li>
                    </ul>
               </div>

          </div>
     </section>


     <section id="home" class="slider" data-stellar-background-ratio="0.5">
          <div class="container">
               <div class="row">

                         <div class="owl-carousel owl-theme">
                              <div class="item item-first">
                                   <div class="caption">
                                        <div class="col-md-offset-1 col-md-10">
                                             <h3>Mari buat hidup Anda lebih bahagia</h3>
                                             <h1>Hidup Sehat</h1>
                                             <a href="{{ route('login') }}" class="section-btn btn btn-default smoothScroll">Login Reservasi</a>
                                        </div>
                                   </div>
                              </div>

                              <div class="item item-second">
                                   <div class="caption">
                                        <div class="col-md-offset-1 col-md-10">
                                             <h3>Layanan medis terpercaya</h3>
                                             <h1>Gaya Hidup Baru</h1>
                                             <a href="#about" class="section-btn btn btn-default btn-gray smoothScroll">Lebih Lanjut</a>
                                        </div>
                                   </div>
                              </div>

                              <div class="item item-third">
                                   <div class="caption">
                                        <div class="col-md-offset-1 col-md-10">
                                             <h3>Kesehatan adalah prioritas utama</h3>
                                             <h1>Manfaat Kesehatan Anda</h1>
                                             <a href="#news" class="section-btn btn btn-default btn-blue smoothScroll">Baca Info Terbaru</a>
                                        </div>
                                   </div>
                              </div>
                         </div>

               </div>
          </div>
     </section>

     <section id="about">
          <div class="container">
               <div class="row">

                    <div class="col-md-6 col-sm-6">
                         <div class="about-info">
                              <h2 class="wow fadeInUp" data-wow-delay="0.6s">Selamat Datang di Amelys Klinik</h2>
                              <div class="wow fadeInUp" data-wow-delay="0.8s">
                                   <p>Kami berdedikasi untuk memberikan pelayanan kesehatan terbaik dengan fasilitas modern dan tenaga medis profesional.</p>
                                   <p>Kesehatan Anda adalah komitmen kami. Kami menyediakan berbagai layanan konsultasi dan perawatan untuk mendukung pemulihan Anda.</p>
                              </div>

                         </div>
                    </div>
                    
               </div>
          </div>
     </section>


    <section id="team" style="background: #ffffff; padding: 60px 0;">
        <div class="container">
            
            <div class="section-title" style="text-align: center; margin-bottom: 50px;">
                <h2 style="font-size: 30px; font-weight: 700; color: #333;">Jadwal Praktik Dokter Amelys</h2>
                <div style="width: 80px; height: 4px; background: var(--primary-blue); margin: 15px auto 0;"></div>
            </div>

            <div class="table-responsive schedule-wrapper">
                <table class="table schedule-table">
                    <thead>
                        <tr>
                            <th width="35%">Nama Dokter</th>
                            <th width="20%">Spesialis</th>
                            <th width="25%">Hari</th>
                            <th width="20%" class="text-right">Jam Praktik</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $doctorName => $specialties)
                            @php
                                $totalRows = $specialties->sum(fn($s) => $s->count());
                            @endphp

                            @foreach($specialties as $specialty => $timeGroups)
                                @foreach($timeGroups as $timeRange => $dayString)
                                    <tr>
                                        @if($loop->parent->first && $loop->first)
                                            <td rowspan="{{ $totalRows }}" class="doc-name-cell">
                                                {{ $doctorName }} {{-- Sesuai inputan admin --}}
                                            </td>
                                        @endif

                                        @if($loop->first)
                                            <td rowspan="{{ $timeGroups->count() }}" class="spec-name-cell">
                                                {{ $specialty ?? 'Umum' }} {{-- Sesuai inputan admin --}}
                                            </td>
                                        @endif

                                        <td class="day-cell">{{ $dayString }}</td>
                                        <td class="time-cell text-right">{{ $timeRange }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="4" class="text-center" style="padding: 50px !important; color: #999;">
                                    Belum ada jadwal dokter tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <style>
        /* Wrapper dengan bayangan halus agar lepas dari BG, tapi tetap dominan putih */
        .schedule-wrapper {
            background: #ffffff;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid #eee;
        }

        .schedule-table thead tr {
            border-top: 4px solid var(--primary-blue);
            background-color: #f8f9fa;
        }

        .schedule-table thead th {
            padding: 18px 15px !important;
            color: #444 !important;
            font-weight: 700 !important;
            border: none !important;
        }

        .schedule-table td {
            padding: 18px 15px !important;
            vertical-align: middle !important;
            border-bottom: 1px solid #f0f0f0 !important;
        }

        .doc-name-cell {
            font-weight: 700;
            color: #222;
            background-color: #ffffff !important;
        }

        .spec-name-cell {
            color: var(--primary-blue);
            font-weight: 600;
        }

        .day-cell { color: #555; }
        
        .time-cell {
            font-weight: 700;
            color: #333;
        }

        .text-right { text-align: right !important; }
    </style>

     <section id="news" data-stellar-background-ratio="2.5">
     <div class="container">
          <div class="row">
               <div class="col-md-12 col-sm-12">
                    <div class="section-title wow fadeInUp" data-wow-delay="0.1s">
                         <h2>Info Terbaru</h2>
                    </div>
               </div>

               @forelse($news as $item)
               <div class="col-md-4 col-sm-6">
                    <div class="news-thumb wow fadeInUp" data-wow-delay="0.4s" style="border: 1px solid #eee; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                         <div class="news-info">
                         <span style="color: #a5a5a5; font-size: 12px;">
                              {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d F Y') }}
                         </span>
                         
                         <h3 style="margin-top: 10px;">
                              <a href="#" style="color: #333; text-decoration: none;">{{ $item->title }}</a>
                         </h3>
                         
                         <p style="color: #777;">{{ Str::limit($item->description, 120) }}</p>
                         
                         <div class="author" style="margin-top: 20px; border-top: 1px dashed #eee; padding-top: 15px;">
                              <div class="author-info">
                                   <h5 style="margin: 0; font-weight: bold;">{{ $item->author_name }}</h5>
                                   <p style="margin: 0; font-size: 12px; color: #39260d;">{{ $item->author_role }}</p>
                              </div>
                         </div>
                         </div>
                    </div>
               </div>
               @empty
               <div class="col-md-12 text-center">
                    <p>Belum ada informasi terbaru.</p>
               </div>
               @endforelse

          </div>
     </div>
     </section>

     <section id="google-map">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d316.4702061124213!2d111.46994462994563!3d-7.878259367276554!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e799f8bfa569b17%3A0x281e7821b4a9b844!2sApotek%20%26%20Praktek%20Dokter%20Amelys!5e0!3m2!1sid!2sus!4v1773306554147!5m2!1sid!2sus" 
            width="100%" 
            height="350" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
     </section>          


     <footer data-stellar-background-ratio="5">
          <div class="container">
               <div class="row">

                    <div class="col-md-4 col-sm-4">
                         <div class="footer-thumb"> 
                              <h4 class="wow fadeInUp" data-wow-delay="0.4s">Info Kontak</h4>
                              <p>Kami siap melayani kebutuhan kesehatan Anda setiap hari. Hubungi kami melalui kanal di bawah ini.</p>

                              <div class="contact-info">
                                   <p><i class="fa fa-phone"></i> +62 823-3548-3854</p>
                                   <p><i class="fa fa-envelope-o"></i> <a href="mailto:klinikapotekamelys118@gmail.com">klinikapotekamelys118@gmail.com</a></p>
                              </div>
                         </div>
                    </div>

                    <div class="col-md-4 col-sm-4"> 
                         <div class="footer-thumb">
                              <div class="opening-hours">
                                   <h4 class="wow fadeInUp" data-wow-delay="0.4s">Jam Buka</h4>
                                   <p>Senin - Sabtu <span>07:30 - 21:00</span></p>
                                   <p>Sabtu <span>09:00 - 16:00</span></p>
                              </div> 

                              <ul class="social-icon">
                                   <li><a href="https://www.facebook.com/share/1KKhW173GQ/" class="fa fa-facebook-square"></a></li>
                                   <li><a href="https://wa.me/6282335483854" class="fa fa-whatsapp"></a></li>
                                   <li><a href="https://www.instagram.com/amelyspraktekdokter?igsh=MzMwc2JqcXg0ZnJw" class="fa fa-instagram"></a></li>
                              </ul>
                         </div>
                    </div>

                    <div class="col-md-12 col-sm-12 border-top">
                         <div class="col-md-4 col-sm-6">
                              <div class="copyright-text"> 
                                   <p>Copyright © 2026 Amelys Klinik 
                                   | Desain: <a href="http://www.tooplate.com" target="_parent">Tooplate</a></p>
                              </div>
                         </div>
                         <!-- <div class="col-md-6 col-sm-6">
                              <div class="footer-link"> 
                                   <a href="#">Tes Laboratorium</a>
                                   <a href="#">Departemen</a>
                                   <a href="#">Asuransi</a>
                                   <a href="#">Karir</a>
                              </div>
                         </div> -->
                         <div class="col-md-2 col-sm-2 text-align-center">
                              <div class="angle-up-btn"> 
                                  <a href="#top" class="smoothScroll wow fadeInUp" data-wow-delay="1.2s"><i class="fa fa-angle-up"></i></a>
                              </div>
                         </div>   
                    </div>
                    
               </div>
          </div>
     </footer>

     <script src="{{ asset('js/jquery.js') }}"></script>
     <script src="{{ asset('js/bootstrap.min.js') }}"></script>
     <script src="{{ asset('js/jquery.sticky.js') }}"></script>
     <script src="{{ asset('js/jquery.stellar.min.js') }}"></script>
     <script src="{{ asset('js/wow.min.js') }}"></script>
     <script src="{{ asset('js/smoothscroll.js') }}"></script>
     <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
     <script src="{{ asset('js/custom.js') }}"></script>

</body>
</html>