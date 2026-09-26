# MADURA MART — SITEMAP & PROJECT FEATURE MAP

Dokumen ini menjadi acuan struktur fitur dan hak akses setiap role
dalam pengembangan sistem Madura Mart.

---

# 1. ROLE SISTEM

Madura Mart memiliki 7 role utama:

1. Super Admin
2. Admin
3. Gudang
4. Kasir
5. Purchasing
6. Kurir
7. Customer

---

# 2. SUPER ADMIN

Fokus: Mengelola seluruh sistem.

## 2.1 Dashboard
- Ringkasan Penjualan
- Total Produk
- Total Pengguna
- Stok
- Pembelian
- Pesanan
- Pengiriman
- Aktivitas Sistem

## 2.2 Manajemen Pengguna
- Lihat Pengguna
- Tambah Pengguna
- Edit Pengguna
- Nonaktifkan Pengguna
- Hapus Pengguna
- Atur Role Pengguna
- Reset Password

## 2.3 Manajemen Role & Hak Akses
- Role
- Permission
- Pengaturan Akses Setiap Role

## 2.4 Pengaturan Sistem
- Identitas Madura Mart
- Pengaturan Toko
- Pengaturan Transaksi
- Pengaturan Pembayaran
- Pengaturan Pengiriman
- Pengaturan Pajak / Diskon

## 2.5 Monitoring
- Aktivitas User
- Log Aktivitas
- Riwayat Perubahan Data

## 2.6 Laporan
- Laporan Penjualan
- Laporan Pembelian
- Laporan Stok
- Laporan Pelanggan
- Laporan Pengiriman


# 3. ADMIN

Fokus: Monitoring dan administrasi operasional.

## 3.1 Dashboard
- Penjualan Hari Ini
- Total Transaksi
- Produk
- Stok Menipis
- Pesanan
- Pembelian
- Pengiriman

## 3.2 Monitoring Produk
- Lihat Produk
- Lihat Kategori
- Lihat Harga
- Lihat Stok

## 3.3 Monitoring Penjualan
- Daftar Transaksi
- Detail Transaksi
- Penjualan Harian
- Penjualan Bulanan
- Produk Terlaris

## 3.4 Monitoring Pembelian
- Daftar Pembelian
- Supplier
- Status Pembelian
- Riwayat Pembelian

## 3.5 Monitoring Stok
- Stok Tersedia
- Stok Menipis
- Stok Habis
- Riwayat Stok

## 3.6 Monitoring Pesanan
- Pesanan Masuk
- Status Pesanan
- Status Pengiriman

## 3.7 Verifikasi Pembayaran
- Lihat bukti pembayaran customer
- Konfirmasi pembayaran
- Tolak bukti pembayaran
- Kelola status pending, paid, rejected

## 3.8 Laporan
- Laporan Penjualan
- Laporan Pembelian
- Laporan Stok
- Laporan Transaksi


# 4. GUDANG

Fokus: Produk dan persediaan barang.

## 4.1 Dashboard Gudang
- Total Produk
- Stok Tersedia
- Stok Menipis
- Stok Habis
- Barang Masuk
- Barang Keluar

## 4.2 Produk
- Tambah Produk
- Edit Produk
- Lihat Produk
- Nonaktifkan Produk
- Kelola SKU / Kode Barang

## 4.3 Kategori
- Tambah Kategori
- Edit Kategori
- Hapus / Nonaktifkan Kategori

## 4.4 Stok
- Lihat Stok
- Update Stok
- Stok Masuk
- Stok Keluar
- Penyesuaian Stok

## 4.5 Stock Opname
- Buat Stock Opname
- Input Stok Aktual
- Bandingkan Stok Sistem dan Aktual
- Catat Selisih
- Riwayat Stock Opname

## 4.6 Barang Masuk
- Penerimaan Barang
- Verifikasi Barang
- Update Stok Otomatis
- Riwayat Barang Masuk

## 4.7 Barang Keluar
- Pengeluaran Barang
- Pengurangan Stok
- Riwayat Barang Keluar


# 5. KASIR

Fokus: Transaksi penjualan dan pembayaran.

## 5.1 Dashboard Kasir
- Status Shift
- Total Transaksi Hari Ini
- Total Penjualan Hari Ini
- Jumlah Transaksi

## 5.2 Shift
- Buka Shift
- Input Modal Awal
- Tutup Shift
- Input Uang Akhir
- Lihat Selisih Kas
- Riwayat Shift

## 5.3 Transaksi
- Transaksi Baru
- Cari Produk
- Scan Barcode
- Tambah Produk ke Keranjang
- Ubah Jumlah
- Hapus Item
- Diskon
- Hitung Total
- Input Pembayaran
- Hitung Kembalian

## 5.4 Pembayaran
- Tunai
- QRIS
- Transfer
- Debit / Kartu

## 5.5 Struk
- Cetak Struk
- Simpan Transaksi
- Lihat Detail Transaksi

## 5.6 Riwayat
- Riwayat Transaksi
- Detail Transaksi
- Cari Transaksi

## 5.7 Retur
- Pengajuan / Proses Retur
- Cari Transaksi
- Pilih Barang
- Input Alasan
- Proses Retur


# 6. PURCHASING

Fokus: Pengadaan barang dari supplier.

## 6.1 Dashboard Purchasing
- Total Supplier
- Purchase Order Aktif
- Pembelian Berjalan
- Barang Belum Diterima

## 6.2 Supplier
- Tambah Supplier
- Edit Supplier
- Lihat Supplier
- Nonaktifkan Supplier
- Informasi Kontak Supplier

## 6.3 Purchase Order
- Buat PO
- Pilih Supplier
- Pilih Produk
- Tentukan Jumlah
- Tentukan Harga Beli
- Kirim PO
- Batalkan PO draft

## 6.4 Pembelian
- Daftar Pembelian
- Detail Pembelian
- Status Pembelian
- Riwayat Pembelian

## 6.5 Penerimaan
- Lihat Barang yang Harus Diterima
- Konfirmasi Penerimaan
- Catat Barang Kurang / Rusak
- Status Penerimaan

CATATAN:
Purchasing membuat pengadaan.
Gudang melakukan penerimaan fisik dan pengelolaan stok.


# 7. KURIR

Fokus: Pengiriman pesanan.

## 7.1 Dashboard Kurir
- Pesanan yang Harus Dikirim
- Pesanan Sedang Dikirim
- Pesanan Selesai
- Pesanan Gagal

## 7.2 Pengiriman
- Lihat Tugas Pengiriman
- Detail Pesanan
- Informasi Penerima
- Alamat Pengiriman
- Nomor Kontak Penerima

## 7.3 Update Status Pengiriman

Menunggu Pengiriman
        ↓
Diproses
        ↓
Diambil Kurir
        ↓
Dalam Pengiriman
        ↓
Sampai
        ↓
Selesai

## 7.4 Riwayat
- Riwayat Pengiriman
- Pengiriman Selesai
- Pengiriman Gagal
- Alasan Gagal


# 8. CUSTOMER

Fokus: Belanja dan mengelola pesanan.

## 8.1 Beranda
- Produk
- Kategori
- Produk Terbaru
- Produk Populer
- Promo

## 8.2 Produk
- Lihat Produk
- Cari Produk
- Filter Kategori
- Detail Produk

## 8.3 Keranjang
- Tambah Produk
- Ubah Jumlah
- Hapus Produk
- Lihat Subtotal

## 8.4 Checkout
- Pilih Alamat
- Pilih Metode Pembayaran
- Pilih Metode Pengiriman
- Konfirmasi Pesanan

## 8.5 Pesanan
- Pesanan Saya
- Detail Pesanan
- Status Pesanan
- Riwayat Pesanan
- Batalkan Pesanan

## 8.6 Pembayaran
- Pilih Metode Pembayaran
- Pilih metode Transfer Bank atau QRIS saat checkout
- Lihat Status Pembayaran
- Upload Bukti Pembayaran JPG, PNG, atau PDF
- Status pembayaran: pending, paid, rejected
- Bukti pembayaran terikat pada pesanan milik customer

## 8.7 Profil
- Edit Nama
- Edit Email
- Ubah Password
- Alamat Pengiriman
- Nomor Telepon


# 9. STRUKTUR ALUR UTAMA SISTEM

## 9.1 Pengadaan Barang

Purchasing
    ↓
Supplier
    ↓
Purchase Order
    ↓
Penerimaan Barang
    ↓
Gudang
    ↓
Stok


## 9.2 Penjualan Toko

Customer
    ↓
Produk
    ↓
Keranjang
    ↓
Checkout
    ↓
Pembayaran
    ↓
Pesanan
    ↓
Pengiriman
    ↓
Kurir
    ↓
Pesanan Selesai


## 9.3 Penjualan Kasir

Customer
    ↓
Kasir
    ↓
Transaksi
    ↓
Pembayaran
    ↓
Struk
    ↓
Stok Berkurang


## 9.4 Pengelolaan Stok

Barang Masuk
    ↓
Gudang
    ↓
Stok Bertambah

Penjualan
    ↓
Gudang / Sistem
    ↓
Stok Berkurang

Stock Opname
    ↓
Perbandingan Stok
    ↓
Penyesuaian Stok


# 10. PEMBAGIAN TANGGUNG JAWAB

| Aktivitas | Role |
|---|---|
| Mengelola seluruh sistem | Super Admin |
| Mengelola pengguna | Super Admin |
| Mengelola role dan permission | Super Admin |
| Pengaturan sistem | Super Admin |
| Monitoring bisnis | Admin |
| Monitoring penjualan | Admin |
| Monitoring pembelian | Admin |
| Monitoring stok | Admin |
| Mengelola produk | Gudang |
| Mengelola kategori | Gudang |
| Mengelola stok | Gudang |
| Stock opname | Gudang |
| Penerimaan barang | Gudang |
| Mengelola supplier | Purchasing |
| Membuat Purchase Order | Purchasing |
| Pengadaan barang | Purchasing |
| Transaksi penjualan | Kasir |
| Pembayaran toko | Kasir |
| Shift kasir | Kasir |
| Retur transaksi | Kasir |
| Pengiriman pesanan | Kurir |
| Update status pengiriman | Kurir |
| Melihat produk | Customer |
| Keranjang | Customer |
| Checkout | Customer |
| Pembayaran online | Customer |
| Mengelola pesanan sendiri | Customer |


# 11. PRIORITAS IMPLEMENTASI

Pengembangan Madura Mart dilakukan secara bertahap.

## PHASE 1 — AUTHENTICATION & ROLE
- [x] Laravel Breeze
- [x] Login
- [x] Register
- [x] Role User
- [x] Role Middleware
- [x] Dashboard berdasarkan Role
- [x] Sidebar berdasarkan Role

## PHASE 2 — MASTER DATA
- [x] User
- [x] Role & Permission
- [x] Kategori
- [x] Produk
- [x] Supplier
- [x] Customer
- [x] Alamat
- [x] Distributor
- [x] Kurir
- [x] Unit

## PHASE 3 — GUDANG
- [x] Dashboard Inventory
- [x] Stok Monitoring
- [x] Barang Masuk
- [x] Barang Keluar
- [x] Stock Opname
- [x] Riwayat Stok

## PHASE 4 — PURCHASING
- [x] Supplier
- [x] Purchase Order
  - [x] Buat PO draft
  - [x] Kirim / submit PO
  - [x] Batalkan PO draft sebelum diterima gudang
- [x] Pembelian
- [x] Penerimaan Barang

## PHASE 5 — KASIR
- [x] Shift
- [x] Transaksi
- [x] Pembayaran
- [x] Struk
- [x] Riwayat Transaksi
- [x] Retur

## PHASE 6 — CUSTOMER
- [x] Produk
- [x] Keranjang
- [x] Checkout
- [x] Pembayaran
- [x] Pesanan Monitoring
- [x] Profil

## PHASE 7 — KURIR
- [x] Dashboard Pengiriman
- [x] Daftar Pengiriman
- [x] Monitoring Tugas Kurir
- [x] Update Status
- [x] Riwayat Pengiriman

## PHASE 8 — ADMIN & REPORTING
- [x] Dashboard Admin
- [x] Monitoring Penjualan
- [x] Monitoring Pembelian
- [x] Monitoring Produk
- [x] Monitoring Supplier
- [x] Monitoring Pesanan
- [x] Monitoring Client
- [x] Monitoring Distributor
- [x] Monitoring Kurir
- [x] Verifikasi Pembayaran
- [x] Laporan Penjualan
- [x] Laporan Pembelian
- [x] Laporan Stok
- [x] Laporan Pengiriman

## PHASE 9 — SUPER ADMIN
- [x] Dashboard Management & Analytics
- [x] Manajemen User
- [x] Role & Permission workspace
  - [x] Role catalog
  - [x] Permission matrix
  - [ ] Dynamic permission editing
- [x] Pengaturan Sistem
- [x] Activity Log
- [x] Audit Log
- [x] Monitoring Sistem


# 12. CATATAN PENGEMBANGAN

Dokumen ini adalah acuan utama fitur Madura Mart.

Setiap fitur baru harus:
1. Ditentukan role yang memiliki akses.
2. Ditentukan apakah fitur membutuhkan database baru.
3. Ditentukan route.
4. Ditentukan controller.
5. Ditentukan model.
6. Ditentukan migration.
7. Ditentukan view.
8. Ditentukan middleware / permission jika diperlukan.

Role & Permission workspace saat ini bersifat read-only; perubahan hak akses tetap direview melalui config/permissions.php.

Jangan membuat fitur yang berada di luar sitemap tanpa memperbarui
dokumen ini terlebih dahulu.

# 13. ROUTE ACCESS RULES

Administrative monitoring and report routes are restricted to `admin` and `super-admin`. Cashier transaction and shift routes are restricted to `kasir`. Authentication is required before role authorization is evaluated.
