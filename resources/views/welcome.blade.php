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
    :root { --primary-blue: #007bff; }

    /* --- Sections & Global Fixes --- */
    #about { padding: 100px 0; position: relative; }
    #team { background: linear-gradient(to bottom, #f9fbff 0%, #f4f7fc 100%); padding: 80px 0; }
    #news { background: #ffffff; padding: 80px 0; }

    /* About Section */
    .about-content { background: rgba(255, 255, 255, 0.85); padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    @media (min-width: 992px) { .about-content { background: transparent; box-shadow: none; padding-left: 0; } }

    /* --- News Section Cards --- */
    .news-card {
        display: flex;
        flex-direction: column;
        background: #ffffff;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0;
        height: 100%; 
    }
    .news-card span { color: #bdc3c7; font-size: 14px; display: block; margin-bottom: 10px; }
    .news-card h3 { font-family: 'Poppins', sans-serif; font-weight: 700; color: #333; line-height: 1.4; margin-bottom: 15px; }
    .news-card p { flex-grow: 1; color: #666; margin-bottom: 20px; }
    .news-card div[style*="margin-top:auto"] { margin-top: auto !important; padding-top: 15px; border-top: 1px dotted #eee; }

    /* --- Schedule Section --- */
    .schedule-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; }
    .doctor-card { background: #fff; border-radius: 18px; border: 1px solid #f0f0f0; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; }
    .doctor-header { background: #fcfdfe; padding: 22px; display: flex; align-items: center; gap: 15px; border-bottom: 1px solid #f8f9fa; }
    .doctor-header i { font-size: 22px; background: #eef6ff; color: var(--primary-blue); padding: 12px; border-radius: 14px; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; }
    .doc-name { margin: 0; font-size: 18px; font-weight: 800; color: #2c3e50; }
    .doc-specialty { font-size: 11px; color: #95a5a6; text-transform: uppercase; font-weight: 700; display: block; margin-top: 3px; }
    .doctor-body { padding: 15px 22px 22px 22px; }
    .schedule-header-label { display: flex; justify-content: space-between; margin-bottom: 10px; }
    .schedule-header-label span { font-size: 10px; font-weight: 800; color: #bdc3c7; text-transform: uppercase; letter-spacing: 1.5px; }
    .schedule-row { display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px dotted #eee; }
    .time-text { font-size: 13px; font-weight: 800; color: var(--primary-blue); background: #f0f7ff; padding: 6px 10px; border-radius: 8px; }

    /* --- Navbar & Branding --- */
    .navbar-brand { display: flex; align-items: center; }
    .navbar-brand .logo { height: 40px; width: auto; margin-right: 10px; }
    .navbar-brand .brand-text { font-size: 20px; font-weight: 700; color: #454545; }
    .section-title.text-center h2::after { content: ""; position: absolute; bottom: 0; left: 50%; margin-left: -30px; width: 60px; height: 3px; background: var(--primary-blue); }
    
    header span i, .appointment-btn a { color: var(--primary-blue) !important; }
    .section-btn, .appointment-btn a { background: var(--primary-blue) !important; border-color: var(--primary-blue) !important; color: #fff !important; }

    @media (max-width: 767px) {
        .schedule-container { grid-template-columns: 1fr; }
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

     @if(!empty($maintenanceActive) && $maintenanceActive)
         <section class="alert alert-warning text-center" style="margin: 0; border-radius: 0;">
             <div class="container">
                 <strong>Perhatian:</strong> {{ $maintenanceMessage ?? 'Sistem sedang dalam perawatan ringan. Beberapa fitur mungkin tidak tersedia sementara.' }}
             </div>
         </section>
     @endif

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
<section id="news" style="padding: 80px 0;">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center" style="margin-bottom: 50px;">
                <div class="section-title">
                    <h2>Info Terbaru</h2>
                </div>
            </div>

            @forelse($news as $index => $item)
                <div class="col-md-8 col-md-offset-2 col-sm-12">
                    <div class="news-card wow fadeInUp" data-wow-delay="0.4s" style="padding: 40px; background: #fff; border-radius: 20px; border: 1px solid #f0f0f0; box-shadow: 0 10px 30px rgba(0,0,0,0.05); margin-bottom: 40px;">
                        
                        <span style="color: #007bff; font-weight: 700; font-size: 14px;">
                            {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d F Y') }}
                        </span>
                        <h3 style="font-size: 24px; font-weight: 700; margin: 15px 0;">{{ $item->title }}</h3>

                        <!-- Teks Singkat (Yang muncul pertama) -->
                        <div id="short-{{$index}}">
                            <p style="color: #555; line-height: 1.8;">{{ Str::limit($item->description, 120) }}</p>
                            <button onclick="toggleNews({{$index}})" style="background: none; border: none; color: #007bff; font-weight: 600; cursor: pointer; padding: 0;">
                                Baca Lanjutan &darr;
                            </button>
                        </div>

                        <!-- Teks Lengkap (Disembunyikan) -->
                        <div id="full-{{$index}}" style="display: none;">
                            <p style="color: #555; line-height: 1.8;">{!! nl2br(e($item->description)) !!}</p>
                            <button onclick="toggleNews({{$index}})" style="background: none; border: none; color: #d9534f; font-weight: 600; cursor: pointer; padding: 0;">
                                Tutup &uarr;
                            </button>
                        </div>

                        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
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
    <section id="google-map" style="padding-top: 80px;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center" style="margin-bottom: 30px;">
                    <div class="section-title">
                        <h2>Lokasi Kami</h2>
                        <p style="color: #666; font-size: 16px; margin-top: 10px;">
                            Kunjungi kami di Apotek & Praktek Dokter Amelys
                        </p>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="wow fadeInUp" data-wow-delay="0.4s" style="border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d316.4702061124213!2d111.46994462994563!3d-7.878259367276554!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e799f8bfa569b17%3A0x281e7821b4a9b844!2sApotek%20%26%20Praktek%20Dokter%20Amelys!5e0!3m2!1sid!2sus!4v1773306554147!5m2!1sid!2sus" 
                            width="100%" 
                            height="400" 
                            frameborder="0" 
                            style="border:0; display: block;" 
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
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

     <!-- Script untuk buka-tutup News -->
    <script>
    function toggleNews(id) {
        var shortText = document.getElementById('short-' + id);
        var fullText = document.getElementById('full-' + id);
        
        if (fullText.style.display === "none") {
            shortText.style.display = "none";
            fullText.style.display = "block";
        } else {
            shortText.style.display = "block";
            fullText.style.display = "none";
        }
    }
    </script>

</body>
</html>