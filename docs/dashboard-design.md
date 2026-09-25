# Dashboard Design System

Dashboard Madura Mart menggunakan pendekatan **role-first**: setiap role mendapat layout dan prioritas informasi yang mengikuti pekerjaan utamanya, bukan satu dashboard generik untuk semua pengguna.

## Visual Direction

Referensi visual ditinjau dari pola dashboard retail, inventory, POS, SaaS, dan analytics di Dribbble serta Pinterest. Dribbble digunakan sebagai referensi eksplorasi visual, sedangkan pola yang dipakai di aplikasi harus tetap berorientasi pada tugas nyata pengguna.

Target visual:
- modern, clean, professional, dan terasa seperti produk SaaS/ERP retail;
- hierarchy informasi kuat: judul → KPI → insight → data operasional → action;
- whitespace cukup, radius konsisten, border/shadow ringan;
- tabel, filter, status badge, chart, dan quick action dibuat ringkas;
- responsive untuk desktop, tablet, dan layar kecil;
- light/dark mode harus mempertahankan kontras dan hirarki yang sama;
- hindari dekorasi berlebihan, glassmorphism berat, dan kartu statistik yang hanya menjadi ornamen;
- angka dashboard harus berasal dari database, bukan angka contoh statis.

## Dashboard by Role

| Role | Dashboard Pattern | Prioritas |
|---|---|---|
| Super Admin | Management + Analytics | kesehatan sistem, user, aktivitas, bisnis |
| Admin | Business Management + Analytics | penjualan, stok, pembelian, pesanan, performa |
| Gudang | Inventory Operations | stok, barang masuk/keluar, stok menipis, opname |
| Purchasing | Procurement Operations | supplier, PO, pembelian, penerimaan |
| Kasir | POS / Transaction Workspace | shift, transaksi, omzet hari ini, pembayaran |
| Kurir | Logistics / Delivery Operations | tugas hari ini, status pengiriman, riwayat |
| Customer | E-commerce / Personal Dashboard | pesanan, checkout, produk, status pembayaran |

## Implementation Rules

1. Dashboard tidak boleh memakai data bisnis fiktif ketika data domain sudah tersedia.
2. KPI hanya menampilkan metrik yang membantu keputusan role tersebut.
3. Dashboard role-specific harus memiliki quick action yang benar-benar tersedia pada role.
4. Status menggunakan label yang jelas dan konsisten.
5. Chart hanya dipakai jika membantu membaca tren/perbandingan; jangan menambah chart tanpa kebutuhan.
6. Komponen visual harus mengikuti layout aplikasi utama agar tidak terasa seperti template yang berbeda.
7. Setiap dashboard baru harus memiliki feature test minimal untuk akses role dan data utama.
8. Perubahan dashboard wajib melewati review struktur, naming, security, responsive behavior, dan konsistensi visual.

## Reference Direction

- Dribbble inventory/admin dashboard: dashboard retail, inventory, POS, dan SaaS digunakan untuk mencari pola visual dan komposisi.
- Pinterest dashboard references digunakan sebagai referensi tambahan untuk hierarchy dan visual composition.

Referensi:
- https://dribbble.com/tags/inventory-management-dashboard
- https://dribbble.com/tags/admin-dashboard-design
- https://id.pinterest.com/pin/302444931243019093/

## Implementation Status

Role-specific dashboards currently covered by the dashboard routing and feature-test suite:

- [x] Super Admin — Management + Analytics
- [x] Admin — Business Management + Analytics
- [x] Gudang — Inventory Operations
- [x] Purchasing — Procurement Operations
- [x] Kasir — POS / Transaction Workspace
- [x] Kurir — Logistics / Delivery Operations
- [x] Customer — E-commerce / Personal Dashboard

Access behavior is also protected by the authenticated dashboard route and a safe fallback for unsupported roles.
