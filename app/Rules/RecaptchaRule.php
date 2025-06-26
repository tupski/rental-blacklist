<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class RecaptchaRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!config('services.recaptcha.secret_key')) {
            return; // Skip validation if reCAPTCHA is not configured
        }

        if (empty($value)) {
            $fail('Silakan selesaikan verifikasi reCAPTCHA.');
            return;
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            $result = $response->json();

            if (!$result['success']) {
                $fail('Verifikasi reCAPTCHA gagal. Silakan coba lagi.');
            }
        } catch (\Exception $e) {
            $fail('Terjadi kesalahan saat memverifikasi reCAPTCHA.');
        }
    }
}
