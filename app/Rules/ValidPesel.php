<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ValidPesel implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^\d{11}$/', (string) $value)) {
            $fail('PESEL musi składać się z dokładnie 11 cyfr.');
            return;
        }

        $weights = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += ((int) $value[$i]) * $weights[$i];
        }
        $checksum = (10 - ($sum % 10)) % 10;

        if ($checksum !== (int) $value[10]) {
            $fail('Podany PESEL jest nieprawidłowy (błędna suma kontrolna).');
        }
    }
}
