<?php

declare(strict_types=1);

namespace App\Helpers;

final class UploadHelper
{
    private const ALLOWED_TYPES = [
        'application/pdf',
        'image/jpeg',
        'image/png',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-excel',
    ];

    private const MAX_SIZE_BYTES = 5_242_880;

    public static function validate(array $file): array
    {
        $errors = [];

        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return $errors;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'File upload failed.';
            return $errors;
        }

        if (($file['size'] ?? 0) > self::MAX_SIZE_BYTES) {
            $errors[] = 'File size must not exceed 5MB.';
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        if (!in_array($mime, self::ALLOWED_TYPES, true)) {
            $errors[] = 'Unsupported file type.';
        }

        return $errors;
    }

    public static function store(array $file): ?array
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $storedName = bin2hex(random_bytes(16)) . ($ext ? '.' . strtolower($ext) : '');
        $relativePath = 'uploads/' . $storedName;
        $fullPath = BASE_PATH . '/public/' . $relativePath;

        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            throw new \RuntimeException('Unable to move uploaded file.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);

        return [
            'original_name' => $file['name'],
            'stored_name' => $storedName,
            'file_path' => $relativePath,
            'mime_type' => $finfo->file($fullPath),
            'file_size' => filesize($fullPath),
        ];
    }
}
