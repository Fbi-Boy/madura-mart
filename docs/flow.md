# Application Flow

## Authentication

1. A visitor opens the application.
2. Unauthenticated visitors are redirected to the login page.
3. Authenticated users are redirected to `/dashboard`.
4. The dashboard controller selects a view from the user's role.

## Dashboard Routing

- `admin` → `admin.dashboard`
- `kasir` → `kasir.dashboard`
- `kurir` → `kurir.dashboard`
- `customer` → `customer.dashboard`
- `purchasing` → `purchasing.dashboard`
- `super-admin` → `super-admin.dashboard`

Unknown roles use the default dashboard view as a safe fallback. The dashboard entry point is covered by feature tests for both unauthenticated access and unsupported-role fallback.

## Operational Areas

After authentication, users can access role-specific screens plus the shared profile area. Each operational route group is protected by the role middleware appropriate to that domain.

## Route groups

The authenticated application routes are grouped by responsibility:

- Dashboard: `/dashboard`
- Admin monitoring: `/admin/monitoring/*`
- Admin reports: `/admin/report/*`
- Admin/master data: `/admin/*`
- Purchasing: `/purchasing/*`
- Warehouse: `/gudang/*`
- Cashier: `/kasir/*`
- Courier: `/kurir/*`
- Customer catalog/cart/checkout/orders: `/customer/*`
- Profile management: `/profile`

### Role boundaries

| Role | Primary route groups |
| --- | --- |
| Admin | `/dashboard`, `/admin/*` |
| Super Admin | `/dashboard`, `/admin/*` |
| Purchasing | `/dashboard`, `/purchasing/*`, purchase monitoring |
| Gudang | `/dashboard`, `/gudang/*` |
| Kasir | `/dashboard`, `/kasir/*` |
| Kurir | `/dashboard`, `/kurir/*` |
| Customer | `/dashboard`, `/customer/*` |

Keeping route prefixes and names grouped makes the HTTP surface easier to review as new modules are added.
