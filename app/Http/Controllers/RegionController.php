<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RegionController extends Controller
{
    private $baseUrl = 'https://tupski.github.io/api-wilayah-indonesia/api';

    public function provinces()
    {
        try {
            $provinces = Cache::remember('provinces', 3600, function () {
                $response = Http::timeout(10)->get($this->baseUrl . '/provinces.json');
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                // Fallback data jika API tidak tersedia
                return $this->getFallbackProvinces();
            });

            return response()->json($provinces);
        } catch (\Exception $e) {
            return response()->json($this->getFallbackProvinces());
        }
    }

    public function regencies($provinceId)
    {
        try {
            $regencies = Cache::remember("regencies_{$provinceId}", 3600, function () use ($provinceId) {
                $response = Http::timeout(10)->get($this->baseUrl . "/regencies/{$provinceId}.json");
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                return [];
            });

            return response()->json($regencies);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    public function districts($regencyId)
    {
        try {
            $districts = Cache::remember("districts_{$regencyId}", 3600, function () use ($regencyId) {
                $response = Http::timeout(10)->get($this->baseUrl . "/districts/{$regencyId}.json");
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                return [];
            });

            return response()->json($districts);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    public function villages($districtId)
    {
        try {
            $villages = Cache::remember("villages_{$districtId}", 3600, function () use ($districtId) {
                $response = Http::timeout(10)->get($this->baseUrl . "/villages/{$districtId}.json");
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                return [];
            });

            return response()->json($villages);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    private function getFallbackProvinces()
    {
        return [
            ['id' => '11', 'name' => 'ACEH'],
            ['id' => '12', 'name' => 'SUMATERA UTARA'],
            ['id' => '13', 'name' => 'SUMATERA BARAT'],
            ['id' => '14', 'name' => 'RIAU'],
            ['id' => '15', 'name' => 'JAMBI'],
            ['id' => '16', 'name' => 'SUMATERA SELATAN'],
            ['id' => '17', 'name' => 'BENGKULU'],
            ['id' => '18', 'name' => 'LAMPUNG'],
            ['id' => '19', 'name' => 'KEPULAUAN BANGKA BELITUNG'],
            ['id' => '21', 'name' => 'KEPULAUAN RIAU'],
            ['id' => '31', 'name' => 'DKI JAKARTA'],
            ['id' => '32', 'name' => 'JAWA BARAT'],
            ['id' => '33', 'name' => 'JAWA TENGAH'],
            ['id' => '34', 'name' => 'DI YOGYAKARTA'],
            ['id' => '35', 'name' => 'JAWA TIMUR'],
            ['id' => '36', 'name' => 'BANTEN'],
            ['id' => '51', 'name' => 'BALI'],
            ['id' => '52', 'name' => 'NUSA TENGGARA BARAT'],
            ['id' => '53', 'name' => 'NUSA TENGGARA TIMUR'],
            ['id' => '61', 'name' => 'KALIMANTAN BARAT'],
            ['id' => '62', 'name' => 'KALIMANTAN TENGAH'],
            ['id' => '63', 'name' => 'KALIMANTAN SELATAN'],
            ['id' => '64', 'name' => 'KALIMANTAN TIMUR'],
            ['id' => '65', 'name' => 'KALIMANTAN UTARA'],
            ['id' => '71', 'name' => 'SULAWESI UTARA'],
            ['id' => '72', 'name' => 'SULAWESI TENGAH'],
            ['id' => '73', 'name' => 'SULAWESI SELATAN'],
            ['id' => '74', 'name' => 'SULAWESI TENGGARA'],
            ['id' => '75', 'name' => 'GORONTALO'],
            ['id' => '76', 'name' => 'SULAWESI BARAT'],
            ['id' => '81', 'name' => 'MALUKU'],
            ['id' => '82', 'name' => 'MALUKU UTARA'],
            ['id' => '91', 'name' => 'PAPUA BARAT'],
            ['id' => '94', 'name' => 'PAPUA'],
        ];
    }
}
