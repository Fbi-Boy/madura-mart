# MADURA MART — DATABASE PURCHASING

## Role
- purchasing

## Fokus
Pengadaan barang dari supplier sampai purchase order diterima gudang.

## Dashboard Purchasing
- Supplier aktif
- Nilai pembelian hari ini
- Jumlah transaksi hari ini
- Draft purchase order
- Barang/purchase yang sudah diterima hari ini
- Ringkasan status pembelian
- Tren nilai procurement 6 bulan
- Supplier dengan nilai pembelian terbesar
- Akses cepat ke workspace purchase order

## Supplier
Supplier digunakan sebagai sumber pengadaan. Purchase wajib menunjuk supplier aktif.

Workspace purchasing menyediakan directory read-only supplier aktif dengan pencarian berdasarkan kode, nama, dan contact person. CRUD supplier tetap berada pada area admin.

## Purchase Order / Pembelian
Implementasi saat ini memakai tabel `purchases` sebagai purchase order sekaligus catatan pembelian:
- Buat invoice
- Pilih supplier aktif
- Pilih produk aktif
- Tentukan quantity
- Tentukan harga beli
- Tambahkan catatan
- Purchasing membuat status `draft`
- Admin dapat membuat transaksi berstatus `received`
- Draft belum menambah stok
- Transaksi received menambah stok

## Penerimaan
Role gudang menangani penerimaan draft:
1. Melihat purchase berstatus `draft`.
2. Mengonfirmasi penerimaan.
3. Sistem mengunci purchase dan produk dalam database transaction.
4. Quantity setiap `purchase_item` ditambahkan ke stok produk.
5. Status purchase berubah menjadi `received`.

## Status Purchase
- `draft` — PO masih menunggu penerimaan.
- `received` — barang sudah diterima dan stok diperbarui.
- `cancelled` — transaksi dibatalkan.

## Tabel yang terkait
- `suppliers`
- `users`
- `purchases`
- `purchase_items`
- `products`

## Struktur Data Utama

### purchases
- `invoice` — nomor invoice unik.
- `supplier_id` — supplier pengadaan.
- `user_id` — user pembuat transaksi.
- `purchase_date` — tanggal pembelian.
- `total` — total nilai pembelian.
- `status` — draft / received / cancelled.
- `notes` — catatan opsional.

### purchase_items
- `purchase_id` — parent purchase.
- `product_id` — produk yang dibeli.
- `quantity` — jumlah barang.
- `unit_price` — harga beli per unit.
- `subtotal` — quantity × unit price.

## Flow
Purchasing → Supplier → Purchase (`draft`) → Gudang Penerimaan → Purchase (`received`) → Stok Produk

> Catatan: dokumentasi ini mengikuti implementasi migration, model, dan controller yang saat ini ada. Tabel `purchase_orders`, `goods_receipts`, dan tabel detail terpisah belum menjadi bagian dari implementasi purchasing saat ini.
