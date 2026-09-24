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

Unknown roles use the default dashboard view.

## Operational Areas

After authentication, users can access role-specific screens plus the shared profile area. Admin monitoring and reporting routes are grouped under the `admin` prefix.


## Route groups

The authenticated application routes are grouped by responsibility:

- Dashboard: `/dashboard`
- Admin monitoring: `/admin/monitoring/*`
- Admin reports: `/admin/report/*`
- Cashier operations: `/kasir/*`
- Profile management: `/profile`

Keeping route prefixes and names grouped makes the HTTP surface easier to review as new modules are added.
