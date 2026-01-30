<?php
/**
 * Validator - validim backend (OOP)
 * AirLugina Faza 2
 */

class Validator
{
    public static function email(?string $value): bool
    {
        return $value !== null && filter_var(trim($value), FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function required(?string $value): bool
    {
        return $value !== null && trim($value) !== '';
    }

    public static function minLength(?string $value, int $min): bool
    {
        return $value !== null && strlen($value) >= $min;
    }

    public static function maxLength(?string $value, int $max): bool
    {
        return $value === null || strlen($value) <= $max;
    }

    public static function contact(array $data): array
    {
        $errors = [];
        if (!self::required($data['name'] ?? null)) {
            $errors['name'] = 'Emri është i detyrueshëm.';
        }
        if (!self::required($data['email'] ?? null)) {
            $errors['email'] = 'Email-i është i detyrueshëm.';
        } elseif (!self::email($data['email'] ?? null)) {
            $errors['email'] = 'Email-i nuk është i vlefshëm.';
        }
        if (!self::required($data['subject'] ?? null)) {
            $errors['subject'] = 'Subjekti është i detyrueshëm.';
        }
        if (!self::required($data['message'] ?? null)) {
            $errors['message'] = 'Mesazhi është i detyrueshëm.';
        }
        return $errors;
    }
}
