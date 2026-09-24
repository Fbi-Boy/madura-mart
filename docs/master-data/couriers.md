# Master Data Kurir

Modul Kurir menyimpan data kurir dan kendaraan pengantaran Madura Mart.

## Field

- code: kode unik kurir.
- name: nama kurir.
- phone: nomor telepon.
- email: email kurir.
- address: alamat.
- vehicle_type: jenis kendaraan.
- vehicle_number: nomor kendaraan.
- is_active: status kurir.

## Akses

CRUD Kurir hanya tersedia untuk role admin dan super-admin.

## Pengujian

Test mencakup akses role, CRUD, dan validasi kode unik.