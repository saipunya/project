<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Models\MonthlyReport;
use RuntimeException;

final class BudgetReportService
{
    private MonthlyReport $reportModel;

    public function __construct()
    {
        $this->reportModel = new MonthlyReport();
    }

    public function getFormData(int $projectId, int $month, int $year): array
    {
        if ($projectId <= 0 || $month <= 0 || $year <= 0) {
            return [
                'report' => null,
                'budget_usage' => ['expense_amount' => '', 'expense_note' => ''],
            ];
        }

        $report = $this->reportModel->findByProjectMonthYear($projectId, $month, $year);
        if (!$report) {
            return [
                'report' => null,
                'budget_usage' => ['expense_amount' => '', 'expense_note' => ''],
            ];
        }

        $db = Database::connection();
        $budgetStmt = $db->prepare('SELECT expense_amount, expense_note
                                    FROM monthly_budget_usage
                                    WHERE monthly_report_id = :monthly_report_id
                                    LIMIT 1');
        $budgetStmt->execute(['monthly_report_id' => $report['id']]);

        return [
            'report' => $report,
            'budget_usage' => $budgetStmt->fetch() ?: ['expense_amount' => '', 'expense_note' => ''],
        ];
    }

    public function save(array $payload, int $userId): int
    {
        $projectId = (int) ($payload['project_id'] ?? 0);
        $month = (int) ($payload['month'] ?? 0);
        $year = (int) ($payload['year'] ?? 0);
        $expenseAmount = (float) ($payload['expense_amount'] ?? 0);
        $expenseNote = trim((string) ($payload['expense_note'] ?? '')) ?: null;

        if ($projectId <= 0) {
            throw new RuntimeException('Project is required.');
        }

        if ($month < 1 || $month > 12) {
            throw new RuntimeException('Month must be between 1 and 12.');
        }

        if ($year <= 0) {
            throw new RuntimeException('Year is required.');
        }

        if ($expenseAmount < 0) {
            throw new RuntimeException('Expense amount must be non-negative.');
        }

        $existing = $this->reportModel->findByProjectMonthYear($projectId, $month, $year);
        if ($existing && (int) $existing['is_locked'] === 1) {
            throw new RuntimeException('This monthly report is locked and cannot be edited.');
        }

        $db = Database::connection();
        $db->beginTransaction();

        try {
            if ($existing) {
                $reportId = (int) $existing['id'];
            } else {
                $reportId = $this->reportModel->create([
                    'project_id' => $projectId,
                    'month' => $month,
                    'year' => $year,
                    'notes' => null,
                    'is_locked' => 0,
                    'submitted_by' => $userId,
                    'submitted_at' => date('Y-m-d H:i:s'),
                ]);
            }

            $budgetCheck = $db->prepare('SELECT id FROM monthly_budget_usage WHERE monthly_report_id = :monthly_report_id LIMIT 1');
            $budgetCheck->execute(['monthly_report_id' => $reportId]);
            $budgetRow = $budgetCheck->fetch();

            if ($budgetRow) {
                $budgetStmt = $db->prepare('UPDATE monthly_budget_usage
                    SET expense_amount = :expense_amount,
                        expense_note = :expense_note
                    WHERE monthly_report_id = :monthly_report_id');

                $budgetParams = [
                    'monthly_report_id' => $reportId,
                    'expense_amount' => $expenseAmount,
                    'expense_note' => $expenseNote,
                ];
            } else {
                $budgetStmt = $db->prepare('INSERT INTO monthly_budget_usage
                    (monthly_report_id, project_id, expense_amount, expense_note)
                    VALUES (:monthly_report_id, :project_id, :expense_amount, :expense_note)');

                $budgetParams = [
                    'monthly_report_id' => $reportId,
                    'project_id' => $projectId,
                    'expense_amount' => $expenseAmount,
                    'expense_note' => $expenseNote,
                ];
            }

            $budgetStmt->execute($budgetParams);

            $db->commit();

            return $reportId;
        } catch (\Throwable $throwable) {
            $db->rollBack();
            throw $throwable;
        }
    }
}
