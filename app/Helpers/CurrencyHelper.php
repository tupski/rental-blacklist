<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Format currency to Indonesian Rupiah format
     * 
     * @param float|int|string|null $amount
     * @param bool $showSymbol
     * @return string
     */
    public static function format($amount, $showSymbol = true)
    {
        if (empty($amount) || $amount == 0) {
            return $showSymbol ? 'Rp 0' : '0';
        }

        // Convert to numeric if string
        if (is_string($amount)) {
            $amount = (float) preg_replace('/[^\d.]/', '', $amount);
        }

        $formatted = number_format($amount, 0, ',', '.');
        
        return $showSymbol ? 'Rp ' . $formatted : $formatted;
    }

    /**
     * Parse formatted currency string to numeric value
     * 
     * @param string $formattedAmount
     * @return float
     */
    public static function parse($formattedAmount)
    {
        if (empty($formattedAmount)) {
            return 0;
        }

        // Remove all non-numeric characters except decimal point
        $numeric = preg_replace('/[^\d.]/', '', $formattedAmount);
        
        return (float) $numeric;
    }

    /**
     * Get display format for currency
     * 
     * @param float|int|string|null $amount
     * @return string
     */
    public static function display($amount)
    {
        if (empty($amount) || $amount == 0) {
            return 'N/A';
        }

        return self::format($amount, true);
    }

    /**
     * Format for input field (with thousands separator but no Rp symbol)
     * 
     * @param float|int|string|null $amount
     * @return string
     */
    public static function formatForInput($amount)
    {
        return self::format($amount, false);
    }
}
