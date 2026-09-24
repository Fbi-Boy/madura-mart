# Master Data Supplier

Modul Supplier digunakan admin untuk mengelola pemasok yang menjadi sumber barang Madura Mart.

## Data

- Kode supplier (unik)
- Nama supplier
- PIC / contact person
- Nomor telepon
- Email
- Alamat
- Kota
- Status aktif/nonaktif

## Akses

Modul berada di `/admin/suppliers` dan hanya dapat diakses oleh role `admin` dan `super-admin`.

## Fitur

- Daftar supplier dengan pagination
- Tambah supplier
- Edit supplier
- Hapus supplier
- Validasi kode supplier unik
- Seeder data awal
- Feature test untuk akses, CRUD, dan validasi

## Relasi Tahap Berikutnya

Supplier nantinya dapat digunakan sebagai referensi pada modul pembelian dan transaksi pengadaan barang.
