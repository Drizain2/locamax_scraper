<?php

namespace App\Http\Controllers;

use App\Models\RentalSource;
use Illuminate\Http\Request;

class RentalSourceController extends Controller
{
    public function index(Request $request)
    {
        $city = $request->input("city");
        $query = RentalSource::query();

        if ($city) {
            $query->where('city', $city);
        }
        // dd( $query->get());
        $cities = RentalSource::select("city")->distinct()->pluck('city');
        return view("annonceListe", [
            "rentals" => $query->get(),
            'cities' => $cities,
            'selectedCity' => $city
        ]);

    }
}
