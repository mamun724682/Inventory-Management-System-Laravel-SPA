<?php

namespace App\Helpers;

class BaseHelper
{
    /**
     * @param int|null $pageNum
     * @return int
     */
    public static function perPage(int|null $pageNum): int
    {
        $perPage = $pageNum ?? 25;
        if ($perPage > 500) {
            $perPage = 500;
        }

        return $perPage;
    }

    /**
     * @param array $data
     * @return array
     */
    public static function convertKeyValueToLabelValueArray(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[] = [
                "label" => $value,
                "value" => $key
            ];
        }

        return $result;
    }
}
