<?php

namespace App\Helpers;

class ArrayHelper
{
    public static function getFiltersValues(array $haystack, array $fields, bool $allowNull = false): array
    {
        $result = [];

        foreach ($fields as $field) {
            if ($allowNull && array_key_exists($field, $haystack) && is_null($haystack[$field])) {
                $result[$field] = $haystack[$field];
            } elseif (isset($haystack[$field])) {
                $result[$field] = $haystack[$field];
            }
        }
        return $result;
    }

    public static function getFieldValues(array $haystack, string $field): string|null
    {
        if (isset($haystack[$field])) {
            return $haystack[$field];
        }

        return null;
    }
}
