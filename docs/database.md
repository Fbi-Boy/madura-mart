# Database Notes

The application uses Laravel migrations and Eloquent models as the source of truth for database structure.

## Local Database

SQLite can be used for lightweight local development when configured. MySQL is also suitable when the project is connected to a MySQL database.

## Migration Workflow

```bash
php artisan migrate
php artisan migrate:status
```

Before committing database changes, verify that migrations run cleanly on a fresh development database.

## Model Conventions

Application models live under `app/Models` and use Laravel's Eloquent ORM. Factories under `database/factories` should stay aligned with attributes used by feature tests.
