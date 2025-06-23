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
                      ->when($search, function ($query, $search) {
                          return $query->where(function ($q) use ($search) {
                              $q->where('name', 'like', "%{$search}%")
                                ->orWhere('company_name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                          });
                      })
                      ->when($province, function ($query, $province) {
                          return $query->where('province', $province);
                      })
                      ->when($city, function ($query, $city) {
                          return $query->where('city', $city);
                      })
                      ->orderBy('created_at', 'desc')
                      ->paginate(12);

        // Get unique provinces and cities for filter
        $provinces = User::where('role', 'pengusaha_rental')
                        ->where('account_status', 'active')
                        ->whereNotNull('province')
                        ->distinct()
                        ->pluck('province')
                        ->sort();

        $cities = User::where('role', 'pengusaha_rental')
                     ->where('account_status', 'active')
                     ->whereNotNull('city')
                     ->when($province, function ($query, $province) {
                         return $query->where('province', $province);
                     })
                     ->distinct()
                     ->pluck('city')
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
