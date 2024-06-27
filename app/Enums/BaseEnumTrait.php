<?php

namespace App\Enums;

trait BaseEnumTrait
{
    public static function values(): array
    {
        $values = [];
        foreach (self::cases() as $case) {
            $values[] = $case->value;
        }

        return $values;
    }

    public static function choices(): array
    {
        $values = [];
        foreach (self::cases() as $case) {
            if (method_exists(self::class, 'labels')) {
                $values[$case->value] = self::labels()[$case->value] ?? ucfirst(str_replace('_', ' ', $case->value));
            } else {
                $values[$case->value] = ucfirst(str_replace('_', ' ', $case->value));
            }
        }

        return $values;
    }

    public function label(): string
    {
        return self::choices()[$this->value];
    }
}
