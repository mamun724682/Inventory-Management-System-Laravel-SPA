<?php

namespace App\Enums;

interface BaseEnumInterface
{
    public static function values(): array;

    public static function choices(): array;

    public function label(): string;

    public static function labels(): array;
}
