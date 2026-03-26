# Thai Fiscal Year Project Tracker (PHP MVC)

Production-ready baseline for tracking project performance and budget usage by Thai fiscal year.

## Stack
- PHP 8+ (Vanilla MVC)
- MySQL 8+
- Bootstrap 5
- PDO prepared statements
- Local file uploads (`public/uploads`)

## Thai Fiscal Year Rule
- Fiscal year starts on `1 Oct` and ends on `30 Sep`.
- Example: `1 Oct 2025 - 30 Sep 2026` maps to Fiscal Year `2026`.

## Project Structure
```
project/
├── app/
│   ├── controllers/
│   ├── core/
│   ├── helpers/
│   ├── models/
│   └── services/
├── config/
├── database/
├── public/
│   └── uploads/
├── routes/
└── views/
```

## Setup
1. Create DB schema:
   - Import `database/schema.sql`
2. (Optional) Add sample records:
   - Import `database/sample_data.sql`
3. Configure DB credentials using env vars:
   - `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`
4. Point web server document root to `public/`
5. Login using sample users (if seeded):
   - `admin` / `Password@123`
   - `staff1` / `Password@123`
   - `executive1` / `Password@123`

## Core Features Implemented
- Fiscal-year linked hierarchy: FiscalYear -> Plan -> Project -> Goals/KPIs/Activities
- RBAC:
  - `ADMIN`: full access + import
  - `STAFF`: monthly updates + activity input + dashboard
  - `EXECUTIVE`: dashboard read-only
- CRUD examples:
  - Projects
  - KPIs
  - Activities
- Monthly report workflow:
  - unique by `(project_id, month, year)`
  - KPI progress, activity updates, budget usage, notes, optional attachment
  - transactional submit with lock support (`is_locked`)
- Business logic:
  - auto project status derivation from activities
  - KPI aggregation and achievement %
  - budget `total_used` and `remaining_budget`
- CSV import:
  - plans, projects, kpis, activities

## Dashboard SQL
- See `database/dashboard_queries.sql` for optimized queries:
  - status breakdown
  - KPI aggregated progress
  - budget usage overview
  - monthly spending trend
  - executive KPI achievement summary

## Security
- PDO prepared statements for all DB operations
- Basic authentication using password hashes
- Route-level RBAC check
- File upload MIME and size validation (5MB max)

## Notes for Production Hardening
- Add CSRF protection middleware
- Add rate limiting for login and upload endpoints
- Add centralized exception handler + structured logs
- Move secrets to secure environment configuration
