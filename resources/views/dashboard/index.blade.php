@extends('layouts.app')

@section('content')
<div class="space-y-5 sm:space-y-8">
    
    <!-- Mobile Top Greeting & Month Selector (App Header Bar) -->
    <div class="flex items-center justify-between gap-2 pb-1">
        <div>
            <div class="flex items-center gap-1.5">
                <span class="text-sm sm:text-base">👋</span>
                <h1 class="text-base sm:text-xl font-extrabold font-display text-taby-charcoal">
                    Halo, Kak Chansa!
                </h1>
            </div>
            <p class="text-[11px] sm:text-xs text-taby-muted mt-0.5">Ringkasan studio Kemang & iklan digital</p>
        </div>

        <!-- Month Filter Pill -->
        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-1.5 shrink-0">
            <div class="relative">
                <input type="month" name="month" value="{{ $month }}" aria-label="Filter Bulan Dashboard" onchange="this.form.submit()" class="rounded-xl border-taby-border text-xs px-2.5 py-1.5 bg-white font-bold text-taby-charcoal shadow-sm cursor-pointer pr-7 max-w-[130px]">
                <span class="absolute right-2 top-2 pointer-events-none text-xs text-taby-muted">▾</span>
            </div>
        </form>
    </div>

    <!-- Hero Cat Mascot & Mood Banner -->
    <div class="anim-enter relative overflow-hidden rounded-3xl border-2 border-taby-border bg-white p-4 sm:p-6 shadow-pastel">
        
        <!-- Mobile & Tablet Layout: Cozy Horizontal App Banner -->
        <div class="flex lg:hidden items-center gap-3.5">
            <!-- Cat Mascot Avatar -->
            <div data-cat-avatar class="p-1.5 rounded-2xl bg-cream-100 border-2 border-taby-border shadow-sm shrink-0">
                <x-cat-mascot :mood="$catMood['mood']" size="md" />
            </div>

            <!-- Speech Bubble -->
            <div data-cat-bubble class="flex-1 rounded-2xl border-2 p-3 {{ $catMood['bg_color'] }} border-taby-border shadow-sm">
                <div class="flex items-center justify-between gap-1 mb-1">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $catMood['badge_class'] }} font-display">
                        {{ $catMood['title'] }}
                    </span>
                    <span class="text-[10px] text-taby-muted font-bold">{{ $periodLabel }}</span>
                </div>
                <p class="text-xs font-bold text-taby-charcoal leading-snug font-sans">
                    "{{ $catMood['quote'] }}"
                </p>
            </div>
        </div>

        <!-- Desktop Layout (Large Desktop 1024px+) -->
        <div class="hidden lg:flex lg:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div data-cat-avatar class="p-2 rounded-3xl bg-cream-100 border-2 border-taby-border shadow-sm shrink-0">
                    <x-cat-mascot :mood="$catMood['mood']" size="lg" />
                </div>

                <div data-cat-bubble class="relative rounded-2xl border-2 p-5 {{ $catMood['bg_color'] }} border-taby-border shadow-sm max-w-xl">
                    <div class="absolute -left-3 top-6 w-0 h-0 border-t-8 border-t-transparent border-b-8 border-b-transparent border-r-8 border-r-taby-border"></div>
                    
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full {{ $catMood['badge_class'] }} font-display">
                            {{ $catMood['title'] }}
                        </span>
                        <span class="text-xs text-taby-muted font-medium">• Periode {{ $periodLabel }}</span>
                    </div>

                    <p class="text-sm font-semibold text-taby-charcoal leading-relaxed font-sans">
                        "{{ $catMood['quote'] }}"
                    </p>

                    <div class="mt-2 text-[11px] text-taby-muted flex items-center gap-2">
                        <span>💡 Cat Tips:</span>
                        <span>Pastikan semua pengeluaran ad spend & petty cash Kemang dicatat sebelum tutup buku ya!</span>
                    </div>
                </div>
            </div>

            <!-- Desktop Month Filter -->
            <div class="flex flex-col items-end justify-center gap-2 shrink-0 border-l border-taby-border pl-6">
                <span class="text-xs font-bold uppercase text-taby-muted font-display">Periode Kas</span>
                <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <input type="month" name="month" value="{{ $month }}" aria-label="Periode Kas Dashboard" class="rounded-xl border-taby-border text-xs px-3 py-2 bg-cream-100 font-semibold">
                    <button type="submit" class="px-3.5 py-2 rounded-xl bg-taby-yellow border-2 border-taby-yellow-border text-xs font-bold shadow-pastel hover:translate-y-[-1px] transition">
                        Tampilkan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- 4 Main Financial Metric Cards (2x2 Compact on Mobile, 4x1 on Desktop) -->
    <div class="anim-enter anim-delay-1 grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4">
        <!-- Kas Masuk -->
        <x-stat-card 
            title="Pemasukan" 
            :value="'Rp ' . number_format($totalInflow, 0, ',', '.')" 
            color="mint" 
            badge="Inflow"
            subtitle="DP & pelunasan jasa"
        />

        <!-- Kas Keluar -->
        <x-stat-card 
            title="Pengeluaran" 
            :value="'Rp ' . number_format($totalOutflow, 0, ',', '.')" 
            color="rose" 
            badge="Outflow"
            subtitle="Gaji, ads, studio"
        />

        <!-- Laba Bersih -->
        <x-stat-card 
            title="Laba Bersih" 
            :value="'Rp ' . number_format($netProfit, 0, ',', '.')" 
            color="{{ $netProfit >= 0 ? 'lilac' : 'rose' }}" 
            badge="{{ $netProfit >= $targetProfit ? 'Target 🎯' : 'Progress' }}"
            subtitle="Target: Rp {{ number_format($targetProfit, 0, ',', '.') }}"
        />

        <!-- Beban Payroll -->
        <x-stat-card 
            title="Beban Gaji" 
            :value="'Rp ' . number_format($totalPayrollBurden, 0, ',', '.')" 
            color="yellow" 
            badge="{{ $pendingPayrollCount === 0 ? 'Lunas 🐾' : $pendingPayrollCount . ' Pending' }}"
            subtitle="{{ $paidPayrollCount }} Lunas • {{ $pendingPayrollCount }} Pending"
        />
    </div>

    <!-- Quick Action Shortcuts (Mobile & Tablet App Feel) -->
    <div class="grid grid-cols-3 gap-2 sm:gap-3 lg:hidden">
        <a href="{{ route('transactions.index') }}" class="flex flex-col items-center justify-center p-2.5 rounded-2xl bg-white border-2 border-taby-border shadow-pastel text-center hover:bg-cream-100 transition active:scale-95">
            <span class="text-xl mb-1">➕</span>
            <span class="text-[11px] font-bold text-taby-charcoal font-display">Catat Kas</span>
        </a>
        <a href="{{ route('payroll.index') }}" class="flex flex-col items-center justify-center p-2.5 rounded-2xl bg-white border-2 border-taby-border shadow-pastel text-center hover:bg-cream-100 transition active:scale-95">
            <span class="text-xl mb-1">🐾</span>
            <span class="text-[11px] font-bold text-taby-charcoal font-display">Gajian Tim</span>
        </a>
        <a href="{{ route('employees.index') }}" class="flex flex-col items-center justify-center p-2.5 rounded-2xl bg-white border-2 border-taby-border shadow-pastel text-center hover:bg-cream-100 transition active:scale-95">
            <span class="text-xl mb-1">👥</span>
            <span class="text-[11px] font-bold text-taby-charcoal font-display">Cek Talent</span>
        </a>
    </div>

    <!-- Realita vs Target Section -->
    <div class="anim-enter anim-delay-2 rounded-3xl border-2 border-taby-border bg-white p-4 sm:p-6 shadow-pastel">
        <div class="flex items-center justify-between mb-3 sm:mb-4 pb-2.5 sm:pb-3 border-b border-taby-border">
            <div>
                <h2 class="text-sm sm:text-lg font-bold font-display text-taby-charcoal flex items-center gap-1.5">
                    <span>✨</span> Realita vs Target Studio
                </h2>
                <p class="text-[11px] sm:text-xs text-taby-muted">Tinjauan omset, profit & reaksi status mood — {{ $periodLabel }}</p>
            </div>
            <div class="text-[10px] sm:text-xs font-bold text-taby-muted bg-cream-200 px-2.5 py-1 rounded-full border border-taby-border">
                Clarity 🐾
            </div>
        </div>

        @php
            $diffOmset = $totalInflow - $targetOmset;
            $diffProfit = $netProfit - $targetProfit;
            $omsetPercent = $targetOmset > 0 ? min(100, round(($totalInflow / $targetOmset) * 100)) : 0;
            $profitPercent = $targetProfit > 0 ? min(100, round((max(0, $netProfit) / $targetProfit) * 100)) : 0;
        @endphp

        <!-- MOBILE & TABLET VIEW: App-Style Progress Cards -->
        <div class="block lg:hidden space-y-3">
            <!-- Mobile Card 1: Omset -->
            <div class="p-3.5 rounded-2xl bg-cream-100/70 border border-taby-border">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-bold text-taby-charcoal">Total Omset / Masuk</span>
                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full {{ $diffOmset >= 0 ? 'bg-taby-mint text-taby-mint-dark' : 'bg-taby-rose text-taby-rose-dark' }}">
                        {{ $diffOmset >= 0 ? '😻 Hapy' : '😾 Angy' }} ({{ $diffOmset >= 0 ? '+' : '' }}@rupiah($diffOmset))
                    </span>
                </div>
                <div class="flex items-baseline justify-between text-xs mb-1.5">
                    <span class="font-extrabold text-sm tabular-nums text-emerald-600">@rupiah($totalInflow)</span>
                    <span class="text-[11px] text-taby-muted">Target: @rupiah($targetOmset)</span>
                </div>
                <!-- Mini Progress Bar -->
                <div class="w-full bg-cream-200 rounded-full h-2 overflow-hidden border border-taby-border">
                    <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" style="width: {{ $omsetPercent }}%"></div>
                </div>
            </div>

            <!-- Mobile Card 2: Laba Bersih -->
            <div class="p-3.5 rounded-2xl bg-cream-100/70 border border-taby-border">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-bold text-taby-charcoal">Laba Bersih (Net Profit)</span>
                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full {{ $netProfit >= $targetProfit ? 'bg-taby-mint text-taby-mint-dark' : ($netProfit >= 0 ? 'bg-taby-lilac text-taby-lilac-dark' : 'bg-taby-rose text-taby-rose-dark') }}">
                        {{ $netProfit >= $targetProfit ? '😻 Hapy' : ($netProfit >= 0 ? '☕ Zen' : '😾 Angy') }} ({{ $diffProfit >= 0 ? '+' : '' }}@rupiah($diffProfit))
                    </span>
                </div>
                <div class="flex items-baseline justify-between text-xs mb-1.5">
                    <span class="font-extrabold text-sm tabular-nums {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">@rupiah($netProfit)</span>
                    <span class="text-[11px] text-taby-muted">Target: @rupiah($targetProfit)</span>
                </div>
                <!-- Mini Progress Bar -->
                <div class="w-full bg-cream-200 rounded-full h-2 overflow-hidden border border-taby-border">
                    <div class="bg-purple-500 h-2 rounded-full transition-all duration-500" style="width: {{ $profitPercent }}%"></div>
                </div>
            </div>

            <!-- Mobile Card 3: Beban Gaji -->
            <div class="p-3.5 rounded-2xl bg-cream-100/70 border border-taby-border">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-bold text-taby-charcoal">Beban Penggajian Tim</span>
                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full {{ $pendingPayrollCount === 0 ? 'bg-taby-mint text-taby-mint-dark' : 'bg-taby-yellow text-taby-yellow-dark' }}">
                        {{ $pendingPayrollCount === 0 ? '🐾 Lunas' : '⏰ ' . $pendingPayrollCount . ' Pending' }}
                    </span>
                </div>
                <div class="flex items-baseline justify-between text-xs">
                    <span class="font-extrabold text-sm tabular-nums text-purple-700">@rupiah($totalPayrollBurden)</span>
                    <span class="text-[11px] text-taby-muted">{{ $paidPayrollCount }} Lunas • {{ $pendingPayrollCount }} Pending</span>
                </div>
            </div>
        </div>

        <!-- DESKTOP VIEW: Spreadsheet-Style Table (Hidden on Mobile & Tablet, Block on Desktop) -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-taby-lilac/70 text-taby-charcoal font-display text-xs uppercase tracking-wider border-b-2 border-taby-lilac-border">
                        <th class="py-3 px-4 rounded-l-xl">Komponen Finansial</th>
                        <th class="py-3 px-4 text-right">Realita</th>
                        <th class="py-3 px-4 text-right">Target / Budget</th>
                        <th class="py-3 px-4 text-center">Selisih</th>
                        <th class="py-3 px-4 text-center rounded-r-xl">Cat Mood</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-taby-border/50">
                    <tr class="hover:bg-cream-50/50 transition">
                        <td class="py-4 px-4 font-bold text-taby-charcoal">
                            Total Omset / Pemasukan
                            <div class="text-xs font-normal text-taby-muted">DP Project + Pelunasan + Retainer Jasa</div>
                        </td>
                        <td class="py-4 px-4 text-right font-bold text-base tabular-nums text-emerald-600">
                            @rupiah($totalInflow)
                        </td>
                        <td class="py-4 px-4 text-right font-semibold tabular-nums text-taby-muted">
                            @rupiah($targetOmset)
                        </td>
                        <td class="py-4 px-4 text-center tabular-nums">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $diffOmset >= 0 ? 'bg-taby-mint text-taby-mint-dark' : 'bg-taby-rose text-taby-rose-dark' }}">
                                {{ $diffOmset >= 0 ? '+' : '' }}@rupiah($diffOmset)
                            </span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $diffOmset >= 0 ? 'bg-taby-mint text-taby-mint-dark' : 'bg-taby-rose text-taby-rose-dark' }} font-bold text-xs">
                                <span>{{ $diffOmset >= 0 ? '😻 Hapy' : '😾 Angy' }}</span>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-cream-50/50 transition">
                        <td class="py-4 px-4 font-bold text-taby-charcoal">
                            Total Laba Bersih (Net Profit)
                            <div class="text-xs font-normal text-taby-muted">Kas Masuk - Semua Beban Operasional & Payroll</div>
                        </td>
                        <td class="py-4 px-4 text-right font-bold text-base tabular-nums {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            @rupiah($netProfit)
                        </td>
                        <td class="py-4 px-4 text-right font-semibold tabular-nums text-taby-muted">
                            @rupiah($targetProfit)
                        </td>
                        <td class="py-4 px-4 text-center tabular-nums">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $diffProfit >= 0 ? 'bg-taby-mint text-taby-mint-dark' : 'bg-taby-rose text-taby-rose-dark' }}">
                                {{ $diffProfit >= 0 ? '+' : '' }}@rupiah($diffProfit)
                            </span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $netProfit >= $targetProfit ? 'bg-taby-mint text-taby-mint-dark' : ($netProfit >= 0 ? 'bg-taby-lilac text-taby-lilac-dark' : 'bg-taby-rose text-taby-rose-dark') }} font-bold text-xs">
                                <span>{{ $netProfit >= $targetProfit ? '😻 Hapy' : ($netProfit >= 0 ? '☕ Zen' : '😾 Angy') }}</span>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-cream-50/50 transition">
                        <td class="py-4 px-4 font-bold text-taby-charcoal">
                            Beban Penggajian Tim
                            <div class="text-xs font-normal text-taby-muted">Video Creator Kemang, Model TikTok, & Ads Specialist</div>
                        </td>
                        <td class="py-4 px-4 text-right font-bold text-base tabular-nums text-purple-700">
                            @rupiah($totalPayrollBurden)
                        </td>
                        <td class="py-4 px-4 text-right font-semibold tabular-nums text-taby-muted">
                            Budget Rp 15.000.000
                        </td>
                        <td class="py-4 px-4 text-center">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-cream-200 text-taby-charcoal border border-taby-border">
                                {{ $paidPayrollCount }} Lunas / {{ $pendingPayrollCount }} Pending
                            </span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $pendingPayrollCount === 0 ? 'bg-taby-mint text-taby-mint-dark' : 'bg-taby-yellow text-taby-yellow-dark' }} font-bold text-xs">
                                <span>{{ $pendingPayrollCount === 0 ? '🐾 Lunas' : '⏰ Reminder' }}</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2 Column Section: Tim Payroll Status & Recent Cashflow -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-8">
        <!-- Status Gaji Tim Bulan Ini -->
        <div class="rounded-3xl border-2 border-taby-border bg-white p-4 sm:p-6 shadow-pastel flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3 pb-2.5 border-b border-taby-border">
                    <h3 class="font-display font-bold text-sm sm:text-base text-taby-charcoal flex items-center gap-1.5">
                        <span>💵</span> Status Gaji Tim
                    </h3>
                    <a href="{{ route('payroll.index', ['period' => $periodLabel]) }}" class="text-[11px] sm:text-xs font-bold text-purple-600 hover:text-purple-800">
                        Kelola Semua →
                    </a>
                </div>

                <div class="space-y-2.5">
                    @forelse($payrollSlips as $slip)
                        <div class="p-3 rounded-2xl bg-cream-100/70 border border-taby-border flex items-center justify-between hover:bg-cream-100 transition">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-xl bg-taby-lilac border border-taby-lilac-border flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ substr($slip->employee->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-xs text-taby-charcoal truncate">{{ $slip->employee->name }}</div>
                                    <div class="text-[10px] text-taby-muted truncate">{{ $slip->employee->role }}</div>
                                </div>
                            </div>

                            <div class="text-right flex items-center gap-2 shrink-0">
                                <div>
                                    <div class="font-bold text-xs tabular-nums text-taby-charcoal">@rupiah($slip->net_pay)</div>
                                    <x-badge :type="$slip->status" class="scale-75 sm:scale-90 origin-right" />
                                </div>
                                <a href="{{ route('slip.pdf', $slip) }}" target="_blank" rel="noopener noreferrer" aria-label="Cetak Slip Gaji PDF {{ $slip->employee->name }}" class="w-8 h-8 rounded-xl bg-white border border-taby-border text-xs hover:bg-cream-200 flex items-center justify-center shrink-0 shadow-sm" title="Cetak PDF">
                                    📄
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-taby-muted text-xs">
                            Belum ada draft payroll pada periode ini.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-3.5 pt-3 border-t border-taby-border flex items-center justify-between">
                <span class="text-[11px] font-semibold text-taby-muted">Total: @rupiah($totalPayrollBurden)</span>
                <a href="{{ route('payroll.index') }}" class="px-3 py-1.5 rounded-xl bg-taby-yellow border-2 border-taby-yellow-border text-xs font-bold text-taby-charcoal shadow-pastel">
                    Buka Payroll 🐾
                </a>
            </div>
        </div>

        <!-- 5 Transaksi Terakhir -->
        <div class="rounded-3xl border-2 border-taby-border bg-white p-4 sm:p-6 shadow-pastel flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3 pb-2.5 border-b border-taby-border">
                    <h3 class="font-display font-bold text-sm sm:text-base text-taby-charcoal flex items-center gap-1.5">
                        <span>📝</span> Arus Kas Terakhir
                    </h3>
                    <a href="{{ route('transactions.index') }}" class="text-[11px] sm:text-xs font-bold text-purple-600 hover:text-purple-800">
                        Buku Kas →
                    </a>
                </div>

                <div class="space-y-2.5">
                    @forelse($recentTransactions as $tx)
                        <div class="p-3 rounded-2xl bg-cream-100/70 border border-taby-border flex items-center justify-between hover:bg-cream-100 transition">
                            <div class="min-w-0 pr-2">
                                <div class="font-semibold text-xs text-taby-charcoal truncate flex items-center gap-1.5">
                                    <span class="truncate">{{ $tx->description }}</span>
                                    <span class="text-[9px] text-taby-muted px-1.5 py-0.2 rounded bg-cream-200 border border-taby-border shrink-0">📍 {{ $tx->location }}</span>
                                </div>
                                <div class="text-[10px] text-taby-muted mt-0.5">
                                    {{ \Carbon\Carbon::parse($tx->transaction_date)->format('d M') }} • {{ $tx->category_label }}
                                </div>
                            </div>
                            <div class="font-bold text-xs tabular-nums whitespace-nowrap shrink-0 {{ $tx->isIncome() ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $tx->isIncome() ? '+' : '-' }} @rupiah($tx->amount)
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-taby-muted text-xs">
                            Belum ada riwayat transaksi kas.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-3.5 pt-3 border-t border-taby-border flex items-center justify-between">
                <span class="text-[11px] font-semibold text-taby-muted">Sisa: @rupiah($netProfit)</span>
                <a href="{{ route('transactions.index') }}" class="px-3 py-1.5 rounded-xl bg-taby-lilac border-2 border-taby-lilac-border text-xs font-bold text-taby-charcoal shadow-pastel">
                    Catat Transaksi ✨
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
