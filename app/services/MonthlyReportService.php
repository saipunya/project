<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Helpers\UploadHelper;
use App\Models\Activity;
use App\Models\MonthlyReport;
use RuntimeException;
use Throwable;

final class MonthlyReportService
{
    private MonthlyReport $reportModel;
    private Activity $activityModel;
    private ProjectService $projectService;

    public function __construct()
    {
        $this->reportModel = new MonthlyReport();
        $this->activityModel = new Activity();
        $this->projectService = new ProjectService();
    }

    public function submit(array $payload, array $attachment, int $userId): int
    {
        $projectId = (int) $payload['project_id'];
        $month = (int) $payload['month'];
        $year = (int) $payload['year'];

        if ($month < 1 || $month > 12) {
            throw new RuntimeException('Month must be between 1 and 12.');
        }

        $existing = $this->reportModel->findByProjectMonthYear($projectId, $month, $year);

        if ($existing && (int) $existing['is_locked'] === 1) {
            throw new RuntimeException('This report is locked and cannot be edited.');
        }

        $uploadErrors = UploadHelper::validate($attachment);
        if ($uploadErrors !== []) {
            throw new RuntimeException(implode(' ', $uploadErrors));
        }

        $db = Database::connection();
        $db->beginTransaction();

        try {
            if ($existing) {
                $reportId = (int) $existing['id'];

                $this->reportModel->updateSubmission(
                    $reportId,
                    (string) ($payload['notes'] ?? ''),
                    !empty($payload['lock_after_submit']),
                    $userId
                );

                $deleteKpi = $db->prepare('DELETE FROM monthly_kpi_progress WHERE monthly_report_id = :monthly_report_id');
                $deleteKpi->execute(['monthly_report_id' => $reportId]);

                $deleteActivity = $db->prepare('DELETE FROM monthly_activity_updates WHERE monthly_report_id = :monthly_report_id');
                $deleteActivity->execute(['monthly_report_id' => $reportId]);
            } else {
                $reportId = $this->reportModel->create([
                    'project_id' => $projectId,
                    'month' => $month,
                    'year' => $year,
                    'notes' => $payload['notes'] ?? null,
                    'is_locked' => !empty($payload['lock_after_submit']) ? 1 : 0,
                    'submitted_by' => $userId,
                    'submitted_at' => date('Y-m-d H:i:s'),
                ]);
            }

            $kpiRows = $payload['kpi_progress'] ?? [];
            foreach ($kpiRows as $row) {
                $stmt = $db->prepare('INSERT INTO monthly_kpi_progress
                    (monthly_report_id, kpi_id, incremental_value, text_value)
                    VALUES (:monthly_report_id, :kpi_id, :incremental_value, :text_value)');

                $stmt->execute([
                    'monthly_report_id' => $reportId,
                    'kpi_id' => $row['kpi_id'],
                    'incremental_value' => $row['incremental_value'] ?? null,
                    'text_value' => $row['text_value'] ?? null,
                ]);
            }

            $activityRows = $payload['activity_updates'] ?? [];
            foreach ($activityRows as $row) {
                $stmt = $db->prepare('INSERT INTO monthly_activity_updates
                    (monthly_report_id, activity_id, status, progress_percent, update_note)
                    VALUES (:monthly_report_id, :activity_id, :status, :progress_percent, :update_note)');

                $stmt->execute([
                    'monthly_report_id' => $reportId,
                    'activity_id' => $row['activity_id'],
                    'status' => $row['status'],
                    'progress_percent' => $row['progress_percent'] ?? null,
                    'update_note' => $row['update_note'] ?? null,
                ]);

                $this->activityModel->updateStatus((int) $row['activity_id'], $row['status']);
            }

            $budget = $payload['budget_usage'] ?? null;
            if ($budget) {
                $stmt = $db->prepare('INSERT INTO monthly_budget_usage
                    (monthly_report_id, project_id, expense_amount, expense_note)
                    VALUES (:monthly_report_id, :project_id, :expense_amount, :expense_note)');
                $stmt->execute([
                    'monthly_report_id' => $reportId,
                    'project_id' => $projectId,
                    'expense_amount' => $budget['expense_amount'],
                    'expense_note' => $budget['expense_note'] ?? null,
                ]);
            }

            $stored = UploadHelper::store($attachment);
            if ($stored) {
                $stmt = $db->prepare('INSERT INTO attachments
                    (monthly_report_id, original_name, stored_name, file_path, mime_type, file_size, uploaded_by)
                    VALUES (:monthly_report_id, :original_name, :stored_name, :file_path, :mime_type, :file_size, :uploaded_by)');
                $stmt->execute([
                    'monthly_report_id' => $reportId,
                    'original_name' => $stored['original_name'],
                    'stored_name' => $stored['stored_name'],
                    'file_path' => $stored['file_path'],
                    'mime_type' => $stored['mime_type'],
                    'file_size' => $stored['file_size'],
                    'uploaded_by' => $userId,
                ]);
            }

            $this->projectService->refreshProjectStatus($projectId);

            if (!empty($payload['lock_after_submit'])) {
                $this->reportModel->lock($reportId);
            }

            $db->commit();

            return $reportId;
        } catch (Throwable $throwable) {
            $db->rollBack();
            throw $throwable;
        }
    }

    public function getForEdit(int $reportId): array
    {
        $report = $this->reportModel->findById($reportId);
        if (!$report) {
            throw new RuntimeException('Monthly report not found.');
        }

        $db = Database::connection();

        $kpiStmt = $db->prepare('SELECT kpi_id, incremental_value, text_value
                                 FROM monthly_kpi_progress
                                 WHERE monthly_report_id = :monthly_report_id
                                 ORDER BY id');
        $kpiStmt->execute(['monthly_report_id' => $reportId]);

        $activityStmt = $db->prepare('SELECT activity_id, status, progress_percent, update_note
                                      FROM monthly_activity_updates
                                      WHERE monthly_report_id = :monthly_report_id
                                      ORDER BY id');
        $activityStmt->execute(['monthly_report_id' => $reportId]);

        $budgetStmt = $db->prepare('SELECT expense_amount, expense_note
                                    FROM monthly_budget_usage
                                    WHERE monthly_report_id = :monthly_report_id
                                    LIMIT 1');
        $budgetStmt->execute(['monthly_report_id' => $reportId]);

        return [
            'report' => $report,
            'kpi_progress' => $kpiStmt->fetchAll(),
            'activity_updates' => $activityStmt->fetchAll(),
            'budget_usage' => $budgetStmt->fetch() ?: ['expense_amount' => 0, 'expense_note' => ''],
        ];
    }

    public function update(int $reportId, array $payload, array $attachment, int $userId): int
    {
        $existing = $this->reportModel->findById($reportId);
        if (!$existing) {
            throw new RuntimeException('Monthly report not found.');
        }

        if ((int) $existing['is_locked'] === 1) {
            throw new RuntimeException('This report is locked and cannot be edited.');
        }

        $uploadErrors = UploadHelper::validate($attachment);
        if ($uploadErrors !== []) {
            throw new RuntimeException(implode(' ', $uploadErrors));
        }

        $db = Database::connection();
        $db->beginTransaction();

        try {
            $this->reportModel->updateSubmission(
                $reportId,
                (string) ($payload['notes'] ?? ''),
                !empty($payload['lock_after_submit']),
                $userId
            );

            $deleteKpi = $db->prepare('DELETE FROM monthly_kpi_progress WHERE monthly_report_id = :monthly_report_id');
            $deleteKpi->execute(['monthly_report_id' => $reportId]);

            $kpiRows = $payload['kpi_progress'] ?? [];
            foreach ($kpiRows as $row) {
                $stmt = $db->prepare('INSERT INTO monthly_kpi_progress
                    (monthly_report_id, kpi_id, incremental_value, text_value)
                    VALUES (:monthly_report_id, :kpi_id, :incremental_value, :text_value)');

                $stmt->execute([
                    'monthly_report_id' => $reportId,
                    'kpi_id' => $row['kpi_id'],
                    'incremental_value' => $row['incremental_value'] ?? null,
                    'text_value' => $row['text_value'] ?? null,
                ]);
            }

            $deleteActivity = $db->prepare('DELETE FROM monthly_activity_updates WHERE monthly_report_id = :monthly_report_id');
            $deleteActivity->execute(['monthly_report_id' => $reportId]);

            $activityRows = $payload['activity_updates'] ?? [];
            foreach ($activityRows as $row) {
                $stmt = $db->prepare('INSERT INTO monthly_activity_updates
                    (monthly_report_id, activity_id, status, progress_percent, update_note)
                    VALUES (:monthly_report_id, :activity_id, :status, :progress_percent, :update_note)');

                $stmt->execute([
                    'monthly_report_id' => $reportId,
                    'activity_id' => $row['activity_id'],
                    'status' => $row['status'],
                    'progress_percent' => $row['progress_percent'] ?? null,
                    'update_note' => $row['update_note'] ?? null,
                ]);

                $this->activityModel->updateStatus((int) $row['activity_id'], $row['status']);
            }

            $budget = $payload['budget_usage'] ?? null;
            if ($budget) {
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
                        'expense_amount' => $budget['expense_amount'],
                        'expense_note' => $budget['expense_note'] ?? null,
                    ];
                } else {
                    $budgetStmt = $db->prepare('INSERT INTO monthly_budget_usage
                        (monthly_report_id, project_id, expense_amount, expense_note)
                        VALUES (:monthly_report_id, :project_id, :expense_amount, :expense_note)');

                    $budgetParams = [
                        'monthly_report_id' => $reportId,
                        'project_id' => (int) $existing['project_id'],
                        'expense_amount' => $budget['expense_amount'],
                        'expense_note' => $budget['expense_note'] ?? null,
                    ];
                }

                $budgetStmt->execute($budgetParams);
            }

            $stored = UploadHelper::store($attachment);
            if ($stored) {
                $stmt = $db->prepare('INSERT INTO attachments
                    (monthly_report_id, original_name, stored_name, file_path, mime_type, file_size, uploaded_by)
                    VALUES (:monthly_report_id, :original_name, :stored_name, :file_path, :mime_type, :file_size, :uploaded_by)');
                $stmt->execute([
                    'monthly_report_id' => $reportId,
                    'original_name' => $stored['original_name'],
                    'stored_name' => $stored['stored_name'],
                    'file_path' => $stored['file_path'],
                    'mime_type' => $stored['mime_type'],
                    'file_size' => $stored['file_size'],
                    'uploaded_by' => $userId,
                ]);
            }

            $this->projectService->refreshProjectStatus((int) $existing['project_id']);

            $db->commit();

            return $reportId;
        } catch (Throwable $throwable) {
            $db->rollBack();
            throw $throwable;
        }
    }
}
