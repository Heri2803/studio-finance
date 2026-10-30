@extends('layouts.app')

@section('content')
<div x-data="{ addModalOpen: false, txType: 'EXPENSE' }" class="space-y-5 sm:space-y-6">
    
    <!-- Top Header Bar & Action -->
    <div class="flex items-center justify-between gap-2 pb-1">
        <div>
            <h1 class="text-base sm:text-xl font-extrabold font-display text-taby-charcoal flex items-center gap-1.5">
                <span>📝</span> Buku Kas Studio & Ads
            </h1>
            <p class="text-[11px] sm:text-xs text-taby-muted mt-0.5">Arus kas Kemang, ad spend, fee klien & petty cash</p>
        </div>

        <button @click="addModalOpen = true" class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 sm:px-5 sm:py-2.5 rounded-2xl bg-taby-yellow border-2 border-taby-yellow-border font-bold text-xs sm:text-sm text-taby-charcoal shadow-pastel hover:translate-y-[-1px] active:scale-95 transition">
            <span>➕</span> Catat Kas
        </button>
    </div>

    <!-- Inspector Kas Banner (Dynamic Personality Mascot!) -->
    <div class="anim-enter rounded-3xl border-2 border-taby-border bg-white p-3.5 sm:p-5 shadow-pastel">
        <div class="flex items-center gap-3 sm:gap-4">
            <div data-cat-avatar class="p-1 sm:p-2 rounded-2xl bg-cream-100 border-2 border-taby-border shadow-sm shrink-0">
                <x-cat-mascot :mood="$catMood['mood'] ?? 'bookkeeper'" size="md" />
            </div>
            <div data-cat-bubble class="flex-1 rounded-2xl border-2 p-3 {{ $catMood['bg_color'] ?? 'bg-taby-lilac-light' }} border-taby-border shadow-sm">
                <div class="flex items-center justify-between gap-1 mb-1">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $catMood['badge_class'] ?? 'bg-taby-lilac text-taby-lilac-dark border-taby-lilac-border' }} font-display">
                        {{ $catMood['title'] ?? 'Inspector Kas 📝🔍' }}
                    </span>
                    <span class="text-[10px] text-taby-muted font-bold">{{ $transactions->count() }} Transaksi</span>
                </div>
                <p class="text-xs font-bold text-taby-charcoal leading-snug">
                    "{{ $catMood['quote'] ?? 'Halo Kak Chansa! Seluruh rupiah dari DP project jasa dan pengeluaran iklan diawasi ketat agar kas studio tetap surplus! 🐾' }}"
                </p>
            </div>
        </div>
    </div>

    <!-- Summary Metrics (3 Compact Cards) -->
    <div class="anim-enter anim-delay-1 grid grid-cols-3 gap-2 sm:gap-4">
        <x-stat-card 
            title="Masuk (+)" 
            :value="'Rp ' . number_format($stats['total_inflow'], 0, ',', '.')" 
            color="mint" 
            subtitle="DP & jasa" 
        />
        <x-stat-card 
            title="Keluar (-)" 
            :value="'Rp ' . number_format($stats['total_outflow'], 0, ',', '.')" 
            color="rose" 
            subtitle="Gaji & ads" 
        />
        <x-stat-card 
            title="Sisa Kas" 
            :value="'Rp ' . number_format($stats['net_cashflow'], 0, ',', '.')" 
            color="{{ $stats['net_cashflow'] >= 0 ? 'lilac' : 'rose' }}" 
            subtitle="{{ $stats['net_cashflow'] >= 0 ? 'Surplus ✨' : 'Tekor 😾' }}" 
        />
    </div>

    <!-- Content Container -->
    <div class="anim-enter anim-delay-2 rounded-3xl border-2 border-taby-border bg-white p-4 sm:p-6 shadow-pastel">
        
        <!-- Filter Controls (Mobile-friendly pills & select) -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4 sm:mb-6 pb-3 sm:pb-4 border-b border-taby-border">
            <!-- Filter Pills -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0 no-scrollbar">
                <a href="{{ route('transactions.index', array_merge(request()->query(), ['type' => null])) }}" class="px-3 min-h-[36px] flex items-center justify-center rounded-xl text-xs font-bold transition shrink-0 {{ !$type ? 'bg-taby-charcoal text-white shadow-sm' : 'bg-cream-200 text-taby-muted hover:bg-cream-300' }}">
                    Semua
                </a>
                <a href="{{ route('transactions.index', array_merge(request()->query(), ['type' => 'INCOME'])) }}" class="px-3 min-h-[36px] flex items-center justify-center rounded-xl text-xs font-bold transition shrink-0 {{ $type === 'INCOME' ? 'bg-taby-mint text-taby-mint-dark border border-taby-mint-border shadow-sm' : 'bg-cream-200 text-taby-muted hover:bg-cream-300' }}">
                    Masuk (+)
                </a>
                <a href="{{ route('transactions.index', array_merge(request()->query(), ['type' => 'EXPENSE'])) }}" class="px-3 min-h-[36px] flex items-center justify-center rounded-xl text-xs font-bold transition shrink-0 {{ $type === 'EXPENSE' ? 'bg-taby-rose text-taby-rose-dark border border-taby-rose-border shadow-sm' : 'bg-cream-200 text-taby-muted hover:bg-cream-300' }}">
                    Keluar (-)
                </a>
            </div>

            <!-- Month, Date & Location Picker (Never overflows on mobile!) -->
            <form method="GET" action="{{ route('transactions.index') }}" x-data="{ filterMode: '{{ $date ? 'date' : 'month' }}' }" class="flex flex-wrap sm:flex-nowrap items-center gap-1.5 w-full md:w-auto">
                @if($type)
                    <input type="hidden" name="type" value="{{ $type }}">
                @endif

                <!-- Switcher Mode: Bulan vs Tanggal -->
                <div class="flex items-center rounded-xl bg-cream-200 p-0.5 border border-taby-border shrink-0">
                    <button type="button" 
                            @click="filterMode = 'month'" 
                            :class="filterMode === 'month' ? 'bg-white font-bold text-taby-charcoal shadow-sm' : 'text-taby-muted font-medium'" 
                            class="px-2 py-1 text-[10px] rounded-lg transition">
                        🗓️ Bulan
                    </button>
                    <button type="button" 
                            @click="filterMode = 'date'" 
                            :class="filterMode === 'date' ? 'bg-white font-bold text-taby-charcoal shadow-sm' : 'text-taby-muted font-medium'" 
                            class="px-2 py-1 text-[10px] rounded-lg transition">
                        📅 Tanggal
                    </button>
                </div>

                <!-- Input Picker Bulan -->
                <template x-if="filterMode === 'month'">
                    <input type="month" 
                           name="month" 
                           value="{{ $month }}" 
                           aria-label="Pilih Bulan Transaksi" 
                           onchange="this.form.submit()" 
                           class="flex-1 sm:flex-none min-w-[125px] rounded-xl border-taby-border text-xs px-2.5 py-1.5 bg-white font-bold text-taby-charcoal min-h-[36px] cursor-pointer shadow-sm">
                </template>

                <!-- Input Picker Tanggal Spesifik -->
                <template x-if="filterMode === 'date'">
                    <input type="date" 
                           name="date" 
                           value="{{ $date ?? date('Y-m-d') }}" 
                           aria-label="Pilih Tanggal Spesifik Transaksi" 
                           onchange="this.form.submit()" 
                           class="flex-1 sm:flex-none min-w-[130px] rounded-xl border-taby-border text-xs px-2.5 py-1.5 bg-white font-bold text-taby-charcoal min-h-[36px] cursor-pointer shadow-sm">
                </template>

                <select name="location" aria-label="Pilih Lokasi Transaksi" onchange="this.form.submit()" class="flex-1 sm:flex-none min-w-[105px] rounded-xl border-taby-border text-xs px-2 py-1.5 bg-white font-bold min-h-[36px] shadow-sm">
                    <option value="">Semua Lokasi</option>
                    <option value="KEMANG" {{ $location === 'KEMANG' ? 'selected' : '' }}>Kemang</option>
                    <option value="SURABAYA" {{ $location === 'SURABAYA' ? 'selected' : '' }}>Surabaya</option>
                    <option value="ONLINE" {{ $location === 'ONLINE' ? 'selected' : '' }}>Online</option>
                </select>
            </form>
        </div>

        <!-- MOBILE & TABLET VIEW: App-Style Transaction Cards -->
        <div class="block lg:hidden space-y-3">
            @forelse($transactions as $tx)
                <div class="p-3.5 rounded-2xl bg-cream-100/70 border border-taby-border space-y-2">
                    <!-- Top Row: Category + Location + Delete Button -->
                    <div class="flex items-center justify-between gap-1.5">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full {{ $tx->isIncome() ? 'bg-taby-mint text-taby-mint-dark' : 'bg-taby-rose text-taby-rose-dark' }}">
                                {{ $tx->category_label }}
                            </span>
                            <span class="text-[9px] text-taby-muted font-bold px-1.5 py-0.5 rounded-md bg-white border border-taby-border">
                                📍 {{ $tx->location }}
                            </span>
                            @if($tx->payroll_slip_id)
                                <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded-md bg-taby-yellow text-taby-yellow-dark">
                                    🐾 Payroll
                                </span>
                            @endif
                        </div>

                        <!-- Delete Button if not payroll locked -->
                        @if(!$tx->payroll_slip_id)
                            <form action="{{ route('transactions.destroy', $tx) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        @click="$dispatch('open-confirm-modal', {
                                            title: 'Hapus Transaksi Kas? 🥺🐾',
                                            message: 'Yakin ingin menghapus catatan “{{ addslashes($tx->description) }}” senilai Rp {{ number_format($tx->amount, 0, \',\', \'.\') }}?',
                                            confirmText: 'Ya, Hapus! 🗑️',
                                            cancelText: 'Batal',
                                            badgeText: 'HAPUS KAS',
                                            mood: 'delete',
                                            targetForm: $el.closest('form')
                                        })"
                                        aria-label="Hapus transaksi {{ $tx->description }}"
                                        class="w-8 h-8 flex items-center justify-center text-xs rounded-lg text-taby-muted hover:text-rose-600 hover:bg-rose-50 transition">
                                    🗑️
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="text-xs font-bold text-taby-charcoal leading-snug">
                        {{ $tx->description }}
                    </div>

                    <!-- Bottom Row: Date & Colored Amount -->
                    <div class="flex items-center justify-between pt-1 border-t border-taby-border/40 text-xs">
                        <span class="text-[10px] text-taby-muted">
                            {{ \Carbon\Carbon::parse($tx->transaction_date)->format('d M Y') }}
                        </span>
                        <span class="font-extrabold text-sm tabular-nums {{ $tx->isIncome() ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $tx->isIncome() ? '+' : '-' }} @rupiah($tx->amount)
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-taby-muted text-xs">
                    <div class="text-2xl mb-1">🐾</div>
                    Belum ada transaksi tercatat pada filter ini.
                </div>
            @endforelse
        </div>

        <!-- DESKTOP VIEW: Spreadsheet-Style Table (Hidden on Mobile & Tablet) -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b-2 border-taby-border bg-cream-100/70 text-xs text-taby-muted font-display uppercase tracking-wider">
                        <th class="py-3 px-4 rounded-l-xl">Tanggal</th>
                        <th class="py-3 px-4">Kategori & Deskripsi</th>
                        <th class="py-3 px-4">Lokasi</th>
                        <th class="py-3 px-4 text-right">Nominal (Rp)</th>
                        <th class="py-3 px-4 rounded-r-xl text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-taby-border/50">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-cream-50/50 transition">
                            <td class="py-3.5 px-4 font-medium text-xs text-taby-muted whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($tx->transaction_date)->format('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full {{ $tx->isIncome() ? 'bg-taby-mint text-taby-mint-dark' : 'bg-taby-rose text-taby-rose-dark' }}">
                                        {{ $tx->category_label }}
                                    </span>
                                    @if($tx->payroll_slip_id)
                                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-taby-yellow text-taby-yellow-dark font-semibold">
                                            Payroll Auto 🐾
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-taby-charcoal font-medium mt-1">
                                    {{ $tx->description }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-xs">
                                <span class="px-2 py-0.5 rounded-md bg-cream-200 border border-taby-border text-taby-muted font-semibold">
                                    📍 {{ $tx->location }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold text-base tabular-nums {{ $tx->isIncome() ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $tx->isIncome() ? '+' : '-' }} @rupiah($tx->amount)
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                @if(!$tx->payroll_slip_id)
                                    <form action="{{ route('transactions.destroy', $tx) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                @click="$dispatch('open-confirm-modal', {
                                                    title: 'Hapus Transaksi Kas? 🥺🐾',
                                                    message: 'Yakin ingin menghapus catatan “{{ addslashes($tx->description) }}” senilai Rp {{ number_format($tx->amount, 0, \',\', \'.\') }}?',
                                                    confirmText: 'Ya, Hapus! 🗑️',
                                                    cancelText: 'Batal',
                                                    badgeText: 'HAPUS KAS',
                                                    mood: 'delete',
                                                    targetForm: $el.closest('form')
                                                })"
                                                aria-label="Hapus transaksi {{ $tx->description }}"
                                                class="px-2 py-1 text-xs rounded-lg bg-gray-100 hover:bg-taby-rose text-gray-500 hover:text-taby-rose-dark transition">
                                            🗑️
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] text-gray-400 font-medium">Terkunci</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-taby-muted">
                                <div class="text-3xl mb-2">🐾</div>
                                Belum ada transaksi tercatat pada filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Catat Transaksi Cepat (Mobile-optimized!) -->
    <div x-show="addModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-taby-charcoal/40 backdrop-blur-sm" @click="addModalOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-3 sm:p-4">
            <div class="relative w-full max-w-lg rounded-3xl bg-white p-5 sm:p-6 shadow-pastel-lg border-2 border-taby-border">
                <div class="flex items-center justify-between mb-4 border-b border-taby-border pb-3">
                    <h3 class="font-display font-bold text-base sm:text-lg text-taby-charcoal flex items-center gap-2">
                        <span>📝</span> Catat Transaksi Cepat
                    </h3>
                    <button @click="addModalOpen = false" type="button" aria-label="Tutup modal catat transaksi" class="text-taby-muted hover:text-taby-charcoal font-bold p-1.5 w-7 h-7 flex items-center justify-center rounded-full hover:bg-cream-200">✕</button>
                </div>

                <form action="{{ route('transactions.store') }}" method="POST" class="space-y-3.5">
                    @csrf
                    
                    <!-- Pilihan Jenis: Kas Masuk vs Keluar -->
                    <div class="grid grid-cols-2 gap-2 p-1 rounded-2xl bg-cream-200 border border-taby-border">
                        <button type="button" @click="txType = 'INCOME'" :class="txType === 'INCOME' ? 'bg-taby-mint text-taby-mint-dark font-bold shadow-sm' : 'text-taby-muted font-medium'" class="py-2 rounded-xl text-xs text-center transition">
                            Kas Masuk (+)
                        </button>
                        <button type="button" @click="txType = 'EXPENSE'" :class="txType === 'EXPENSE' ? 'bg-taby-rose text-taby-rose-dark font-bold shadow-sm' : 'text-taby-muted font-medium'" class="py-2 rounded-xl text-xs text-center transition">
                            Pengeluaran (-)
                        </button>
                    </div>
                    <input type="hidden" name="type" :value="txType">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3">
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Kategori</label>
                            <template x-if="txType === 'INCOME'">
                                <select name="category" required class="w-full rounded-xl border-taby-border text-xs sm:text-sm">
                                    <option value="PROJECT_DP">DP Proyek Jasa</option>
                                    <option value="PROJECT_SETTLEMENT">Pelunasan Proyek</option>
                                    <option value="CLIENT_RETAINER">Retainer Klien</option>
                                    <option value="OTHER">Pemasukan Lainnya</option>
                                </select>
                            </template>
                            <template x-if="txType === 'EXPENSE'">
                                <select name="category" required class="w-full rounded-xl border-taby-border text-xs sm:text-sm">
                                    <option value="AD_SPEND">Saldo Iklan (Meta/TikTok)</option>
                                    <option value="STUDIO_KEMANG">Operasional Studio Kemang</option>
                                    <option value="PROPS_GEAR">Properti & Sewa Alat</option>
                                    <option value="SUBSCRIPTION">Langganan Tools / Software</option>
                                    <option value="OTHER">Pengeluaran Lainnya</option>
                                </select>
                            </template>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Nominal (Rp)</label>
                            <input type="number" name="amount" required placeholder="5000000" min="1" class="w-full rounded-xl border-taby-border text-xs sm:text-sm font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Deskripsi Transaksi</label>
                        <input type="text" name="description" required placeholder="Contoh: Top-up saldo ads campaign brand skincare" class="w-full rounded-xl border-taby-border text-xs sm:text-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-2.5 sm:gap-3">
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Tanggal</label>
                            <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" required class="w-full rounded-xl border-taby-border text-xs sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Lokasi</label>
                            <select name="location" class="w-full rounded-xl border-taby-border text-xs sm:text-sm">
                                <option value="KEMANG">Kemang, Jaksel</option>
                                <option value="SURABAYA">Surabaya</option>
                                <option value="ONLINE">Online / Ads</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-taby-border">
                        <button type="button" @click="addModalOpen = false" class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold text-taby-muted hover:bg-cream-200">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-taby-yellow border-2 border-taby-yellow-border font-bold text-xs sm:text-sm text-taby-charcoal shadow-pastel">
                            Simpan Transaksi 🐾
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
