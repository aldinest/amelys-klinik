<footer class="main-footer {{ auth()->user()->role === 'pasien' ? 'text-sm border-top-0 bg-light' : '' }}" 
        style="{{ auth()->user()->role === 'pasien' ? 'margin-left: 0 !important;' : '' }}">
    
    {{-- Tampilan Desktop --}}
    <div class="d-none d-md-block text-center text-md-left">
        <strong>Copyright &copy; {{ date('Y') }} <a href="#" class="text-primary text-decoration-none">Amelys Klinik</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <span class="text-muted">Layanan Kesehatan Terpercaya</span>
        </div>
    </div>

    {{-- Tampilan Mobile (Diberi padding lebih besar agar tidak mepet konten) --}}
    <div class="d-md-none text-center py-4"> 
        <small class="text-muted d-block">&copy; {{ date('Y') }} Amelys Klinik</small>
        <small class="text-muted">Layanan Kesehatan Terpercaya</small>
    </div>
</footer>

<style>
    @media (max-width: 768px) {
        /* Memberikan jarak antara konten terakhir dengan bagian bawah layar */
        .content-wrapper {
            padding-bottom: 30px; 
        }

        @if(auth()->user()->role === 'pasien')
        .main-footer {
            display: none !important;
        }
        @endif
    }

    /* Penyesuaian khusus untuk footer agar lebih longgar */
    .main-footer {
        padding: 20px 10px;
    }

    @if(auth()->user()->role === 'pasien')
    .main-footer {
        margin-left: 0 !important;
        background-color: transparent !important;
        border-top: 1px solid #eef2f7 !important;
    }
    @endif
</style>