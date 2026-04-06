<div align="center">

<img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"/>
<img src="https://img.shields.io/badge/Filament-PHP-FDAE4B?style=for-the-badge&logo=php&logoColor=white"/>
<img src="https://img.shields.io/badge/TailwindCSS-3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white"/>
<img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white"/>
<img src="https://img.shields.io/badge/Status-In%20Development-F59E0B?style=for-the-badge"/>

<br/><br/>

# 🌾 SID Terpadu
### Sistem Informasi Desa Terpadu

**Platform manajemen desa modern berbasis web — mendigitalisasi pelayanan publik,
meningkatkan transparansi anggaran, dan memberdayakan ekonomi lokal.**

<br/>

[📖 Dokumentasi](#-fitur-utama) · [🗄️ Database Schema](#️-struktur-database) · [⚙️ Instalasi](#️-instalasi--setup) · [🔧 Tech Stack](#-tech-stack)

</div>

---

## 📌 Tentang Proyek

**SID Terpadu** adalah platform digital desa yang dirancang untuk menjembatani kebutuhan pelayanan administrasi warga dengan kemudahan teknologi. Dibangun dengan pendekatan **Modular Monolith** menggunakan Laravel + Filament PHP, sistem ini mengutamakan stabilitas, keamanan, dan kemudahan pengelolaan.

> 💡 **Filosofi:** *"Pelayanan yang Memanusiakan"* — menghubungkan efisiensi birokrasi digital dengan kemudahan akses bagi seluruh lapisan masyarakat, termasuk warga senior.

---

## 🚀 Fitur Utama

### 1. 🔐 SSO Gateway (Single Sign-On)
Sistem pintu tunggal untuk seluruh layanan desa menggunakan **NIK** sebagai identitas unik.

| Fitur | Deskripsi |
|---|---|
| Penyediaan Akun | Dibuat oleh Admin Desa berdasarkan data NIK resmi |
| First-Time Login | Wajib ganti password pada login pertama |
| Hybrid Reset Password | Reset via Email (mandiri) atau bantuan WhatsApp Admin |
| Role-Based Access | `admin` · `perangkat_desa` · `warga` · `publik` |

---

### 2. 🏠 Portal Mandiri Warga
Dashboard personal warga dengan desain **Bento Grid** yang intuitif (gaya SIAKAD).

```
┌─────────────────────────────────────────────────┐
│  Halo, [Nama]! Mau urus apa hari ini?           │
├──────────────┬──────────────┬────────────────────┤
│  📄 E-Surat  │  🛒 UMKM     │  📢 E-Lapor        │
│  x surat     │  x produk    │  x laporan         │
│  diproses    │  aktif       │  menunggu          │
├──────────────┴──────────────┴────────────────────┤
│  👤 Profil & Pengaturan Akun                     │
└─────────────────────────────────────────────────┘
```

#### 📄 E-Surat Engine
- **Jenis Surat Tersedia:** SKU, SKTM, Domisili, Pengantar Nikah, Kematian, Kelahiran, SKCK Desa, dll.
- **Auto-Fill Data:** NIK & nama warga terisi otomatis dari database
- **Tracking Status Visual:**
  - 🟡 `pending` — Menunggu verifikasi admin
  - 🔵 `process` — Sedang diproses
  - 🟢 `finished` — Selesai & siap diunduh
  - 🔴 `rejected` — Ditolak (beserta keterangan alasan)

#### 📢 E-Lapor (Pengaduan Warga)
- Form laporan masalah fasilitas desa dengan upload foto bukti
- Opsi **laporan anonim** untuk warga yang tidak ingin diketahui identitasnya

---

### 3. ⚙️ Digital Engine & Validasi Dokumen

| Fitur | Detail |
|---|---|
| **Auto-Numbering** | Format: `400/001/DS-PLU/IV/2026` — otomatis sesuai kode klasifikasi |
| **QR Code Validation** | Scan QR → halaman verifikasi keaslian di website desa (pengganti stempel fisik) |
| **Generate PDF** | Template Blade → DomPDF dengan layout tabel presisi |
| **Arsip Digital** | Warga bisa unduh ulang surat lama kapan saja |

---

### 4. 🛒 Hybrid Marketplace UMKM
Wadah promosi produk desa terbuka untuk publik luas — **tanpa payment gateway**.

- **Katalog Terbuka:** Pengunjung luar bisa lihat produk & harga tanpa login
- **Transaksi Low-Friction:** Tombol **"Beli via WhatsApp"** langsung ke penjual
- **Seller Dashboard:** Warga kelola produk, stok, dan harga secara mandiri
- **Sistem Rating:**
  - Publik (guest) → wajib isi nama/WA + CAPTCHA + moderasi admin
  - Warga login → langsung tampil dengan label **"Warga Terverifikasi"** ✅

---

### 5. 📊 Transparansi & Publikasi (Area Publik)
Diakses siapa saja tanpa login. Fokus transparansi dan promosi desa.

- 📈 **Interactive Budget (APBDes):** Grafik Chart.js — realisasi Dana Masuk vs. Penggunaan
- 📰 **Pusat Informasi:** Berita terkini, agenda kegiatan desa
- 📚 **Produk Hukum:** Perpustakaan digital Perdes (PDF)
- 🏞️ **Etalase Wisata & Budaya:** Galeri foto & video potensi lokal

---

### 6. 📬 Alur Kerja Admin & WhatsApp Gateway
Proses penyelesaian surat yang menggabungkan sistem digital dengan sentuhan personal.

```
Warga submit permohonan
        │
        ▼
Admin dapat notifikasi di Dashboard Filament
        │
        ▼
Admin verifikasi data & berkas lampiran
        │
        ├─── Tolak ──► Warga menerima keterangan via status tracking
        │
        └─── Setujui ──► Sistem generate PDF (Nomor Surat + QR Code)
                              │
                              ▼
                    Admin klik "Kirim via WA"
                              │
                              ▼
                    WhatsApp Web terbuka dengan template pesan otomatis
                              │
                              ▼
                    Admin lampirkan PDF → Warga menerima surat ✅
```

> **Template pesan WA otomatis:**
> *"Halo [Nama], surat [Jenis Surat] Anda (No. [Nomor Surat]) sudah selesai diproses. Silakan cek file terlampir. — Perangkat Desa"*

---

## 🛠️ Tech Stack

| Komponen | Teknologi | Kegunaan |
|---|---|---|
| Backend Framework | Laravel 11 | Core aplikasi, routing, Eloquent ORM |
| Admin Panel | Filament PHP (TALL Stack) | Dashboard manajemen warga & admin |
| Frontend Styling | Tailwind CSS | Bento UI & desain responsif modern |
| Database | MySQL / PostgreSQL | Penyimpanan seluruh data relasional |
| PDF Engine | DomPDF (Laravel-DomPDF) | Generate surat via template Blade |
| Grafik | Chart.js | Visualisasi APBDes interaktif |
| QR Code | `SimpleSoftware/QrCode` | Validasi keaslian dokumen digital |
| Security | Bcrypt + Middleware | Enkripsi password & Role-Based Access |
| Dev Environment | Laragon (Windows) | PHP + MySQL + Apache lokal |
| Editor | Visual Studio Code | + ekstensi PHP Intelephense & Tailwind |
| DB GUI | HeidiSQL / TablePlus | Manajemen tabel secara visual |

---

## 🗄️ Struktur Database

Struktur data dibagi menjadi **4 grup logis** sesuai pendekatan Modular Monolith.

```
📦 SID Terpadu Database
│
├── 🔐 Grup 1: Identitas & Akses
│   ├── users          — Akun login (NIK sebagai username)
│   └── citizens       — Master data kependudukan (Single Source of Truth)
│
├── 📄 Grup 2: Administrasi & Persuratan
│   ├── letter_types      — Jenis surat yang tersedia
│   ├── letter_requests   — Permohonan surat dari warga
│   └── generated_letters — Arsip surat yang sudah disetujui + QR token
│
├── 🛒 Grup 3: Ekonomi & Marketplace
│   ├── products        — Katalog produk UMKM warga
│   └── product_reviews — Ulasan & rating produk
│
└── 📢 Grup 4: Informasi & Pengaduan
    ├── posts       — Berita & pengumuman desa
    ├── complaints  — Laporan aduan warga
    └── budgets     — Data realisasi APBDes
```

### Relasi Utama (Eloquent Relationships)

```php
// users ──(1:1)──► citizens
User::class         → hasOne(Citizen::class)

// citizens ──(1:N)──► letter_requests
Citizen::class      → hasMany(LetterRequest::class)

// letter_requests ──(1:1)──► generated_letters
LetterRequest::class → hasOne(GeneratedLetter::class)

// citizens ──(1:N)──► products
Citizen::class      → hasMany(Product::class)

// products ──(1:N)──► product_reviews
Product::class      → hasMany(ProductReview::class)
```

---

## ⚙️ Instalasi & Setup

### Prasyarat
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL / PostgreSQL
- Laragon *(direkomendasikan untuk Windows)*

### Langkah Instalasi

```bash
# 1. Clone repository
git clone [https://github.com/username/sid-terpadu.git](https://github.com/alenslhi/rvps-sid)
cd rvps-sid

# 2. Install dependensi PHP
composer install

# 3. Install dependensi Node.js
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Konfigurasi database di .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=rvps-sid
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Jalankan migrasi & seeder
php artisan migrate --seed

# 8. Build aset frontend
npm run dev

# 9. Jalankan server lokal
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

### Akun Default (Seeder)

| Role | NIK / Username | Password |
|---|---|---|
| Admin | `admin` | `password` |
| Perangkat Desa | `1234567890123456` | `password` |
| Warga | `9876543210987654` | `password` |

> ⚠️ Ganti semua password default setelah instalasi pertama.

---

## 📁 Struktur Folder (Modular Monolith)

```
app/
├── Modules/
│   ├── Auth/           — SSO, Login, Reset Password
│   ├── Citizen/        — Master data kependudukan
│   ├── Letter/         — E-Surat Engine (generate, QR, arsip)
│   ├── Marketplace/    — UMKM, produk, ulasan
│   ├── Complaint/      — E-Lapor & pengaduan
│   └── Publication/    — Berita, APBDes, produk hukum
├── Filament/
│   └── Resources/      — Admin panel resources (Filament)
resources/
├── views/
│   └── letters/        — Template Blade untuk generate PDF surat
```

---

## 🗺️ Roadmap Pengembangan

- [x] Perancangan Blueprint & Database Schema
- [ ] **Fase 1** — Setup environment (Laravel + Filament + Tailwind)
- [ ] **Fase 2** — Migration & Model + Eloquent Relationships
- [ ] **Fase 3** — Implementasi Auth & Role-Based Access
- [ ] **Fase 4** — E-Surat Engine (form, PDF, QR Code, WhatsApp Gateway)
- [ ] **Fase 5** — Area Publik & Marketplace UMKM
- [ ] **Fase 6** — Finishing, testing, dan deployment

---

## 🤝 Kontribusi

Pull request dan saran sangat diterima. Untuk perubahan besar, buka *issue* terlebih dahulu untuk mendiskusikan ide kamu.

---

## 📄 Lisensi

Proyek ini dikembangkan untuk keperluan akademis.
**© 2026 — SID Terpadu Project | UNTAD IT Dept**

---

<div align="center">

Dibuat dengan ❤️ untuk digitalisasi desa Indonesia 🇮🇩

</div>
