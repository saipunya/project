<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Response;
use App\Models\Activity;
use App\Models\Kpi;
use App\Models\Project;
use App\Services\MonthlyReportService;

final class ReportController
{
    private MonthlyReportService $monthlyReportService;

    public function __construct()
    {
        $this->monthlyReportService = new MonthlyReportService();
    }

    public function create(): void
    {
        $projectId = (int) ($_GET['project_id'] ?? 0);
        $kpis = $projectId > 0 ? (new Kpi())->listByProject($projectId) : [];
        $activities = $projectId > 0 ? (new Activity())->listByProject($projectId) : [];
        $projects = (new Project())->all();

        Response::view('reports/create', [
            'title' => 'Submit Monthly Report',
            'mode' => 'create',
            'reportData' => null,
            'projectId' => $projectId,
            'kpis' => $kpis,
            'activities' => $activities,
            'projects' => $projects,
        ]);
    }

    public function edit(string $id): void
    {
        try {
            $reportData = $this->monthlyReportService->getForEdit((int) $id);

            if ((int) $reportData['report']['is_locked'] === 1) {
                Response::json(['error' => 'This report is locked and cannot be edited.'], 422);
            }

            $projectId = (int) $reportData['report']['project_id'];
            $kpis = (new Kpi())->listByProject($projectId);
            $activities = (new Activity())->listByProject($projectId);
            $projects = (new Project())->all();

            Response::view('reports/create', [
                'title' => 'Edit Monthly Report',
                'mode' => 'edit',
                'reportData' => $reportData,
                'projectId' => $projectId,
                'kpis' => $kpis,
                'activities' => $activities,
                'projects' => $projects,
            ]);
        } catch (\Throwable $throwable) {
            Response::json(['error' => $throwable->getMessage()], 404);
        }
    }

    public function store(): void
    {
        try {
            $payload = $this->buildPayload();

            $reportId = $this->monthlyReportService->submit(
                $payload,
                $_FILES['attachment'] ?? ['error' => UPLOAD_ERR_NO_FILE],
                (int) Auth::user()['id']
            );

            Response::json(['message' => 'Monthly report submitted', 'report_id' => $reportId], 201);
        } catch (\Throwable $throwable) {
            Response::json(['error' => $throwable->getMessage()], 422);
        }
    }

    public function update(string $id): void
    {
        try {
            $payload = $this->buildPayload();

            $reportId = $this->monthlyReportService->update(
                (int) $id,
                $payload,
                $_FILES['attachment'] ?? ['error' => UPLOAD_ERR_NO_FILE],
                (int) Auth::user()['id']
            );

            Response::json(['message' => 'Monthly report updated', 'report_id' => $reportId]);
        } catch (\Throwable $throwable) {
            Response::json(['error' => $throwable->getMessage()], 422);
        }
    }

    public function loadProjectData(): void
    {
        $projectId = (int) ($_GET['project_id'] ?? 0);
        
        if ($projectId === 0) {
            Response::json(['error' => 'Project ID is required'], 400);
            return;
        }

        $kpis = (new Kpi())->listByProject($projectId);
        $activities = (new Activity())->listByProject($projectId);

        Response::json([
            'kpis' => $kpis,
            'activities' => $activities,
        ]);
    }

    private function buildPayload(): array
    {
        $kpiProgress = [];
        $kpiIds = $_POST['kpi_ids'] ?? [];
        $kpiIncrementalValues = $_POST['kpi_incremental_values'] ?? [];
        $kpiTextValues = $_POST['kpi_text_values'] ?? [];

        foreach ($kpiIds as $index => $kpiId) {
            $kpiProgress[] = [
                'kpi_id' => (int) $kpiId,
                'incremental_value' => $kpiIncrementalValues[$index] !== '' ? (float) $kpiIncrementalValues[$index] : null,
                'text_value' => trim((string) ($kpiTextValues[$index] ?? '')) ?: null,
            ];
        }

        $activityUpdates = [];
        $activityIds = $_POST['activity_ids'] ?? [];
        $activityStatuses = $_POST['activity_statuses'] ?? [];
        $activityProgress = $_POST['activity_progress_percents'] ?? [];
        $activityNotes = $_POST['activity_update_notes'] ?? [];

        foreach ($activityIds as $index => $activityId) {
            $activityUpdates[] = [
                'activity_id' => (int) $activityId,
                'status' => (string) ($activityStatuses[$index] ?? 'NOT_STARTED'),
                'progress_percent' => $activityProgress[$index] !== '' ? (float) $activityProgress[$index] : null,
                'update_note' => trim((string) ($activityNotes[$index] ?? '')) ?: null,
            ];
        }

        if ($kpiProgress === []) {
            $kpiProgress = json_decode((string) ($_POST['kpi_progress_json'] ?? '[]'), true) ?: [];
        }

        if ($activityUpdates === []) {
            $activityUpdates = json_decode((string) ($_POST['activity_updates_json'] ?? '[]'), true) ?: [];
        }

        return [
            'project_id' => (int) ($_POST['project_id'] ?? 0),
            'month' => (int) ($_POST['month'] ?? 0),
            'year' => (int) ($_POST['year'] ?? 0),
            'notes' => trim((string) ($_POST['notes'] ?? '')),
            'lock_after_submit' => isset($_POST['lock_after_submit']),
            'kpi_progress' => $kpiProgress,
            'activity_updates' => $activityUpdates,
        ];
    }
}
