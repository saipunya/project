<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Response;
use App\Services\DashboardService;

final class LandingController
{
    private DashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function index(): void
    {
        $user = Auth::user();

        if ($user) {
            Response::redirect('/dashboard');
            return;
        }

        $summary = $this->dashboardService->publicSummary();

        $stats = [
            'total_projects' => $summary['total_projects'] ?? 0,
            'total_budget' => number_format($summary['total_allocated'] ?? 0, 0),
            'total_kpis' => $summary['total_kpis'] ?? 0,
            'kpi_achievement' => number_format($summary['kpi_achievement'] ?? 0, 1),
        ];

        Response::view('landing/index', [
            'title' => 'ระบบติดตามโครงการ - หน้าหลัก',
            'summary' => $stats,
        ]);
    }
}
