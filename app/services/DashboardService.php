<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

final class DashboardService
{
    public function adminSummary(int $fiscalYearId): array
    {
        $pdo = Database::connection();

        $projectStmt = $pdo->prepare('SELECT project_status, COUNT(*) AS total
                                      FROM projects
                                      WHERE fiscal_year_id = :fiscal_year_id
                                      GROUP BY project_status');
        $projectStmt->execute(['fiscal_year_id' => $fiscalYearId]);

        $budgetStmt = $pdo->prepare('SELECT COALESCE(SUM(p.allocated_budget), 0) AS total_allocated,
                                            COALESCE(SUM(mbu.expense_amount), 0) AS total_used
                                     FROM projects p
                                     LEFT JOIN monthly_budget_usage mbu ON mbu.project_id = p.id
                                     WHERE p.fiscal_year_id = :fiscal_year_id');
        $budgetStmt->execute(['fiscal_year_id' => $fiscalYearId]);

        $trendStmt = $pdo->prepare('SELECT mr.year, mr.month,
                                           COALESCE(SUM(mbu.expense_amount), 0) AS monthly_expense
                                    FROM monthly_reports mr
                                    LEFT JOIN monthly_budget_usage mbu ON mbu.monthly_report_id = mr.id
                                    INNER JOIN projects p ON p.id = mr.project_id
                                    WHERE p.fiscal_year_id = :fiscal_year_id
                                    GROUP BY mr.year, mr.month
                                    ORDER BY mr.year, mr.month');
        $trendStmt->execute(['fiscal_year_id' => $fiscalYearId]);

        return [
            'status_breakdown' => $projectStmt->fetchAll(),
            'budget' => $budgetStmt->fetch() ?: ['total_allocated' => 0, 'total_used' => 0],
            'monthly_trend' => $trendStmt->fetchAll(),
        ];
    }

    public function executiveSummary(int $fiscalYearId): array
    {
        $kpiService = new KpiService();
        $projectService = new ProjectService();

        $pdo = Database::connection();
        $projectStmt = $pdo->prepare('SELECT id, name, project_status, allocated_budget
                                      FROM projects
                                      WHERE fiscal_year_id = :fiscal_year_id
                                      ORDER BY name');
        $projectStmt->execute(['fiscal_year_id' => $fiscalYearId]);
        $projects = $projectStmt->fetchAll();

        $projectSummaries = [];
        foreach ($projects as $project) {
            $budget = $projectService->budgetSnapshot((int) $project['id']);
            $projectSummaries[] = [
                'id' => (int) $project['id'],
                'name' => $project['name'],
                'status' => $project['project_status'],
                'budget_used_percent' => $budget['allocated_budget'] > 0
                    ? min(100, ($budget['total_used'] / $budget['allocated_budget']) * 100)
                    : 0,
            ];
        }

        return [
            'kpi_achievement_percent' => $kpiService->aggregateAchievementPercent($fiscalYearId),
            'projects' => $projectSummaries,
        ];
    }
}
