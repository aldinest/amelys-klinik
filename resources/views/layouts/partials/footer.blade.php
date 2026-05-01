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

    {{-- Tampilan Mobile (Sangat Ringkas atau Hidden) --}}
    <div class="d-md-none text-center pb-5"> 
        <small class="text-muted">&copy; {{ date('Y') }} Amelys Klinik</small>
    </div>
</footer>

<style>
    @media (max-width: 768px) {
        /* Jika user adalah pasien, kita sembunyikan footer total di mobile 
           karena sudah ada Bottom Navigation */
        @if(auth()->user()->role === 'pasien')
        .main-footer {
            display: none !important;
        }
        @endif
    }

    /* Penyesuaian margin jika sidebar off */
    @if(auth()->user()->role === 'pasien')
    .main-footer {
        margin-left: 0 !important;
        padding: 15px 5%;
        background-color: transparent !important;
        border-top: 1px solid #eef2f7 !important;
    }
    @endif
</style>