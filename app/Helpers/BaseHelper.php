<?php

namespace App\Helpers;

use App\Enums\Core\AmountTypeEnum;
use App\Enums\Setting\SettingFieldsEnum;
use Illuminate\Support\Facades\Storage;

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

    /**
     * @param string|null $fileName
     * @param string $folderPath
     * @return string
     */
    public static function storageLink(string $fileName = null, string $folderPath = "others"): string
    {
        if ($fileName) {
            return Storage::url("{$folderPath}/{$fileName}");
        } else {
            return asset("assets/img/default-image.jpg");
        }
    }

    /**
     * @param float|int $amount
     * @param float|int $percentage
     * @return float|int
     */
    public static function calculatePercentage(float|int $amount, float|int $percentage): float|int
    {
        return self::numberFormat($amount * ($percentage / 100));
    }

    /**
     * @param float|int $number
     * @return float|int
     */
    public static function numberFormat(float|int $number): float|int
    {
        return (double) number_format(
            num: $number,
            decimals: settings()->get(SettingFieldsEnum::DECIMAL_POINT->value, 4),
            thousands_separator: ''
        );
    }

    /**
     * @param float|int $amount
     * @return array
     */
    public static function calculateDefaultDiscount(float|int $amount): array
    {
        $discount = settings()->get(SettingFieldsEnum::DISCOUNT->value, 0);
        $discountType = "percentage";
        if ($discountType == AmountTypeEnum::PERCENTAGE->value) {
            $totalDiscount = self::calculatePercentage(
                amount: $amount,
                percentage: $discount
            );
        } else {
            $totalDiscount = $discount;
        }

        return [
            "discount"      => (double) $discount,
            "discountType"  => $discountType,
            "totalDiscount" => (double) $totalDiscount
        ];
    }

    /**
     * @param float|int $amount
     * @param float|int $discount
     * @param string $discountType
     * @return array
     */
    public static function calculateCustomDiscount(float|int $amount, float|int $discount, string $discountType): array
    {
        if ($discountType == AmountTypeEnum::PERCENTAGE->value) {
            $totalDiscount = self::calculatePercentage(
                amount: $amount,
                percentage: $discount
            );
        } else {
            $totalDiscount = $discount;
        }

        return [
            "discount"      => (double) $discount,
            "discountType"  => $discountType,
            "totalDiscount" => (double) $totalDiscount
        ];
    }

    /**
     * @param float|int $amount
     * @return array
     */
    public static function calculateTax(float|int $amount): array
    {
        $tax = settings()->get(SettingFieldsEnum::TAX->value, 0);
        $totalTax = self::calculatePercentage(
            amount: $amount,
            percentage: $tax
        );

        return [
            "tax"      => (double) $tax,
            "totalTax" => (double) $totalTax
        ];
    }
}
