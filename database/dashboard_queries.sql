-- 1) Admin: Project status breakdown by fiscal year
SELECT p.project_status,
       COUNT(*) AS total_projects
FROM projects p
WHERE p.fiscal_year_id = fiscal_year_id
GROUP BY p.project_status;

-- 2) Admin: KPI aggregated progress by project
SELECT k.project_id,
       k.id AS kpi_id,
       k.name,
       k.type,
       k.target_value,
       COALESCE(SUM(mkp.incremental_value), 0) AS current_value,
       CASE
           WHEN k.type IN ('number', 'percentage') AND COALESCE(k.target_value, 0) > 0
           THEN LEAST(100, (COALESCE(SUM(mkp.incremental_value), 0) / k.target_value) * 100)
           ELSE NULL
       END AS achievement_percent
FROM kpis k
LEFT JOIN monthly_kpi_progress mkp ON mkp.kpi_id = k.id
GROUP BY k.project_id, k.id, k.name, k.type, k.target_value
ORDER BY k.project_id, k.id;

-- 3) Admin/Executive: Budget usage per project
SELECT p.id AS project_id,
       p.name,
       p.allocated_budget,
       COALESCE(SUM(mbu.expense_amount), 0) AS total_used,
       (p.allocated_budget - COALESCE(SUM(mbu.expense_amount), 0)) AS remaining_budget
FROM projects p
LEFT JOIN monthly_budget_usage mbu ON mbu.project_id = p.id
WHERE p.fiscal_year_id = fiscal_year_id
GROUP BY p.id, p.name, p.allocated_budget
ORDER BY p.name;

-- 4) Admin: Monthly trend for budget spending
SELECT mr.year,
       mr.month,
       COALESCE(SUM(mbu.expense_amount), 0) AS monthly_expense
FROM monthly_reports mr
LEFT JOIN monthly_budget_usage mbu ON mbu.monthly_report_id = mr.id
INNER JOIN projects p ON p.id = mr.project_id
WHERE p.fiscal_year_id =fiscal_year_id
GROUP BY mr.year, mr.month
ORDER BY mr.year, mr.month;

-- 5) Executive: High-level KPI achievement percentage for fiscal year
SELECT AVG(progress_ratio) * 100 AS kpi_achievement_percent
FROM (
    SELECT k.id,
           CASE
               WHEN k.type IN ('number', 'percentage') AND COALESCE(k.target_value, 0) > 0
               THEN LEAST(1, COALESCE(SUM(mkp.incremental_value), 0) / k.target_value)
               ELSE NULL
           END AS progress_ratio
    FROM kpis k
    INNER JOIN projects p ON p.id = k.project_id
    LEFT JOIN monthly_kpi_progress mkp ON mkp.kpi_id = k.id
    WHERE p.fiscal_year_id = fiscal_year_id
    GROUP BY k.id, k.type, k.target_value
) summary;
