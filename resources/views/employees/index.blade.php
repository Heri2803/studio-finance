@extends('layouts.app')

@section('content')
<div x-data="{ addModalOpen: false, editModalOpen: false, currentEmployee: {} }" class="space-y-5 sm:space-y-6">
    
    <!-- Top Header Bar & Action -->
    <div class="flex items-center justify-between gap-2 pb-1">
        <div>
            <h1 class="text-base sm:text-xl font-extrabold font-display text-taby-charcoal flex items-center gap-1.5">
                <span>👥</span> Karyawan & Talent
            </h1>
            <p class="text-[11px] sm:text-xs text-taby-muted mt-0.5">Tim WFO Kemang, talent TikTok & retainer</p>
        </div>
        <button @click="addModalOpen = true" class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 sm:px-5 sm:py-2.5 rounded-2xl bg-taby-yellow border-2 border-taby-yellow-border font-bold text-xs sm:text-sm text-taby-charcoal shadow-pastel hover:translate-y-[-1px] active:scale-95 transition">
            <span>➕</span> Tambah Talent
        </button>
    </div>

    <!-- Talent Scout Banner (Dynamic Personality Mascot!) -->
    <div class="anim-enter rounded-3xl border-2 border-taby-border bg-white p-3.5 sm:p-5 shadow-pastel">
        <div class="flex items-center gap-3 sm:gap-4">
            <div data-cat-avatar class="p-1 sm:p-2 rounded-2xl bg-cream-100 border-2 border-taby-border shadow-sm shrink-0">
                <x-cat-mascot :mood="$catMood['mood'] ?? 'talent'" size="md" />
            </div>
            <div data-cat-bubble class="flex-1 rounded-2xl border-2 p-3 {{ $catMood['bg_color'] ?? 'bg-taby-mint-light' }} border-taby-border shadow-sm">
                <div class="flex items-center justify-between gap-1 mb-1">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $catMood['badge_class'] ?? 'bg-taby-mint text-taby-mint-dark border-taby-mint-border' }} font-display">
                        {{ $catMood['title'] ?? 'Talent Scout 👥✨' }}
                    </span>
                    <span class="text-[10px] text-taby-muted font-bold">{{ $employees->count() }} Terdaftar</span>
                </div>
                <p class="text-xs font-bold text-taby-charcoal leading-snug">
                    "{{ $catMood['quote'] ?? 'Halo Kak Chansa! Pastikan detail nomor rekening bank talent TikTok dan kreator Kemang sudah tepat ya~ 🐾' }}"
                </p>
            </div>
        </div>
    </div>

    <!-- Stats Quick Cards (2x2 on Mobile, 4x1 on Desktop) -->
    <div class="anim-enter anim-delay-1 grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4">
        <x-stat-card title="Total Pekerja" :value="$stats['total'] . ' Orang'" color="cream" subtitle="Studio & remote" />
        <x-stat-card title="Full-Time WFO" :value="$stats['fulltime'] . ' Staf'" color="lilac" subtitle="Video Creator" />
        <x-stat-card title="Freelance" :value="$stats['freelance'] . ' Talent'" color="yellow" subtitle="Model TikTok sesi" />
        <x-stat-card title="Retainer" :value="$stats['retainer'] . ' Mitra'" color="mint" subtitle="Media Buyer Ads" />
    </div>

    <!-- Content Container -->
    <div class="anim-enter anim-delay-2 rounded-3xl border-2 border-taby-border bg-white p-4 sm:p-6 shadow-pastel">
        <div class="flex items-center justify-between mb-3 sm:mb-4 pb-2.5 sm:pb-3 border-b border-taby-border">
            <h2 class="text-sm sm:text-base font-bold font-display text-taby-charcoal flex items-center gap-1.5">
                <span>📋</span> Daftar Tim & Skema Kompensasi
            </h2>
            <span class="text-[10px] sm:text-xs text-taby-muted font-bold bg-cream-200 px-2.5 py-1 rounded-full border border-taby-border">
                {{ $employees->count() }} Orang
            </span>
        </div>

        <!-- MOBILE & TABLET VIEW: App-Style Talent Cards -->
        <div class="block lg:hidden space-y-3">
            @forelse($employees as $emp)
                <div class="p-3.5 rounded-2xl bg-cream-100/70 border border-taby-border space-y-2.5">
                    <!-- Top Row: Avatar + Name + Role + Action Buttons -->
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-9 h-9 rounded-2xl bg-taby-lilac border border-taby-lilac-border flex items-center justify-center font-extrabold text-xs shrink-0 shadow-sm">
                                {{ substr($emp->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <div class="font-extrabold text-xs text-taby-charcoal truncate">{{ $emp->name }}</div>
                                <div class="text-[10px] text-taby-muted truncate">{{ $emp->role }}</div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-1.5 shrink-0">
                            <button 
                                @click="currentEmployee = {{ json_encode($emp) }}; editModalOpen = true" 
                                class="px-3 min-h-[36px] text-xs font-bold rounded-xl bg-taby-lilac hover:bg-taby-lilac-border text-taby-charcoal border border-taby-lilac-border transition shadow-sm flex items-center justify-center">
                                ✏️ Edit
                            </button>
                            <form action="{{ route('employees.destroy', $emp) }}" method="POST" onsubmit="return confirm('Hapus pekerja {{ $emp->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        aria-label="Hapus data pekerja {{ $emp->name }}"
                                        class="w-9 h-9 text-xs rounded-xl bg-taby-rose hover:bg-taby-rose-border text-taby-rose-dark border border-taby-rose-border transition shadow-sm flex items-center justify-center">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Middle Info Row: Contract Badge & Rate -->
                    <div class="flex items-center justify-between text-xs pt-1 border-t border-taby-border/50">
                        <x-badge :type="$emp->employment_type" class="scale-90 origin-left" />
                        <div class="font-extrabold tabular-nums text-taby-charcoal">
                            @rupiah($emp->base_salary_or_rate)
                            <span class="text-[10px] font-normal text-taby-muted">
                                {{ $emp->employment_type === 'FREELANCE' ? '/ sesi' : '/ bln' }}
                            </span>
                        </div>
                    </div>

                    <!-- Bottom Bank Info & Location -->
                    <div class="flex items-center justify-between text-[11px] text-taby-muted bg-white/70 px-2.5 py-1.5 rounded-xl border border-taby-border/50">
                        <span class="truncate">💳 {{ $emp->bank_name }}: {{ $emp->bank_account_number ?? '-' }} (a.n {{ $emp->bank_account_name ?? $emp->name }})</span>
                        <span class="shrink-0 font-bold ml-1">📍 {{ $emp->location }}</span>
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-taby-muted text-xs">
                    <div class="text-2xl mb-1">🐾</div>
                    Belum ada data pekerja. Klik tombol "+ Tambah Talent" di atas!
                </div>
            @endforelse
        </div>

        <!-- DESKTOP VIEW: Spreadsheet-Style Table (Hidden on Mobile & Tablet) -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b-2 border-taby-border bg-cream-100/70 text-xs text-taby-muted font-display uppercase tracking-wider">
                        <th class="py-3 px-4 rounded-l-xl">Nama & Role</th>
                        <th class="py-3 px-4">Tipe Kontrak</th>
                        <th class="py-3 px-4">Base Rate / Gaji</th>
                        <th class="py-3 px-4">Rekening Transfer</th>
                        <th class="py-3 px-4">Lokasi</th>
                        <th class="py-3 px-4 rounded-r-xl text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-taby-border/50">
                    @forelse($employees as $emp)
                        <tr class="hover:bg-cream-50/50 transition">
                            <td class="py-3.5 px-4 font-medium">
                                <div class="font-bold text-taby-charcoal">{{ $emp->name }}</div>
                                <div class="text-xs text-taby-muted">{{ $emp->role }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <x-badge :type="$emp->employment_type" />
                            </td>
                            <td class="py-3.5 px-4 font-bold text-taby-charcoal tabular-nums">
                                @rupiah($emp->base_salary_or_rate)
                                <span class="text-[11px] font-normal text-taby-muted">
                                    {{ $emp->employment_type === 'FREELANCE' ? '/ sesi' : '/ bulan' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-xs">
                                <div class="font-semibold text-taby-charcoal">{{ $emp->bank_name }} - {{ $emp->bank_account_number ?? '-' }}</div>
                                <div class="text-taby-muted font-medium">a.n {{ $emp->bank_account_name ?? $emp->name }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-xs px-2.5 py-1 rounded-lg bg-cream-200 border border-taby-border font-medium">
                                    📍 {{ $emp->location }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button 
                                        @click="currentEmployee = {{ json_encode($emp) }}; editModalOpen = true" 
                                        class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-taby-lilac hover:bg-taby-lilac-border transition text-taby-charcoal">
                                        ✏️ Edit
                                    </button>
                                    <form action="{{ route('employees.destroy', $emp) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pekerja ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-taby-rose hover:bg-taby-rose-border transition text-taby-rose-dark">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-taby-muted">
                                <div class="text-3xl mb-2">🐾</div>
                                Belum ada data pekerja. Klik tombol "+ Tambah Talent" di atas!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Pekerja (Thumb-friendly on mobile!) -->
    <div x-show="addModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-taby-charcoal/40 backdrop-blur-sm" @click="addModalOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-3 sm:p-4">
            <div class="relative w-full max-w-lg rounded-3xl bg-white p-5 sm:p-6 shadow-pastel-lg border-2 border-taby-border">
                <div class="flex items-center justify-between mb-4 border-b border-taby-border pb-3">
                    <h3 class="font-display font-bold text-base sm:text-lg text-taby-charcoal flex items-center gap-2">
                        <span>✨</span> Tambah Pekerja / Talent
                    </h3>
                    <button @click="addModalOpen = false" type="button" aria-label="Tutup modal tambah pekerja" class="text-taby-muted hover:text-taby-charcoal font-bold p-1.5 w-7 h-7 flex items-center justify-center rounded-full hover:bg-cream-200">✕</button>
                </div>

                <form action="{{ route('employees.store') }}" method="POST" class="space-y-3.5">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Nama Lengkap</label>
                        <input type="text" name="name" required placeholder="Contoh: Bella Safira" class="w-full rounded-xl border-taby-border text-xs sm:text-sm">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3">
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Posisi / Role</label>
                            <input type="text" name="role" required placeholder="Model TikTok / Video Creator" class="w-full rounded-xl border-taby-border text-xs sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Tipe Kontrak</label>
                            <select name="employment_type" required class="w-full rounded-xl border-taby-border text-xs sm:text-sm">
                                <option value="FULLTIME">Full-Time WFO</option>
                                <option value="FREELANCE">Freelance Per-Sesi</option>
                                <option value="RETAINER">Retainer Partner</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Gaji Pokok / Rate Per Sesi (Rp)</label>
                        <input type="number" name="base_salary_or_rate" required placeholder="6000000" class="w-full rounded-xl border-taby-border text-xs sm:text-sm font-bold">
                    </div>

                    <div class="grid grid-cols-2 gap-2.5 sm:gap-3">
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Bank</label>
                            <input type="text" name="bank_name" value="BCA" required class="w-full rounded-xl border-taby-border text-xs sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Nomor Rekening</label>
                            <input type="text" name="bank_account_number" placeholder="1234567890" class="w-full rounded-xl border-taby-border text-xs sm:text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5 sm:gap-3">
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Atas Nama Rekening</label>
                            <input type="text" name="bank_account_name" placeholder="Nama pemilik rek" class="w-full rounded-xl border-taby-border text-xs sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Lokasi Kerja</label>
                            <select name="location" class="w-full rounded-xl border-taby-border text-xs sm:text-sm">
                                <option value="KEMANG">Kemang, Jaksel</option>
                                <option value="SURABAYA">Surabaya</option>
                                <option value="REMOTE">Remote / Online</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-taby-border">
                        <button type="button" @click="addModalOpen = false" class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold text-taby-muted hover:bg-cream-200">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-taby-yellow border-2 border-taby-yellow-border font-bold text-xs sm:text-sm text-taby-charcoal shadow-pastel">
                            Simpan Pekerja 🐾
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Pekerja -->
    <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-taby-charcoal/40 backdrop-blur-sm" @click="editModalOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-3 sm:p-4">
            <div class="relative w-full max-w-lg rounded-3xl bg-white p-5 sm:p-6 shadow-pastel-lg border-2 border-taby-border">
                <div class="flex items-center justify-between mb-4 border-b border-taby-border pb-3">
                    <h3 class="font-display font-bold text-base sm:text-lg text-taby-charcoal flex items-center gap-2">
                        <span>✏️</span> Edit Data Pekerja
                    </h3>
                    <button @click="editModalOpen = false" type="button" aria-label="Tutup modal edit pekerja" class="text-taby-muted hover:text-taby-charcoal font-bold p-1.5 w-7 h-7 flex items-center justify-center rounded-full hover:bg-cream-200">✕</button>
                </div>

                <form :action="'/employees/' + currentEmployee.id" method="POST" class="space-y-3.5">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Nama Lengkap</label>
                        <input type="text" name="name" x-model="currentEmployee.name" required class="w-full rounded-xl border-taby-border text-xs sm:text-sm">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3">
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Posisi / Role</label>
                            <input type="text" name="role" x-model="currentEmployee.role" required class="w-full rounded-xl border-taby-border text-xs sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Tipe Kontrak</label>
                            <select name="employment_type" x-model="currentEmployee.employment_type" required class="w-full rounded-xl border-taby-border text-xs sm:text-sm">
                                <option value="FULLTIME">Full-Time WFO</option>
                                <option value="FREELANCE">Freelance Per-Sesi</option>
                                <option value="RETAINER">Retainer Partner</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Gaji Pokok / Rate Per Sesi (Rp)</label>
                        <input type="number" name="base_salary_or_rate" x-model="currentEmployee.base_salary_or_rate" required class="w-full rounded-xl border-taby-border text-xs sm:text-sm font-bold">
                    </div>

                    <div class="grid grid-cols-2 gap-2.5 sm:gap-3">
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Bank</label>
                            <input type="text" name="bank_name" x-model="currentEmployee.bank_name" required class="w-full rounded-xl border-taby-border text-xs sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Nomor Rekening</label>
                            <input type="text" name="bank_account_number" x-model="currentEmployee.bank_account_number" class="w-full rounded-xl border-taby-border text-xs sm:text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5 sm:gap-3">
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Atas Nama Rekening</label>
                            <input type="text" name="bank_account_name" x-model="currentEmployee.bank_account_name" class="w-full rounded-xl border-taby-border text-xs sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Lokasi Kerja</label>
                            <select name="location" x-model="currentEmployee.location" class="w-full rounded-xl border-taby-border text-xs sm:text-sm">
                                <option value="KEMANG">Kemang, Jaksel</option>
                                <option value="SURABAYA">Surabaya</option>
                                <option value="REMOTE">Remote / Online</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-taby-border">
                        <button type="button" @click="editModalOpen = false" class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold text-taby-muted hover:bg-cream-200">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-taby-lilac border-2 border-taby-lilac-border font-bold text-xs sm:text-sm text-taby-charcoal shadow-pastel">
                            Simpan Perubahan ✨
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
