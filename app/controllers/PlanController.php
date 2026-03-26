<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\Plan;

final class PlanController
{
    private Plan $planModel;

    public function __construct()
    {
        $this->planModel = new Plan();
    }

    public function index(): void
    {
        $this->renderPage(false);
    }

    public function create(): void
    {
        $this->renderPage(Auth::hasRole(['ADMIN']));
    }

    private function renderPage(bool $canCreate): void
    {
        $fiscalYears = $canCreate ? $this->planModel->fiscalYears() : [];
        $selectedFiscalYearId = $canCreate ? (int) ($_GET['fiscal_year_id'] ?? 0) : 0;

        if ($canCreate && $selectedFiscalYearId === 0 && $fiscalYears !== []) {
            $selectedFiscalYearId = (int) $fiscalYears[0]['id'];
        }

        $previewCode = $canCreate && $selectedFiscalYearId > 0
            ? $this->planModel->nextCodeByFiscalYear($selectedFiscalYearId)
            : null;

        Response::view('plans/create', [
            'title' => $canCreate ? 'Create Plan' : 'Plans',
            'plans' => $this->planModel->all(),
            'fiscalYears' => $fiscalYears,
            'selectedFiscalYearId' => $selectedFiscalYearId,
            'previewCode' => $previewCode,
            'createdCode' => $canCreate ? ($_GET['code'] ?? null) : null,
            'errorMessage' => $canCreate ? ($_GET['error'] ?? null) : null,
            'old' => [],
            'canCreate' => $canCreate,
        ]);
    }

    public function store(): void
    {
        $input = [
            'fiscal_year_id' => (int) ($_POST['fiscal_year_id'] ?? 0),
            'name' => trim((string) ($_POST['name'] ?? '')),
            'description' => trim((string) ($_POST['description'] ?? '')),
            'owner_department' => trim((string) ($_POST['owner_department'] ?? '')),
            'created_by' => Auth::user()['id'],
        ];

        $errors = Validator::required($input, ['fiscal_year_id', 'name']);
        if ((int) $input['fiscal_year_id'] <= 0) {
            $errors['fiscal_year_id'] = 'fiscal_year_id is required';
        }

        if ($errors !== []) {
            Response::view('plans/create', [
                'title' => 'Create Plan',
                'plans' => $this->planModel->all(),
                'fiscalYears' => $this->planModel->fiscalYears(),
                'selectedFiscalYearId' => $input['fiscal_year_id'],
                'previewCode' => $input['fiscal_year_id'] > 0 ? $this->planModel->nextCodeByFiscalYear($input['fiscal_year_id']) : null,
                'errorMessage' => implode(' ', $errors),
                'old' => $input,
            ]);
            return;
        }

        try {
            $plan = $this->planModel->create($input);

            Response::redirect('/plans/create?created=1&code=' . urlencode($plan['code']) . '&fiscal_year_id=' . $input['fiscal_year_id']);
        } catch (\Throwable $throwable) {
            Response::view('plans/create', [
                'title' => 'Create Plan',
                'plans' => $this->planModel->all(),
                'fiscalYears' => $this->planModel->fiscalYears(),
                'selectedFiscalYearId' => $input['fiscal_year_id'],
                'previewCode' => $input['fiscal_year_id'] > 0 ? $this->planModel->nextCodeByFiscalYear($input['fiscal_year_id']) : null,
                'errorMessage' => $throwable->getMessage(),
                'old' => $input,
            ]);
        }
    }
}
