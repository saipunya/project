<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\Activity;
use App\Services\ProjectService;

final class ActivityController
{
    private Activity $activityModel;
    private ProjectService $projectService;

    public function __construct()
    {
        $this->activityModel = new Activity();
        $this->projectService = new ProjectService();
    }

    public function index(): void
    {
        $projectId = (int) ($_GET['project_id'] ?? 0);
        $activities = $projectId > 0 ? $this->activityModel->listByProject($projectId) : [];
        $canUpdate = Auth::hasRole(['ADMIN', 'STAFF']);
        $canDelete = Auth::hasRole(['ADMIN']);

        Response::view('activities/index', [
            'title' => 'Activities',
            'activities' => $activities,
            'projectId' => $projectId,
            'canUpdate' => $canUpdate,
            'canDelete' => $canDelete,
        ]);
    }

    public function store(): void
    {
        $input = [
            'project_id' => (int) ($_POST['project_id'] ?? 0),
            'name' => trim((string) ($_POST['name'] ?? '')),
            'description' => trim((string) ($_POST['description'] ?? '')),
            'status' => trim((string) ($_POST['status'] ?? 'NOT_STARTED')),
            'start_date' => (string) ($_POST['start_date'] ?? ''),
            'end_date' => (string) ($_POST['end_date'] ?? ''),
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
        ];

        $errors = Validator::required($input, ['project_id', 'name', 'status', 'start_date', 'end_date']);
        if (!Validator::enum($input['status'], ['NOT_STARTED', 'IN_PROGRESS', 'COMPLETED'])) {
            $errors['status'] = 'Invalid activity status';
        }

        if ($errors !== []) {
            Response::json(['errors' => $errors], 422);
        }

        $activityId = $this->activityModel->create($input);
        $this->projectService->refreshProjectStatus((int) $input['project_id']);

        Response::json(['message' => 'Activity created', 'activity_id' => $activityId]);
    }

    public function update(string $id): void
    {
        $activityId = (int) $id;
        $input = [
            'name' => trim((string) ($_POST['name'] ?? '')),
            'description' => trim((string) ($_POST['description'] ?? '')),
            'status' => trim((string) ($_POST['status'] ?? 'NOT_STARTED')),
            'start_date' => (string) ($_POST['start_date'] ?? ''),
            'end_date' => (string) ($_POST['end_date'] ?? ''),
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
        ];

        $errors = Validator::required($input, ['name', 'status', 'start_date', 'end_date']);
        if (!Validator::enum($input['status'], ['NOT_STARTED', 'IN_PROGRESS', 'COMPLETED'])) {
            $errors['status'] = 'Invalid activity status';
        }

        if ($errors !== []) {
            Response::json(['errors' => $errors], 422);
        }

        $updated = $this->activityModel->update($activityId, $input);

        $projectId = (int) ($_POST['project_id'] ?? 0);
        if ($projectId > 0) {
            $this->projectService->refreshProjectStatus($projectId);
        }

        Response::json(['updated' => $updated]);
    }

    public function destroy(string $id): void
    {
        $deleted = $this->activityModel->delete((int) $id);
        Response::json(['deleted' => $deleted]);
    }
}
