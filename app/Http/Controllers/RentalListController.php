<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RentalListController extends Controller
{
    /**
     * Display list of registered rental companies
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $province = $request->get('province');
        $city = $request->get('city');

        $rentals = User::where('role', 'pengusaha_rental')
                      ->where('account_status', 'active')
                      ->with('rentalRegistration')
                      ->when($search, function ($query, $search) {
                          return $query->where(function ($q) use ($search) {
                              $q->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhereHas('rentalRegistration', function($subQuery) use ($search) {
                                    $subQuery->where('nama_rental', 'like', "%{$search}%");
                                });
                          });
                      })
                      ->when($province, function ($query, $province) {
                          return $query->whereHas('rentalRegistration', function($subQuery) use ($province) {
                              $subQuery->where('provinsi', $province);
                          });
                      })
                      ->when($city, function ($query, $city) {
                          return $query->whereHas('rentalRegistration', function($subQuery) use ($city) {
                              $subQuery->where('kota', $city);
                          });
                      })
                      ->orderBy('created_at', 'desc')
                      ->paginate(12);

        // Get unique provinces and cities for filter from rental_registrations
        $provinces = \App\Models\RentalRegistration::whereHas('user', function($query) {
                        $query->where('role', 'pengusaha_rental')
                              ->where('account_status', 'active');
                    })
                    ->whereNotNull('provinsi')
                    ->distinct()
                    ->pluck('provinsi')
                    ->sort();

        $cities = \App\Models\RentalRegistration::whereHas('user', function($query) {
                     $query->where('role', 'pengusaha_rental')
                           ->where('account_status', 'active');
                 })
                 ->whereNotNull('kota')
                 ->when($province, function ($query, $province) {
                     return $query->where('provinsi', $province);
                 })
                 ->distinct()
                 ->pluck('kota')
                 ->sort();

        return view('rental-list.index', compact('rentals', 'provinces', 'cities', 'search', 'province', 'city'));
    }

    /**
     * Show rental profile
     */
    public function show(User $rental)
    {
        if ($rental->role !== 'pengusaha_rental' || !$rental->isActive()) {
            abort(404);
        }

        // Get rental's reports count
        $reportsCount = $rental->rentalBlacklists()->count();

        return view('rental-list.show', compact('rental', 'reportsCount'));
    }
}
