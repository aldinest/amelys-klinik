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

     <!-- Main css -->
     <link rel="stylesheet" href="{{ asset('css/tooplate-style.css') }}">

     <style>
       /* --- Remake Section Selamat Datang --- */
#about {
    padding: 100px 0;
    position: relative;
}

/* Default untuk Mobile & Tablet (Transparan Putih) */
.about-content {
    background: rgba(255, 255, 255, 0.85); /* Background transparan sesuai ralat lo */
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

/* Khusus Mode Desktop (Layar Lebar) */
@media (min-width: 992px) {
    .about-content {
        background: transparent; /* Hilangkan background sesuai foto desktop */
        box-shadow: none;
        padding-left: 0;
    }
}

/* --- Remake Section News (Info Terbaru) --- */
.news-card {
    background: #ffffff;
    border-radius: 15px; /* Sudut melengkung halus */
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05); /* Shadow tipis seperti foto */
    border: 1px solid #f0f0f0;
    /* Menjaga proporsi agar tidak lonjong di desktop */
    display: flex;
    flex-direction: column;
    min-height: 300px;
}

.news-card span {
    color: #bdc3c7;
    font-size: 14px;
    display: block;
    margin-bottom: 10px;
}

.news-card h3 {
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
    color: #333;
    line-height: 1.4;
}
        :root {
            --primary-blue: #007bff;
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
        /* Custom Biru Amelys */
        header span i, .section-title h2::after, .about-info i, 
        .owl-theme .owl-controls .owl-page.active span,
        .navbar-default .navbar-nav li a:hover,
        .appointment-btn a { color: var(--primary-blue) !important; }
        
        .section-btn, .appointment-btn a {
            background: var(--primary-blue) !important;
            border-color: var(--primary-blue) !important;
            color: #fff !important;
        }

        /* --- AMELYS SCHEDULE SYSTEM --- */
        .schedule-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 25px;
        }

        .doctor-card {
            background: #fff;
            border-radius: 18px;
            border: 1px solid #f0f0f0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            overflow: hidden;
            transition: 0.3s;
        }

        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        /* Header: Nama Dokter & Spesialis */
        .doctor-header {
            background: #fcfdfe;
            padding: 22px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-bottom: 1px solid #f8f9fa;
        }

        .doctor-header i {
            font-size: 22px;
            background: #eef6ff;
            color: var(--primary-blue);
            padding: 12px;
            border-radius: 14px;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .doc-name {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
            color: #2c3e50;
            letter-spacing: -0.3px;
        }

        .doc-specialty {
            font-size: 11px;
            color: #95a5a6;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1.2px;
            margin-top: 3px;
        }

        /* Body: Penataan Jadwal */
        .doctor-body {
            padding: 15px 22px 22px 22px;
        }

        .schedule-header-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .schedule-header-label span {
            font-size: 10px;
            font-weight: 800;
            color: #bdc3c7;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .schedule-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px dotted #eee;
        }

        .schedule-row:last-child {
            border-bottom: none;
        }

        .day-text {
            font-size: 14px;
            font-weight: 700;
            color: #454545;
            max-width: 60%;
        }

        .time-text {
            font-size: 15px;
            font-weight: 800;
            color: var(--primary-blue);
            text-align: right;
            background: #f0f7ff;
            padding: 6px 12px;
            border-radius: 8px;
            min-width: 110px;
        }

        /* Mobile Tweak */
        @media (max-width: 767px) {
            .schedule-container { grid-template-columns: 1fr; }
            .doc-name { font-size: 16px; }
            .day-text { font-size: 13px; }
            .time-text { font-size: 13px; min-width: 100px; }
        }

        /* --- PERBAIKAN BACKGROUND & ALIGNMENT --- */
        /* Section Jadwal Dokter */
        #team {
            background: linear-gradient(to bottom, #f9fbff 0%, #f4f7fc 100%); /* Gradasi halus kebiruan */
            padding: 80px 0;
        }

        /* Memastikan Judul Section Rata Tengah */
        .section-title.text-center {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title.text-center h2 {
            position: relative;
            display: inline-block;
            padding-bottom: 20px;
        }

        /* Menggeser garis bawah h2 ke tengah */
        .section-title.text-center h2::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            margin-left: -30px; /* Setengah dari lebar garis (60px) */
            width: 60px;
            height: 3px;
            background: var(--primary-blue);
        }

        /* Section Info Terbaru (Berita) juga diberi background beda biar tidak monoton */
        #news {
            background: #ffffff;
        }

     </style>

</head>
<body id="top" data-spy="scroll" data-target=".navbar-collapse" data-offset="50">

     <!-- PRE LOADER -->
     <section class="preloader">
          <div class="spinner">
               <span class="spinner-rotate"></span>
          </div>
     </section>


     <!-- HEADER -->
     <header>
          <div class="container">
               <div class="row">
                    <div class="col-md-4 col-sm-5">
                         <p>Selamat Datang di Amelys Klinik</p>
                    </div>
                         
                    <div class="col-md-8 col-sm-7 text-align-right">
                        <span class="phone-icon"><i class="fa fa-phone"></i> +62 823-3548-3854</span>
                        <span class="email-icon"><i class="fa fa-envelope-o"></i> <a href="mailto:klinikapotekamelys118@gmail.com">klinikapotekamelys118@gmail.com</a></span>
                    </div>
               </div>
          </div>
     </header>


     <!-- MENU -->
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

               <!-- MENU LINKS -->
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


     <!-- HOME / SLIDER -->
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


     <!-- ABOUT -->
    <section id="about">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-sm-12">
                    <div class="about-content wow fadeInUp" data-wow-delay="0.6s">
                        <h2 style="font-weight: 800; font-size: 38px;">Selamat Datang di Amelys Klinik</h2>
                        <p>Kami berdedikasi untuk memberikan pelayanan kesehatan terbaik dengan fasilitas modern dan tenaga medis profesional.</p>
                        <p>Kesehatan Anda adalah komitmen kami. Kami menyediakan berbagai layanan kesehatan untuk mendukung pemulihan Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

        <!-- TEAM / JADWAL DOKTER -->
        <section id="team" data-stellar-background-ratio="1">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <!-- Tambahkan class text-center di sini -->
                        <div class="section-title wow fadeInUp text-center" data-wow-delay="0.1s">
                            <h2>Jadwal Praktik Dokter</h2>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="schedule-container wow fadeInUp" data-wow-delay="0.3s">
                            @forelse($schedules as $doctorName => $specialties)
                                <!-- Satu Kartu per Dokter -->
                                <div class="doctor-card">
                                    <div class="doctor-header">
                                        <i class="fa fa-user-md"></i>
                                        <div>
                                            <h4 class="doc-name">{{ $doctorName }}</h4>
                                            @foreach($specialties as $specialty => $timeGroups)
                                                <span class="doc-specialty">{{ $specialty ?? 'Umum' }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <div class="doctor-body">
                                        <!-- Header Label Manual -->
                                        <div class="schedule-header-label">
                                            <span>HARI</span>
                                            <span>JAM PRAKTIK</span>
                                        </div>

                                        @foreach($specialties as $specialty => $timeGroups)
                                            @foreach($timeGroups as $timeRange => $dayString)
                                                <div class="schedule-row">
                                                    <div class="day-text">{{ $dayString }}</div>
                                                    <div class="time-text">{{ $timeRange }}</div>
                                                </div>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="text-center p-5">
                                    <p>Belum ada jadwal dokter tersedia.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>


     <!-- NEWS -->
    <section id="news">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="section-title">
                        <h2>Info Terbaru</h2>
                    </div>
                </div>
                @forelse($news as $item)
                    <div class="col-md-4 col-sm-12"> <!-- col-md-4 menjaga card tidak lonjong di desktop -->
                        <div class="news-card wow fadeInUp" data-wow-delay="0.4s">
                            <span>{{ \Carbon\Carbon::parse($item->date)->translatedFormat('d F Y') }}</span>
                            <h3>{{ $item->title }}</h3>
                            <p>{{ Str::limit($item->description, 150) }}</p>
                            <div style="margin-top:auto; padding-top:15px; border-top:1px dotted #eee;">
                                <strong>{{ $item->author_name }}</strong><br>
                                <small class="text-muted">{{ $item->author_role }}</small>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center">Belum ada info.</p>
                @endforelse
            </div>
        </div>
    </section>


     <!-- GOOGLE MAP -->
     <section id="google-map">
          <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d316.4702061124213!2d111.46994462994563!3d-7.878259367276554!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e799f8bfa569b17%3A0x281e7821b4a9b844!2sApotek%20%26%20Praktek%20Dokter%20Amelys!5e0!3m2!1sid!2sus!4v1773306554147!5m2!1sid!2sus" width="100%" height="350" frameborder="0" style="border:0" allowfullscreen></iframe>
     </section>            


     <!-- FOOTER (Isi asli Tooplate) -->
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
                                   <p>Minggu <span>09:00 - 16:00</span></p>
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
                                   <p>Copyright &copy; 2026 Amelys Klinik 
                                   | Desain: <a href="http://www.tooplate.com" target="_parent">Tooplate</a></p>
                              </div>
                         </div>
                         <div class="col-md-2 col-sm-2 text-align-center">
                              <div class="angle-up-btn"> 
                                  <a href="#top" class="smoothScroll wow fadeInUp" data-wow-delay="1.2s"><i class="fa fa-angle-up"></i></a>
                              </div>
                         </div>   
                    </div>
                    
               </div>
          </div>
     </footer>

     <!-- SCRIPTS -->
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