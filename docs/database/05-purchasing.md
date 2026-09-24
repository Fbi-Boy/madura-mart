# MADURA MART — DATABASE PURCHASING

## Role
- purchasing

## Fokus
Pengadaan barang dari supplier.

## Menu
### Dashboard Purchasing
- Total Supplier
- Purchase Order Aktif
- Pembelian Berjalan
- Barang Belum Diterima

### Supplier
- Tambah Supplier
- Edit Supplier
- Lihat Supplier
- Nonaktifkan Supplier
- Informasi Kontak Supplier

### Purchase Order
- Buat PO
- Pilih Supplier
- Pilih Produk
- Tentukan Jumlah
- Tentukan Harga Beli
- Kirim PO
- Batalkan PO

### Pembelian
- Daftar Pembelian
- Detail Pembelian
- Status Pembelian
- Riwayat Pembelian

### Penerimaan
- Lihat Barang yang Harus Diterima
- Konfirmasi Penerimaan
- Catat Barang Kurang / Rusak
- Status Penerimaan

## Tabel yang terkait
- suppliers
- purchase_orders
- purchase_order_details
- purchases
- purchase_details
- products
- goods_receipts
- goods_receipt_details
- stocks
- stock_movements

## Flow
Purchasing → Supplier → Purchase Order → Pembelian → Penerimaan Barang → Gudang → Stok
