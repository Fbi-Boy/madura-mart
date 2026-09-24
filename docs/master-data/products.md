# Master Data Produk

## Tujuan
Modul produk menjadi katalog master yang menghubungkan produk dengan kategori.

## Field
- category_id — kategori produk.
- sku — kode unik produk.
- name — nama produk.
- slug — URL-friendly name yang dibuat otomatis.
- description — deskripsi opsional.
- price — harga jual.
- stock — stok tersedia.
- unit — satuan stok.
- is_active — status produk.

## Akses
CRUD produk berada di bawah /admin/products dan dibatasi untuk role admin serta super-admin.

## Pengujian
- akses role
- create/update/delete
- validasi field wajib
- validasi SKU unik
