<?php

declare(strict_types=1);

namespace App\Models;

final class Project extends BaseModel
{
    public function allByFiscalYear(int $fiscalYearId): array
    {
        $sql = 'SELECT p.*, pl.name AS plan_name
                FROM projects p
                INNER JOIN plans pl ON pl.id = p.plan_id
                WHERE p.fiscal_year_id = :fiscal_year_id
                ORDER BY p.created_at DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['fiscal_year_id' => $fiscalYearId]);

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM projects WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function create(array $payload): int
    {
        $sql = 'INSERT INTO projects
                (fiscal_year_id, plan_id, code, name, description, allocated_budget, start_date, end_date, created_by)
                VALUES
                (:fiscal_year_id, :plan_id, :code, :name, :description, :allocated_budget, :start_date, :end_date, :created_by)';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'fiscal_year_id' => $payload['fiscal_year_id'],
            'plan_id' => $payload['plan_id'],
            'code' => $payload['code'],
            'name' => $payload['name'],
            'description' => $payload['description'] ?? null,
            'allocated_budget' => $payload['allocated_budget'],
            'start_date' => $payload['start_date'] ?? null,
            'end_date' => $payload['end_date'] ?? null,
            'created_by' => $payload['created_by'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function nextCodeForPlan(int $planId, int $fiscalYear, string $planCode): string
    {
        $stmt = $this->db->prepare('SELECT code FROM projects WHERE plan_id = :plan_id ORDER BY id ASC');
        $stmt->execute(['plan_id' => $planId]);

        $maxSeq = 0;
        foreach ($stmt->fetchAll() as $row) {
            if (preg_match('/(\d{3})$/', (string) $row['code'], $matches)) {
                $seq = (int) $matches[1];
                if ($seq > $maxSeq) {
                    $maxSeq = $seq;
                }
            }
        }

        $nextSeq = $maxSeq + 1;

        return sprintf('%d%s%03d', $fiscalYear, strtoupper(trim($planCode)), $nextSeq);
    }

    public function update(int $id, array $payload): bool
    {
        $sql = 'UPDATE projects
                SET plan_id = :plan_id,
                    fiscal_year_id = :fiscal_year_id,
                    name = :name,
                    description = :description,
                    allocated_budget = :allocated_budget,
                    start_date = :start_date,
                    end_date = :end_date
                WHERE id = :id';

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'plan_id' => $payload['plan_id'],
            'fiscal_year_id' => $payload['fiscal_year_id'],
            'name' => $payload['name'],
            'description' => $payload['description'] ?? null,
            'allocated_budget' => $payload['allocated_budget'],
            'start_date' => $payload['start_date'] ?? null,
            'end_date' => $payload['end_date'] ?? null,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM projects WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }

    public function all(): array
    {
        $sql = 'SELECT p.*, pl.name AS plan_name, fy.fiscal_year AS fiscal_year_name
                FROM projects p
                INNER JOIN plans pl ON pl.id = p.plan_id
                INNER JOIN fiscal_years fy ON fy.id = p.fiscal_year_id
                ORDER BY fy.fiscal_year DESC, p.code ASC';
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function updateStatus(int $projectId, string $status): void
    {
        $stmt = $this->db->prepare('UPDATE projects SET project_status = :status WHERE id = :id');
        $stmt->execute([
            'status' => $status,
            'id' => $projectId,
        ]);
    }
}
