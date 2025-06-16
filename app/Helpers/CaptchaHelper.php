<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CaptchaHelper
{
    /**
     * Check if captcha is enabled for specific form
     */
    public static function isEnabled(string $formType = 'general'): bool
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        
        // Check if captcha is globally enabled
        if (!isset($settings['captcha_enabled']) || $settings['captcha_enabled'] !== '1') {
            return false;
        }

        // Check if captcha is enabled for specific form
        $formKey = "captcha_{$formType}";
        if (isset($settings[$formKey]) && $settings[$formKey] === '1') {
            return true;
        }

        return false;
    }

    /**
     * Get captcha configuration
     */
    public static function getConfig(): array
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        
        return [
            'enabled' => $settings['captcha_enabled'] ?? '0',
            'type' => $settings['captcha_type'] ?? 'recaptcha_v2',
            'recaptcha_v2_site_key' => $settings['recaptcha_v2_site_key'] ?? '',
            'recaptcha_v2_secret_key' => $settings['recaptcha_v2_secret_key'] ?? '',
            'recaptcha_v3_site_key' => $settings['recaptcha_v3_site_key'] ?? '',
            'recaptcha_v3_secret_key' => $settings['recaptcha_v3_secret_key'] ?? '',
            'hcaptcha_site_key' => $settings['hcaptcha_site_key'] ?? '',
            'hcaptcha_secret_key' => $settings['hcaptcha_secret_key'] ?? '',
            'turnstile_site_key' => $settings['turnstile_site_key'] ?? '',
            'turnstile_secret_key' => $settings['turnstile_secret_key'] ?? '',
        ];
    }

    /**
     * Render captcha HTML
     */
    public static function render(string $formType = 'general'): string
    {
        if (!self::isEnabled($formType)) {
            return '';
        }

        $config = self::getConfig();
        $type = $config['type'];

        switch ($type) {
            case 'recaptcha_v2':
                if (empty($config['recaptcha_v2_site_key'])) {
                    return '';
                }
                return '<div class="g-recaptcha" data-sitekey="' . $config['recaptcha_v2_site_key'] . '"></div>';

            case 'recaptcha_v3':
                if (empty($config['recaptcha_v3_site_key'])) {
                    return '';
                }
                return '<input type="hidden" id="recaptcha-token" name="g-recaptcha-response">';

            case 'hcaptcha':
                if (empty($config['hcaptcha_site_key'])) {
                    return '';
                }
                return '<div class="h-captcha" data-sitekey="' . $config['hcaptcha_site_key'] . '"></div>';

            case 'turnstile':
                if (empty($config['turnstile_site_key'])) {
                    return '';
                }
                return '<div class="cf-turnstile" data-sitekey="' . $config['turnstile_site_key'] . '"></div>';

            default:
                return '';
        }
    }

    /**
     * Get captcha scripts
     */
    public static function getScripts(string $formType = 'general'): string
    {
        if (!self::isEnabled($formType)) {
            return '';
        }

        $config = self::getConfig();
        $type = $config['type'];

        switch ($type) {
            case 'recaptcha_v2':
                if (empty($config['recaptcha_v2_site_key'])) {
                    return '';
                }
                return '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';

            case 'recaptcha_v3':
                if (empty($config['recaptcha_v3_site_key'])) {
                    return '';
                }
                return '
                <script src="https://www.google.com/recaptcha/api.js?render=' . $config['recaptcha_v3_site_key'] . '"></script>
                <script>
                    grecaptcha.ready(function() {
                        grecaptcha.execute("' . $config['recaptcha_v3_site_key'] . '", {action: "submit"}).then(function(token) {
                            document.getElementById("recaptcha-token").value = token;
                        });
                    });
                </script>';

            case 'hcaptcha':
                if (empty($config['hcaptcha_site_key'])) {
                    return '';
                }
                return '<script src="https://js.hcaptcha.com/1/api.js" async defer></script>';

            case 'turnstile':
                if (empty($config['turnstile_site_key'])) {
                    return '';
                }
                return '<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>';

            default:
                return '';
        }
    }

    /**
     * Verify captcha response
     */
    public static function verify(string $response, string $formType = 'general'): bool
    {
        if (!self::isEnabled($formType)) {
            return true; // Skip verification if disabled
        }

        if (empty($response)) {
            return false;
        }

        $config = self::getConfig();
        $type = $config['type'];

        try {
            switch ($type) {
                case 'recaptcha_v2':
                case 'recaptcha_v3':
                    return self::verifyRecaptcha($response, $config, $type);

                case 'hcaptcha':
                    return self::verifyHcaptcha($response, $config);

                case 'turnstile':
                    return self::verifyTurnstile($response, $config);

                default:
                    return true;
            }
        } catch (\Exception $e) {
            Log::error('Captcha verification error', [
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Verify reCAPTCHA
     */
    private static function verifyRecaptcha(string $response, array $config, string $type): bool
    {
        $secretKey = $type === 'recaptcha_v2' ? $config['recaptcha_v2_secret_key'] : $config['recaptcha_v3_secret_key'];
        
        if (empty($secretKey)) {
            return true; // Skip if no secret key
        }

        $verifyResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $response,
            'remoteip' => request()->ip()
        ]);

        if ($verifyResponse->successful()) {
            $result = $verifyResponse->json();
            return isset($result['success']) && $result['success'] === true;
        }

        return false;
    }

    /**
     * Verify hCaptcha
     */
    private static function verifyHcaptcha(string $response, array $config): bool
    {
        $secretKey = $config['hcaptcha_secret_key'];
        
        if (empty($secretKey)) {
            return true; // Skip if no secret key
        }

        $verifyResponse = Http::asForm()->post('https://hcaptcha.com/siteverify', [
            'secret' => $secretKey,
            'response' => $response,
            'remoteip' => request()->ip()
        ]);

        if ($verifyResponse->successful()) {
            $result = $verifyResponse->json();
            return isset($result['success']) && $result['success'] === true;
        }

        return false;
    }

    /**
     * Verify Cloudflare Turnstile
     */
    private static function verifyTurnstile(string $response, array $config): bool
    {
        $secretKey = $config['turnstile_secret_key'];
        
        if (empty($secretKey)) {
            return true; // Skip if no secret key
        }

        $verifyResponse = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => $secretKey,
            'response' => $response,
            'remoteip' => request()->ip()
        ]);

        if ($verifyResponse->successful()) {
            $result = $verifyResponse->json();
            return isset($result['success']) && $result['success'] === true;
        }

        return false;
    }

    /**
     * Get validation rules for captcha
     */
    public static function getValidationRules(string $formType = 'general'): array
    {
        if (!self::isEnabled($formType)) {
            return [];
        }

        $config = self::getConfig();
        $type = $config['type'];

        switch ($type) {
            case 'recaptcha_v2':
            case 'recaptcha_v3':
                return ['g-recaptcha-response' => 'required'];

            case 'hcaptcha':
                return ['h-captcha-response' => 'required'];

            case 'turnstile':
                return ['cf-turnstile-response' => 'required'];

            default:
                return [];
        }
    }

    /**
     * Get validation messages for captcha
     */
    public static function getValidationMessages(): array
    {
        return [
            'g-recaptcha-response.required' => 'Verifikasi captcha wajib diisi.',
            'h-captcha-response.required' => 'Verifikasi captcha wajib diisi.',
            'cf-turnstile-response.required' => 'Verifikasi captcha wajib diisi.',
        ];
    }
}
