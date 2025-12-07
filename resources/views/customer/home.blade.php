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
