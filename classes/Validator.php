<?php
class Validator
{
    public static function email($value)
    {
        return $value !== null && filter_var(trim($value), FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function required($value)
    {
        return $value !== null && trim($value) !== '';
    }

    public static function minLength($value, $min)
    {
        return $value !== null && strlen($value) >= $min;
    }

    public static function maxLength($value, $max)
    {
        return $value === null || strlen($value) <= $max;
    }

    public static function contact($data)
    {
        $errors = [];
        if (!self::required($data['name'] ?? null)) {
            $errors['name'] = 'Emri eshte i detyrueshm.';
        }
        if (!self::required($data['email'] ?? null)) {
            $errors['email'] = 'Email-i eshte i detyrueshm.';
        } elseif (!self::email($data['email'] ?? null)) {
            $errors['email'] = 'Email-i nuk eshte i vlefshem.';
        }
        if (!self::required($data['subject'] ?? null)) {
            $errors['subject'] = 'Subjekti eshte i detyrueshm.';
        }
        if (!self::required($data['message'] ?? null)) {
            $errors['message'] = 'Mesazhi eshte i detyrueshm.';
        }
        return $errors;
    }
}
