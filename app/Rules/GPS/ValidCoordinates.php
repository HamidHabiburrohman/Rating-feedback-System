<?php

namespace App\Rules\Gps;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCoordinates implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_array($value) || !isset($value['latitude'], $value['longitude'])) {
            $fail('Atribut harus berupa array dengan key latitude dan longitude.');
            return;
        }

        $lat = $value['latitude'];
        $lng = $value['longitude'];

        if (!is_numeric($lat) || $lat < -90 || $lat > 90) {
            $fail('Nilai latitude harus antara -90 dan 90 derajat.');
        }

        if (!is_numeric($lng) || $lng < -180 || $lng > 180) {
            $fail('Nilai longitude harus antara -180 dan 180 derajat.');
        }
    }
}