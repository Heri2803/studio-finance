# Panduan Skenario Pengujian (QA & Testing Scenarios)
## TabyFinance (PawRoll) — Cute Cat Financial & Payroll Companion

Dokumen ini memandu pengujian menyeluruh (*User Acceptance Testing*) terhadap seluruh fitur yang telah dibangun. Skenario disusun berdasarkan alur kerja nyata pemilik bisnis (**Kak Chansa**), mulai dari pemantauan kas harian, pengelolaan talent freelance, pembayaran gaji, cetak slip PDF, hingga verifikasi reaksi maskot kucing.

---

## Ringkasan Matriks Pengujian

| ID | Modul / Fitur | Fokus Pengujian | Tingkat Kritis |
| :--- | :--- | :--- | :--- |
| **TC-01** | Dashboard & Cat Mood Engine | Reaksi emosi maskot kucing terhadap surplus vs defisit kas | Tinggi |
| **TC-02** | Manajemen Karyawan & Talent | CRUD pekerja dengan 3 tipe kontrak (WFO, Freelance, Retainer) | Sedang |
| **TC-03** | Penggajian Hybrid | Kalkulasi otomatis gaji tetap, sesi shooting, dan bonus FYP | Tinggi |
| **TC-04** | Eksekusi Bayar Gaji & Sync | Sinkronisasi otomatis gaji lunas ke buku kas keluar (Bebas duplikasi) | **Kritis** |
| **TC-05** | Generator Slip Gaji PDF | Kerapian cetak dokumen PDF A5 dan tombol kirim WhatsApp | Tinggi |
| **TC-06** | Buku Kas Operasional | Pencatatan kas masuk/keluar & filter lokasi Kemang vs Surabaya | Sedang |

---

## Rincian Skenario & Langkah Pengujian

### Skenario 1: Dashboard & Reaktivitas Maskot Kucing ("Angy Taby" Engine)

#### Kasus 1.1: Pemantauan Kondisi Kas Sehat (State `Hapy`)
* **Tujuan:** Memastikan maskot kucing menyemangati owner saat kas studio aman dan profit surplus.
* **Langkah Uji:**
  1. Buka browser dan akses `http://127.0.0.1:8000/dashboard`.
  2. Perhatikan banner atas (Hero Taby's Mood) dan kartu ringkasan keuangan.
* **Ekspektasi Hasil:**
  * Maskot Taby tampil dalam ekspresi **Super Hapy** (mata berbinar, bandana hijau, ada kilauan bintang).
  * Balon percakapan berwarna hijau mint dengan ucapan: *"Yay keuangan studio sehat dan target omset tercapai! Kerja keras terbayar lunas Kak Chansa! 🎉"*.
  * Kartu metrik menampilkan: Pemasukan $\ge$ Rp 48jt, Pengeluaran $\approx$ Rp 20jt, Laba Bersih surplus.
  * Tabel **Realita vs Target** menunjukkan status *Hapy* dengan badge hijau mint.

#### Kasus 1.2: Simulasi Kondisi Kas Defisit / Tekor (State `Angy`)
* **Tujuan:** Memastikan maskot kucing berubah cemberut saat pengeluaran melebihi pemasukan.
* **Langkah Uji:**
  1. Masuk ke menu **Buku Kas** (`/transactions`).
  2. Klik tombol **"+ Catat Transaksi Cepat"**.
  3. Pilih **Pengeluaran (-)**, Kategori: *Operasional Studio Kemang*, Nominal: **Rp 35.000.000**, Deskripsi: *"Simulasi Biaya Renovasi & Upgrade Kamera Studio"*, Lokasi: *Kemang*. Klik **Simpan Transaksi**.
  4. Kembali ke halaman **Dashboard** (`/dashboard`).
* **Ekspektasi Hasil:**
  * Laba bersih berubah menjadi **minus (defisit)**.
  * Maskot Taby langsung berubah menjadi **Angy Taby** (alis marah lucu, pipi merah merona, dasi ungu, simbol kesal merah).
  * Balon percakapan berubah menjadi warna soft rose dengan pesan: *"Waduh bulan ini masih tekor! Yuk rem pengeluaran ad spend atau percepat pelunasan project jasa klien! 😾"*.
  * *(Catatan: Hapus kembali transaksi Rp 35jt ini setelah selesai uji coba).*

---

### Skenario 2: Manajemen Karyawan & Talent (Hybrid Contract CRUD)

#### Kasus 2.1: Menambah Talent Freelance Baru (Model TikTok Kedua)
* **Tujuan:** Memvalidasi fleksibilitas sistem dalam menambah pekerja lepas per-sesi.
* **Langkah Uji:**
  1. Buka menu **Karyawan & Talent** (`/employees`).
  2. Klik tombol **"+ Tambah Pekerja Baru"**.
  3. Masukkan data:
     * Nama: **Clarissa Putri**
     * Posisi: **Model Konten TikTok Hijab**
     * Tipe Kontrak: **Freelance Per-Sesi**
     * Rate Per Sesi: **600000**
     * Bank: **BCA**, No Rekening: **7120982311**, Atas Nama: **Clarissa Putri**
     * Lokasi: **Kemang, Jaksel**
  4. Klik **Simpan Pekerja 🐾**.
* **Ekspektasi Hasil:**
  * Muncul notifikasi sukses: *"Pekerja/Talent baru berhasil ditambahkan! 🐾"*.
  * Clarissa Putri muncul di tabel dengan badge kuning **Freelance Sesi** dan rate `Rp 600.000 / sesi`.
  * Kartu ringkasan *Freelance Talent* bertambah menjadi 2 talent.

#### Kasus 2.2: Mengubah Gaji Pokok Tim WFO
* **Tujuan:** Memastikan fitur edit data pekerja berjalan instan.
* **Langkah Uji:**
  1. Pada baris **Dian Pratama** (Video Creator), klik tombol **"✏️ Edit"**.
  2. Ubah nominal gaji pokok dari `6000000` menjadi `6500000`.
  3. Klik **Simpan Perubahan ✨**.
* **Ekspektasi Hasil:**
  * Nominal di tabel langsung ter-update menjadi `Rp 6.500.000 / bulan`.

---

### Skenario 3: Modul Penggajian Hybrid (Kalkulator Sesi & Bonus)

#### Kasus 3.1: Generate Draft Payroll Pekerja Baru
* **Tujuan:** Menguji otomasi pembuatan draf slip gaji untuk seluruh pekerja aktif.
* **Langkah Uji:**
  1. Buka menu **Penggajian Tim** (`/payroll`).
  2. Perhatikan tombol kuning di kanan atas: **"✨ Generate Draft (1)"**. Klik tombol tersebut.
* **Ekspektasi Hasil:**
  * Draft slip gaji untuk Clarissa Putri otomatis terbuat untuk periode berjalan.
  * Status awal otomatis bertuliskan **Pending ⏳**.

#### Kasus 3.2: Penyesuaian Sesi Shooting & Bonus Reward FYP
* **Tujuan:** Menguji rumus perhitungan kompensasi freelance.
* **Langkah Uji:**
  1. Pada baris **Bella Safira** (Model TikTok), klik tombol **"✏️ Edit"**.
  2. Ubah komponen:
     * Jumlah Sesi Shooting: Ubah dari `4` menjadi **`6`** (6 x Rp 500.000 = Rp 3.000.000).
     * Bonus: Masukkan **`500000`** (Reward video tembus 2M views).
     * Potongan: Masukkan **`200000`** (Kasbon transport).
  3. Klik **Simpan Perhitungan 🐾**.
* **Ekspektasi Hasil:**
  * Formula berjalan otomatis: $(6 \times 500.000) + 100.000 (\text{tunjangan}) + 500.000 (\text{bonus}) - 200.000 (\text{potongan})$.
  * Total *Take Home Pay* di tabel berubah tepat menjadi **Rp 3.400.000**.

---

### Skenario 4: Pembayaran Gaji & Sinkronisasi Kas Keluar (Cross-Module Sync)

> [!IMPORTANT]
> Ini adalah titik kritis fungsional untuk memastikan tidak ada pencatatan ganda atau saldo kas yang selisih.

#### Kasus 4.1: Eksekusi Tombol "Bayar Sekarang 🐾"
* **Tujuan:** Memverifikasi bahwa gaji yang dibayar otomatis masuk ke buku kas keluar.
* **Langkah Uji:**
  1. Di halaman **Penggajian Tim** (`/payroll`), cari slip gaji **Rian Hidayat** (Ads Specialist) yang berstatus **Pending ⏳** senilai Rp 4.000.000.
  2. Klik tombol hijau **"🐾 Bayar"**.
  3. Perhatikan perubahan pada halaman tersebut.
  4. Sekarang, buka menu **Buku Kas** (`/transactions`).
* **Ekspektasi Hasil:**
  * Di halaman payroll: Status slip Rian berubah menjadi **Paid 🐾** dengan tanggal & jam transfer.
  * Di halaman buku kas: Muncul otomatis satu baris transaksi pengeluaran baru:
    * Kategori: **Gaji & Honor Tim**
    * Deskripsi: *"Gaji Oktober 2026 - Rian Hidayat (Meta & TikTok Ads Specialist)"*
    * Badge: **Payroll Auto 🐾** (Terkunci, tidak bisa dihapus sembarangan).
    * Nominal: **- Rp 4.000.000** (warna merah).
  * Di dashboard: Total pengeluaran otomatis bertambah Rp 4.000.000 dan sisa kas berkurang secara sinkron.

#### Kasus 4.2: Pembatalan Pembayaran (Revert Idempotency)
* **Tujuan:** Memastikan pembatalan bayar menghapus pengeluaran kas secara bersih tanpa sisa.
* **Langkah Uji:**
  1. Di halaman **Penggajian Tim**, klik tombol abu-abu **"Batalkan"** pada slip Rian Hidayat. Konfirmasi dialog.
  2. Buka menu **Buku Kas** (`/transactions`).
* **Ekspektasi Hasil:**
  * Status slip Rian kembali menjadi **Pending ⏳**.
  * Transaksi kas keluar Rp 4.000.000 tadi **otomatis terhapus bersih** dari tabel buku kas.
  * Saldo kas kembali bertambah ke nilai awal tanpa ada selisih angka.

---

### Skenario 5: Generator Slip Gaji PDF & Distribusi WhatsApp

#### Kasus 5.1: Unduh & Pratinjau Dokumen PDF Slip Gaji
* **Tujuan:** Memastikan file PDF tercetak rapi sesuai standar visual "Angy Taby".
* **Langkah Uji:**
  1. Pada salah satu slip gaji yang berstatus *Paid* (contoh: Dian Pratama), klik tombol **"📄 PDF"**.
* **Ekspektasi Hasil:**
  * Browser membuka dokumen PDF A5 Landscape.
  * Tata letak rapi, font terbaca jelas, tidak ada teks bertumpuk atau tabel pecah.
  * Menampilkan:
    * Header resmi: *🐾 STUDIO KEMANG CREATIVE*.
    * Rincian hak & potongan yang transparan.
    * Kotak hijau tebal *Total Gaji Bersih (Take Home Pay)*.
    * Stempel resmi di kanan bawah: **"🐾 VERIFIED & PAID WITH LOVE"**.

#### Kasus 5.2: Fitur Quick Share Pesan WhatsApp
* **Tujuan:** Memudahkan Kak Chansa mengabari tim via WA dalam 1 detik.
* **Langkah Uji:**
  1. Klik tombol balon chat hijau **"💬"** di samping tombol PDF.
* **Ekspektasi Hasil:**
  * Membuka tab baru WhatsApp (`wa.me`) dengan draf pesan otomatis:  
    *"Halo Kak Dian Pratama! 🌸 Slip gaji periode Oktober 2026 dari Studio Kemang sudah selesai dihitung ya. Total Take Home Pay: Rp 7.000.000. Terima kasih atas kerja kerasnya! 🐾✨"*.

---

### Skenario 6: Buku Kas & Filter Arus Kas Operasional

#### Kasus 6.1: Catat Pemasukan Pelunasan Project Klien
* **Tujuan:** Mencatat kas masuk jasa studio.
* **Langkah Uji:**
  1. Buka menu **Buku Kas** (`/transactions`).
  2. Klik **"+ Catat Transaksi Cepat"**.
  3. Pilih tab **Kas Masuk (+)**, Kategori: *Pelunasan Proyek*, Nominal: **10000000**, Deskripsi: *"Pelunasan Video Reels Skincare Client D"*, Lokasi: *Kemang*.
  4. Klik **Simpan Transaksi 🐾**.
* **Ekspektasi Hasil:**
  * Transaksi baru muncul paling atas dengan warna hijau **+ Rp 10.000.000**.
  * Total Kas Masuk (Inflow) langsung bertambah Rp 10.000.000.

#### Kasus 6.2: Filter Berdasarkan Jenis & Lokasi
* **Tujuan:** Memisahkan pengeluaran studio fisik di Kemang vs ad spend online.
* **Langkah Uji:**
  1. Klik tombol filter **"Pengeluaran (-)"**.
  2. Pilih dropdown lokasi: **"Kemang"**, klik **Filter**.
* **Ekspektasi Hasil:**
  * Tabel hanya memunculkan pengeluaran fisik yang terjadi di Kemang (Sewa studio, properti shooting, gaji Dian & Bella).
  * Pengeluaran online seperti *Saldo Iklan Meta Ads* dan *Subscription Adobe* tersembunyi dari tampilan.
