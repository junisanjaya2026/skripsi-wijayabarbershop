@extends('customer.layouts.master')

@section('content')
<!-- Hero Section -->
<section id="home" class="hero">
    <div class="slider" aria-hidden="false">
        <div class="slide active"
             style="background-image: url('https://images.unsplash.com/photo-1706769015484-248bd241945c?q=80&w=1021&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');"
             role="img" aria-label="Slide 1"></div>

        <div class="slide"
             style="background-image: url('https://images.unsplash.com/photo-1626653395376-1f6bae92125e?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');"
             role="img" aria-label="Slide 2"></div>

        <div class="slide"
             style="background-image: url('https://images.unsplash.com/photo-1621747650384-6e944e454a54?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');"
             role="img" aria-label="Slide 3"></div>
    </div>

    <div class="hero-content">
        <h1>Potong Rambut Berkualitas di Wijaya Barber</h1>
        <p>Gaya modern untuk pria yang percaya diri</p>
        <a href="#" class="btn">Booking Sekarang</a>
    </div>

    <div class="slider-nav" role="tablist" aria-label="Slider navigation">
        <button class="nav-btn active" data-index="0" aria-label="Tampilkan slide 1"></button>
        <button class="nav-btn" data-index="1" aria-label="Tampilkan slide 2"></button>
        <button class="nav-btn" data-index="2" aria-label="Tampilkan slide 3"></button>
    </div>


    
<!-- Video Widget Start -->
<div class="modal video-widget-modal" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            <div class="modal-header border-0 py-1 px-2 bg-primary">
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe
                        id="videoModalIframe"
                        src=""
                        title="Video"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                    ></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Video Widget End -->

<style>
    /* Widget video mengambang di pojok kanan bawah, tidak menutupi seluruh layar */
    .video-widget-modal {
        background: transparent;
        pointer-events: none; /* biar area kosong di belakang tetap bisa diklik */
    }

    .video-widget-modal .modal-dialog {
        position: fixed;
        right: 20px;
        bottom: 20px;
        margin: 0;
        width: 720px;
        max-width: calc(100vw - 40px);
        pointer-events: auto; /* widget-nya sendiri tetap bisa diklik */
    }

    /* --- Sembunyikan tombol close secara default --- */
    .video-widget-modal .btn-close {
        opacity: 0;
        transition: opacity 0.25s ease-in-out;
    }

    /* --- Tampilkan tombol close saat modal di-hover --- */
    .video-widget-modal .modal-content:hover .btn-close {
        opacity: 0.8; /* Nilai transparansi saat di-hover (bisa diubah ke 1 untuk full transparan ke padat) */
    }

    /* Tampilkan penuh saat tombol close itu sendiri di-hover */
    .video-widget-modal .btn-close:hover {
        opacity: 1;
    }

    @media (max-width: 480px) {
        .video-widget-modal .modal-dialog {
            width: calc(100vw - 24px);
            right: 12px;
            bottom: 12px;
        }
    }
</style>
</section>



<!-- Services Section -->
<section id="services" class="services">
    <div class="container">
        <h2 class="section-title">Layanan Kami</h2>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon" aria-hidden="true">✂️</div>
                <h3>Haircut</h3>
                <p>Potongan rambut profesional dengan gaya terkini.</p>
            </div>

            <div class="service-card">
                <div class="service-icon" aria-hidden="true">🪒</div>
                <h3>Shaving</h3>
                <p>Cukur jenggot dan kumis yang halus dan presisi.</p>
            </div>

            <div class="service-card">
                <div class="service-icon" aria-hidden="true">💇‍♂️</div>
                <h3>Styling</h3>
                <p>Styling rambut dengan produk premium.</p>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Selectors
    const slides = Array.from(document.querySelectorAll('.slide'));
    const navButtons = Array.from(document.querySelectorAll('.slider-nav .nav-btn'));

    if (!slides.length || !navButtons.length) return;

    let currentSlide = 0;
    let autoSlideInterval = null;
    const AUTO_SLIDE_MS = 5000;

    function showSlide(index) {
        // bounds check
        if (index < 0 || index >= slides.length) return;

        slides.forEach((s, i) => s.classList.toggle('active', i === index));
        navButtons.forEach((b, i) => b.classList.toggle('active', i === index));
        currentSlide = index;
    }

    function nextSlide() {
        showSlide((currentSlide + 1) % slides.length);
    }

    // attach nav button listeners
    navButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            const idx = parseInt(this.getAttribute('data-index'), 10);
            if (!isNaN(idx)) {
                showSlide(idx);
                // reset autoplay timer when user interacts
                restartAutoSlide();
            }
        });
    });

    // autoplay control
    function startAutoSlide() {
        stopAutoSlide();
        autoSlideInterval = setInterval(nextSlide, AUTO_SLIDE_MS);
    }

    function stopAutoSlide() {
        if (autoSlideInterval) {
            clearInterval(autoSlideInterval);
            autoSlideInterval = null;
        }
    }

    function restartAutoSlide() {
        stopAutoSlide();
        startAutoSlide();
    }

    // pause on hover for better UX
    const hero = document.querySelector('.hero');
    if (hero) {
        hero.addEventListener('mouseenter', stopAutoSlide);
        hero.addEventListener('mouseleave', startAutoSlide);
    }

    // initialize
    showSlide(0);
    startAutoSlide();
});
</script>
@endsection
