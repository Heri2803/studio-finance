<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="StudioFinance — Aplikasi Keuangan & Penggajian Karyawan Studio Kemang & Agensi Jasa Kak Chansa">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'StudioFinance — Cat Companion Financial & Payroll' }}</title>

    <!-- High-Performance Asynchronous Google Fonts (Zero Render-Blocking) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Nunito:wght@600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap">
    </noscript>

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    $headerMood = 'hapy';
    $headerRoleTitle = 'Studio Kemang & Ads';
    if (request()->routeIs('payroll.*')) {
        $headerMood = $catMood['mood'] ?? 'payroll';
        $headerRoleTitle = 'Payroll Butler 💵';
    } elseif (request()->routeIs('employees.*')) {
        $headerMood = $catMood['mood'] ?? 'talent';
        $headerRoleTitle = 'Talent Scout 👥';
    } elseif (request()->routeIs('transactions.*')) {
        $headerMood = $catMood['mood'] ?? 'bookkeeper';
        $headerRoleTitle = 'Inspector Kas 📝';
    } elseif (request()->routeIs('dashboard')) {
        $headerMood = $mood['mood'] ?? 'hapy';
        $headerRoleTitle = 'Studio Kemang & Ads';
    }
@endphp

<body class="min-h-screen flex flex-col font-sans antialiased text-taby-charcoal bg-cream-100 selection:bg-taby-lilac selection:text-taby-charcoal">

    <!-- Top Header Bar -->
    <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b-2 border-taby-border shadow-sm transform-gpu">
        <div class="max-w-7xl mx-auto px-3.5 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14 sm:h-16 gap-2">
                <!-- Brand / Logo -->
                <div id="header-brand" class="flex items-center gap-2 min-w-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group min-w-0" aria-label="StudioFinance Beranda">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-2xl bg-taby-lilac border-2 border-taby-lilac-border flex items-center justify-center shadow-pastel group-hover:rotate-6 transition overflow-hidden shrink-0">
                            <x-cat-mascot :mood="$headerMood" size="xs" />
                        </div>
                        <div class="min-w-0">
                            <span class="font-display font-extrabold text-base sm:text-xl tracking-tight text-taby-charcoal flex items-center gap-1 leading-none">
                                Studio<span class="text-purple-600">Finance</span>
                            </span>
                            <span class="block text-[9px] sm:text-[10px] font-bold text-taby-muted uppercase tracking-wider truncate max-w-[110px] min-[360px]:max-w-[150px] sm:max-w-none mt-0.5">
                                {{ $headerRoleTitle }}
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Nav Links (Hidden on Mobile & Tablet Portrait) -->
                <nav id="desktop-nav" class="hidden lg:flex items-center gap-1.5" aria-label="Navigasi Utama Desktop">
                    <a href="{{ route('dashboard') }}" class="px-3.5 py-2 rounded-xl text-xs xl:text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-taby-lilac text-taby-charcoal border-2 border-taby-lilac-border shadow-pastel' : 'text-taby-muted hover:text-taby-charcoal hover:bg-cream-200' }}" {{ request()->routeIs('dashboard') ? 'aria-current=page' : '' }}>
                        📊 Dashboard
                    </a>
                    <a href="{{ route('payroll.index') }}" class="px-3.5 py-2 rounded-xl text-xs xl:text-sm font-semibold transition {{ request()->routeIs('payroll.*') ? 'bg-taby-yellow text-taby-charcoal border-2 border-taby-yellow-border shadow-pastel' : 'text-taby-muted hover:text-taby-charcoal hover:bg-cream-200' }}" {{ request()->routeIs('payroll.*') ? 'aria-current=page' : '' }}>
                        💵 Penggajian Tim
                    </a>
                    <a href="{{ route('employees.index') }}" class="px-3.5 py-2 rounded-xl text-xs xl:text-sm font-semibold transition {{ request()->routeIs('employees.*') ? 'bg-taby-mint text-taby-charcoal border-2 border-taby-mint-border shadow-pastel' : 'text-taby-muted hover:text-taby-charcoal hover:bg-cream-200' }}" {{ request()->routeIs('employees.*') ? 'aria-current=page' : '' }}>
                        👥 Karyawan & Talent
                    </a>
                    <a href="{{ route('transactions.index') }}" class="px-3.5 py-2 rounded-xl text-xs xl:text-sm font-semibold transition {{ request()->routeIs('transactions.*') ? 'bg-taby-rose text-taby-charcoal border-2 border-taby-rose-border shadow-pastel' : 'text-taby-muted hover:text-taby-charcoal hover:bg-cream-200' }}" {{ request()->routeIs('transactions.*') ? 'aria-current=page' : '' }}>
                        📝 Buku Kas Masuk/Keluar
                    </a>
                </nav>

                <!-- Profile / Location Badge -->
                <div class="flex items-center gap-2 shrink-0">
                    <div class="flex items-center gap-1.5 px-2 py-1 sm:px-3 sm:py-1.5 rounded-full bg-cream-200 border border-taby-border text-[11px] sm:text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-taby-charcoal font-bold whitespace-nowrap">Kak Chansa</span>
                        <span class="hidden sm:inline text-taby-muted font-normal text-[10px]">(Surabaya ⇄ Kemang)</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Dynamic Pop-Up Notification Modal (Create / Update / Delete) -->
    <x-notification-modal />

    <!-- Main Content Area with Bottom Padding for Mobile & Tablet Tab Bar -->
    <main id="main-content" class="page-content flex-1 max-w-7xl mx-auto px-3.5 sm:px-6 lg:px-8 py-4 sm:py-6 w-full pb-24 lg:pb-8">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Fixed Mobile & Tablet Bottom Navigation Bar (Touch Target 48px+, Smooth App Feel) -->
    <nav id="mobile-nav" class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-lg border-t-2 border-taby-border px-2 py-1.5 shadow-[0_-4px_20px_rgba(45,43,56,0.10)] pb-safe transform-gpu" aria-label="Navigasi Utama Mobile & Tablet">
        <div class="grid grid-cols-4 gap-1 items-center max-w-md mx-auto">
            <!-- Dashboard Tab -->
            <a href="{{ route('dashboard') }}" 
               aria-label="Menu Dashboard"
               {{ request()->routeIs('dashboard') ? 'aria-current=page' : '' }}
               class="flex flex-col items-center justify-center min-h-[48px] py-1 px-1 rounded-2xl transition duration-150 transform-gpu {{ request()->routeIs('dashboard') ? 'bg-taby-lilac text-taby-charcoal font-extrabold shadow-sm scale-105' : 'text-taby-muted hover:text-taby-charcoal active:bg-cream-200' }}">
                <span class="text-base leading-none mb-1">📊</span>
                <span class="text-[10px] font-display font-bold leading-tight">Dashboard</span>
            </a>
            <!-- Gaji Tim Tab -->
            <a href="{{ route('payroll.index') }}" 
               aria-label="Menu Penggajian Tim"
               {{ request()->routeIs('payroll.*') ? 'aria-current=page' : '' }}
               class="flex flex-col items-center justify-center min-h-[48px] py-1 px-1 rounded-2xl transition duration-150 transform-gpu {{ request()->routeIs('payroll.*') ? 'bg-taby-yellow text-taby-charcoal font-extrabold shadow-sm scale-105' : 'text-taby-muted hover:text-taby-charcoal active:bg-cream-200' }}">
                <span class="text-base leading-none mb-1">💵</span>
                <span class="text-[10px] font-display font-bold leading-tight">Gaji Tim</span>
            </a>
            <!-- Karyawan Tab -->
            <a href="{{ route('employees.index') }}" 
               aria-label="Menu Karyawan dan Talent"
               {{ request()->routeIs('employees.*') ? 'aria-current=page' : '' }}
               class="flex flex-col items-center justify-center min-h-[48px] py-1 px-1 rounded-2xl transition duration-150 transform-gpu {{ request()->routeIs('employees.*') ? 'bg-taby-mint text-taby-charcoal font-extrabold shadow-sm scale-105' : 'text-taby-muted hover:text-taby-charcoal active:bg-cream-200' }}">
                <span class="text-base leading-none mb-1">👥</span>
                <span class="text-[10px] font-display font-bold leading-tight">Karyawan</span>
            </a>
            <!-- Buku Kas Tab -->
            <a href="{{ route('transactions.index') }}" 
               aria-label="Menu Buku Kas"
               {{ request()->routeIs('transactions.*') ? 'aria-current=page' : '' }}
               class="flex flex-col items-center justify-center min-h-[48px] py-1 px-1 rounded-2xl transition duration-150 transform-gpu {{ request()->routeIs('transactions.*') ? 'bg-taby-rose text-taby-charcoal font-extrabold shadow-sm scale-105' : 'text-taby-muted hover:text-taby-charcoal active:bg-cream-200' }}">
                <span class="text-base leading-none mb-1">📝</span>
                <span class="text-[10px] font-display font-bold leading-tight">Buku Kas</span>
            </a>
        </div>
    </nav>

    <!-- Desktop Footer -->
    <footer class="hidden lg:block mt-auto border-t-2 border-taby-border py-6 bg-white/60">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-taby-muted">
            <p class="font-medium text-taby-charcoal">StudioFinance — Didesain khusus dengan cinta untuk Studio Kemang & Agensi Jasa Kak Chansa 🌸</p>
            <p class="text-[11px] mt-1 text-taby-muted">Palette: Pastel Paper Grid "Angy Cat" • Single-Entry Bookkeeping & Hybrid Payroll</p>
        </div>
    </footer>

</body>
</html>
