<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Response;
use App\Services\DashboardService;

final class SummaryController
{
    private DashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function index(): void
    {
        $summary = $this->dashboardService->publicSummary();

        Response::view('summary/index', [
            'title' => 'สรุปโครงการ',
            'summary' => $summary,
        ]);
    }
}
