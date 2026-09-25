# Madura Mart

Madura Mart is a Laravel-based management application for retail operations. The current application provides role-aware dashboards and modules for administration, cashier operations, delivery, purchasing, customers, monitoring, and reporting.

## Stack

- PHP 8.3+
- Laravel 13
- Blade
- Tailwind CSS
- Alpine.js
- Vite
- MySQL or SQLite for local development

## Application Roles

The dashboard is selected from the authenticated user's `role`:

| Role | Dashboard |
| --- | --- |
| Admin | Business Management |
| Gudang | Inventory Operations |
| Kasir | Cashier / POS |
| Kurir | Delivery Operations |
| Customer | Personal Shopping |
| Purchasing | Procurement Operations |
| Super Admin | Management + Analytics |

## Main Modules

- **Admin Monitoring** — sales, purchases, orders, products, distributors, clients, and couriers.
- **Admin Reports** — sales, purchases, and stock reports.
- **Kasir** — new transactions, transaction history, returns, and shift management.
- **Profile** — update account information and delete an account.

## Local Setup

Clone the repository and install the PHP and JavaScript dependencies:

```bash
git clone https://github.com/Fbi-Boy/madura-mart.git
cd madura-mart

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate

npm install
npm run build
```

Start the development server:

```bash
php artisan serve
```

Then open `http://127.0.0.1:8000`.

## Testing

Run the application test suite with:

```bash
php artisan test
```

For frontend asset validation:

```bash
npm run build
```

## Master Data

The admin master-data module includes category, product, supplier, and customer CRUD for `admin` and `super-admin` roles.

## Documentation

Project notes and development documentation live in the `docs/` directory:

- [Application flow](docs/flow.md)
- [Database notes](docs/database.md)
- [Project sitemap](docs/sidemap.md)
- [Development roadmap](docs/to-do.md)
- [Dashboard design system](docs/dashboard-design.md)

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for the branch, commit, validation, and pull request workflow.


## Validation workflow

For routine development, validate backend and frontend changes separately:

```bash
php artisan test
npm run build
```

For route-related changes, also inspect the registered endpoints:

```bash
php artisan route:list
```

## Route access

Protected application routes are scoped by authenticated role. Administrative monitoring and reporting routes require `admin` or `super-admin`, while cashier routes require `kasir`. Authorization behavior is covered by feature tests in `tests/Feature/RoleRouteAccessTest.php`.

## Master Data Produk
- Kategori: CRUD admin.
- Produk: CRUD admin dengan SKU, kategori, harga, stok, dan status aktif.
- Dokumentasi: `docs/master-data/products.md`.
- Supplier: CRUD admin untuk data pemasok.
- Customer: CRUD admin untuk data pelanggan.
- Distributor: CRUD admin untuk data distributor.
- Kurir: CRUD admin untuk data kurir dan kendaraan.
- Dokumentasi: `docs/master-data/couriers.md`.
- Dokumentasi: `docs/master-data/distributors.md`.
- Dokumentasi: `docs/master-data/suppliers.md` dan `docs/master-data/customers.md`.
## Continuous Integration

Every push to `main` and every pull request targeting `main` is validated by two GitHub Actions workflows:

- **CI** — installs dependencies, builds Vite assets, and runs the Laravel test suite.
- **Laravel Quality** — runs the same application quality validation in the CI pipeline.

A change is considered ready only after the required checks complete successfully.
