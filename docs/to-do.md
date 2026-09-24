# Development Roadmap

## Current Priorities

- Expand feature coverage for role-specific dashboards.
- Add tests for important monitoring and reporting routes.
- Replace placeholder screens with domain-specific data.
- Document database entities as migrations are introduced.
- Keep frontend assets buildable with Vite.

## Quality Checklist

Before opening a pull request:

- Run `php artisan test`.
- Run `npm run build`.
- Review routes with `php artisan route:list`.
- Check that no environment secrets are committed.
- Keep the change focused and document non-obvious behavior.
