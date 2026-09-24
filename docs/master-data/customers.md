# Master Data Customer

Modul Customer menyimpan data pelanggan yang digunakan oleh Madura Mart.

## Field

- code: kode unik customer.
- name: nama customer.
- phone: nomor telepon.
- email: alamat email.
- address: alamat lengkap.
- city: kota.
- is_active: status customer.

## Akses

CRUD Customer hanya tersedia untuk role admin dan super-admin.

## Route

- GET /admin/customers
- GET /admin/customers/create
- POST /admin/customers
- GET /admin/customers/{customer}/edit
- PUT/PATCH /admin/customers/{customer}
- DELETE /admin/customers/{customer}

## Pengujian

Test mencakup:

- pembatasan akses berdasarkan role;
- create, update, dan delete;
- validasi field wajib;
- validasi kode customer unik.
