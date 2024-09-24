<?php

namespace App\Services;

use App\Enums\Setting\SettingFieldsEnum;

class SettingService
{
    /**
     * @param array $payload
     * @return mixed
     */
    public function update(array $payload): mixed
    {
        $processPayload = [];
        foreach (SettingFieldsEnum::values() as $value) {
            if (isset($payload[$value])) {
                $processPayload[$value] = $payload[$value];
            }
        }

        return settings()->set($processPayload);
    }
}
