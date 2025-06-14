<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RentalBlacklist;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helpers\PhoneHelper;

class BlacklistApiController extends Controller
{
    /**
     * Search blacklist data
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:3',
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        $search = $request->input('cari');
        $limit = $request->input('limit', 10);

        $results = RentalBlacklist::search($search)
            ->where('status_validitas', 'Valid')
            ->with('user:id,name')
            ->limit($limit)
            ->get()
            ->map(function ($item) use ($search) {
                // Normalisasi search untuk nomor HP
                $normalizedSearch = PhoneHelper::normalize($search);

                // Cek apakah search cocok dengan data
                $isSearchingNik = $item->nik === $search;
                $isSearchingName = stripos($item->nama_lengkap, $search) !== false;
                $isSearchingPhone = $item->no_hp === $search || $item->no_hp === $normalizedSearch;

                // Logika sensor: tampilkan query yang dicari, sensor yang lain
                $displayNama = $item->sensored_nama;
                $displayNik = $item->sensored_nik;
                $displayPhone = $item->sensored_no_hp;

                // Jika search cocok dengan nama, tampilkan bagian yang cocok
                if ($isSearchingName) {
                    $displayNama = $this->highlightSearchInName($item->nama_lengkap, $search);
                }

                // Jika search cocok dengan NIK, tampilkan bagian yang cocok
                if ($isSearchingNik) {
                    $displayNik = $this->highlightSearchInNik($item->nik, $search);
                }

                // Jika search cocok dengan nomor HP, tampilkan bagian yang cocok
                if ($isSearchingPhone) {
                    $displayPhone = $this->highlightSearchInPhone($item->no_hp, $search, $normalizedSearch);
                }

                return [
                    'id' => $item->id,
                    'nama_lengkap' => $displayNama,
                    'nik' => $displayNik,
                    'no_hp' => $displayPhone,
                    'jenis_rental' => $item->jenis_rental,
                    'jenis_laporan' => $item->jenis_laporan,
                    'tanggal_kejadian' => $item->tanggal_kejadian->format('Y-m-d'),
                    'jumlah_laporan' => RentalBlacklist::countReportsByNik($item->nik),
                    'pelapor' => $item->user->name ?? 'Unknown',
                    'created_at' => $item->created_at->format('Y-m-d H:i:s')
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $results,
            'total' => $results->count(),
            'query' => $search
        ]);
    }

    /**
     * Get blacklist detail
     */
    public function show($id): JsonResponse
    {
        $blacklist = RentalBlacklist::with('user:id,name')->find($id);

        if (!$blacklist) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'For full access, please register as rental business (FREE) or purchase credits.',
            'data' => [
                'id' => $blacklist->id,
                'nama_lengkap' => $blacklist->sensored_nama,
                'nik' => $blacklist->sensored_nik,
                'no_hp' => $blacklist->sensored_no_hp,
                'jenis_rental' => $blacklist->jenis_rental,
                'jenis_laporan' => $blacklist->jenis_laporan,
                'tanggal_kejadian' => $blacklist->tanggal_kejadian->format('Y-m-d'),
                'jumlah_laporan' => RentalBlacklist::countReportsByNik($blacklist->nik),
                'pelapor' => $blacklist->user->name ?? 'Unknown',
                'created_at' => $blacklist->created_at->format('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * Get statistics
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total_laporan' => RentalBlacklist::where('status_validitas', 'Valid')->count(),
            'total_pelanggan_bermasalah' => RentalBlacklist::where('status_validitas', 'Valid')->distinct('nik')->count(),
            'rental_terdaftar' => RentalBlacklist::distinct('user_id')->count(),
            'laporan_bulan_ini' => RentalBlacklist::where('status_validitas', 'Valid')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get all blacklist data (authenticated users only)
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100',
            'search' => 'nullable|string|min:3'
        ]);

        $query = RentalBlacklist::with('user:id,name')
            ->where('status_validitas', 'Valid');

        if ($request->has('search')) {
            $query->search($request->input('cari'));
        }

        $limit = $request->input('limit', 15);
        $results = $query->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $results->items(),
            'pagination' => [
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage(),
                'per_page' => $results->perPage(),
                'total' => $results->total()
            ]
        ]);
    }

    /**
     * Store new blacklist data (authenticated users only)
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nik' => 'required|string|size:16',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'jenis_rental' => 'required|string|max:100',
            'jenis_laporan' => 'required|array|min:1',
            'kronologi' => 'required|string',
            'tanggal_kejadian' => 'required|date|before_or_equal:today'
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['status_validitas'] = 'Valid';

        $blacklist = RentalBlacklist::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Blacklist data created successfully',
            'data' => $blacklist
        ], 201);
    }

    /**
     * Update blacklist data (authenticated users only)
     */
    public function update(Request $request, $id): JsonResponse
    {
        $blacklist = RentalBlacklist::find($id);

        if (!$blacklist) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 404);
        }

        // Check if user owns this data
        if ($blacklist->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'nik' => 'sometimes|string|size:16',
            'nama_lengkap' => 'sometimes|string|max:255',
            'jenis_kelamin' => 'sometimes|in:Laki-laki,Perempuan',
            'no_hp' => 'sometimes|string|max:20',
            'alamat' => 'sometimes|string',
            'jenis_rental' => 'sometimes|string|max:100',
            'jenis_laporan' => 'sometimes|array|min:1',
            'kronologi' => 'sometimes|string',
            'tanggal_kejadian' => 'sometimes|date|before_or_equal:today'
        ]);

        $blacklist->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Blacklist data updated successfully',
            'data' => $blacklist
        ]);
    }

    /**
     * Delete blacklist data (authenticated users only)
     */
    public function destroy($id): JsonResponse
    {
        $blacklist = RentalBlacklist::find($id);

        if (!$blacklist) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 404);
        }

        // Check if user owns this data
        if ($blacklist->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $blacklist->delete();

        return response()->json([
            'success' => true,
            'message' => 'Blacklist data deleted successfully'
        ]);
    }

    /**
     * Highlight search term in name while keeping other parts censored
     */
    private function highlightSearchInName($fullName, $search)
    {
        $words = explode(' ', $fullName);
        $result = [];

        foreach ($words as $word) {
            if (stripos($word, $search) !== false) {
                // Jika kata mengandung search term, tampilkan utuh
                $result[] = $word;
            } else {
                // Sensor kata yang tidak mengandung search term
                if (strlen($word) <= 2) {
                    $result[] = $word;
                } else {
                    $first = substr($word, 0, 1);
                    $last = substr($word, -1);
                    $middle = str_repeat('*', strlen($word) - 2);
                    $result[] = $first . $middle . $last;
                }
            }
        }

        return implode(' ', $result);
    }

    /**
     * Highlight search term in NIK while keeping other parts censored
     */
    private function highlightSearchInNik($fullNik, $search)
    {
        $searchPos = strpos($fullNik, $search);
        if ($searchPos !== false) {
            $before = substr($fullNik, 0, $searchPos);
            $after = substr($fullNik, $searchPos + strlen($search));

            // Sensor bagian sebelum dan sesudah search
            $sensoredBefore = str_repeat('*', strlen($before));
            $sensoredAfter = str_repeat('*', strlen($after));

            return $sensoredBefore . $search . $sensoredAfter;
        }

        // Fallback ke sensor biasa
        if (strlen($fullNik) >= 8) {
            $start = substr($fullNik, 0, 4);
            $end = substr($fullNik, -4);
            $middle = str_repeat('*', strlen($fullNik) - 8);
            return $start . $middle . $end;
        }
        return $fullNik;
    }

    /**
     * Highlight search term in phone while keeping other parts censored
     */
    private function highlightSearchInPhone($fullPhone, $originalSearch, $normalizedSearch)
    {
        // Cari posisi search term
        $searchTerm = $originalSearch;
        $searchPos = strpos($fullPhone, $searchTerm);

        // Jika tidak ditemukan dengan original search, coba dengan normalized
        if ($searchPos === false && $normalizedSearch !== $originalSearch) {
            $searchTerm = $normalizedSearch;
            $searchPos = strpos($fullPhone, $searchTerm);
        }

        if ($searchPos !== false) {
            $before = substr($fullPhone, 0, $searchPos);
            $after = substr($fullPhone, $searchPos + strlen($searchTerm));

            // Sensor bagian sebelum dan sesudah search
            $sensoredBefore = str_repeat('*', strlen($before));
            $sensoredAfter = str_repeat('*', strlen($after));

            return $sensoredBefore . $searchTerm . $sensoredAfter;
        }

        // Fallback ke sensor biasa
        if (strlen($fullPhone) >= 6) {
            $start = substr($fullPhone, 0, 4);
            $end = substr($fullPhone, -2);
            $middle = str_repeat('*', strlen($fullPhone) - 6);
            return $start . $middle . $end;
        }
        return $fullPhone;
    }
}
