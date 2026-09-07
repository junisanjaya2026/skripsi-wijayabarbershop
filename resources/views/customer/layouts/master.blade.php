@include('customer.layouts.__header')
<body>

<!-- Spinner Start -->
<div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-grow text-primary" role="status"></div>
</div>
<!-- Spinner End -->

<!-- Navbar start -->
@include('customer.layouts.__navbar')
<!-- Navbar End -->


@yield('content')


<!-- Footer Start -->
@include('customer.layouts.__footer')
<!-- Footer End -->




<!-- Back to Top -->
<a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

<!-- JavaScript Libraries -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/customer/lib/easing/easing.min.js') }}"></script>
<script src="{{ asset('assets/customer/lib/waypoints/waypoints.min.js') }}"></script>
<script src="{{ asset('assets/customer/lib/lightbox/js/lightbox.min.js') }}"></script>
<script src="{{ asset('assets/customer/lib/owlcarousel/owl.carousel.min.js') }}"></script>

<!-- Template Javascript -->
<script src="{{ asset('assets/customer/js/main.js') }}"></script>
@yield('script')

<script>
    document.getElementById('currentYear').textContent = new Date().getFullYear();
</script>

<!-- Video Modal Script -->
<script>
    (function () {
        // Ganti VIDEO_ID dengan ID video YouTube kamu.
        // Contoh: https://www.youtube.com/watch?v=dQw4w9WgXcQ -> ID-nya "dQw4w9WgXcQ"
        const YOUTUBE_VIDEO_ID = '0I79rQCAat4';

        // mute=1 dipakai supaya autoplay HAMPIR PASTI diizinkan browser.
        // Kebijakan autoplay browser modern memblokir video dengan suara
        // yang jalan otomatis tanpa interaksi user terlebih dulu. Kalau mau
        // ada suara, user harus klik unmute manual di dalam player.
        const EMBED_URL = `https://www.youtube.com/embed/${YOUTUBE_VIDEO_ID}?autoplay=1&mute=1&rel=0`;

        const videoModalEl = document.getElementById('videoModal');
        const videoIframe = document.getElementById('videoModalIframe');

        if (!videoModalEl || !videoIframe) return;

        const videoModal = new bootstrap.Modal(videoModalEl);

        // Set src baru setiap kali modal ditampilkan -> video mulai autoplay
        videoModalEl.addEventListener('show.bs.modal', function () {
            videoIframe.src = EMBED_URL;
        });

        // Kosongkan src saat modal ditutup (klik X / backdrop / Esc)
        // -> ini yang benar-benar MENGHENTIKAN video, bukan cuma sembunyikan
        videoModalEl.addEventListener('hidden.bs.modal', function () {
            videoIframe.src = '';
        });

        // Tampilkan widget otomatis saat halaman selesai dimuat
        window.addEventListener('load', function () {
            videoModal.show();
        });

        // Bootstrap modal SELALU mengunci scroll body (nambah class
        // "modal-open" -> overflow: hidden) meski backdrop dimatikan.
        // Karena ini cuma widget kecil di pojok, bukan modal penuh layar,
        // scroll body harus tetap jalan normal -> override manual di sini.
        function unlockBodyScroll() {
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        }

        videoModalEl.addEventListener('show.bs.modal', unlockBodyScroll);
        videoModalEl.addEventListener('shown.bs.modal', unlockBodyScroll);
    })();
</script>
</body>
</html>