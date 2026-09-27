# Production Deployment

## Minimum production settings

Set these values in the real production environment; do not commit secrets:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.example

LOG_LEVEL=warning

SESSION_SECURE_COOKIE=true
```

Use a real production database, cache, queue, mail, and object/file storage configuration as required by the deployment environment.

## Deployment sequence

1. Install PHP dependencies with `composer install --no-dev --optimize-autoloader`.
2. Install/build frontend assets with `npm ci` and `npm run build`, then remove Node tooling if the host does not need it at runtime.
3. Configure the production `.env` outside version control.
4. Run `php artisan migrate --force`.
5. Run `php artisan config:cache`.
6. Run `php artisan route:cache`.
7. Run `php artisan view:cache`.
8. Restart workers after deployment when queues are enabled.
9. Verify `/login`, `/dashboard`, authentication, and the role-specific workspaces.

## Validation

The Laravel Quality workflow validates the same cache-building commands used during deployment. A pull request should not be considered deploy-ready until the required CI checks are successful.

## Security checklist

- Keep `APP_DEBUG=false`.
- Never commit `.env`, credentials, API keys, or payment secrets.
- Use HTTPS and secure session cookies.
- Run migrations with `--force` only in the intended production deployment step.
- Keep dependencies updated and review security advisories before release.
- Back up the production database before destructive or irreversible migrations.
