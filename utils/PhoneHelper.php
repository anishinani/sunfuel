<?php

class PhoneHelper
{
    public static function digitsOnly(string $phone): string
    {
        return preg_replace('/\D+/', '', $phone);
    }

    public static function toInternational(string $phone): string
    {
        $digits = self::digitsOnly($phone);

        if (str_starts_with($digits, '256')) {
            return $digits;
        }

        if (str_starts_with($digits, '0') && strlen($digits) === 10) {
            return '256' . substr($digits, 1);
        }

        if (strlen($digits) === 9) {
            return '256' . $digits;
        }

        return $digits;
    }

    public static function toLocal(string $phone): string
    {
        $digits = self::digitsOnly($phone);

        if (str_starts_with($digits, '256') && strlen($digits) === 12) {
            return '0' . substr($digits, 3);
        }

        if (str_starts_with($digits, '0') && strlen($digits) === 10) {
            return $digits;
        }

        if (strlen($digits) === 9) {
            return '0' . $digits;
        }

        return $digits;
    }

    public static function variants(string $phone): array
    {
        $digits = self::digitsOnly($phone);
        $local = self::toLocal($phone);
        $international = self::toInternational($phone);

        return array_values(array_unique(array_filter([
            $phone,
            $digits,
            $local,
            $international,
            '+' . $international,
        ])));
    }

    public static function sqlInList(array $phones): string
    {
        $escaped = array_map(function ($phone) {
            return "'" . addslashes($phone) . "'";
        }, $phones);

        return implode(', ', $escaped);
    }
}
