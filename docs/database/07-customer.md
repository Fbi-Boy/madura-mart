# MADURA MART — DATABASE CUSTOMER

## Role
- customer

## Fokus
Belanja dan mengelola pesanan.

## Menu
### Beranda
- Produk
- Kategori
- Produk Terbaru
- Produk Populer
- Promo

### Produk
- Lihat Produk
- Cari Produk
- Filter Kategori
- Detail Produk

### Keranjang
- Tambah Produk
- Ubah Jumlah
- Hapus Produk
- Lihat Subtotal

### Checkout
- Pilih Alamat
- Pilih Metode Pembayaran
- Pilih Metode Pengiriman
- Konfirmasi Pesanan

### Pesanan
- Pesanan Saya
- Detail Pesanan
- Status Pesanan
- Riwayat Pesanan
- Batalkan Pesanan

### Pembayaran
- Pilih Metode Pembayaran
- Lihat Status Pembayaran
- Upload Bukti Pembayaran

### Profil
- Edit Nama
- Edit Email
- Ubah Password
- Alamat
- Nomor Telepon

## Tabel yang terkait
- users
- customers
- addresses
- products
- categories
- carts
- cart_items
- orders
- order_details
- payments
- order_payments
- shipments

## Flow
Customer → Produk → Keranjang → Checkout → Pembayaran → Pesanan → Pengiriman → Kurir → Pesanan Selesai
