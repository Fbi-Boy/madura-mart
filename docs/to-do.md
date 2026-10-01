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

## Sprint 24 — Super Admin UI Hardening

- [x] Tampilkan user terbaru lebih dahulu pada User & Staff.
- [x] Gunakan kontrol aksi berbasis ikon untuk edit, status, dan hapus user.
- [x] Rapikan workspace Pengaturan Sistem agar konsisten dengan dashboard.
- [x] Tambahkan regression coverage untuk urutan user dan kontrol aksi.
- [x] Tambahkan validation coverage untuk nilai pengaturan transaksi.

## Sprint 25 — Sales Return Hardening

- [x] Reject future return dates.
- [x] Reject return dates earlier than the original sale.
- [x] Prevent cross-sale item returns.
- [x] Keep refund method aligned with the original payment method.
- [x] Record successful returns in Activity Log.
- [x] Add feature coverage for cashier-only access and return integrity.


## Sprint 26 — Payment Verification Hardening

- [x] Require a rejection reason when an admin rejects a payment proof.
- [x] Provide a dedicated rejection workspace in the admin verification screen.
- [x] Record both successful verification and rejection outcomes in Activity Log.
- [x] Cover rejected-without-reason and successful-verification regressions.
- [x] Verify operational roles cannot access the payment verification workspace.
