<?php

declare(strict_types=1);

namespace App\Models;

final class Kpi extends BaseModel
{
    public function listByProject(int $projectId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM kpis WHERE project_id = :project_id ORDER BY sort_order, id');
        $stmt->execute(['project_id' => $projectId]);

        return $stmt->fetchAll();
    }

    public function create(array $payload): int
    {
        $sql = 'INSERT INTO kpis (project_id, name, type, target_value, unit, baseline_value, sort_order)
                VALUES (:project_id, :name, :type, :target_value, :unit, :baseline_value, :sort_order)';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'project_id' => $payload['project_id'],
            'name' => $payload['name'],
            'type' => $payload['type'],
            'target_value' => $payload['target_value'] ?? null,
            'unit' => $payload['unit'] ?? null,
            'baseline_value' => $payload['baseline_value'] ?? null,
            'sort_order' => $payload['sort_order'] ?? 0,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $payload): bool
    {
        $sql = 'UPDATE kpis
                SET name = :name,
                    type = :type,
                    target_value = :target_value,
                    unit = :unit,
                    baseline_value = :baseline_value,
                    sort_order = :sort_order
                WHERE id = :id';

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'name' => $payload['name'],
            'type' => $payload['type'],
            'target_value' => $payload['target_value'] ?? null,
            'unit' => $payload['unit'] ?? null,
            'baseline_value' => $payload['baseline_value'] ?? null,
            'sort_order' => $payload['sort_order'] ?? 0,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM kpis WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }
}
