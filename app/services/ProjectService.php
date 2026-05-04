<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Models\Activity;
use App\Models\Project;

final class ProjectService
{
    private Activity $activityModel;
    private Project $projectModel;

    public function __construct()
    {
        $this->activityModel = new Activity();
        $this->projectModel = new Project();
    }

    public function refreshProjectStatus(int $projectId): string
    {
        $summary = $this->activityModel->statusSummaryByProject($projectId);
        $total = array_sum($summary);

        if ($total === 0 || $summary['NOT_STARTED'] === $total) {
            $status = 'NOT_STARTED';
        } elseif ($summary['COMPLETED'] === $total) {
            $status = 'COMPLETED';
        } else {
            $status = 'IN_PROGRESS';
        }

        $this->projectModel->updateStatus($projectId, $status);

        return $status;
    }

    public function budgetSnapshot(int $projectId): array
    {
        $sql = 'SELECT p.allocated_budget,
                       COALESCE(SUM(mbu.expense_amount), 0) AS total_used
                FROM projects p
                LEFT JOIN monthly_budget_usage mbu ON mbu.project_id = p.id
                WHERE p.id = :project_id
                GROUP BY p.id';

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['project_id' => $projectId]);
        $row = $stmt->fetch();

        if (!$row) {
            return [
                'allocated_budget' => 0,
                'total_used' => 0,
                'remaining_budget' => 0,
            ];
        }

        $allocated = (float) $row['allocated_budget'];
        $used = (float) $row['total_used'];

        return [
            'allocated_budget' => $allocated,
            'total_used' => $used,
            'remaining_budget' => $allocated - $used,
        ];
    }

    public function getProjectSummaries()
    {
        $db = Database::connection();
        $query = "SELECT name, project_status AS status, start_date, end_date FROM projects";
        $result = $db->query($query);

        $summaries = $result->fetchAll(\PDO::FETCH_ASSOC);

        return $summaries;
    }
}
