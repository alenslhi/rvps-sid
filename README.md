# 🌾 SID Terpadu (Sistem Informasi Desa Terpadu)

[cite_start]**SID Terpadu** adalah platform manajemen desa modern yang dirancang untuk mendigitalisasi pelayanan publik, meningkatkan transparansi anggaran, dan memberdayakan ekonomi lokal melalui teknologi[cite: 2, 11]. [cite_start]Proyek ini dikembangkan dengan pendekatan **Modular Monolith** menggunakan Laravel + Filament PHP untuk memastikan sistem yang stabil, aman, dan mudah dikelola[cite: 7, 80].

---

## 🚀 Fitur Utama

### 1. SSO Gateway (Single Sign-On)
[cite_start]Sistem pintu tunggal untuk seluruh layanan desa menggunakan **NIK** sebagai identitas unik[cite: 25, 26].
* [cite_start]**Penyediaan Akun**: Akun warga dibuat oleh Admin Desa berdasarkan data NIK resmi[cite: 29].
* [cite_start]**First-Time Login**: Keamanan ekstra dengan kewajiban mengganti password pada login pertama[cite: 30].
* [cite_start]**Hybrid Reset Password**: Pemulihan akun melalui Email atau bantuan langsung WhatsApp Admin[cite: 31].

### 2. Portal Mandiri Warga (Bento UI)
[cite_start]Dashboard personal warga dengan desain **Bento Grid** yang intuitif (gaya SIAKAD)[cite: 41, 42].
* [cite_start]**E-Surat Engine**: Pembuatan surat otomatis (SKU, SKTM, Domisili, dll.) dengan fitur *auto-fill* data warga[cite: 47, 48].
* [cite_start]**Tracking Status**: Pelacakan status permohonan secara visual (Kuning, Biru, Hijau, Merah)[cite: 49].
* [cite_start]**E-Lapor**: Sistem pengaduan fasilitas desa dengan dukungan lampiran foto dan opsi anonim[cite: 50, 52].

### 3. Digital Engine & Validasi
[cite_start]Otomatisasi dokumen hukum desa yang rapi dan sah secara digital[cite: 56].
* [cite_start]**Auto-Numbering**: Penomoran surat otomatis sesuai kode klasifikasi resmi desa[cite: 57, 58].
* [cite_start]**QR Code Validation**: Scan QR untuk verifikasi keaslian dokumen melalui website desa, menggantikan stempel fisik[cite: 63, 65].
* [cite_start]**Arsip Digital**: Penyimpanan riwayat dokumen yang dapat diunduh kapan saja oleh warga[cite: 64].

### 4. Hybrid Marketplace UMKM
[cite_start]Wadah promosi produk desa yang terbuka untuk publik luas tanpa memerlukan *payment gateway* rumit[cite: 66, 67].
* [cite_start]**Katalog Terbuka**: Pengunjung luar dapat melihat produk dan harga tanpa perlu login[cite: 69].
* [cite_start]**Transaksi Low-Friction**: Pembelian langsung diarahkan ke WhatsApp penjual melalui tombol khusus[cite: 70].
* [cite_start]**Seller Dashboard**: Warga dapat mengelola produk, stok, dan harga secara mandiri[cite: 71].

### 5. Transparansi & Publikasi
[cite_start]Area publik untuk membangun kepercayaan masyarakat desa[cite: 34].
* [cite_start]**Interactive Budget**: Visualisasi realisasi APBDes menggunakan grafik interaktif (Dana Masuk vs. Penggunaan)[cite: 37, 128].
* [cite_start]**Pusat Informasi**: Berita desa terkini, agenda kegiatan, dan perpustakaan digital produk hukum (Perdes)[cite: 36, 38].

---

## 🛠️ Tech Stack

[cite_start]Sistem ini dioptimasi untuk berjalan dengan performa tinggi pada lingkungan pengembangan lokal (Laragon/Ryzen 3)[cite: 145].

* [cite_start]**Framework**: Laravel 11[cite: 7].
* [cite_start]**Admin Panel**: Filament PHP (TALL Stack)[cite: 137].
* [cite_start]**Frontend UI**: Tailwind CSS (Bento UI & Modern SaaS Design)[cite: 123].
* [cite_start]**Database**: MySQL / PostgreSQL[cite: 79].
* [cite_start]**Engine**: DomPDF (Surat) & Chart.js (Grafik Anggaran)[cite: 62, 128].

---

## 📁 Struktur Database (Modular Monolith)

[cite_start]Struktur data dibagi menjadi 4 grup logis untuk mendukung skalabilitas[cite: 80, 81]:
1. [cite_start]**Grup 1: Identitas & Akses**: Tabel `users` dan `citizens`[cite: 82, 85].
2. [cite_start]**Grup 2: Administrasi & Persuratan**: Tabel `letter_types`, `letter_requests`, dan `generated_letters`[cite: 90, 92, 94].
3. [cite_start]**Grup 3: Ekonomi & Marketplace**: Tabel `products` dan `product_reviews`[cite: 98, 104].
4. [cite_start]**Grup 4: Informasi & Pengaduan**: Tabel `posts`, `complaints`, dan `budgets`[cite: 107, 111, 114].

---

## 🔧 Alur Kerja Admin & WhatsApp Gateway

[cite_start]Proses penyelesaian surat menggabungkan sistem digital dengan sentuhan personal[cite: 73, 74]:
1. [cite_start]Admin memverifikasi permohonan surat melalui dashboard Filament[cite: 139].
2. [cite_start]Admin menyetujui permohonan, sistem men-generate PDF secara otomatis[cite: 96, 139].
3. [cite_start]Admin mengirim notifikasi dan file surat secara langsung ke WhatsApp warga menggunakan template pesan yang tersedia[cite: 75].

---

## 📜 Filosofi Pengembangan
[cite_start]Proyek ini dikembangkan dengan prinsip **"Pelayanan yang Memanusiakan"**, menghubungkan efisiensi birokrasi digital dengan kemudahan akses bagi seluruh lapisan masyarakat, termasuk warga senior[cite: 152].

---

**© 2026 - SID Terpadu Project | [cite_start]UNTAD IT Dept** [cite: 6, 22]
