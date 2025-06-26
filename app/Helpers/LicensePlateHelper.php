<?php

namespace App\Helpers;

class LicensePlateHelper
{
    /**
     * Format license plate number with proper spacing
     * Example: B1234ABC -> B 1234 ABC
     * 
     * @param string|null $licensePlate
     * @return string
     */
    public static function format($licensePlate)
    {
        if (empty($licensePlate)) {
            return '';
        }

        // Remove all spaces and convert to uppercase
        $clean = strtoupper(str_replace(' ', '', $licensePlate));
        
        // Pattern: 1-2 letters, 1-4 digits, 0-3 letters
        if (preg_match('/^([A-Z]{1,2})(\d{1,4})([A-Z]{0,3})$/', $clean, $matches)) {
            $cityCode = $matches[1];
            $numbers = $matches[2];
            $suffix = $matches[3];
            
            $formatted = $cityCode . ' ' . $numbers;
            if (!empty($suffix)) {
                $formatted .= ' ' . $suffix;
            }
            
            return $formatted;
        }
        
        // If doesn't match pattern, return as is
        return $licensePlate;
    }

    /**
     * Validate license plate format
     * 
     * @param string|null $licensePlate
     * @return bool
     */
    public static function isValid($licensePlate)
    {
        if (empty($licensePlate)) {
            return true; // Allow empty
        }

        $clean = strtoupper(str_replace(' ', '', $licensePlate));
        return preg_match('/^[A-Z]{1,2}\d{1,4}[A-Z]{0,3}$/', $clean);
    }

    /**
     * Get formatted display for license plate
     * 
     * @param string|null $licensePlate
     * @return string
     */
    public static function display($licensePlate)
    {
        $formatted = self::format($licensePlate);
        return $formatted ?: 'N/A';
    }
}
