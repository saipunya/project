<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Response;
use App\Services\DashboardService;

final class DashboardController
{
    private DashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function index(): void
    {
        $fiscalYearId = (int) ($_GET['fiscal_year_id'] ?? 0);
        $role = Auth::user()['role'] ?? null;

        if ($role === 'EXECUTIVE') {
            $summary = $this->dashboardService->executiveSummary($fiscalYearId);
            Response::view('dashboard/executive', [
                'title' => 'Executive Dashboard',
                'summary' => $summary,
            ]);
            return;
        }

        $summary = $this->dashboardService->adminSummary($fiscalYearId);
        Response::view('dashboard/admin', [
            'title' => 'Admin Dashboard',
            'summary' => $summary,
        ]);
    }
}
