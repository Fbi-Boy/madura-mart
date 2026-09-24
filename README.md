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
| Admin | Administration |
| Kasir | Cashier |
| Kurir | Delivery |
| Customer | Customer |
| Purchasing | Purchasing |
| Super Admin | Super administration |

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

## Documentation

Project notes and development documentation live in the `docs/` directory:

- [Application flow](docs/flow.md)
- [Database notes](docs/database.md)
- [Project sitemap](docs/sidemap.md)
- [Development roadmap](docs/to-do.md)

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
