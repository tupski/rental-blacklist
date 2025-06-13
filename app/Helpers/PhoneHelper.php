<?php

namespace App\Helpers;

class PhoneHelper
{
    /**
     * Normalize phone number to 08 format
     * 
     * @param string $phone
     * @return string
     */
    public static function normalize($phone)
    {
        if (empty($phone)) {
            return '';
        }

        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Handle different formats
        if (substr($phone, 0, 3) === '628') {
            // +628xxxxxxxxx -> 08xxxxxxxxx
            $phone = '0' . substr($phone, 2);
        } elseif (substr($phone, 0, 2) === '62') {
            // 62xxxxxxxxx -> 08xxxxxxxxx
            $phone = '0' . substr($phone, 1);
        } elseif (substr($phone, 0, 1) === '8') {
            // 8xxxxxxxxx -> 08xxxxxxxxx
            $phone = '0' . $phone;
        }

        return $phone;
    }

    /**
     * Check if phone number is valid Indonesian mobile number
     * 
     * @param string $phone
     * @return bool
     */
    public static function isValid($phone)
    {
        $normalized = self::normalize($phone);
        
        // Indonesian mobile numbers start with 08 and have 10-13 digits
        return preg_match('/^08[0-9]{8,11}$/', $normalized);
    }

    /**
     * Format phone number for display
     * 
     * @param string $phone
     * @return string
     */
    public static function format($phone)
    {
        $normalized = self::normalize($phone);
        
        if (strlen($normalized) >= 10) {
            return substr($normalized, 0, 4) . '-' . 
                   substr($normalized, 4, 4) . '-' . 
                   substr($normalized, 8);
        }
        
        return $normalized;
    }
}
