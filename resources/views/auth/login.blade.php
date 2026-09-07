<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Wijaya barber - Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet"/>
  <style>
    :root {
      --gold: #81C408;
      --gold-light: #4DC97A;
      --dark: #F7FAF8;
      --dark-2: #EEF5F1;
      --dark-3: #E4EEE9;
      --dark-4: #C8DDD2;
      --cream: #1A2E23;
      --muted: #7A9B8A;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'DM Sans', sans-serif;
      background-color: var(--dark);
      color: var(--cream);
      min-height: 100vh;
      overflow: hidden;
    }

    /* Grain texture overlay */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='1'/%3E%3C/svg%3E");
      opacity: 0.02;
      pointer-events: none;
      z-index: 100;
    }

    .display-font { font-family: 'Bebas Neue', sans-serif; }

    /* Gold shimmer animation */
    @keyframes shimmer {
      0% { background-position: -200% center; }
      100% { background-position: 200% center; }
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(24px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes lineExpand {
      from { width: 0; }
      to { width: 100%; }
    }

    @keyframes rotateSlow {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    @keyframes floatBg {
      0%, 100% { transform: translateY(0) scale(1); }
      50% { transform: translateY(-20px) scale(1.02); }
    }

    .gold-text {
      background: linear-gradient(90deg, var(--gold) 0%, var(--gold-light) 40%, var(--gold) 60%, var(--gold-light) 100%);
      background-size: 200% auto;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      animation: shimmer 4s linear infinite;
    }

    .animate-fade-up { animation: fadeUp 0.7s ease forwards; }
    .delay-1 { animation-delay: 0.1s; opacity: 0; }
    .delay-2 { animation-delay: 0.2s; opacity: 0; }
    .delay-3 { animation-delay: 0.35s; opacity: 0; }
    .delay-4 { animation-delay: 0.5s; opacity: 0; }
    .delay-5 { animation-delay: 0.65s; opacity: 0; }
    .delay-6 { animation-delay: 0.8s; opacity: 0; }

    /* Input styling */
    .input-field {
      background: #ffffff;
      border: 1px solid var(--dark-4);
      color: var(--cream);
      transition: all 0.3s ease;
      outline: none;
    }
    .input-field::placeholder { color: var(--muted); }
    .input-field:focus {
      border-color: var(--gold);
      background: #ffffff;
      box-shadow: 0 0 0 3px rgba(46,158,91,0.12);
    }

    /* Button */
    .btn-gold {
      background: linear-gradient(135deg, #81C408 0%, #81C408 50%, #81C408 100%);
      background-size: 200% auto;
      color: #ffffff;
      font-weight: 500;
      letter-spacing: 0.1em;
      transition: all 0.4s ease;
      position: relative;
      overflow: hidden;
    }
    .btn-gold:hover {
      background-position: right center;
      box-shadow: 0 8px 30px rgba(46,158,91,0.35);
      transform: translateY(-1px);
    }
    .btn-gold:active { transform: translateY(0); }
    .btn-gold::after {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(255,255,255,0);
      transition: background 0.2s;
    }
    .btn-gold:hover::after { background: rgba(255,255,255,0.06); }

    /* Divider line */
    .divider-line {
      height: 1px;
      background: linear-gradient(90deg, transparent, var(--gold), transparent);
    }

    /* Scissor icon rotation on hover */
    .scissor-icon { transition: transform 0.5s ease; }
    .scissor-icon:hover { transform: rotate(45deg); }

    /* Background illustration */
    .bg-barber-pole {
      position: absolute;
      width: 4px;
      border-radius: 2px;
      background: repeating-linear-gradient(
        180deg,
        #81C408 0px, #81C408 12px,
        var(--dark-4) 12px, var(--dark-4) 24px
      );
    }

    /* Geometric circle accent */
    .circle-accent {
      border: 1px solid rgba(46,158,91,0.2);
      border-radius: 50%;
      animation: rotateSlow 20s linear infinite;
    }

    /* Left panel BG */
    .left-bg {
      background:
        radial-gradient(ellipse at 30% 50%, rgba(46,158,91,0.12) 0%, transparent 65%),
        radial-gradient(ellipse at 80% 20%, rgba(46,158,91,0.06) 0%, transparent 50%),
        var(--dark-2);
      animation: floatBg 8s ease-in-out infinite;
    }

    /* Checkmark */
    .feature-dot {
      width: 6px; height: 6px;
      background: var(--gold);
      border-radius: 50%;
      flex-shrink: 0;
      margin-top: 7px;
    }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: var(--dark); }
    ::-webkit-scrollbar-thumb { background: var(--gold); border-radius: 2px; }

    .stat-num {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 2.5rem;
      line-height: 1;
      color: #81C408;
    }
  </style>
</head>
<body class="flex items-stretch min-h-screen">

  <!-- ======================== LEFT PANEL ======================== -->
  <div class="hidden lg:flex flex-col justify-between relative overflow-hidden left-bg w-5/12 px-14 py-12">

    <!-- Decorative circles -->
    <div class="circle-accent absolute -top-32 -left-32 w-80 h-80"></div>
    <div class="circle-accent absolute bottom-16 -right-20 w-64 h-64" style="animation-direction: reverse; animation-duration: 28s;"></div>

    <!-- Barber poles -->
    <div class="bg-barber-pole absolute right-0 top-0 h-full opacity-30"></div>
    <div class="bg-barber-pole absolute right-8 top-0 h-full opacity-10"></div>

    <!-- Logo -->
    <div>
      <div class="flex items-center gap-3 mb-8">
        <svg class="scissor-icon" width="28" height="28" viewBox="0 0 24 24" fill="none">
          <path d="M6 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" stroke="#2E9E5B" stroke-width="1.5"/>
          <path d="M6 21a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" stroke="#2E9E5B" stroke-width="1.5"/>
          <path d="M20 4L8.12 15.88M14.47 14.48L20 20M8.12 8.12 12 12" stroke="#2E9E5B" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
        <span class="display-font gold-text tracking-widest text-xl">Wijaya Barbershop</span>
      </div>
      <div class="divider-line w-16 mb-10"></div>

      <h1 class="display-font text-6xl leading-none mb-3" style="color: var(--cream);">
        PREMIUM<br/>
        <span class="gold-text">BARBERSHOP</span><br/>
        EXPERIENCE
      </h1>

      <p class="text-sm mt-6 leading-relaxed" style="color: var(--muted); max-width: 300px;">
        Layanan pangkas rambut eksklusif yang menggabungkan tradisi dan keahlian modern untuk penampilan terbaik Anda.
      </p>
    </div>

    <!-- Features list -->
    <div class="space-y-4 my-auto py-10">
      <div class="flex items-start gap-3">
        <div class="feature-dot mt-2"></div>
        <div>
          <p class="text-sm font-medium" style="color: var(--cream);">Booking Mudah & Cepat</p>
          <p class="text-xs mt-0.5" style="color: var(--muted);">Reservasi slot waktu favoritmu dalam hitungan detik</p>
        </div>
      </div>
      <div class="flex items-start gap-3">
        <div class="feature-dot mt-2"></div>
        <div>
          <p class="text-sm font-medium" style="color: var(--cream);">Barber Profesional</p>
          <p class="text-xs mt-0.5" style="color: var(--muted);">Tim berpengalaman dengan keahlian teknik terkini</p>
        </div>
      </div>
      <div class="flex items-start gap-3">
        <div class="feature-dot mt-2"></div>
        <div>
          <p class="text-sm font-medium" style="color: var(--cream);">Riwayat & Gaya Simpan</p>
          <p class="text-xs mt-0.5" style="color: var(--muted);">Simpan gaya favoritmu untuk kunjungan berikutnya</p>
        </div>
      </div>
    </div>

    <!-- Stats -->
    <div>
      <div class="divider-line mb-6"></div>
      <div class="grid grid-cols-3 gap-4">
        <div>
          <div class="stat-num">5K+</div>
          <p class="text-xs mt-1" style="color: var(--muted);">Pelanggan</p>
        </div>
        <div>
          <div class="stat-num">12</div>
          <p class="text-xs mt-1" style="color: var(--muted);">Barber Expert</p>
        </div>
        <div>
          <div class="stat-num">8</div>
          <p class="text-xs mt-1" style="color: var(--muted);">Tahun Berdiri</p>
        </div>
      </div>
    </div>
  </div>

  <!-- ======================== RIGHT PANEL ======================== -->
  <div class="flex flex-1 items-center justify-center px-6 py-12 relative" style="background: var(--dark);">

    <!-- Subtle radial glow -->
    <div class="absolute inset-0 pointer-events-none" style="background: radial-gradient(ellipse at 60% 40%, rgba(46,158,91,0.06) 0%, transparent 60%);"></div>

    <!-- Mobile logo -->
    <div class="lg:hidden absolute top-8 left-1/2 -translate-x-1/2 flex items-center gap-2">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
        <path d="M6 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" stroke="#2E9E5B" stroke-width="1.5"/>
        <path d="M6 21a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" stroke="#2E9E5B" stroke-width="1.5"/>
        <path d="M20 4L8.12 15.88M14.47 14.48L20 20M8.12 8.12 12 12" stroke="#2E9E5B" stroke-width="1.5" stroke-linecap="round"/>
      </svg>
      <span class="display-font gold-text tracking-widest text-lg">BLADE & CO</span>
    </div>

    <!-- Login Card -->
    <div class="w-full max-w-sm relative z-10">

      <!-- Header -->
      <div class="animate-fade-up delay-1">
        <p class="text-xs tracking-[0.3em] uppercase mb-2" style="color: var(--gold);">Selamat Datang Kembali</p>
        <h2 class="display-font text-4xl" style="color: var(--cream);">MASUK KE<br/>AKUN ANDA</h2>
      </div>

      <div class="divider-line mt-5 mb-8 animate-fade-up delay-2"></div>

      <!-- Form -->
     <form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf

    <!-- Email -->
    <div class="animate-fade-up delay-3">
        <label class="block text-xs tracking-widest uppercase mb-2" style="color: var(--muted);">
            Email / Username
        </label>

        <div class="relative">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6B6B6B" stroke-width="1.5">
                    <path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"/>
                    <path d="m22 6-10 7L2 6"/>
                </svg>
            </div>

            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="nama@email.com"
                class="input-field w-full pl-11 pr-4 py-3.5 rounded-sm text-sm"
            />
        </div>

        @error('email')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
        @enderror
    </div>

    <!-- Password -->
    <div class="animate-fade-up delay-4">
        <label class="block text-xs tracking-widest uppercase mb-2" style="color: var(--muted);">
            Password
        </label>

        <div class="relative">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6B6B6B" stroke-width="1.5">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>

            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
                class="input-field w-full pl-11 pr-12 py-3.5 rounded-sm text-sm"
            />

            <button
                type="button"
                onclick="togglePassword()"
                class="absolute right-4 top-1/2 -translate-y-1/2 opacity-40 hover:opacity-80 transition-opacity"
                id="eye-btn"
            >
                <svg id="eye-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--cream)">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </button>
        </div>

        @error('password')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
        @enderror
    </div>

    <!-- Remember & Forgot -->
    <div class="flex items-center justify-between animate-fade-up delay-4">

        <label class="flex items-center gap-2 cursor-pointer group">
            <div class="relative">
                <input
                    type="checkbox"
                    id="remember"
                    name="remember"
                    class="sr-only peer"
                />

                <div class="w-4 h-4 border rounded-sm peer-checked:bg-green-600 peer-checked:border-green-600 transition-all"
                    style="border-color: var(--dark-4); background: #ffffff;">
                </div>

                <svg class="absolute inset-0 w-4 h-4 hidden peer-checked:block pointer-events-none p-0.5"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="#ffffff"
                    stroke-width="3">
                    <path d="M20 6L9 17l-5-5"/>
                </svg>
            </div>

            <span class="text-xs" style="color: var(--muted);">
                Ingat saya
            </span>
        </label>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}"
               class="text-xs transition-colors hover:text-green-600"
               style="color: var(--muted);">
                Lupa Password?
            </a>
        @endif
    </div>

    <!-- Submit -->
    <div class="animate-fade-up delay-5 pt-2">
        <button
            type="submit"
            class="btn-gold w-full py-4 rounded-sm text-sm tracking-[0.15em] uppercase display-font text-xl"
        >
            Masuk Sekarang
        </button>
    </div>

</form>

      <!-- Register link -->
      <p class="text-center text-xs mt-8 animate-fade-up delay-5" style="color: var(--muted);">
        Belum punya akun?
        <a href="/register" class="ml-1 font-medium transition-colors hover:text-green-700" style="color: var(--gold);">Daftar Gratis</a>
      </p>

    </div>
  </div>

  <!-- Toast notification -->
  <div id="toast" class="fixed bottom-8 left-1/2 -translate-x-1/2 px-6 py-3 rounded-sm text-sm tracking-wide opacity-0 pointer-events-none transition-all duration-300 flex items-center gap-2" style="background: #ffffff; border: 1px solid var(--gold); color: var(--cream); z-index: 200;">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2E9E5B" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
    <span id="toast-msg">Logging in...</span>
  </div>

  <script>
    function togglePassword() {
      const input = document.getElementById('password');
      const icon = document.getElementById('eye-icon');
      if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>`;
      } else {
        input.type = 'password';
        icon.innerHTML = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
      }
    }

    function showToast(msg) {
      const t = document.getElementById('toast');
      document.getElementById('toast-msg').textContent = msg;
      t.style.opacity = '1';
      t.style.transform = 'translateX(-50%) translateY(0)';
      setTimeout(() => {
        t.style.opacity = '0';
        t.style.transform = 'translateX(-50%) translateY(10px)';
      }, 2500);
    }

    function handleLogin(e) {
      e.preventDefault();
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      if (!email || !password) {
        showToast('⚠ Lengkapi email dan password');
        return;
      }
      showToast('✓ Berhasil masuk. Selamat datang!');
    }
  </script>
</body>
</html>