<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class NoProfanity implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            return;
        }

        $text = preg_replace('/\p{Cf}/u', '', $value);

        foreach (config('moderation.blocked_words', []) as $word) {
            $word = trim($word);

            if ($word === '') {
                continue;
            }

            $pattern = '/(?<![\p{L}\p{M}])' . preg_quote($word, '/') . '(?![\p{L}\p{M}])/iu';

            if (preg_match($pattern, $text) === 1) {
                $fail('The :attribute contains a word that is not allowed.');
                return;
            }
        }
    }
}