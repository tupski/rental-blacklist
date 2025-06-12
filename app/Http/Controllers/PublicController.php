<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RentalBlacklist;

class PublicController extends Controller
{
    public function index()
    {
        return view('public.search');
    }

    public function search(Request $request)
    {
        $request->validate([
            'search' => 'required|string|min:3'
        ]);

        $search = $request->input('search');

        $results = RentalBlacklist::search($search)
            ->where('status_validitas', 'Valid')
            ->with('user')
            ->get()
            ->map(function ($item) use ($search) {
                // Jangan sensor value yang dicari
                $isSearchingNik = $item->nik === $search;
                $isSearchingName = stripos($item->nama_lengkap, $search) !== false;

                return [
                    'id' => $item->id,
                    'nama_lengkap' => $isSearchingName ? $item->nama_lengkap : $item->sensored_nama,
                    'nik' => $isSearchingNik ? $item->nik : $item->sensored_nik,
                    'no_hp' => $item->sensored_no_hp,
                    'jenis_rental' => $item->jenis_rental,
                    'jenis_laporan' => $item->jenis_laporan,
                    'tanggal_kejadian' => $item->tanggal_kejadian->format('d/m/Y'),
                    'jumlah_laporan' => RentalBlacklist::countReportsByNik($item->nik),
                    'pelapor' => $item->user->name
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $results,
            'total' => $results->count()
        ]);
    }

    public function detail($id)
    {
        $blacklist = RentalBlacklist::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Untuk melihat data lengkap, silakan login sebagai pengusaha rental (GRATIS) atau beli kredit untuk akses sekali lihat.',
            'data' => [
                'nama_lengkap' => $blacklist->sensored_nama,
                'nik' => $blacklist->sensored_nik,
                'no_hp' => $blacklist->sensored_no_hp,
                'jenis_rental' => $blacklist->jenis_rental,
                'jenis_laporan' => $blacklist->jenis_laporan,
                'tanggal_kejadian' => $blacklist->tanggal_kejadian->format('d/m/Y'),
                'jumlah_laporan' => RentalBlacklist::countReportsByNik($blacklist->nik),
                'pelapor' => $blacklist->user->name
            ]
        ]);
    }
}
