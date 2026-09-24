# MADURA MART — DATABASE KURIR

## Role
- kurir

## Fokus
Pengiriman pesanan.

## Menu
### Dashboard Kurir
- Pesanan yang Harus Dikirim
- Pesanan Sedang Dikirim
- Pesanan Selesai
- Pesanan Gagal

### Pengiriman
- Lihat Tugas Pengiriman
- Detail Pesanan
- Informasi Penerima
- Alamat Pengiriman
- Nomor Kontak Penerima

### Update Status Pengiriman
- Menunggu Pengiriman
- Diproses
- Diambil Kurir
- Dalam Pengiriman
- Sampai
- Selesai

### Riwayat
- Riwayat Pengiriman
- Pengiriman Selesai
- Pengiriman Gagal
- Alasan Gagal

## Tabel yang terkait
- users
- customers
- addresses
- orders
- order_details
- shipments
- shipment_status_histories

## Flow
Pesanan → Menunggu Pengiriman → Diproses → Diambil Kurir → Dalam Pengiriman → Sampai → Selesai
