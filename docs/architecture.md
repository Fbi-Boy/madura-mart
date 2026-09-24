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
