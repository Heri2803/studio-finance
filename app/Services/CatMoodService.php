<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class CatMoodService
{
    /**
     * Tentukan status mood kucing untuk Dashboard utama.
     *
     * @param float $netProfit Selisih Kas Masuk - Kas Keluar
     * @param float $targetProfit Target laba bersih bulanan
     * @param int $pendingPayrollCount Jumlah slip gaji berstatus PENDING
     * @param Carbon|null $currentDate Tanggal evaluasi (default: hari ini)
     * @return array{mood: string, title: string, quote: string, bg_color: string, text_color: string, badge_class: string}
     */
    public function determineMood(
        float $netProfit,
        float $targetProfit = 20000000,
        int $pendingPayrollCount = 0,
        ?Carbon $currentDate = null
    ): array {
        $today = $currentDate ?? Carbon::now();
        $isEndOfMonth = $today->day >= 25;

        // 1. Kondisi Alert: H-akhir bulan (tgl >= 25) dan masih ada tim belum gajian
        if ($isEndOfMonth && $pendingPayrollCount > 0) {
            return [
                'mood' => 'alert',
                'title' => "Pengingat Gaji ⏰",
                'quote' => "Ada {$pendingPayrollCount} tim yang belum gajian lho! Yuk dicek dan segera ditransfer ya Kak Chansa~ 🐾",
                'bg_color' => 'bg-taby-yellow-light',
                'text_color' => 'text-taby-yellow-dark',
                'badge_class' => 'bg-taby-yellow text-taby-yellow-dark border-taby-yellow-border',
            ];
        }

        // 2. Kondisi Angy: Kas Defisit / Rugi
        if ($netProfit < 0) {
            return [
                'mood' => 'angy',
                'title' => "Status Mood: Angy! 😾",
                'quote' => "Waduh bulan ini masih tekor! Yuk rem pengeluaran ad spend atau percepat pelunasan project jasa klien! 😿",
                'bg_color' => 'bg-taby-rose-light',
                'text_color' => 'text-taby-rose-dark',
                'badge_class' => 'bg-taby-rose text-taby-rose-dark border-taby-rose-border',
            ];
        }

        // 3. Kondisi Hapy: Net profit mencapai / melebihi target
        if ($netProfit >= $targetProfit) {
            return [
                'mood' => 'hapy',
                'title' => "Status Mood: Super Hapy! 😻✨",
                'quote' => "Yay keuangan studio sehat dan target omset tercapai! Kerja keras terbayar lunas Kak Chansa! 🎉",
                'bg_color' => 'bg-taby-mint-light',
                'text_color' => 'text-taby-mint-dark',
                'badge_class' => 'bg-taby-mint text-taby-mint-dark border-taby-mint-border',
            ];
        }

        // 4. Kondisi Zen / Santai: Surplus aman tapi di bawah target
        return [
            'mood' => 'zen',
            'title' => "Status Mood: Chill & Zen ☕",
            'quote' => "Arus kas aman dan surplus. Pelan-pelan target omset bulan ini pasti tercapai! Tetap semangat ya~ ✨",
            'bg_color' => 'bg-taby-lilac-light',
            'text_color' => 'text-taby-lilac-dark',
            'badge_class' => 'bg-taby-lilac text-taby-lilac-dark border-taby-lilac-border',
        ];
    }

    /**
     * Tentukan status mood kucing untuk Halaman Penggajian Tim (Payroll).
     *
     * @param Collection $slips Koleksi slip gaji periode aktif
     * @param float $netCashflow Sisa kas studio bulan berjalan
     * @param int $activeEmployeesWithoutSlip Pekerja aktif yang belum dibuatkan slip
     * @return array{mood: string, title: string, quote: string, bg_color: string, text_color: string, badge_class: string}
     */
    public function determinePayrollMood(Collection $slips, float $netCashflow = 0, int $activeEmployeesWithoutSlip = 0): array
    {
        $totalSlips = $slips->count();
        $pendingCount = $slips->where('status', 'PENDING')->count();
        $paidCount = $slips->where('status', 'PAID')->count();

        // 1. Belum ada slip gaji sama sekali
        if ($totalSlips === 0) {
            $msg = $activeEmployeesWithoutSlip > 0
                ? "Periode baru telah tiba! Ada {$activeEmployeesWithoutSlip} pekerja aktif yang siap digenerate. Klik 'Draft Gaji' untuk mulai kalkulasi ya~ ✨"
                : "Belum ada draft slip gaji untuk periode ini. Tambahkan data karyawan atau klik 'Draft Gaji' untuk memulai! 📋";

            return [
                'mood' => 'payroll_zen',
                'title' => 'Payroll Butler 💵🐾',
                'quote' => $msg,
                'bg_color' => 'bg-taby-lilac-light',
                'text_color' => 'text-taby-lilac-dark',
                'badge_class' => 'bg-taby-lilac text-taby-lilac-dark border-taby-lilac-border',
            ];
        }

        // 2. Ada gaji pending SEMENTARA kas studio sedang defisit / tekor
        if ($pendingCount > 0 && $netCashflow < 0) {
            $tekorFormatted = 'Rp ' . number_format(abs($netCashflow), 0, ',', '.');
            return [
                'mood' => 'payroll_worried',
                'title' => 'Waduh Kas Tipis! 🙀💦',
                'quote' => "Kas studio lagi tekor {$tekorFormatted}, tapi ada {$pendingCount} slip gaji menunggu dibayar. Cek pos pemasukan & tagih DP klien dulu ya Kak Chansa! 😿",
                'bg_color' => 'bg-taby-rose-light',
                'text_color' => 'text-taby-rose-dark',
                'badge_class' => 'bg-taby-rose text-taby-rose-dark border-taby-rose-border',
            ];
        }

        // 3. Ada gaji pending yang perlu ditransfer
        if ($pendingCount > 0) {
            return [
                'mood' => 'payroll_alert',
                'title' => "Payroll Reminder 💵⏰",
                'quote' => "Waktunya bagi rezeki! Ada {$pendingCount} slip gaji menunggu ditransfer. Sekali klik 'Bayar' otomatis lunas dan tercatat di buku kas lho~ 🐾",
                'bg_color' => 'bg-taby-yellow-light',
                'text_color' => 'text-taby-yellow-dark',
                'badge_class' => 'bg-taby-yellow text-taby-yellow-dark border-taby-yellow-border',
            ];
        }

        // 4. Semua slip lunas
        return [
            'mood' => 'payroll_happy',
            'title' => 'Semua Gaji Lunas! 🎉',
            'quote' => "Hore! Seluruh {$paidCount} tim Kemang & talent TikTok sudah lunas gajian! Kak Chansa emang boss idaman terbaik~ 💖✨",
            'bg_color' => 'bg-taby-mint-light',
            'text_color' => 'text-taby-mint-dark',
            'badge_class' => 'bg-taby-mint text-taby-mint-dark border-taby-mint-border',
        ];
    }

    /**
     * Tentukan status mood kucing untuk Halaman Karyawan & Talent.
     *
     * @param Collection $employees Koleksi seluruh pekerja
     * @return array{mood: string, title: string, quote: string, bg_color: string, text_color: string, badge_class: string}
     */
    public function determineEmployeeMood(Collection $employees): array
    {
        $total = $employees->count();

        // 1. Belum ada karyawan terdaftar
        if ($total === 0) {
            return [
                'mood' => 'talent_zen',
                'title' => 'Talent Scout 👥🐾',
                'quote' => 'Studio Kemang masih sepi nih Kak Chansa! Yuk rekrut video creator WFO atau model sesi TikTok pertamamu! ➕',
                'bg_color' => 'bg-taby-lilac-light',
                'text_color' => 'text-taby-lilac-dark',
                'badge_class' => 'bg-taby-lilac text-taby-lilac-dark border-taby-lilac-border',
            ];
        }

        // 2. Ada karyawan yang nomor rekening banknya belum diisi
        $missingBankCount = $employees->filter(function ($e) {
            return empty(trim((string)$e->bank_account_number)) || empty(trim((string)$e->bank_name));
        })->count();

        if ($missingBankCount > 0) {
            return [
                'mood' => 'talent_alert',
                'title' => "Scout Warning! 🔍⚠️",
                'quote' => "Perhatian Kak Chansa! Ada {$missingBankCount} pekerja yang belum mengisi nomor rekening bank. Nanti transfer gaji bisa nyasar lho! 🐾",
                'bg_color' => 'bg-taby-rose-light',
                'text_color' => 'text-taby-rose-dark',
                'badge_class' => 'bg-taby-rose text-taby-rose-dark border-taby-rose-border',
            ];
        }

        // 3. Semua data lengkap dan rapi
        $freelanceCount = $employees->where('employment_type', 'FREELANCE')->count();
        $freelanceText = $freelanceCount > 0 ? " ({$freelanceCount} talent TikTok aktif)" : "";

        return [
            'mood' => 'talent_happy',
            'title' => 'Talent Scout 👥✨',
            'quote' => "Data seluruh {$total} tim Kemang{$freelanceText} rapi dan lengkap! Siap gas produksi konten & campaign iklan! 🎬💖",
            'bg_color' => 'bg-taby-mint-light',
            'text_color' => 'text-taby-mint-dark',
            'badge_class' => 'bg-taby-mint text-taby-mint-dark border-taby-mint-border',
        ];
    }

    /**
     * Tentukan status mood kucing untuk Halaman Buku Kas (Transactions).
     *
     * @param array $stats Ringkasan arus kas ['total_inflow', 'total_outflow', 'net_cashflow']
     * @param int $transactionCount Jumlah transaksi periode berjalan
     * @return array{mood: string, title: string, quote: string, bg_color: string, text_color: string, badge_class: string}
     */
    public function determineTransactionMood(array $stats, int $transactionCount): array
    {
        // 1. Belum ada transaksi
        if ($transactionCount === 0) {
            return [
                'mood' => 'bookkeeper_zen',
                'title' => 'Inspector Kas 📝🐾',
                'quote' => 'Buku kas bulan ini masih bersih. Jangan lupa catat uang muka (DP) project jasa klien atau biaya operasional pertama ya! ✨',
                'bg_color' => 'bg-taby-lilac-light',
                'text_color' => 'text-taby-lilac-dark',
                'badge_class' => 'bg-taby-lilac text-taby-lilac-dark border-taby-lilac-border',
            ];
        }

        // 2. Kas Defisit / Rugi
        if ($stats['net_cashflow'] < 0) {
            $tekorFormatted = 'Rp ' . number_format(abs($stats['net_cashflow']), 0, ',', '.');
            return [
                'mood' => 'bookkeeper_angy',
                'title' => "Cat Mood: Angy Inspector! 😾",
                'quote' => "Waduh bulan ini masih tekor {$tekorFormatted}! Sedang memeriksa pos belanja ads & operasional yang bengkak, yuk rem pengeluaran dulu! 😿",
                'bg_color' => 'bg-taby-rose-light',
                'text_color' => 'text-taby-rose-dark',
                'badge_class' => 'bg-taby-rose text-taby-rose-dark border-taby-rose-border',
            ];
        }

        // 3. Kas Surplus Sehat
        $surplusFormatted = 'Rp ' . number_format($stats['net_cashflow'], 0, ',', '.');
        return [
            'mood' => 'bookkeeper_happy',
            'title' => 'Inspector Kas 😻💰',
            'quote' => "Kas studio surplus {$surplusFormatted}! DP klien masuk lancar dan arus kas aman terkendali Kak Chansa! 💰✨",
            'bg_color' => 'bg-taby-mint-light',
            'text_color' => 'text-taby-mint-dark',
            'badge_class' => 'bg-taby-mint text-taby-mint-dark border-taby-mint-border',
        ];
    }
}
