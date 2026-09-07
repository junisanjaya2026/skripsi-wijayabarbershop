<div class="container-fluid fixed-top">
    <div class="container px-0">
        <nav class="navbar navbar-light bg-white navbar-expand-xl">
            
            <!-- Logo -->
            <a href="/" class="navbar-brand">
                <h1 class="text-primary display-6">Wijaya Barber</h1>
            </a>

            <!-- Toggle Mobile -->
            <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars text-primary"></span>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse bg-white" id="navbarCollapse">

       
            <!-- Menu Tengah -->
            <div class="navbar-nav mx-auto">

                <a 
                    href="/"
                    class="nav-item nav-link {{ request()->is('/') ? 'active text-primary fw-bold' : '' }}"
                >
                    Home
                </a>

                <a 
                    href="{{ route('menu') }}"
                    class="nav-item nav-link {{ request()->routeIs('menu') ? 'active text-primary fw-bold' : '' }}"
                >
                    Layanan
                </a>

                <a 
                    href="{{ route('my.orders') }}"
                    class="nav-item nav-link {{ request()->routeIs('my.orders') ? 'active text-primary fw-bold' : '' }}"
                >
                    My Orders
                </a>
                <a 
                    href="#"
                    class="nav-item nav-link {{ request()->is('kontak') ? 'active text-primary fw-bold' : '' }}"
                >
                    Kontak
                </a>
                
            </div>

                <!-- Menu Kanan -->
                <div class="d-flex align-items-center m-3 me-0">

                    <!-- Cart
                    <a href="{{ route('cart') }}" class="position-relative me-4 my-auto">
                        <i class="fa fa-shopping-bag fa-2x"></i>
                    </a> -->


                    <!-- Tempel di dalam <nav> navbar customer, biasanya dekat menu/profile -->
                    <a href="{{ route('cart') }}" class="position-relative text-dark me-3" id="cart-icon-link">
                        <i class="fa fa-shopping-bag fa-2x"></i>
                        <span
                            id="cart-count-badge"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $cartCount > 0 ? '' : 'd-none' }}"
                            style="font-size: 0.65rem;"
                        >
                            {{ $cartCount }}
                        </span>
                    </a>
                    @guest
                        <!-- Belum Login -->
                        <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            Register
                        </a>
                    @endguest

                    @auth
                        <!-- Sudah Login -->
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fa fa-user-circle fa-2x text-primary me-2"></i>

                                <!-- Ganti sesuai field kamu -->
                                <span class="fw-semibold">
                                    {{ auth()->user()->fullname ?? auth()->user()->name }}
                                </span>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        admin dashboard
                                    </a>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endauth

                </div>
            </div>
        </nav>
    </div>
</div>