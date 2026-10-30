# AGENTS.md — Onboarding & System Instructions

## 1. Tech Stack
Project ini dibangun menggunakan **Laravel 10 (PHP 8.2+)** dan **MySQL 8.0** sebagai backend & database. Antarmuka menggunakan **Blade Templating** yang diperkaya dengan **Tailwind CSS 3.x** (palet pastel "Angy Taby") dan **Alpine.js** untuk micro-interactions (Cat Mood Mascot, dialogs, form calc). Pembuatan slip gaji PDF menggunakan **barryvdh/laravel-dompdf**, dan iconography menggunakan **Lucide Icons**.

---

## 2. Struktur Folder Project
```text
Client-Prototype-Web/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php   # Cat Mood status & cashflow summary
│   │   │   ├── EmployeeController.php    # WFO, Freelance, Retainer CRUD
│   │   │   ├── PayrollController.php     # Hitung gaji & status pembayaran
│   │   │   ├── TransactionController.php # Cash In & Cash Out operasional
│   │   │   └── SlipGajiController.php    # Preview & Export PDF slip gaji
│   │   └── Requests/                     # Validasi form (PayrollRequest, etc.)
│   ├── Models/
│   │   ├── Employee.php
│   │   ├── PayrollSlip.php
│   │   └── Transaction.php
│   └── Services/
│       ├── CatMoodService.php            # Rule engine penentu mood kucing (Hapy/Angy/Zen)
│       └── PayrollCalculatorService.php  # Rumus kalkulasi 3 tipe pekerja
├── database/
│   ├── migrations/                       # Schema tabel: employees, payroll_slips, transactions
│   └── seeders/                          # Dummy data: Tim Kemang, talent TikTok, ads retainer
├── resources/
│   ├── css/app.css                       # Tailwind + pattern grid background custom
│   ├── js/app.js                         # Alpine.js init
│   └── views/
│       ├── components/                   # Reusable UI (cat-mascot.blade.php, stat-card.blade.php)
│       ├── layouts/app.blade.php         # Layout utama bertema pastel grid
│       ├── dashboard/index.blade.php     # Hero Taby's Mood + summary cards
│       ├── payroll/                      # Daftar gaji, input komponen, modal slip
│       ├── transactions/                 # Form cepat arus kas & filter kategori
│       └── pdf/slip-gaji.blade.php       # Template PDF estetik dengan stempel kucing
├── routes/
│   └── web.php                           # Route web monolith
├── AGENTS.md                             # File ini
├── product.md                            # Referensi kebutuhan & domain bisnis
└── PRD.md                                # PRD master
```

---

## 3. Konvensi Kode
* **Naming:**
  * Models & Controllers: `PascalCase` (`PayrollSlip`, `TransactionController`).
  * Database tables & columns: `snake_case` plural untuk tabel (`payroll_slips`), singular untuk FK (`employee_id`).
  * Views: `kebab-case` (`slip-gaji.blade.php`).
  * Methods & Variables: `camelCase` (`calculateNetSalary()`, `$totalInflow`).
  * Routes: `kebab-case` (`/payroll-slips`, `/cash-flow`).
* **Pola Wajib (Architecture):**
  * **Skinny Controller, Fat Service:** Logika rumus gaji dilarang di Controller; wajib masuk ke `PayrollCalculatorService`. Logika status kucing masuk ke `CatMoodService`.
  * **Blade Components First:** Gunakan `<x-cat-mascot :mood="$mood" />` untuk menjaga konsistensi visual maskot kucing.
  * **Explicit Number Formatting:** Semua nominal rupiah wajib diformat via helper standard `number_format($amount, 0, ',', '.')` atau Blade directive `@rupiah($val)`.

---

## 4. Perintah Run / Test / Build
```bash
# Setup dependency
composer install
npm install

# Setup env & database
cp .env.example .env
php artisan key:generate
php artisan migrate --seed

# Menjalankan server lokal (Laragon / Terminal)
php artisan serve
npm run dev

# Build assets untuk production
npm run build

# Menjalankan unit/feature test
php artisan test
```

---

## 5. Yang TIDAK Boleh Dilakukan (Strict Guardrails)
1. **DILARANG membuat sistem Akuntansi Double-Entry:** Jangan membuat jurnal debet/kredit, buku besar, neraca aktiva-pasiva, atau depresiasi aset. Sistem ini murni **Single-Entry Cashflow (Kas Masuk & Kas Keluar)**.
2. **DILARANG integrasi hardware absensi/GPS:** Jangan membuat modul fingerprint, selfie tracking, atau verifikasi geofencing.
3. **DILARANG membuat sistem pajak formal rumit:** Jangan menambah perhitungan otomatis e-Faktur PPN atau form potong PPh 21 tarif pasal 17 dinamis.
4. **DILARANG merusak tema visual "Angy Taby":** Jangan gunakan styling enterprise kaku (abu-abu gelap, bootstrap default, atau tabel flat membosankan). Wajib konsisten dengan pastel paper grid, rounded corners, dan tone ceria.
5. **DILARANG over-engineer Multi-Tenancy / SaaS:** Sistem ini dirancang untuk single studio/agency milik Chansa Panggabean. Jangan memasang package multi-tenant yang menambah kompleksitas query.
