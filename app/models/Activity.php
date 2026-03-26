<?php

declare(strict_types=1);

namespace App\Models;

final class Activity extends BaseModel
{
    public function listByProject(int $projectId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM activities WHERE project_id = :project_id ORDER BY sort_order, id');
        $stmt->execute(['project_id' => $projectId]);

        return $stmt->fetchAll();
    }

    public function create(array $payload): int
    {
        $sql = 'INSERT INTO activities
                (project_id, name, description, status, start_date, end_date, sort_order)
                VALUES
                (:project_id, :name, :description, :status, :start_date, :end_date, :sort_order)';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'project_id' => $payload['project_id'],
            'name' => $payload['name'],
            'description' => $payload['description'] ?? null,
            'status' => $payload['status'],
            'start_date' => $payload['start_date'],
            'end_date' => $payload['end_date'],
            'sort_order' => $payload['sort_order'] ?? 0,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $payload): bool
    {
        $sql = 'UPDATE activities
                SET name = :name,
                    description = :description,
                    status = :status,
                    start_date = :start_date,
                    end_date = :end_date,
                    sort_order = :sort_order
                WHERE id = :id';

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'name' => $payload['name'],
            'description' => $payload['description'] ?? null,
            'status' => $payload['status'],
            'start_date' => $payload['start_date'],
            'end_date' => $payload['end_date'],
            'sort_order' => $payload['sort_order'] ?? 0,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM activities WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }

    public function statusSummaryByProject(int $projectId): array
    {
        $sql = 'SELECT status, COUNT(*) AS total
                FROM activities
                WHERE project_id = :project_id
                GROUP BY status';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['project_id' => $projectId]);
        $rows = $stmt->fetchAll();

        $summary = [
            'NOT_STARTED' => 0,
            'IN_PROGRESS' => 0,
            'COMPLETED' => 0,
        ];

        foreach ($rows as $row) {
            $summary[$row['status']] = (int) $row['total'];
        }

        return $summary;
    }

    public function updateStatus(int $id, string $status): void
    {
        $stmt = $this->db->prepare('UPDATE activities SET status = :status WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'status' => $status,
        ]);
    }
}
