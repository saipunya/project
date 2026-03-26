<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Response;
use App\Models\Project;
use App\Services\BudgetReportService;

final class BudgetReportController
{
    private BudgetReportService $budgetReportService;

    public function __construct()
    {
        $this->budgetReportService = new BudgetReportService();
    }

    public function create(): void
    {
        $projectId = (int) ($_GET['project_id'] ?? 0);
        $month = (int) ($_GET['month'] ?? 0);
        $year = (int) ($_GET['year'] ?? 0);
        $formData = $this->budgetReportService->getFormData($projectId, $month, $year);

        Response::view('budget_reports/create', [
            'title' => 'Budget Report',
            'projects' => (new Project())->all(),
            'projectId' => $projectId,
            'month' => $month,
            'year' => $year,
            'reportData' => $formData,
        ]);
    }

    public function store(): void
    {
        try {
            $reportId = $this->budgetReportService->save($_POST, (int) Auth::user()['id']);

            Response::json([
                'message' => 'Budget report saved',
                'report_id' => $reportId,
            ], 201);
        } catch (\Throwable $throwable) {
            Response::json(['error' => $throwable->getMessage()], 422);
        }
    }
}
