# Application Architecture

```text
Browser
   |
   v
Routes (web.php / auth.php)
   |
   v
Middleware
   |
   v
Controllers
   |
   +----> Eloquent Models ----> Database
   |
   v
Blade Views
   |
   v
Vite / Tailwind / Alpine assets
```

## Backend

- Routes define HTTP entry points.
- Middleware protects authenticated areas.
- Controllers coordinate application behavior.
- Eloquent models represent persisted data.
- Blade renders server-side HTML.

## Frontend

Tailwind CSS provides utility styling. Alpine.js handles lightweight browser interactions, while Vite builds frontend assets.

## Testing

Feature tests exercise HTTP endpoints and authenticated flows. Unit tests are reserved for isolated application behavior.


## Request lifecycle

For an authenticated dashboard request, the application follows this sequence:

1. The browser requests `GET /dashboard`.
2. The route is resolved from `routes/web.php`.
3. The `auth` middleware verifies the current session.
4. `DashboardController` reads the authenticated user's role.
5. The controller selects the dashboard view associated with that role.
6. Blade renders the selected dashboard and returns the HTTP response.

This flow keeps authentication at the route boundary and role-based view selection in the dashboard controller.
