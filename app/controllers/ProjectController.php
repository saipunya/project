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
        $projects = $fiscalYearId > 0 ? $this->projectModel->allByFiscalYear($fiscalYearId) : $this->projectModel->all();
        $plans = (new Plan())->all();

        Response::view('projects/index', [
            'title' => 'Projects',
            'projects' => $projects,
            'fiscalYearId' => $fiscalYearId,
            'plans' => $plans,
            'canManage' => Auth::hasRole(['ADMIN']),
        ]);
    }

    public function store(): void
    {
        $input = [
            'plan_id' => (int) ($_POST['plan_id'] ?? 0),
            'name' => trim((string) ($_POST['name'] ?? '')),
            'description' => trim((string) ($_POST['description'] ?? '')),
            'allocated_budget' => (float) ($_POST['allocated_budget'] ?? 0),
            'start_date' => $_POST['start_date'] ?: null,
            'end_date' => $_POST['end_date'] ?: null,
            'created_by' => Auth::user()['id'],
        ];

        $errors = Validator::required($input, ['plan_id', 'name']);
        if ($errors !== []) {
            Response::json(['errors' => $errors], 422);
        }

        $plan = (new Plan())->findWithFiscalYear((int) $input['plan_id']);
        if (!$plan) {
            Response::json(['errors' => ['plan_id' => 'Plan not found']], 422);
        }

        $input['fiscal_year_id'] = (int) $plan['fiscal_year_id'];
        $projectCode = $this->projectModel->nextCodeForPlan((int) $input['plan_id'], (int) $plan['fiscal_year'], (string) $plan['code']);
        $input['code'] = $projectCode;

        $projectId = $this->projectModel->create($input);
        $this->projectService->refreshProjectStatus($projectId);

        Response::redirect('/projects?fiscal_year_id=' . $input['fiscal_year_id'] . '&created_code=' . urlencode($projectCode));
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

        $plan = (new Plan())->findWithFiscalYear((int) $input['plan_id']);
        if (!$plan) {
            Response::json(['errors' => ['plan_id' => 'Plan not found']], 422);
        }

        $input['fiscal_year_id'] = (int) $plan['fiscal_year_id'];

        $updated = $this->projectModel->update($projectId, $input);
        $this->projectService->refreshProjectStatus($projectId);

        Response::json(['updated' => $updated]);
    }

    public function show(string $id): void
    {
        $project = $this->projectModel->find((int) $id);
        if (!$project) {
            Response::json(['error' => 'Project not found'], 404);
            return;
        }

        $plan = (new Plan())->find($project['plan_id']);
        
        Response::json([
            'project' => $project,
            'plan' => $plan,
        ]);
    }

    public function destroy(string $id): void
    {
        $deleted = $this->projectModel->delete((int) $id);
        Response::json(['deleted' => $deleted]);
    }
}
