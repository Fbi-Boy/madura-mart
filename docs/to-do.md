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

## Definition of Done

A feature or fix is ready for review when:

- The intended behavior is implemented.
- Relevant feature or unit tests are added or updated.
- Existing behavior is not knowingly broken.
- Documentation is updated when the behavior or workflow changes.
- Local validation commands complete successfully.

## Authorization hardening

- [x] Apply role middleware to administrative monitoring routes.
- [x] Apply role middleware to administrative report routes.
- [x] Apply role middleware to cashier routes.
- [x] Add feature coverage for allowed and denied role access.
