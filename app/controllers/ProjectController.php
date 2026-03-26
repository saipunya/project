<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\Plan;
use App\Models\Project;
use App\Services\ProjectService;

final class ProjectController
{
    private Project $projectModel;
    private ProjectService $projectService;

    public function __construct()
    {
        $this->projectModel = new Project();
        $this->projectService = new ProjectService();
    }

    public function index(): void
    {
        $fiscalYearId = (int) ($_GET['fiscal_year_id'] ?? 0);
        $projects = $fiscalYearId > 0 ? $this->projectModel->allByFiscalYear($fiscalYearId) : [];
        $plans = (new Plan())->all();

        Response::view('projects/index', [
            'title' => 'Projects',
            'projects' => $projects,
            'fiscalYearId' => $fiscalYearId,
            'plans' => $plans,
        ]);
    }

    public function store(): void
    {
        $input = [
            'fiscal_year_id' => (int) ($_POST['fiscal_year_id'] ?? 0),
            'plan_id' => (int) ($_POST['plan_id'] ?? 0),
            'code' => trim((string) ($_POST['code'] ?? '')),
            'name' => trim((string) ($_POST['name'] ?? '')),
            'description' => trim((string) ($_POST['description'] ?? '')),
            'allocated_budget' => (float) ($_POST['allocated_budget'] ?? 0),
            'start_date' => $_POST['start_date'] ?: null,
            'end_date' => $_POST['end_date'] ?: null,
            'created_by' => Auth::user()['id'],
        ];

        $errors = Validator::required($input, ['fiscal_year_id', 'plan_id', 'code', 'name']);
        if ($errors !== []) {
            Response::json(['errors' => $errors], 422);
        }

        $projectId = $this->projectModel->create($input);
        $this->projectService->refreshProjectStatus($projectId);

        Response::json(['message' => 'Project created', 'project_id' => $projectId]);
    }

    public function update(string $id): void
    {
        $projectId = (int) $id;
        $input = [
            'plan_id' => (int) ($_POST['plan_id'] ?? 0),
            'name' => trim((string) ($_POST['name'] ?? '')),
            'description' => trim((string) ($_POST['description'] ?? '')),
            'allocated_budget' => (float) ($_POST['allocated_budget'] ?? 0),
            'start_date' => $_POST['start_date'] ?: null,
            'end_date' => $_POST['end_date'] ?: null,
        ];

        $errors = Validator::required($input, ['plan_id', 'name']);
        if ($errors !== []) {
            Response::json(['errors' => $errors], 422);
        }

        $updated = $this->projectModel->update($projectId, $input);
        $this->projectService->refreshProjectStatus($projectId);

        Response::json(['updated' => $updated]);
    }

    public function destroy(string $id): void
    {
        $deleted = $this->projectModel->delete((int) $id);
        Response::json(['deleted' => $deleted]);
    }
}
