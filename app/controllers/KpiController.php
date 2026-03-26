<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\Kpi;

final class KpiController
{
    private Kpi $kpiModel;

    public function __construct()
    {
        $this->kpiModel = new Kpi();
    }

    public function index(): void
    {
        $projectId = (int) ($_GET['project_id'] ?? 0);
        $kpis = $projectId > 0 ? $this->kpiModel->listByProject($projectId) : [];

        Response::view('kpis/index', [
            'title' => 'KPIs',
            'kpis' => $kpis,
            'projectId' => $projectId,
        ]);
    }

    public function store(): void
    {
        $input = [
            'project_id' => (int) ($_POST['project_id'] ?? 0),
            'name' => trim((string) ($_POST['name'] ?? '')),
            'type' => trim((string) ($_POST['type'] ?? 'number')),
            'target_value' => $_POST['target_value'] !== '' ? (float) $_POST['target_value'] : null,
            'unit' => trim((string) ($_POST['unit'] ?? '')),
            'baseline_value' => $_POST['baseline_value'] !== '' ? (float) $_POST['baseline_value'] : null,
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
        ];

        $errors = Validator::required($input, ['project_id', 'name', 'type']);
        if (!Validator::enum($input['type'], ['number', 'percentage', 'text'])) {
            $errors['type'] = 'Invalid KPI type';
        }

        if ($errors !== []) {
            Response::json(['errors' => $errors], 422);
        }

        $kpiId = $this->kpiModel->create($input);
        Response::json(['message' => 'KPI created', 'kpi_id' => $kpiId]);
    }

    public function update(string $id): void
    {
        $input = [
            'name' => trim((string) ($_POST['name'] ?? '')),
            'type' => trim((string) ($_POST['type'] ?? 'number')),
            'target_value' => $_POST['target_value'] !== '' ? (float) $_POST['target_value'] : null,
            'unit' => trim((string) ($_POST['unit'] ?? '')),
            'baseline_value' => $_POST['baseline_value'] !== '' ? (float) $_POST['baseline_value'] : null,
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
        ];

        $errors = Validator::required($input, ['name', 'type']);
        if (!Validator::enum($input['type'], ['number', 'percentage', 'text'])) {
            $errors['type'] = 'Invalid KPI type';
        }

        if ($errors !== []) {
            Response::json(['errors' => $errors], 422);
        }

        $updated = $this->kpiModel->update((int) $id, $input);
        Response::json(['updated' => $updated]);
    }

    public function destroy(string $id): void
    {
        $deleted = $this->kpiModel->delete((int) $id);
        Response::json(['deleted' => $deleted]);
    }
}
