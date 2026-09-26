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

## Stock Movement Ledger

Table `stock_movements` records signed stock changes so warehouse users can inspect inbound, outbound, returns, and stock-opname adjustments. Positive quantities add stock; negative quantities reduce stock. Each entry may reference its source transaction through `reference_type` and `reference_id`.


## Activity Logs

Table `activity_logs` stores important authenticated actions for administrative monitoring.

- `user_id` identifies the actor and is nullable so logs can survive user deletion.
- `action` stores a stable event name such as `user.created`.
- `subject_type` and `subject_id` identify the affected model when applicable.
- `description` stores a human-readable event summary.
- `metadata` stores structured context such as role changes.
- `ip_address` and `user_agent` preserve request context for operational review.

The initial implementation records user lifecycle actions and exposes them to `admin` and `super-admin` through the Activity Log workspace.
