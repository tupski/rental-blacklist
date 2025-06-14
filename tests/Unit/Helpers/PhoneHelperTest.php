<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Helpers\PhoneHelper;

/**
 * Test Unit untuk PhoneHelper
 *
 * Menguji fungsi normalisasi nomor telepon:
 * - Konversi berbagai format ke format 08xx
 * - Handling edge cases
 * - Validasi input
 */
class PhoneHelperTest extends TestCase
{
    /**
     * Test: Normalisasi nomor HP format +62
     *
     * Alur:
     * Input: Nomor HP dengan format +62xxxxxxxxxx
     * Proses: Panggil PhoneHelper::normalize()
     * Output: Nomor HP dalam format 08xxxxxxxxxx
     */
    public function test_normalize_plus_62_format()
    {
        $testCases = [
            '+6281234567890' => '081234567890',
            '+6287654321098' => '087654321098',
            '+6285555666677' => '085555666677',
            '+6282111222333' => '082111222333',
        ];

        foreach ($testCases as $input => $expected) {
            $result = PhoneHelper::normalize($input);
            $this->assertEquals($expected, $result, "Failed for input: {$input}");
        }
    }

    /**
     * Test: Normalisasi nomor HP format 62 (tanpa +)
     *
     * Alur:
     * Input: Nomor HP dengan format 62xxxxxxxxxx
     * Proses: Panggil PhoneHelper::normalize()
     * Output: Nomor HP dalam format 08xxxxxxxxxx
     */
    public function test_normalize_62_format()
    {
        $testCases = [
            '6281234567890' => '081234567890',
            '6287654321098' => '087654321098',
            '6285555666677' => '085555666677',
            '6282111222333' => '082111222333',
        ];

        foreach ($testCases as $input => $expected) {
            $result = PhoneHelper::normalize($input);
            $this->assertEquals($expected, $result, "Failed for input: {$input}");
        }
    }

    /**
     * Test: Normalisasi nomor HP format 8 (tanpa 0)
     *
     * Alur:
     * Input: Nomor HP dengan format 8xxxxxxxxxx
     * Proses: Panggil PhoneHelper::normalize()
     * Output: Nomor HP dalam format 08xxxxxxxxxx
     */
    public function test_normalize_8_format()
    {
        $testCases = [
            '81234567890' => '081234567890',
            '87654321098' => '087654321098',
            '85555666677' => '085555666677',
            '82111222333' => '082111222333',
        ];

        foreach ($testCases as $input => $expected) {
            $result = PhoneHelper::normalize($input);
            $this->assertEquals($expected, $result, "Failed for input: {$input}");
        }
    }

    /**
     * Test: Nomor HP yang sudah dalam format 08 tidak berubah
     *
     * Alur:
     * Input: Nomor HP dengan format 08xxxxxxxxxx
     * Proses: Panggil PhoneHelper::normalize()
     * Output: Nomor HP tetap sama
     */
    public function test_normalize_already_08_format()
    {
        $testCases = [
            '081234567890',
            '087654321098',
            '085555666677',
            '082111222333',
        ];

        foreach ($testCases as $input) {
            $result = PhoneHelper::normalize($input);
            $this->assertEquals($input, $result, "Failed for input: {$input}");
        }
    }

    /**
     * Test: Handling nomor HP dengan spasi dan karakter khusus
     *
     * Alur:
     * Input: Nomor HP dengan spasi, dash, atau karakter lain
     * Proses: Panggil PhoneHelper::normalize()
     * Output: Nomor HP bersih dalam format 08xxxxxxxxxx
     */
    public function test_normalize_with_special_characters()
    {
        $testCases = [
            '+62 812 3456 7890' => '081234567890',
            '62-812-345-67890' => '081234567890',  // Note: digits are extracted and normalized
            '+62 (812) 345-67890' => '081234567890',
            '62 812.345.67890' => '081234567890',
            ' +62 812 345 67890 ' => '081234567890', // with leading/trailing spaces
        ];

        foreach ($testCases as $input => $expected) {
            $result = PhoneHelper::normalize($input);
            $this->assertEquals($expected, $result, "Failed for input: {$input}");
        }
    }

    /**
     * Test: Handling nomor HP yang terlalu pendek
     *
     * Alur:
     * Input: Nomor HP dengan panjang kurang dari standar
     * Proses: Panggil PhoneHelper::normalize()
     * Output: Nomor HP tetap atau dikembalikan apa adanya
     */
    public function test_normalize_short_numbers()
    {
        $testCases = [
            '081234' => '081234', // Too short, return as is
            '8123' => '08123',    // Add 0 prefix but still short
            '123' => '123',       // Very short, return as is
        ];

        foreach ($testCases as $input => $expected) {
            $result = PhoneHelper::normalize($input);
            $this->assertEquals($expected, $result, "Failed for input: {$input}");
        }
    }

    /**
     * Test: Handling input kosong atau null
     *
     * Alur:
     * Input: String kosong, null, atau whitespace
     * Proses: Panggil PhoneHelper::normalize()
     * Output: Input dikembalikan apa adanya
     */
    public function test_normalize_empty_input()
    {
        $testCases = [
            '' => '',
            '   ' => '',  // Whitespace should be trimmed
            null => null,
        ];

        foreach ($testCases as $input => $expected) {
            $result = PhoneHelper::normalize($input);
            $this->assertEquals($expected, $result, "Failed for input: " . var_export($input, true));
        }
    }

    /**
     * Test: Handling nomor HP dengan awalan operator yang berbeda
     *
     * Alur:
     * Input: Nomor HP dengan berbagai awalan operator Indonesia
     * Proses: Panggil PhoneHelper::normalize()
     * Output: Semua dinormalisasi ke format 08xx
     */
    public function test_normalize_different_operators()
    {
        $testCases = [
            // Telkomsel
            '+62811234567' => '0811234567',
            '+62812345678' => '0812345678',
            '+62813456789' => '0813456789',

            // Indosat
            '+62814567890' => '0814567890',
            '+62815678901' => '0815678901',
            '+62816789012' => '0816789012',

            // XL
            '+62817890123' => '0817890123',
            '+62818901234' => '0818901234',
            '+62819012345' => '0819012345',

            // Tri
            '+62895123456' => '0895123456',
            '+62896234567' => '0896234567',
            '+62897345678' => '0897345678',

            // Smartfren
            '+62881234567' => '0881234567',
            '+62882345678' => '0882345678',
            '+62883456789' => '0883456789',
        ];

        foreach ($testCases as $input => $expected) {
            $result = PhoneHelper::normalize($input);
            $this->assertEquals($expected, $result, "Failed for input: {$input}");
        }
    }

    /**
     * Test: Handling nomor HP yang sangat panjang
     *
     * Alur:
     * Input: Nomor HP dengan panjang berlebihan
     * Proses: Panggil PhoneHelper::normalize()
     * Output: Nomor HP dinormalisasi tanpa error
     */
    public function test_normalize_very_long_numbers()
    {
        $testCases = [
            '+6281234567890123' => '081234567890123', // Very long
            '6281234567890123' => '081234567890123',
        ];

        foreach ($testCases as $input => $expected) {
            $result = PhoneHelper::normalize($input);
            $this->assertEquals($expected, $result, "Failed for input: {$input}");
        }
    }

    /**
     * Test: Handling input dengan karakter non-numeric
     *
     * Alur:
     * Input: String dengan huruf atau karakter aneh
     * Proses: Panggil PhoneHelper::normalize()
     * Output: Karakter non-numeric dihilangkan
     */
    public function test_normalize_non_numeric_characters()
    {
        $testCases = [
            '+62abc812def345ghi67890' => '081234567890',
            '62-812-ABC-345-67890' => '081234567890',
            'phone: +62 812 345 67890' => '081234567890',
        ];

        foreach ($testCases as $input => $expected) {
            $result = PhoneHelper::normalize($input);
            $this->assertEquals($expected, $result, "Failed for input: {$input}");
        }
    }

    /**
     * Test: Performance dengan banyak input
     *
     * Alur:
     * Input: Banyak nomor HP untuk test performance
     * Proses: Panggil PhoneHelper::normalize() berkali-kali
     * Output: Semua berhasil dinormalisasi dalam waktu wajar
     */
    public function test_normalize_performance()
    {
        $inputs = [];
        for ($i = 0; $i < 1000; $i++) {
            $inputs[] = '+62812345' . str_pad($i, 4, '0', STR_PAD_LEFT);
        }

        $startTime = microtime(true);

        foreach ($inputs as $input) {
            PhoneHelper::normalize($input);
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Should complete within 1 second for 1000 operations
        $this->assertLessThan(1.0, $executionTime, "Normalization took too long: {$executionTime} seconds");
    }
}
