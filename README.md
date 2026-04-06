================================================================================
         MASTER BLUEPRINT — SISTEM INFORMASI DESA (SID) TERPADU
         Dokumen Perencanaan Web Development — Versi 1.0
================================================================================

DAFTAR ISI
----------
  BAB 1 — Arsitektur Sistem & Konsep Utama
  BAB 2 — Tech Stack & Fondasi Teknologi
  BAB 3 — Skema Database (Entity & Relasi)
  BAB 4 — Struktur Halaman & Fitur (Page Map)
  BAB 5 — Panduan UI/UX & Design System
  BAB 6 — Alur Kerja (User Journey & Workflow)
  BAB 7 — Persiapan Lingkungan Development


================================================================================
BAB 1 — ARSITEKTUR SISTEM & KONSEP UTAMA
================================================================================

1.1 FILOSOFI DESAIN
-------------------
  Sistem ini dibangun di atas tiga prinsip utama:
    [1] SATU PINTU  — Satu login SSO untuk semua layanan.
    [2] TRANSPARAN  — Data publik (APBDes, berita) terbuka tanpa login.
    [3] PERSONAL    — Antarmuka menyesuaikan konteks pengguna (warga vs admin).

1.2 PEMBAGIAN ZONA AKSES
-------------------------
  +----------------+----------------------------------------------------------+
  | ZONA           | DESKRIPSI                                                |
  +----------------+----------------------------------------------------------+
  | PUBLIK         | Dapat diakses siapa saja tanpa login.                    |
  |                | Konten: Profil desa, berita, APBDes, UMKM, produk hukum. |
  +----------------+----------------------------------------------------------+
  | WARGA          | Butuh login. Akses penuh ke layanan administrasi.        |
  |                | Konten: E-Surat, E-Lapor, Marketplace seller, Profil.    |
  +----------------+----------------------------------------------------------+
  | ADMIN /        | Butuh login + role khusus. Kendali penuh sistem.         |
  | PERANGKAT DESA | Konten: Verifikasi surat, manajemen data, konten web.    |
  +----------------+----------------------------------------------------------+

1.3 SISTEM AUTENTIKASI (SSO GATEWAY)
--------------------------------------
  - Akun warga dibuat oleh Admin berdasarkan NIK.
  - Username = NIK warga (hanya angka).
  - Password default diberikan saat pembuatan akun.
  - Warga WAJIB ganti password saat login pertama kali (First-Time Login Gate).

  Reset Password (Hybrid):
    [A] OTOMATIS  — Link reset dikirim ke email (untuk warga tech-savvy).
    [B] MANUAL    — Tombol "Bantuan Login" → WhatsApp Admin (untuk warga gaptek).

  Role System:
    - `warga`          — Akses portal mandiri.
    - `perangkat_desa` — Akses sebagian fitur admin.
    - `admin`          — Akses penuh, termasuk manajemen user.


================================================================================
BAB 2 — TECH STACK & FONDASI TEKNOLOGI
================================================================================

2.1 STACK UTAMA
---------------
  +---------------------+------------------------------------------------------+
  | KOMPONEN            | TEKNOLOGI                                            |
  +---------------------+------------------------------------------------------+
  | Framework Backend   | Laravel (PHP) — Modular Monolith Architecture       |
  | Admin Panel         | Filament PHP (dashboard admin & perangkat desa)      |
  | Database            | MySQL / PostgreSQL                                   |
  | Frontend Styling    | Tailwind CSS (JIT Mode)                              |
  | Templating          | Blade (Laravel) + Alpine.js (interaktivitas ringan)  |
  | PDF Generator       | Laravel DomPDF / Snappy (wkhtmltopdf)                |
  | QR Code             | SimpleSoftwareIO/simple-qrcode                       |
  | WhatsApp Gateway    | Tombol deep-link (wa.me) — tanpa API berbayar        |
  | File Storage        | Local disk (public/private) via Laravel Storage      |
  +---------------------+------------------------------------------------------+

2.2 ARSITEKTUR FOLDER (MODULAR MONOLITH)
-----------------------------------------
  app/
  ├── Modules/
  │   ├── Auth/           — SSO, login, reset password
  │   ├── Citizen/        — Master data penduduk
  │   ├── Letter/         — E-Surat (request, generate, arsip)
  │   ├── Marketplace/    — Produk UMKM & ulasan
  │   ├── Complaint/      — E-Lapor
  │   └── PublicInfo/     — Berita, APBDes, produk hukum
  ├── Http/
  │   ├── Controllers/
  │   └── Middleware/     — RoleMiddleware, FirstLoginMiddleware
  └── Models/

2.3 KEAMANAN
------------
  - Password  : Hash Bcrypt via Laravel built-in.
  - NIK/Data  : Enkripsi kolom sensitif di database.
  - Audit Log : Setiap aksi admin tercatat (siapa, kapan, apa).
  - Middleware : Role-based access control di setiap route group.
  - QR Token  : UUID unik per surat untuk verifikasi keaslian.


================================================================================
BAB 3 — SKEMA DATABASE
================================================================================

3.1 GRUP IDENTITAS & AKSES
---------------------------

  TABEL: users
  ┌──────────────────┬─────────────────────────────────────────────────────┐
  │ Kolom            │ Keterangan                                          │
  ├──────────────────┼─────────────────────────────────────────────────────┤
  │ id               │ Primary Key                                         │
  │ username         │ String, Unique — diisi NIK                          │
  │ password         │ String — Bcrypt                                     │
  │ role             │ Enum: admin | perangkat_desa | warga                │
  │ citizen_id       │ Foreign Key → citizens.id                           │
  │ is_first_login   │ Boolean — trigger ganti password                    │
  │ last_login       │ Timestamp                                           │
  │ remember_token   │ String                                              │
  └──────────────────┴─────────────────────────────────────────────────────┘

  TABEL: citizens (Master Data Penduduk — Single Source of Truth)
  ┌──────────────────────┬─────────────────────────────────────────────────┐
  │ Kolom                │ Keterangan                                      │
  ├──────────────────────┼─────────────────────────────────────────────────┤
  │ id                   │ Primary Key                                     │
  │ nik                  │ String, Unique, Index                           │
  │ no_kk                │ String                                          │
  │ nama_lengkap         │ String                                          │
  │ tempat_lahir         │ String                                          │
  │ tanggal_lahir        │ Date                                            │
  │ jenis_kelamin        │ Enum: L | P                                     │
  │ alamat               │ Text                                            │
  │ rt / rw              │ String                                          │
  │ agama                │ String                                          │
  │ status_perkawinan    │ String                                          │
  │ pekerjaan            │ String                                          │
  │ pendidikan_terakhir  │ String                                          │
  │ foto_ktp             │ String Nullable — path file                     │
  └──────────────────────┴─────────────────────────────────────────────────┘

3.2 GRUP ADMINISTRASI & PERSURATAN
------------------------------------

  TABEL: letter_types (Jenis Surat yang Tersedia)
  ┌───────────────┬────────────────────────────────────────────────────────┐
  │ Kolom         │ Keterangan                                             │
  ├───────────────┼────────────────────────────────────────────────────────┤
  │ id            │ Primary Key                                            │
  │ nama_surat    │ String — "Surat Keterangan Usaha"                      │
  │ kode_surat    │ String — "SKU", "SKTM", "DOMISILI", dll.              │
  │ template_file │ String — nama Blade view untuk generate PDF            │
  │ persyaratan   │ JSON — list syarat (foto KK, pengantar RT, dll.)      │
  └───────────────┴────────────────────────────────────────────────────────┘

  TABEL: letter_requests (Permohonan dari Warga)
  ┌────────────────────┬───────────────────────────────────────────────────┐
  │ Kolom              │ Keterangan                                        │
  ├────────────────────┼───────────────────────────────────────────────────┤
  │ id                 │ Primary Key                                       │
  │ citizen_id         │ FK → citizens.id                                  │
  │ letter_type_id     │ FK → letter_types.id                              │
  │ status             │ Enum: pending | process | finished | rejected     │
  │ keperluan          │ Text                                              │
  │ data_tambahan      │ JSON — input unik per jenis surat                 │
  │ lampiran           │ String/JSON — path file yang diunggah             │
  │ keterangan_admin   │ Text Nullable — alasan penolakan                  │
  └────────────────────┴───────────────────────────────────────────────────┘

  TABEL: generated_letters (Arsip Surat yang Sudah Jadi)
  ┌──────────────────┬─────────────────────────────────────────────────────┐
  │ Kolom            │ Keterangan                                          │
  ├──────────────────┼─────────────────────────────────────────────────────┤
  │ id               │ Primary Key                                         │
  │ letter_request_id│ FK → letter_requests.id                             │
  │ nomor_surat      │ String, Unique — misal: 400/001/DS-PLU/IV/2026     │
  │ qr_code_token    │ String, Unique — UUID untuk verifikasi scan         │
  │ file_path        │ String — lokasi file PDF                            │
  │ signed_at        │ Timestamp                                           │
  └──────────────────┴─────────────────────────────────────────────────────┘

  Contoh Format Nomor Surat:
    400  = Kode bidang/klasifikasi
    001  = Nomor urut (auto-increment per tahun)
    DS-PLU = Inisial desa + kode unit
    IV   = Bulan Romawi
    2026 = Tahun

3.3 GRUP EKONOMI & MARKETPLACE
--------------------------------

  TABEL: products
  ┌────────────────────┬───────────────────────────────────────────────────┐
  │ Kolom              │ Keterangan                                        │
  ├────────────────────┼───────────────────────────────────────────────────┤
  │ id                 │ Primary Key                                       │
  │ citizen_id         │ FK → citizens.id (pemilik/penjual)                │
  │ nama_produk        │ String                                            │
  │ slug               │ String, Unique — untuk URL SEO-friendly           │
  │ deskripsi          │ Text                                              │
  │ harga              │ Decimal                                           │
  │ stok               │ Integer                                           │
  │ kategori           │ String                                            │
  │ foto_produk        │ String/JSON — satu atau beberapa foto             │
  │ whatsapp_seller    │ String — nomor WA penjual untuk tombol beli       │
  └────────────────────┴───────────────────────────────────────────────────┘

  TABEL: product_reviews
  ┌────────────────────┬───────────────────────────────────────────────────┐
  │ Kolom              │ Keterangan                                        │
  ├────────────────────┼───────────────────────────────────────────────────┤
  │ id                 │ Primary Key                                       │
  │ product_id         │ FK → products.id                                  │
  │ user_id            │ FK Nullable → users.id (null jika tamu)          │
  │ reviewer_name      │ String — nama reviewer tamu                       │
  │ reviewer_contact   │ String — email/WA reviewer tamu                   │
  │ rating             │ TinyInteger (1-5)                                 │
  │ comment            │ Text                                              │
  │ is_verified        │ Boolean — true jika reviewer adalah warga login   │
  │ status             │ Enum: pending | approved                          │
  └────────────────────┴───────────────────────────────────────────────────┘

3.4 GRUP INFORMASI PUBLIK & PENGADUAN
---------------------------------------

  TABEL: posts (Berita & Pengumuman)
    id, title, slug, content (longText), category, image (path), 
    author_id (FK→users), status (Enum: draft|published), published_at

  TABEL: complaints (E-Lapor)
  ┌────────────────────┬───────────────────────────────────────────────────┐
  │ Kolom              │ Keterangan                                        │
  ├────────────────────┼───────────────────────────────────────────────────┤
  │ id                 │ Primary Key                                       │
  │ citizen_id         │ FK → citizens.id                                  │
  │ judul_laporan      │ String                                            │
  │ isi_laporan        │ Text                                              │
  │ foto_lampiran      │ String — path foto bukti                          │
  │ status             │ Enum: waiting | on-process | resolved             │
  │ is_anonymous       │ Boolean — sembunyikan nama pelapor                │
  └────────────────────┴───────────────────────────────────────────────────┘

  TABEL: budgets (Transparansi APBDes)
    id, tahun, kategori (Enum: pendapatan|pengeluaran), 
    label (misal: "Dana Desa"), nominal (Decimal), realisasi (Decimal)

3.5 RELASI ANTAR TABEL
-----------------------
  users           → citizens        : One-to-One (1:1)
  citizens        → letter_requests : One-to-Many (1:N)
  letter_types    → letter_requests : One-to-Many (1:N)
  letter_requests → generated_letters: One-to-One (1:1)
  citizens        → products        : One-to-Many (1:N)
  products        → product_reviews : One-to-Many (1:N)
  citizens        → complaints      : One-to-Many (1:N)


================================================================================
BAB 4 — STRUKTUR HALAMAN & FITUR (PAGE MAP)
================================================================================

4.1 HALAMAN PUBLIK (AREA TERBUKA — TANPA LOGIN)
-------------------------------------------------

  [P-01] LANDING PAGE (Halaman Utama)
  ------------------------------------
  Route: /
  Komponen:
    [HEADER]
      - Logo + Nama Desa (kiri)
      - Navigasi: Beranda | Profil | Berita | UMKM | Transparansi
      - Tombol "Masuk ke Portal Warga" — warna hijau/biru, kanan atas
      - Sticky + Glassmorphism saat di-scroll

    [HERO SECTION]
      - Foto pemandangan/kantor desa (full-width)
      - Teks besar: "Selamat Datang di Desa [Nama]"
      - Sub-teks tagline desa
      - CTA Button: "Lihat Layanan Warga"

    [SECTION BERITA]
      - Judul: "Berita & Pengumuman Terbaru"
      - Tampil 3 kartu berita terbaru dalam susunan Bento Grid
      - Setiap kartu: Foto, Kategori, Judul, Ringkasan, Tanggal
      - Tombol "Lihat Semua Berita →"

    [SECTION TRANSPARANSI ANGGARAN]
      - Judul: "Transparansi APBDes [Tahun]"
      - Grafik interaktif: Pie chart atau Bar chart
      - Data dari tabel `budgets`
      - Tampilkan nominal pendapatan vs pengeluaran vs realisasi

    [SECTION UMKM UNGGULAN]
      - Judul: "Produk Unggulan Warga Desa"
      - Tampil 4 produk acak/unggulan dari tabel `products`
      - Setiap kartu: Foto, Nama Produk, Harga, Tombol "Beli via WA"
      - Tombol "Jelajahi Semua Produk →"

    [FOOTER]
      - Alamat kantor desa lengkap
      - Nomor WhatsApp desa
      - Link sosial media (Facebook, Instagram)
      - Link cepat: Profil | Berita | UMKM | Kontak

  [P-02] PROFIL DESA
  -------------------
  Route: /profil
  Konten: Sejarah, Visi-Misi, Peta Wilayah (embed Google Maps), 
          Struktur Organisasi (Bagan visual), Foto galeri

  [P-03] BERITA & PENGUMUMAN
  ---------------------------
  Route: /berita
  Konten: Grid semua berita yang published, filter kategori,
          halaman detail per artikel (/berita/{slug})

  [P-04] KATALOG UMKM
  --------------------
  Route: /umkm
  Konten: Grid semua produk (tanpa login), filter kategori/harga,
          halaman detail produk (/umkm/{slug}) dengan tombol "Beli via WA",
          rating dan testimoni (dengan label "Warga Terverifikasi")

  [P-05] TRANSPARANSI ANGGARAN
  -----------------------------
  Route: /transparansi
  Konten: Data APBDes lengkap per tahun (pilih tahun), grafik interaktif
  
  [P-06] PRODUK HUKUM
  --------------------
  Route: /peraturan
  Konten: Perpustakaan Perdes dalam format PDF, bisa diunduh

  [P-07] HALAMAN VERIFIKASI SURAT (QR)
  --------------------------------------
  Route: /verifikasi/{qr_code_token}
  Konten: Menampilkan data surat yang sudah diterbitkan (nomor surat, nama pemohon,
          tanggal, jenis surat, status keabsahan) untuk diakses oleh pihak ketiga
          saat scan QR Code pada surat fisik/digital.


4.2 GERBANG AUTENTIKASI
------------------------

  [A-01] HALAMAN LOGIN
  ---------------------
  Route: /login
  Desain: Sangat bersih, kartu di tengah halaman, background gradasi lembut.
  Komponen:
    - Logo + Nama Desa (atas kartu)
    - Field NIK (hanya terima input angka, maxlength=16)
    - Field Password (dengan ikon mata Show/Hide)
    - Tombol "Masuk" — besar, warna primer
    - Tautan: "Lupa Password? Reset via Email"
    - Tautan: "Butuh Bantuan? Hubungi Admin (WhatsApp)"
    - Catatan kecil: "Belum punya akun? Hubungi Kantor Desa"

  [A-02] HALAMAN GANTI PASSWORD PERTAMA (First-Time Login Gate)
  --------------------------------------------------------------
  Route: /first-login
  Middleware: Redirect otomatis jika is_first_login = true
  Komponen:
    - Pesan selamat datang
    - Field Password Baru
    - Field Konfirmasi Password
    - Tombol "Simpan & Masuk"

  [A-03] RESET PASSWORD VIA EMAIL
  ---------------------------------
  Route: /password/reset
  Standar Laravel Password Reset flow.


4.3 PORTAL MANDIRI WARGA (BUTUH LOGIN)
----------------------------------------

  [W-01] DASHBOARD HUB (Halaman Utama Portal)
  ---------------------------------------------
  Route: /portal
  Layout: Bento Grid — kotak-kotak modular dengan ukuran bervariasi.
  Komponen:
    [WELCOME CARD — Full Width]
      - "Halo, [Nama Warga]! Mau urus apa hari ini?"
      - Tanggal & Waktu

    [MENU CARDS — Bento Grid]
      - KARTU E-SURAT (ukuran besar, 2×2)
          Ikon dokumen | Judul "Layanan Surat"
          Badge: "[N] Surat Sedang Diproses"
          Tombol: "Buat Surat Baru" | "Lihat Riwayat"

      - KARTU MARKETPLACE (ukuran medium)
          Ikon keranjang | Judul "Produk UMKM Saya"
          Info: "[N] Produk Aktif"
          Tombol: "Kelola Produk"

      - KARTU E-LAPOR (ukuran medium)
          Ikon megaphone | Judul "Lapor Masalah"
          Badge status laporan terakhir
          Tombol: "Buat Laporan Baru"

      - KARTU PROFIL (ukuran kecil)
          Ikon orang | Judul "Profil Saya"
          Tombol: "Edit Profil" | "Ganti Password"

    [TABEL RIWAYAT AKTIVITAS — Full Width]
      - 5 aktivitas terakhir (surat disetujui, laporan dibalas, dll.)
      - Kolom: Jenis | Keterangan | Status | Tanggal

  [W-02] MODUL E-SURAT
  ----------------------
  Route: /portal/surat

  Sub-halaman [W-02a] Pilih Jenis Surat:
    - Grid kartu jenis surat yang tersedia (SKU, SKTM, Domisili, dll.)
    - Setiap kartu: Nama surat, deskripsi singkat, persyaratan

  Sub-halaman [W-02b] Form Permohonan (Multi-Step):
    - LANGKAH 1 — Data Diri:
        Data otomatis terisi dari database citizens.
        Warga hanya verifikasi & isi kolom yang kosong.
    - LANGKAH 2 — Keperluan & Data Tambahan:
        Input unik per jenis surat (Nama Usaha untuk SKU, dll.)
        Teks mikro instruksi di bawah setiap field.
    - LANGKAH 3 — Upload Lampiran:
        Upload file syarat sesuai list di letter_types.persyaratan
        Preview file sebelum submit.
    - LANGKAH 4 — Konfirmasi & Kirim:
        Ringkasan semua data.
        Tombol "Kirim Permohonan"

  Sub-halaman [W-02c] Riwayat Surat:
    - Tabel semua permohonan warga.
    - Kolom: No. | Jenis Surat | Tanggal | Status (badge warna) | Aksi
    - Status: MENUNGGU (kuning) | DIPROSES (biru) | SELESAI (hijau) | DITOLAK (merah)
    - Aksi: Tombol "Unduh PDF" (jika selesai) | "Lihat Detail"

  [W-03] MARKETPLACE SELLER
  ---------------------------
  Route: /portal/produk
    - Daftar produk milik warga yang login.
    - Tombol "Tambah Produk Baru"
    - Form tambah/edit: Nama, Deskripsi, Harga, Stok, Kategori, Foto (multi-upload)
    - Nomor WhatsApp penjual.

  [W-04] E-LAPOR
  ---------------
  Route: /portal/laporan
    - Form laporan: Judul, Isi (textarea), Upload Foto Bukti.
    - Toggle: "Kirim secara anonim"
    - Tabel riwayat laporan dengan status.

  [W-05] PROFIL WARGA
  --------------------
  Route: /portal/profil
    - Tampilkan data warga (read-only dari citizens).
    - Form ganti password.


4.4 DASHBOARD ADMIN (FILAMENT PHP — BACK-END)
-----------------------------------------------

  Akses: /admin
  Sidebar Navigasi:

    [DASHBOARD]
      - Widget Statistik: Total Warga, Surat Masuk Hari Ini, Aduan Pending
      - Grafik surat per bulan

    [KEPENDUDUKAN]
      - Tabel master data citizens.
      - CRUD: Tambah, Edit, Hapus warga.
      - Import data dari Excel/CSV.
      - Tombol "Buat Akun Login" per warga.

    [LAYANAN SURAT]
      Sub-menu:
      → Permohonan Masuk:
          Tabel surat dengan status pending/process.
          Klik satu baris → buka halaman verifikasi detail.
          HALAMAN VERIFIKASI: Tampilkan semua data warga + lampiran.
          Tombol "SETUJUI" → trigger generate PDF + QR Code.
          Tombol "TOLAK" → modal isi alasan penolakan.
          Setelah setuju → Tombol "KIRIM via WhatsApp" 
            (membuka wa.me/{nomor} dengan template pesan siap kirim).
      
      → Arsip Surat Jadi:
          Tabel seluruh generated_letters.
          Filter per tanggal, jenis surat, warga.
          Unduh ulang PDF.

    [UMKM]
      → Manajemen Produk: Kelola semua produk (edit, nonaktifkan).
      → Moderasi Ulasan: Setujui/tolak ulasan dari tamu (pending reviews).

    [KONTEN WEB]
      → Berita & Pengumuman: CRUD posts.
      → Transparansi Anggaran: CRUD data budgets per tahun.
      → Produk Hukum: Upload/kelola file Perdes PDF.
      → Galeri: Manajemen foto untuk halaman publik.

    [PENGADUAN]
      → Tabel complaints dengan update status.
      → Balas via WhatsApp langsung dari dashboard.

    [PENGATURAN]
      → Data profil desa (nama, alamat, kontak).
      → Manajemen user admin.
      → Konfigurasi sistem (format nomor surat, dll.)


================================================================================
BAB 5 — PANDUAN UI/UX & DESIGN SYSTEM
================================================================================

5.1 STRATEGI DESAIN PER ZONA
------------------------------
  ZONA PUBLIK   → Gaya "Clean Institutional" (terinspirasi Apple)
  ZONA WARGA    → Gaya "Bento Grid UI" (modern, modular)
  ZONA ADMIN    → Gaya "SaaS Functional" (Filament PHP + custom theme)

5.2 DESIGN TOKENS (TAILWIND CONFIG)
-------------------------------------
  Warna Primer:
    primary-50  : #f0fdf4   (background sangat terang)
    primary-500 : #22c55e   (hijau utama — aksi utama)
    primary-600 : #16a34a   (hover state)
    primary-700 : #15803d   (pressed state)

  Warna Aksen:
    accent-500  : #3b82f6   (biru — info & link)
    accent-600  : #2563eb   (hover biru)

  Status Colors:
    status-pending  : #f59e0b  (kuning — menunggu)
    status-process  : #3b82f6  (biru — diproses)
    status-success  : #22c55e  (hijau — selesai)
    status-rejected : #ef4444  (merah — ditolak)
    status-waiting  : #6b7280  (abu — waiting)

  Tipografi:
    Font Utama  : "Plus Jakarta Sans" (sans-serif modern, local feel)
    Font Fallback: "Inter", system-ui, sans-serif
    Heading XL  : 3.5rem / font-weight: 800
    Heading LG  : 2.25rem / font-weight: 700
    Heading MD  : 1.5rem / font-weight: 600
    Body        : 1rem / font-weight: 400
    Caption     : 0.875rem / font-weight: 400

  Spacing & Shape:
    Border radius default: 0.75rem (12px)
    Border radius card   : 1.5rem (24px) — ciri khas Bento
    Border radius button : 0.625rem (10px)
    Shadow card: box-shadow: 0 1px 3px rgba(0,0,0,0.06) — subtle, tidak berdebu
    Border card: 1px solid #f3f4f6 — abu-abu sangat terang

5.3 KOMPONEN UI DETAIL
------------------------

  [NAVBAR — GLASSMORPHISM]
  - Position: sticky top-0 z-50
  - Background: bg-white/80 backdrop-blur-md
  - Border bawah: border-b border-gray-100/50
  - Efek: konten di belakang terlihat samar saat scroll

  [BENTO GRID — PORTAL WARGA]
  - Container: grid grid-cols-4 gap-4
  - Kartu E-Surat : col-span-2 row-span-2 (dominan, fitur utama)
  - Kartu lain    : col-span-1 atau col-span-2
  - Setiap kartu  : rounded-3xl border border-gray-100 p-6 bg-white hover:shadow-md transition
  - Icon size     : w-10 h-10, dalam container bg-primary-50 rounded-2xl

  [FORM INPUTS — SAAS MINIMAL]
  - Floating Labels: label mengapung ke atas saat field aktif/terisi
  - Input style: border border-gray-200 rounded-xl px-4 py-3 w-full
  - Focus state: ring-2 ring-primary-500/20 border-primary-400 (glow biru tipis)
  - Error state: border-red-400 + teks merah di bawah
  - Micro-copy: teks kecil text-gray-400 mt-1 di bawah input untuk instruksi

  [STATUS BADGE]
  - Bentuk: pill (rounded-full), padding px-3 py-1, text-sm font-medium
  - Setiap status punya warna berbeda (lihat 5.2 Status Colors)

  [BUTTON HIERARCHY]
  - Primary: bg-primary-500 text-white rounded-xl px-6 py-2.5 hover:bg-primary-600
  - Secondary: bg-white border border-gray-200 text-gray-700 rounded-xl
  - Danger: bg-red-500 text-white rounded-xl (untuk tolak/hapus)
  - Ghost: text-primary-600 hover:bg-primary-50 (untuk link-like actions)

  [STEPPER NAVIGATION — MULTI-STEP FORM]
  - Tampilkan 4 langkah di bagian atas form
  - Step aktif: lingkaran warna primer + label bold
  - Step selesai: lingkaran hijau + centang
  - Step belum: lingkaran abu-abu

5.4 PRO TOUCHES (DETAIL YANG MEMBEDAKAN)
-----------------------------------------

  [1] SKELETON LOADERS
  - Tampil saat data sedang di-fetch dari database.
  - Bentuk menyerupai konten asli (kotak abu-abu sesuai ukuran card/tabel).
  - Animasi: animate-pulse (Tailwind).
  - Implementasi: Blade @if($isLoading) ... @endif atau Alpine.js x-show.

  [2] EMPTY STATE ILLUSTRATIONS
  - Jika tabel/data kosong, jangan tampilkan "Data tidak ditemukan".
  - Gunakan ilustrasi minimalis SVG + teks motivatif + tombol CTA.
  - Contoh E-Surat kosong: 
    Ilustrasi + "Belum ada surat? Yuk, ajukan surat pertamamu!" + [Buat Surat]
  - Contoh Produk kosong:
    Ilustrasi + "Kamu belum punya produk. Mulai jualan sekarang!" + [Tambah Produk]

  [3] CUSTOM SCROLLBAR
  - Tailwind: [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-thumb]:bg-gray-300
    [&::-webkit-scrollbar-thumb]:rounded-full
  - Tipis, elegan, tidak merusak estetika Bento.

  [4] TOAST NOTIFICATIONS
  - Notifikasi kecil muncul di pojok kanan bawah setelah aksi sukses/gagal.
  - Warna: Hijau (sukses), Merah (error), Kuning (warning).
  - Auto-dismiss setelah 3 detik.

  [5] KONFIRMASI MODAL
  - Sebelum aksi destruktif (tolak surat, hapus produk), tampilkan modal konfirmasi.
  - Bukan alert browser, tapi modal yang konsisten dengan design system.

  [6] RESPONSIVE DESIGN
  - Mobile-first approach.
  - Bento Grid collapse ke 1 kolom di mobile.
  - Tabel berubah menjadi card list di mobile.
  - Tombol di bawah layar untuk aksi utama (thumb-friendly).


================================================================================
BAB 6 — ALUR KERJA (USER JOURNEY & WORKFLOW)
================================================================================

6.1 ALUR PENGAJUAN E-SURAT (SKENARIO UTAMA)
--------------------------------------------

  WARGA:
  [1] Login di /login dengan NIK + Password.
  [2] Masuk ke Dashboard Hub (/portal).
  [3] Klik kartu "E-Surat" → Pilih jenis surat (misal: SKU).
  [4] Isi form multi-step:
        Step 1: Verifikasi data diri (otomatis dari database).
        Step 2: Isi nama usaha, alamat usaha, keperluan surat.
        Step 3: Upload foto KTP sebagai lampiran.
        Step 4: Review & kirim permohonan.
  [5] Kembali ke riwayat surat — status: MENUNGGU (kuning).

  ADMIN:
  [6] Dashboard admin menampilkan notifikasi/badge "1 Permohonan Baru".
  [7] Admin buka halaman "Permohonan Masuk" di Filament.
  [8] Admin klik permohonan → baca data warga + lampiran.
  [9a] Jika data lengkap: Klik "SETUJUI".
        → Sistem generate PDF berformat resmi dengan nomor surat dan QR Code.
        → Status otomatis berubah ke SELESAI.
        → Tombol "KIRIM via WhatsApp" aktif.
  [9b] Jika data kurang: Klik "TOLAK" → isi alasan → simpan.
        → Status berubah ke DITOLAK.
        → Warga melihat alasan di riwayat surat.
  [10] Admin klik "KIRIM via WhatsApp":
        → Browser membuka wa.me/{nomorWarga}?text=Halo+[Nama],...
        → Template pesan sudah terisi otomatis.
        → Admin lampirkan file PDF secara manual ke chat.

  WARGA:
  [11] Warga terima pesan WA dari Admin.
  [12] Warga juga bisa unduh ulang PDF kapan saja dari /portal/surat/riwayat.

6.2 ALUR VERIFIKASI SURAT OLEH PIHAK KETIGA (QR SCAN)
-------------------------------------------------------
  [1] Pihak ketiga (Bank, Polisi, dsb.) menerima surat fisik/digital dari warga.
  [2] Pihak ketiga scan QR Code di surat menggunakan HP.
  [3] Browser otomatis buka: desa.go.id/verifikasi/{qr_code_token}
  [4] Halaman menampilkan: Nama warga, Jenis surat, Nomor surat, Tanggal terbit, 
      Status: SAH (hijau) / TIDAK VALID (merah).
  [5] Pihak ketiga mendapat konfirmasi keaslian dokumen secara digital.

6.3 ALUR PENDAFTARAN PRODUK UMKM
----------------------------------
  [1] Warga login ke Portal.
  [2] Klik kartu "Marketplace" → masuk ke /portal/produk.
  [3] Klik "Tambah Produk Baru" → isi form: nama, deskripsi, harga, foto, no. WA.
  [4] Produk langsung tampil di halaman publik /umkm.
  [5] Pembeli (warga/tamu) bisa klik "Beli via WA" → langsung ke chat penjual.

6.4 ALUR RATING PRODUK (HYBRID)
---------------------------------
  [TAMU / PUBLIK]:
  [1] Buka halaman produk tanpa login.
  [2] Klik "Beri Rating" → isi nama, kontak WA/email, rating bintang, komentar.
  [3] Selesaikan CAPTCHA.
  [4] Review masuk dengan status: PENDING.
  [5] Admin moderasi di Filament → klik "Setujui".
  [6] Review tampil tanpa label khusus.

  [WARGA LOGIN]:
  [1] Buka halaman produk saat sudah login.
  [2] Klik "Beri Rating" → isi rating dan komentar (nama sudah otomatis terisi).
  [3] Review langsung tampil dengan label "Warga Terverifikasi ✓".


================================================================================
BAB 7 — PERSIAPAN LINGKUNGAN DEVELOPMENT
================================================================================

7.1 SOFTWARE YANG DIBUTUHKAN
------------------------------
  [1] Laragon (bundled: PHP 8.x, Apache/Nginx, MySQL)
      → Download: laragon.org
      → Ini sudah mencakup PHP, Apache, dan database tool (HeidiSQL).

  [2] Composer (PHP Package Manager)
      → Download: getcomposer.org
      → Digunakan untuk install Laravel, Filament, dan library lainnya.

  [3] Node.js & NPM (v18+ LTS)
      → Download: nodejs.org
      → Digunakan untuk compile Tailwind CSS, Alpine.js, dll.

  [4] VS Code + Ekstensi Rekomendasi:
      → Laravel Blade Snippets (Winnie Lin)
      → Tailwind CSS IntelliSense (Tailwind Labs)
      → PHP Intelephense (Ben Mewburn)
      → GitLens
      → Prettier - Code Formatter

  [5] HeidiSQL / TablePlus (Database GUI)
      → HeidiSQL sudah included di Laragon.
      → TablePlus opsional (tampilan lebih modern).

7.2 INISIALISASI PROJECT LARAVEL
----------------------------------
  # 1. Buat project baru
  composer create-project laravel/laravel sid-terpadu

  # 2. Masuk ke folder
  cd sid-terpadu

  # 3. Install Filament PHP (Admin Panel)
  composer require filament/filament:"^3.0" -W
  php artisan filament:install --panels

  # 4. Install library pendukung
  composer require barryvdh/laravel-dompdf           # PDF Generator
  composer require simplesoftwareio/simple-qrcode     # QR Code
  npm install                                         # Install Tailwind etc.

  # 5. Konfigurasi database di .env
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=sid_terpadu
  DB_USERNAME=root
  DB_PASSWORD=

  # 6. Generate key
  php artisan key:generate

  # 7. Jalankan migration (setelah membuat file migration)
  php artisan migrate

  # 8. Jalankan development server
  php artisan serve
  npm run dev   # (terminal terpisah, untuk compile Tailwind)

7.3 URUTAN PENGERJAAN YANG DIREKOMENDASIKAN
---------------------------------------------
  FASE 1 — FONDASI (Week 1-2)
    [ ] Setup project & konfigurasi database
    [ ] Buat semua file migration & model
    [ ] Implementasi autentikasi (Login, SSO, Role Middleware)
    [ ] Buat First-Time Login Gate

  FASE 2 — HALAMAN PUBLIK (Week 3-4)
    [ ] Landing Page (Hero, Berita, APBDes, UMKM)
    [ ] Halaman Profil Desa
    [ ] Halaman Berita & Detail Artikel
    [ ] Halaman Katalog UMKM & Detail Produk
    [ ] Halaman Transparansi Anggaran (grafik interaktif)
    [ ] Halaman Verifikasi QR Surat

  FASE 3 — PORTAL WARGA (Week 5-7)
    [ ] Dashboard Hub (Bento Grid)
    [ ] Modul E-Surat (form multi-step, riwayat, unduh PDF)
    [ ] Engine PDF generator + auto-numbering + QR Code
    [ ] Modul Marketplace Seller (kelola produk)
    [ ] Modul E-Lapor
    [ ] Halaman Profil & Ganti Password

  FASE 4 — DASHBOARD ADMIN FILAMENT (Week 8-10)
    [ ] Resource: Citizens (CRUD + buat akun)
    [ ] Resource: Letter Requests (verifikasi, setujui, tolak)
    [ ] Resource: Generated Letters (arsip)
    [ ] Resource: Products + Moderasi Ulasan
    [ ] Resource: Posts (berita)
    [ ] Resource: Budgets (APBDes)
    [ ] Tombol "Kirim via WhatsApp" dengan template pesan

  FASE 5 — FINISHING & POLISH (Week 11-12)
    [ ] Skeleton loaders
    [ ] Empty state illustrations
    [ ] Toast notifications
    [ ] Responsive design (mobile testing)
    [ ] Custom scrollbar
    [ ] Audit log sistem
    [ ] Security review (enkripsi NIK, middleware check)
    [ ] Testing end-to-end seluruh user journey


================================================================================
CATATAN AKHIR
================================================================================

  Sistem ini dirancang agar:
  - Dapat dikerjakan solo developer dalam 2-3 bulan.
  - Ringan di server shared hosting sekalipun (Modular Monolith).
  - Bisa diupgrade bertahap (fitur bisa ditambah per modul).
  - Dapat diadaptasi untuk desa lain dengan mengubah konten & konfigurasi.

  Prioritas utama saat development:
  1. Keamanan data warga (NIK, KTP) — TIDAK boleh dikompromikan.
  2. Kemudahan pakai — warga boomer harus bisa pakai tanpa training.
  3. Estetika — tampilan harus membuat warga dan perangkat desa bangga.

================================================================================
  Dokumen ini adalah LIVING DOCUMENT.
  Update saat ada perubahan requirement atau keputusan teknis baru.
  Versi: 1.0 | Dibuat untuk project SID Terpadu
================================================================================
