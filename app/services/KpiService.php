<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

final class KpiService
{
    public function aggregateProgressByProject(int $projectId): array
    {
        $sql = 'SELECT k.id,
                       k.name,
                       k.type,
                       k.target_value,
                       k.unit,
                       COALESCE(SUM(mkp.incremental_value), 0) AS current_value,
                       MAX(mkp.text_value) AS latest_text
                FROM kpis k
                LEFT JOIN monthly_kpi_progress mkp ON mkp.kpi_id = k.id
                WHERE k.project_id = :project_id
                GROUP BY k.id, k.name, k.type, k.target_value, k.unit
                ORDER BY k.id';

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['project_id' => $projectId]);
        $rows = $stmt->fetchAll();

        foreach ($rows as &$row) {
            if ($row['type'] === 'text') {
                $row['achievement_percent'] = null;
                continue;
            }

            $target = (float) ($row['target_value'] ?? 0);
            $current = (float) ($row['current_value'] ?? 0);
            $row['achievement_percent'] = $target > 0 ? min(100, ($current / $target) * 100) : 0;
        }

        return $rows;
    }

    public function aggregateAchievementPercent(int $fiscalYearId): float
    {
        $sql = 'SELECT AVG(progress_ratio) AS avg_progress
                FROM (
                    SELECT k.id,
                           CASE
                               WHEN k.type IN (\'number\', \'percentage\') AND COALESCE(k.target_value, 0) > 0
                               THEN LEAST(1, COALESCE(SUM(mkp.incremental_value), 0) / k.target_value)
                               ELSE NULL
                           END AS progress_ratio
                    FROM kpis k
                    INNER JOIN projects p ON p.id = k.project_id
                    LEFT JOIN monthly_kpi_progress mkp ON mkp.kpi_id = k.id
                    WHERE p.fiscal_year_id = :fiscal_year_id
                    GROUP BY k.id, k.type, k.target_value
                ) summary';

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['fiscal_year_id' => $fiscalYearId]);
        $row = $stmt->fetch();

        return $row && $row['avg_progress'] !== null ? (float) $row['avg_progress'] * 100 : 0.0;
    }
}
