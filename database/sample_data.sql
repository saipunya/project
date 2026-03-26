USE naimet_db;

-- Passwords below use bcrypt for the plaintext: Password@123
INSERT INTO users (role_id, username, password_hash, full_name, is_active)
SELECT r.id, 'admin', '$2y$10$GJZXA4ecAcfLhACiP9IRH..fXXM7OInN2fJjD6.D5DUNL4n5VfHnK', 'System Admin', 1
FROM roles r WHERE r.name = 'ADMIN';

INSERT INTO users (role_id, username, password_hash, full_name, is_active)
SELECT r.id, 'staff1', '$2y$10$GJZXA4ecAcfLhACiP9IRH..fXXM7OInN2fJjD6.D5DUNL4n5VfHnK', 'Planning Staff', 1
FROM roles r WHERE r.name = 'STAFF';

INSERT INTO users (role_id, username, password_hash, full_name, is_active)
SELECT r.id, 'executive1', '$2y$10$GJZXA4ecAcfLhACiP9IRH..fXXM7OInN2fJjD6.D5DUNL4n5VfHnK', 'Executive User', 1
FROM roles r WHERE r.name = 'EXECUTIVE';

INSERT INTO plans (fiscal_year_id, code, name, description, owner_department, created_by)
VALUES
(2, 'PLAN-2026-01', 'Digital Government Plan', 'Modernization plan for citizen services', 'Digital Department', 1);

INSERT INTO projects (fiscal_year_id, plan_id, code, name, description, allocated_budget, project_status, start_date, end_date, created_by)
VALUES
(2, 1, 'PRJ-2026-001', 'e-Document Modernization', 'Electronic document and workflow initiative', 2500000.00, 'NOT_STARTED', '2025-10-01', '2026-09-30', 1);

INSERT INTO project_goals (project_id, goal_text)
VALUES
(1, 'Improve processing speed by 40%'),
(1, 'Increase digital service adoption to 80%');

INSERT INTO kpis (project_id, name, type, target_value, unit, baseline_value, sort_order)
VALUES
(1, 'Processed cases per month', 'number', 12000, 'cases', 5000, 1),
(1, 'Citizen satisfaction', 'percentage', 90, '%', 70, 2),
(1, 'Qualitative implementation note', 'text', NULL, NULL, NULL, 3);

INSERT INTO activities (project_id, name, description, status, start_date, end_date, sort_order)
VALUES
(1, 'Requirements Analysis', 'Collect requirements from departments', 'COMPLETED', '2025-10-01', '2025-11-15', 1),
(1, 'System Development', 'Build and configure platform', 'IN_PROGRESS', '2025-11-16', '2026-06-30', 2),
(1, 'Pilot and Training', 'Pilot implementation and staff training', 'NOT_STARTED', '2026-07-01', '2026-09-15', 3);

INSERT INTO monthly_reports (project_id, month, year, notes, is_locked, submitted_by, submitted_at)
VALUES
(1, 10, 2025, 'Initial month report submitted', 0, 2, NOW());

INSERT INTO monthly_kpi_progress (monthly_report_id, kpi_id, incremental_value, text_value)
VALUES
(1, 1, 600.00, NULL),
(1, 2, 2.50, NULL),
(1, 3, NULL, 'Kickoff completed and stakeholder engagement started.');

INSERT INTO monthly_activity_updates (monthly_report_id, activity_id, status, progress_percent, update_note)
VALUES
(1, 2, 'IN_PROGRESS', 25.00, 'Development sprint 1 complete');

INSERT INTO monthly_budget_usage (monthly_report_id, project_id, expense_amount, expense_note)
VALUES
(1, 1, 125000.00, 'Software licenses and initial consulting');
