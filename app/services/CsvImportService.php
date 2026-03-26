<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use RuntimeException;

final class CsvImportService
{
    public function import(string $entity, string $tmpPath): int
    {
        $allowed = ['plans', 'projects', 'kpis', 'activities'];
        if (!in_array($entity, $allowed, true)) {
            throw new RuntimeException('Unsupported import type.');
        }

        $handle = fopen($tmpPath, 'rb');
        if (!$handle) {
            throw new RuntimeException('Unable to open CSV file.');
        }

        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            throw new RuntimeException('CSV header is required.');
        }

        $db = Database::connection();
        $db->beginTransaction();

        try {
            $count = 0;
            while (($row = fgetcsv($handle)) !== false) {
                $data = array_combine($headers, $row);
                $this->insertRow($entity, $data);
                $count++;
            }

            $db->commit();
            fclose($handle);

            return $count;
        } catch (\Throwable $throwable) {
            $db->rollBack();
            fclose($handle);
            throw $throwable;
        }
    }

    private function insertRow(string $entity, array $data): void
    {
        $db = Database::connection();

        if ($entity === 'plans') {
            $sql = 'INSERT INTO plans (fiscal_year_id, code, name, description, owner_department, created_by)
                    VALUES (:fiscal_year_id, :code, :name, :description, :owner_department, :created_by)';
        } elseif ($entity === 'projects') {
            $sql = 'INSERT INTO projects
                    (fiscal_year_id, plan_id, code, name, description, allocated_budget, start_date, end_date, created_by)
                    VALUES
                    (:fiscal_year_id, :plan_id, :code, :name, :description, :allocated_budget, :start_date, :end_date, :created_by)';
        } elseif ($entity === 'kpis') {
            $sql = 'INSERT INTO kpis (project_id, name, type, target_value, unit, baseline_value, sort_order)
                    VALUES (:project_id, :name, :type, :target_value, :unit, :baseline_value, :sort_order)';
        } else {
            $sql = 'INSERT INTO activities
                    (project_id, name, description, status, start_date, end_date, sort_order)
                    VALUES
                    (:project_id, :name, :description, :status, :start_date, :end_date, :sort_order)';
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($data);
    }
}
