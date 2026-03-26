<?php

declare(strict_types=1);

namespace App\Models;

use PDOException;

final class Plan extends BaseModel
{
    public function all(): array
    {
        $sql = 'SELECT p.*, fy.fiscal_year AS fiscal_year_name
                FROM plans p
                INNER JOIN fiscal_years fy ON fy.id = p.fiscal_year_id
                ORDER BY fy.fiscal_year DESC, p.code ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function fiscalYears(): array
    {
        $stmt = $this->db->prepare('SELECT id, fiscal_year, start_date, end_date, is_active
                                    FROM fiscal_years
                                    ORDER BY fiscal_year DESC');
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function nextCodeByFiscalYear(int $fiscalYearId): string
    {
        $stmt = $this->db->prepare('SELECT code
                                    FROM plans
                                    WHERE fiscal_year_id = :fiscal_year_id
                                    ORDER BY id ASC');
        $stmt->execute(['fiscal_year_id' => $fiscalYearId]);

        $maxIndex = 0;
        foreach ($stmt->fetchAll() as $row) {
            $index = $this->codeToIndex((string) $row['code']);
            if ($index > $maxIndex) {
                $maxIndex = $index;
            }
        }

        return $this->indexToCode($maxIndex + 1);
    }

    public function create(array $payload): array
    {
        $sql = 'INSERT INTO plans
                (fiscal_year_id, code, name, description, owner_department, created_by)
                VALUES
                (:fiscal_year_id, :code, :name, :description, :owner_department, :created_by)';

        $attempts = 0;

        while (true) {
            $code = $this->nextCodeByFiscalYear((int) $payload['fiscal_year_id']);

            try {
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    'fiscal_year_id' => $payload['fiscal_year_id'],
                    'code' => $code,
                    'name' => $payload['name'],
                    'description' => $payload['description'] ?? null,
                    'owner_department' => $payload['owner_department'] ?? null,
                    'created_by' => $payload['created_by'],
                ]);

                return [
                    'id' => (int) $this->db->lastInsertId(),
                    'code' => $code,
                ];
            } catch (PDOException $exception) {
                if ($exception->getCode() === '23000' && $attempts < 2) {
                    $attempts++;
                    continue;
                }

                throw $exception;
            }
        }
    }

    private function codeToIndex(string $code): int
    {
        $code = strtoupper(trim($code));
        if ($code === '' || !preg_match('/^[A-Z]+$/', $code)) {
            return 0;
        }

        $index = 0;
        $length = strlen($code);
        for ($i = 0; $i < $length; $i++) {
            $index = ($index * 26) + (ord($code[$i]) - 64);
        }

        return $index;
    }

    private function indexToCode(int $index): string
    {
        if ($index <= 0) {
            return 'A';
        }

        $code = '';
        while ($index > 0) {
            $index--;
            $code = chr(65 + ($index % 26)) . $code;
            $index = intdiv($index, 26);
        }

        return $code;
    }
}
