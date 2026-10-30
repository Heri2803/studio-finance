# product.md — Ringkasan Kebutuhan & Domain Bisnis

> **Tujuan Dokumen:** Acuan singkat domain bisnis untuk AI coding assistant. Jangan ubah aturan bisnis di sini tanpa konfirmasi Tech Lead.

---

## 1. Profil Pengguna & Kasus Penggunaan
* **Owner:** Chansa Panggabean (Ibu rumah tangga dengan balita + Founder agensi kreatif).
* **Operasional:** Studio fisik di Kemang (Jakarta Selatan), dikelola remote dari Surabaya.
* **Kebutuhan Utama:** Mengetahui laba/rugi seketika, membayar gaji tim hybrid secara akurat, dan mencetak slip gaji bernuansa manis ("Angy Taby").

---

## 2. Aturan Bisnis Penggajian (Payroll Domain)
Setiap record pekerja (`employees`) wajib memiliki salah satu dari 3 tipe kompensasi (`employment_type`):

1. **`FULLTIME` (Staf Tetap WFO Kemang, e.g. Video Content Creator):**
   * Formula: `(Gaji Pokok + Tunjangan Transport/Makan + Bonus) - Potongan`
   * Komponen default: Gaji Pokok 5.000.000 - 7.000.000 IDR.
2. **`FREELANCE` (Model Konten TikTok / Talent Shooting):**
   * Formula: `(Rate Per Sesi × Jumlah Sesi + Bonus FYP/Views) - Potongan`
3. **`RETAINER` (Media Buyer / Pengelola Ads):**
   * Formula: `(Fee Retainer Bulanan + Bonus Efisiensi Ads) - Potongan`

*Status Pembayaran Slip:* `PENDING` (menunggu transfer) atau `PAID` (sudah dibayar).

---

## 3. Aturan Bisnis Arus Kas (Cashflow Domain)
Model pembukuan menggunakan pencatatan kas riil (Cash Basis):
* **Kas Masuk (`INCOME`):**
  * `PROJECT_DP`: Uang muka proyek video/jasa klien.
  * `PROJECT_SETTLEMENT`: Pelunasan proyek jasa.
  * `CLIENT_RETAINER`: Langganan jasa bulanan.
* **Kas Keluar (`EXPENSE`):**
  * `PAYROLL`: Terkoneksi otomatis saat slip gaji berstatus `PAID`.
  * `AD_SPEND`: Top-up saldo iklan Meta Ads / TikTok Ads.
  * `STUDIO_KEMANG`: Biaya operasional sewa, listrik, wifi, katering shooting.
  * `PROPS_GEAR`: Wardrobe shooting, sewa lensa/alat.
  * `SUBSCRIPTION`: Canva, CapCut, Adobe, Google Drive, AI tools.

---

## 4. The Cat Mood Rules Engine
Logika status maskot kucing (`CatMoodService`):
* **State `hapy`:** Net Profit $\ge$ Target Omset bulanan, atau 100% gaji bulan berjalan sudah `PAID`.
* **State `zen`:** Net Profit $> 0$ (surplus) namun belum mencapai target.
* **State `angy`:** Net Profit $< 0$ (defisit kas), atau pengeluaran melebihi budget.
* **State `alert`:** Tanggal saat ini $\ge$ tanggal 25 dan masih terdapat slip gaji berstatus `PENDING`.

---

## 5. Visual Token Cepat
* **Background:** Canvas Warm Cream Grid `#FDFBF7`
* **Card Pastel:** Lavender `#EDE9FE`, Butter Yellow `#FEF3C7`
* **Badge:** Paid / Surplus `#D1FAE5` (Teks `#065F46`), Pending / Defisit `#FEE2E2` (Teks `#991B1B`)
* **Text Utama:** `#2D2B38` (Hindari hitam murni `#000000`)
