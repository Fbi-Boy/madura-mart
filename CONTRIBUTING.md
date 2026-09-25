# Contributing to Madura Mart

## Before You Start

Read the project documentation in `docs/` and make sure your local Laravel and Node.js environments are available. For dashboard work, also follow `docs/dashboard-design.md`.

## Branches

Create a focused branch from `main`:

```bash
git checkout main
git pull origin main
git checkout -b feat/short-description
```

Use prefixes such as `feat/`, `fix/`, `docs/`, `test/`, or `refactor/`.

## Commits

Keep commits small and meaningful. A commit should represent one logical change.

Examples:

```text
feat: add product monitoring screen
fix: correct purchasing dashboard routing
test: cover protected dashboard routes
docs: update application flow
```

## Validation

Run the relevant checks before opening a pull request:

```bash
php artisan test
npm run build
```

For route changes, also review:

```bash
php artisan route:list
```

## Pull Requests

A pull request should explain:

- what changed;
- why the change was needed;
- how it was tested;
- any follow-up work that remains.

Do not commit `.env` files, credentials, generated dependencies, or local machine configuration.

## Authorization Changes

When adding or changing a protected route, update its role middleware and add a feature test that verifies both permitted and denied access where applicable.
