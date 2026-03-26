<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Response;
use App\Services\CsvImportService;

final class ImportController
{
    private CsvImportService $importService;

    public function __construct()
    {
        $this->importService = new CsvImportService();
    }

    public function store(string $entity): void
    {
        if (!isset($_FILES['csv']) || $_FILES['csv']['error'] !== UPLOAD_ERR_OK) {
            Response::json(['error' => 'Valid CSV file is required'], 422);
        }

        $imported = $this->importService->import($entity, $_FILES['csv']['tmp_name']);
        Response::json(['message' => 'Import successful', 'rows' => $imported]);
    }
}
