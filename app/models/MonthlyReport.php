<?php

declare(strict_types=1);

namespace App\Models;

final class MonthlyReport extends BaseModel
{
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM monthly_reports WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findByProjectMonthYear(int $projectId, int $month, int $year): ?array
    {
        $sql = 'SELECT * FROM monthly_reports WHERE project_id = :project_id AND month = :month AND year = :year LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'project_id' => $projectId,
            'month' => $month,
            'year' => $year,
        ]);

        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $payload): int
    {
        $sql = 'INSERT INTO monthly_reports
                (project_id, month, year, notes, is_locked, submitted_by, submitted_at)
                VALUES
                (:project_id, :month, :year, :notes, :is_locked, :submitted_by, :submitted_at)';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'project_id' => $payload['project_id'],
            'month' => $payload['month'],
            'year' => $payload['year'],
            'notes' => $payload['notes'] ?? null,
            'is_locked' => $payload['is_locked'] ?? 0,
            'submitted_by' => $payload['submitted_by'],
            'submitted_at' => $payload['submitted_at'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function updateSubmission(int $reportId, string $notes, bool $isLocked, int $submittedBy): void
    {
        $sql = 'UPDATE monthly_reports
                SET notes = :notes,
                    is_locked = :is_locked,
                    submitted_by = :submitted_by,
                    submitted_at = :submitted_at
                WHERE id = :id';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $reportId,
            'notes' => $notes,
            'is_locked' => $isLocked ? 1 : 0,
            'submitted_by' => $submittedBy,
            'submitted_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function lock(int $reportId): void
    {
        $stmt = $this->db->prepare('UPDATE monthly_reports SET is_locked = 1 WHERE id = :id');
        $stmt->execute(['id' => $reportId]);
    }
}
