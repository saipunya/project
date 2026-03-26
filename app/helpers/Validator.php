<?php

declare(strict_types=1);

namespace App\Helpers;

final class Validator
{
    public static function required(array $data, array $fields): array
    {
        $errors = [];

        foreach ($fields as $field) {
            if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
                $errors[$field] = sprintf('%s is required', $field);
            }
        }

        return $errors;
    }

    public static function enum(string $value, array $allowed): bool
    {
        return in_array($value, $allowed, true);
    }
}
