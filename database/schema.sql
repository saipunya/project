CREATE DATABASE IF NOT EXISTS naimet_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE naimet_db;

CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id BIGINT UNSIGNED NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    last_login_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_users_role_id (role_id),
    INDEX idx_users_is_active (is_active)
) ENGINE=InnoDB;

CREATE TABLE fiscal_years (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fiscal_year SMALLINT UNSIGNED NOT NULL UNIQUE,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_fiscal_year_dates CHECK (start_date < end_date),
    INDEX idx_fiscal_year_active (is_active)
) ENGINE=InnoDB;

CREATE TABLE plans (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fiscal_year_id BIGINT UNSIGNED NOT NULL,
    code VARCHAR(50) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    owner_department VARCHAR(255) NULL,
    created_by BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT uq_plan_code_per_fy UNIQUE (fiscal_year_id, code),
    CONSTRAINT fk_plans_fiscal_year FOREIGN KEY (fiscal_year_id) REFERENCES fiscal_years(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_plans_created_by FOREIGN KEY (created_by) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_plans_fiscal_year_id (fiscal_year_id),
    INDEX idx_plans_created_by (created_by)
) ENGINE=InnoDB;

CREATE TABLE projects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fiscal_year_id BIGINT UNSIGNED NOT NULL,
    plan_id BIGINT UNSIGNED NOT NULL,
    code VARCHAR(50) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    allocated_budget DECIMAL(14,2) NOT NULL DEFAULT 0.00,
    project_status ENUM('NOT_STARTED', 'IN_PROGRESS', 'COMPLETED') NOT NULL DEFAULT 'NOT_STARTED',
    start_date DATE NULL,
    end_date DATE NULL,
    created_by BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT uq_project_code_per_fy UNIQUE (fiscal_year_id, code),
    CONSTRAINT fk_projects_fiscal_year FOREIGN KEY (fiscal_year_id) REFERENCES fiscal_years(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_projects_plan FOREIGN KEY (plan_id) REFERENCES plans(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_projects_created_by FOREIGN KEY (created_by) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_project_budget_nonnegative CHECK (allocated_budget >= 0),
    INDEX idx_projects_fiscal_year_id (fiscal_year_id),
    INDEX idx_projects_plan_id (plan_id),
    INDEX idx_projects_status (project_status),
    INDEX idx_projects_created_by (created_by)
) ENGINE=InnoDB;

CREATE TABLE project_goals (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id BIGINT UNSIGNED NOT NULL,
    goal_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_project_goals_project FOREIGN KEY (project_id) REFERENCES projects(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    INDEX idx_project_goals_project_id (project_id)
) ENGINE=InnoDB;

CREATE TABLE kpis (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    type ENUM('number', 'percentage', 'text') NOT NULL,
    target_value DECIMAL(14,2) NULL,
    unit VARCHAR(100) NULL,
    baseline_value DECIMAL(14,2) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_kpis_project FOREIGN KEY (project_id) REFERENCES projects(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    INDEX idx_kpis_project_id (project_id),
    INDEX idx_kpis_type (type)
) ENGINE=InnoDB;

CREATE TABLE activities (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    status ENUM('NOT_STARTED', 'IN_PROGRESS', 'COMPLETED') NOT NULL DEFAULT 'NOT_STARTED',
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_activities_project FOREIGN KEY (project_id) REFERENCES projects(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT chk_activity_dates CHECK (start_date <= end_date),
    INDEX idx_activities_project_id (project_id),
    INDEX idx_activities_status (status),
    INDEX idx_activities_date_range (start_date, end_date)
) ENGINE=InnoDB;

CREATE TABLE monthly_reports (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id BIGINT UNSIGNED NOT NULL,
    month TINYINT UNSIGNED NOT NULL,
    year SMALLINT UNSIGNED NOT NULL,
    notes TEXT NULL,
    is_locked TINYINT(1) NOT NULL DEFAULT 0,
    submitted_by BIGINT UNSIGNED NOT NULL,
    submitted_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT uq_monthly_project UNIQUE (project_id, month, year),
    CONSTRAINT fk_monthly_reports_project FOREIGN KEY (project_id) REFERENCES projects(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_monthly_reports_submitted_by FOREIGN KEY (submitted_by) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_monthly_month_range CHECK (month BETWEEN 1 AND 12),
    INDEX idx_monthly_reports_project_id (project_id),
    INDEX idx_monthly_reports_year_month (year, month),
    INDEX idx_monthly_reports_locked (is_locked)
) ENGINE=InnoDB;

CREATE TABLE monthly_kpi_progress (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    monthly_report_id BIGINT UNSIGNED NOT NULL,
    kpi_id BIGINT UNSIGNED NOT NULL,
    incremental_value DECIMAL(14,2) NULL,
    text_value TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT uq_monthly_kpi UNIQUE (monthly_report_id, kpi_id),
    CONSTRAINT fk_monthly_kpi_report FOREIGN KEY (monthly_report_id) REFERENCES monthly_reports(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_monthly_kpi_kpi FOREIGN KEY (kpi_id) REFERENCES kpis(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    INDEX idx_monthly_kpi_report_id (monthly_report_id),
    INDEX idx_monthly_kpi_kpi_id (kpi_id)
) ENGINE=InnoDB;

CREATE TABLE monthly_activity_updates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    monthly_report_id BIGINT UNSIGNED NOT NULL,
    activity_id BIGINT UNSIGNED NOT NULL,
    status ENUM('NOT_STARTED', 'IN_PROGRESS', 'COMPLETED') NOT NULL,
    progress_percent DECIMAL(5,2) NULL,
    update_note TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT uq_monthly_activity UNIQUE (monthly_report_id, activity_id),
    CONSTRAINT fk_monthly_activity_report FOREIGN KEY (monthly_report_id) REFERENCES monthly_reports(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_monthly_activity_activity FOREIGN KEY (activity_id) REFERENCES activities(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT chk_activity_progress_range CHECK (progress_percent IS NULL OR (progress_percent >= 0 AND progress_percent <= 100)),
    INDEX idx_monthly_activity_report_id (monthly_report_id),
    INDEX idx_monthly_activity_activity_id (activity_id)
) ENGINE=InnoDB;

CREATE TABLE monthly_budget_usage (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    monthly_report_id BIGINT UNSIGNED NOT NULL,
    project_id BIGINT UNSIGNED NOT NULL,
    expense_amount DECIMAL(14,2) NOT NULL,
    expense_note TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT uq_monthly_budget UNIQUE (monthly_report_id),
    CONSTRAINT fk_monthly_budget_report FOREIGN KEY (monthly_report_id) REFERENCES monthly_reports(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_monthly_budget_project FOREIGN KEY (project_id) REFERENCES projects(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT chk_budget_expense_nonnegative CHECK (expense_amount >= 0),
    INDEX idx_monthly_budget_project_id (project_id),
    INDEX idx_monthly_budget_report_id (monthly_report_id)
) ENGINE=InnoDB;

CREATE TABLE attachments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    monthly_report_id BIGINT UNSIGNED NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    stored_name VARCHAR(255) NOT NULL UNIQUE,
    file_path VARCHAR(500) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    file_size BIGINT UNSIGNED NOT NULL,
    uploaded_by BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_attachments_monthly_report FOREIGN KEY (monthly_report_id) REFERENCES monthly_reports(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_attachments_uploaded_by FOREIGN KEY (uploaded_by) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_attachments_monthly_report_id (monthly_report_id),
    INDEX idx_attachments_uploaded_by (uploaded_by)
) ENGINE=InnoDB;

INSERT INTO roles (name) VALUES ('ADMIN'), ('STAFF'), ('EXECUTIVE');

INSERT INTO fiscal_years (fiscal_year, start_date, end_date, is_active)
VALUES
    (2025, '2024-10-01', '2025-09-30', 0),
    (2026, '2025-10-01', '2026-09-30', 1);
