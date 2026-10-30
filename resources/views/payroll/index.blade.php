@extends('layouts.app')

@section('content')
<div x-data="{ editModalOpen: false, currentSlip: {} }" class="space-y-5 sm:space-y-6">
    
    <!-- Top Header Bar & Period Selector -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-1">
        <div>
            <h1 class="text-base sm:text-xl font-extrabold font-display text-taby-charcoal flex items-center gap-1.5">
                <span>💵</span> Penggajian Tim & Honor
            </h1>
            <p class="text-[11px] sm:text-xs text-taby-muted mt-0.5">Kalkulasi gaji tetap Kemang, sesi TikTok & retainer</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
            <form method="GET" action="{{ route('payroll.index') }}" class="flex items-center gap-1.5">
                <input type="text" name="period" value="{{ $period }}" placeholder="Bulan Tahun" aria-label="Filter Periode Gaji" class="rounded-xl border-taby-border text-xs px-2.5 py-1.5 w-28 min-[380px]:w-36 sm:w-40 bg-white font-bold text-taby-charcoal shadow-sm">
                <button type="submit" class="px-3 py-1.5 min-h-[36px] rounded-xl bg-taby-lilac border border-taby-lilac-border text-xs font-bold shadow-pastel">
                    Pilih
                </button>
            </form>

            @if($activeEmployeesWithoutSlip > 0)
                <form method="POST" action="{{ route('payroll.generate') }}">
                    @csrf
                    <input type="hidden" name="period" value="{{ $period }}">
                    <button type="submit" class="px-3 py-1.5 min-h-[36px] sm:px-4 sm:py-2 rounded-xl sm:rounded-2xl bg-taby-yellow border-2 border-taby-yellow-border font-bold text-xs text-taby-charcoal shadow-pastel hover:translate-y-[-1px] transition flex items-center gap-1 shrink-0">
                        <span>✨</span> Draft ({{ $activeEmployeesWithoutSlip }})
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Payroll Butler Banner (Dynamic Personality Mascot!) -->
    <div class="anim-enter rounded-3xl border-2 border-taby-border bg-white p-3.5 sm:p-5 shadow-pastel">
        <div class="flex items-center gap-3 sm:gap-4">
            <div data-cat-avatar class="p-1 sm:p-2 rounded-2xl bg-cream-100 border-2 border-taby-border shadow-sm shrink-0">
                <x-cat-mascot :mood="$catMood['mood'] ?? 'payroll'" size="md" />
            </div>
            <div data-cat-bubble class="flex-1 rounded-2xl border-2 p-3 {{ $catMood['bg_color'] ?? 'bg-taby-yellow-light' }} border-taby-border shadow-sm">
                <div class="flex items-center justify-between gap-1 mb-1">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $catMood['badge_class'] ?? 'bg-taby-yellow text-taby-yellow-dark border-taby-yellow-border' }} font-display">
                        {{ $catMood['title'] ?? 'Payroll Butler 💵🐾' }}
                    </span>
                    <span class="text-[10px] text-taby-muted font-bold">{{ $period }}</span>
                </div>
                <p class="text-xs font-bold text-taby-charcoal leading-snug">
                    "{{ $catMood['quote'] ?? 'Waktunya bagi rezeki untuk seluruh tim Kemang dan talent TikTok! 🐾' }}"
                </p>
            </div>
        </div>
    </div>

    <!-- Summary Metrics (3 Compact Cards) -->
    <div class="anim-enter anim-delay-1 grid grid-cols-3 gap-2 sm:gap-4">
        <x-stat-card 
            title="Total Beban" 
            :value="'Rp ' . number_format($stats['total_burden'], 0, ',', '.')" 
            color="cream" 
            :subtitle="$slips->count() . ' slip terdaftar'" 
        />
        <x-stat-card 
            title="Sudah Ditransfer" 
            :value="'Rp ' . number_format($stats['total_paid'], 0, ',', '.')" 
            color="mint" 
            :subtitle="$stats['count_paid'] . ' Lunas 🐾'" 
        />
        <x-stat-card 
            title="Menunggu" 
            :value="'Rp ' . number_format($stats['total_pending'], 0, ',', '.')" 
            color="yellow" 
            :subtitle="$stats['count_pending'] . ' Pending ⏳'" 
        />
    </div>

    <!-- Content Container -->
    <div class="anim-enter anim-delay-2 rounded-3xl border-2 border-taby-border bg-white p-4 sm:p-6 shadow-pastel">
        <div class="flex items-center justify-between mb-3 sm:mb-4 pb-2.5 sm:pb-3 border-b border-taby-border">
            <h2 class="text-sm sm:text-base font-bold font-display text-taby-charcoal flex items-center gap-1.5">
                <span>📑</span> Daftar Slip Gaji — {{ $period }}
            </h2>
            @if($stats['count_pending'] === 0 && $slips->count() > 0)
                <span class="text-[10px] sm:text-xs font-bold px-2.5 py-0.5 rounded-full bg-taby-mint text-taby-mint-dark border border-taby-mint-border">
                    Semua Beres! 😻
                </span>
            @endif
        </div>

        <!-- MOBILE & TABLET VIEW: App-Style Payroll Cards -->
        <div class="block lg:hidden space-y-3.5">
            @forelse($slips as $slip)
                <div class="p-3.5 rounded-2xl bg-cream-100/70 border border-taby-border space-y-3">
                    <!-- Top Row: Avatar + Name + Role + Status Badge -->
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-9 h-9 rounded-2xl bg-taby-lilac border border-taby-lilac-border flex items-center justify-center font-extrabold text-xs shrink-0 shadow-sm">
                                {{ substr($slip->employee->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <div class="font-extrabold text-xs text-taby-charcoal truncate">{{ $slip->employee->name }}</div>
                                <div class="text-[10px] text-taby-muted truncate">{{ $slip->employee->role }}</div>
                            </div>
                        </div>
                        <x-badge :type="$slip->status" class="scale-90 origin-right shrink-0" />
                    </div>

                    <!-- Middle Breakdown Row -->
                    <div class="p-2.5 rounded-xl bg-white/80 border border-taby-border/60 text-[11px] space-y-1">
                        <div class="flex items-center justify-between text-taby-muted">
                            <span>
                                @if($slip->employee->employment_type === 'FREELANCE')
                                    {{ $slip->sessions_count }} Sesi × @rupiah($slip->employee->base_salary_or_rate)
                                @else
                                    Gaji Pokok / Retainer
                                @endif
                            </span>
                            <span class="font-bold text-taby-charcoal">@rupiah($slip->basic_amount)</span>
                        </div>

                        @if($slip->allowance > 0 || $slip->bonus > 0 || $slip->deduction > 0)
                            <div class="flex items-center justify-between text-[10px] pt-1 border-t border-taby-border/40">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    @if($slip->allowance > 0)
                                        <span class="text-emerald-600 font-semibold">+Tunj: @rupiah($slip->allowance)</span>
                                    @endif
                                    @if($slip->bonus > 0)
                                        <span class="text-purple-600 font-semibold">+Bon: @rupiah($slip->bonus)</span>
                                    @endif
                                    @if($slip->deduction > 0)
                                        <span class="text-rose-600 font-semibold">-Pot: @rupiah($slip->deduction)</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Take Home Pay & Bank Account -->
                    <div class="flex items-center justify-between pt-0.5">
                        <div class="text-[10px] text-taby-muted truncate max-w-[160px]" title="{{ $slip->employee->bank_name }}: {{ $slip->employee->bank_account_number ?? 'Belum ada rekening' }}">
                            💳 {{ $slip->employee->bank_name ?: 'Bank -' }}: {{ $slip->employee->bank_account_number ?: 'Belum diisi' }}
                        </div>
                        <div class="text-right">
                            <span class="text-[9px] uppercase font-bold text-taby-muted block">Take Home Pay</span>
                            <span class="font-extrabold text-sm sm:text-base tabular-nums text-taby-charcoal">@rupiah($slip->net_pay)</span>
                        </div>
                    </div>

                    <!-- Action Buttons (Thumb-friendly row!) -->
                    <div class="flex items-center gap-1.5 pt-2 border-t border-taby-border/50">
                        @if($slip->status === 'PENDING')
                            <form action="{{ route('payroll.paid', $slip) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full min-h-[38px] py-1.5 rounded-xl bg-taby-mint hover:bg-taby-mint-border border border-taby-mint-border text-xs font-bold text-taby-mint-dark shadow-sm transition flex items-center justify-center gap-1">
                                    <span>🐾</span> Bayar
                                </button>
                            </form>
                        @else
                            <form action="{{ route('payroll.pending', $slip) }}" method="POST" onsubmit="return confirm('Kembalikan status ke PENDING?')">
                                @csrf
                                <button type="submit" class="px-2.5 min-h-[38px] py-1.5 text-[11px] rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 transition flex items-center justify-center">
                                    Batal
                                </button>
                            </form>
                        @endif

                        <button 
                            @click="currentSlip = {{ json_encode($slip) }}; editModalOpen = true" 
                            class="px-3 min-h-[38px] py-1.5 text-xs font-semibold rounded-xl bg-taby-lilac text-taby-charcoal border border-taby-lilac-border transition shadow-sm flex items-center justify-center">
                            ✏️ Edit
                        </button>

                        <a href="{{ route('slip.pdf', $slip) }}" target="_blank" rel="noopener noreferrer" aria-label="Cetak PDF Slip {{ $slip->employee->name }}" class="px-3 min-h-[38px] py-1.5 text-xs font-semibold rounded-xl bg-white text-taby-charcoal border border-taby-border transition shadow-sm flex items-center justify-center gap-1">
                            <span>📄</span> PDF
                        </a>

                        @php
                            $waText = urlencode("Halo Kak {$slip->employee->name}! 🌸\nSlip gaji periode {$slip->period} dari Studio Kemang sudah selesai dihitung ya.\nTotal Take Home Pay: Rp " . number_format($slip->net_pay, 0, ',', '.') . "\nTerima kasih atas kerja kerasnya! 🐾✨");
                        @endphp
                        <a href="https://wa.me/?text={{ $waText }}" target="_blank" rel="noopener noreferrer" aria-label="Kirim Slip via WhatsApp ke {{ $slip->employee->name }}" class="px-2.5 min-h-[38px] py-1.5 text-xs font-semibold rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 transition shadow-sm flex items-center justify-center" title="Share ke WA">
                            💬
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-taby-muted text-xs">
                    <div class="text-2xl mb-1">🐾</div>
                    Belum ada slip gaji pada periode {{ $period }}.
                </div>
            @endforelse
        </div>

        <!-- DESKTOP VIEW: Spreadsheet-Style Table (Hidden on Mobile & Tablet) -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b-2 border-taby-border bg-cream-100/70 text-xs text-taby-muted font-display uppercase tracking-wider">
                        <th class="py-3 px-4 rounded-l-xl">Pekerja</th>
                        <th class="py-3 px-4">Tipe</th>
                        <th class="py-3 px-4">Komponen Gaji</th>
                        <th class="py-3 px-4">Take Home Pay</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 rounded-r-xl text-right">Aksi & Slip</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-taby-border/50">
                    @forelse($slips as $slip)
                        <tr class="hover:bg-cream-50/50 transition">
                            <td class="py-3.5 px-4 font-medium">
                                <div class="font-bold text-taby-charcoal">{{ $slip->employee->name }}</div>
                                <div class="text-xs text-taby-muted">{{ $slip->employee->role }}</div>
                                <div class="text-[11px] text-gray-400 mt-0.5">{{ $slip->employee->bank_name }}: {{ $slip->employee->bank_account_number }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <x-badge :type="$slip->employee->employment_type" />
                            </td>
                            <td class="py-3.5 px-4 text-xs">
                                @if($slip->employee->employment_type === 'FREELANCE')
                                    <div class="font-semibold text-taby-charcoal">{{ $slip->sessions_count }} Sesi × @rupiah($slip->employee->base_salary_or_rate) = @rupiah($slip->basic_amount)</div>
                                @else
                                    <div class="font-semibold text-taby-charcoal">Pokok: @rupiah($slip->basic_amount)</div>
                                @endif

                                <div class="text-taby-muted flex items-center gap-2 mt-0.5">
                                    @if($slip->allowance > 0)
                                        <span class="text-emerald-600">+Tunj: @rupiah($slip->allowance)</span>
                                    @endif
                                    @if($slip->bonus > 0)
                                        <span class="text-purple-600">+Bonus: @rupiah($slip->bonus)</span>
                                    @endif
                                    @if($slip->deduction > 0)
                                        <span class="text-rose-600">-Pot: @rupiah($slip->deduction)</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-base text-taby-charcoal tabular-nums">
                                @rupiah($slip->net_pay)
                            </td>
                            <td class="py-3.5 px-4">
                                <x-badge :type="$slip->status" />
                                @if($slip->paid_at)
                                    <div class="text-[10px] text-taby-muted mt-0.5">
                                        {{ $slip->paid_at->format('d M H:i') }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($slip->status === 'PENDING')
                                        <form action="{{ route('payroll.paid', $slip) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-taby-mint hover:bg-taby-mint-border border border-taby-mint-border text-xs font-bold text-taby-mint-dark shadow-sm transition flex items-center gap-1">
                                                <span>🐾</span> Bayar
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('payroll.pending', $slip) }}" method="POST" onsubmit="return confirm('Kembalikan status ke PENDING?')">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 text-[11px] rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 transition">
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif

                                    <button 
                                        @click="currentSlip = {{ json_encode($slip) }}; editModalOpen = true" 
                                        class="px-2.5 py-1.5 text-xs font-semibold rounded-xl bg-taby-lilac hover:bg-taby-lilac-border text-taby-charcoal border border-taby-lilac-border transition">
                                        ✏️ Edit
                                    </button>

                                    <a href="{{ route('slip.pdf', $slip) }}" target="_blank" class="px-2.5 py-1.5 text-xs font-semibold rounded-xl bg-cream-200 hover:bg-cream-300 text-taby-charcoal border border-taby-border transition flex items-center gap-1">
                                        <span>📄</span> PDF
                                    </a>

                                    <a href="https://wa.me/?text={{ $waText }}" target="_blank" class="px-2 py-1.5 text-xs font-semibold rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 transition" title="Kirim ke WhatsApp">
                                        💬
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-taby-muted">
                                <div class="text-3xl mb-2">🐾</div>
                                Belum ada slip gaji pada periode {{ $period }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Edit Komponen Gaji (Mobile-optimized!) -->
    <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-taby-charcoal/40 backdrop-blur-sm" @click="editModalOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-3 sm:p-4">
            <div class="relative w-full max-w-lg rounded-3xl bg-white p-5 sm:p-6 shadow-pastel-lg border-2 border-taby-border">
                <div class="flex items-center justify-between mb-4 border-b border-taby-border pb-3">
                    <div>
                        <h3 class="font-display font-bold text-base sm:text-lg text-taby-charcoal flex items-center gap-2">
                            <span>✏️</span> Sesuaikan Komponen Gaji
                        </h3>
                        <p class="text-xs text-taby-muted mt-0.5" x-text="currentSlip.employee ? currentSlip.employee.name + ' (' + currentSlip.employee.role + ')' : ''"></p>
                    </div>
                    <button @click="editModalOpen = false" type="button" aria-label="Tutup modal edit gaji" class="text-taby-muted hover:text-taby-charcoal font-bold p-1.5 w-7 h-7 flex items-center justify-center rounded-full hover:bg-cream-200">✕</button>
                </div>

                <form :action="'/payroll/' + currentSlip.id + '/update-component'" method="POST" class="space-y-3.5">
                    @csrf
                    @method('PUT')

                    <template x-if="currentSlip.employee && currentSlip.employee.employment_type === 'FREELANCE'">
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Jumlah Sesi Shooting</label>
                            <input type="number" name="sessions_count" x-model="currentSlip.sessions_count" min="0" class="w-full rounded-xl border-taby-border text-xs sm:text-sm font-bold">
                            <span class="text-[10px] text-taby-muted">Rate per sesi: Rp <span x-text="Number(currentSlip.employee.base_salary_or_rate).toLocaleString('id-ID')"></span></span>
                        </div>
                    </template>

                    <div class="grid grid-cols-2 gap-2.5 sm:gap-3">
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Tunjangan (Rp)</label>
                            <input type="number" name="allowance" x-model="currentSlip.allowance" min="0" class="w-full rounded-xl border-taby-border text-xs sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Bonus/FYP (Rp)</label>
                            <input type="number" name="bonus" x-model="currentSlip.bonus" min="0" class="w-full rounded-xl border-taby-border text-xs sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Potongan Kasbon (Rp)</label>
                        <input type="number" name="deduction" x-model="currentSlip.deduction" min="0" class="w-full rounded-xl border-taby-border text-xs sm:text-sm text-rose-600 font-bold">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase text-taby-muted mb-1">Catatan</label>
                        <textarea name="notes" x-model="currentSlip.notes" rows="2" placeholder="Catatan bonus atau potongan..." class="w-full rounded-xl border-taby-border text-xs sm:text-sm"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-taby-border">
                        <button type="button" @click="editModalOpen = false" class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold text-taby-muted hover:bg-cream-200">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-taby-yellow border-2 border-taby-yellow-border font-bold text-xs sm:text-sm text-taby-charcoal shadow-pastel">
                            Simpan Perhitungan 🐾
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
