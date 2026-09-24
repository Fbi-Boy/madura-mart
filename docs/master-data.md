# Master Data

## Kategori

Kategori adalah master data awal untuk mengelompokkan produk Madura Mart.

Route utama:

- `GET /admin/categories` — daftar kategori.
- `GET /admin/categories/create` — form tambah.
- `POST /admin/categories` — simpan kategori.
- `GET /admin/categories/{category}/edit` — form edit.
- `PUT /admin/categories/{category}` — perbarui kategori.
- `DELETE /admin/categories/{category}` — hapus kategori.

Akses dibatasi untuk role `admin` dan `super-admin`.
