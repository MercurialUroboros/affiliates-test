<?php

namespace App\Http\Controllers;

use App\Services\GeoPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GeoController extends Controller
{
    /**
     * Fetch affiliate files from disk, and checks whether they are within 100km from Dublin.
     */
    public function index(Request $request)
    {
        $affiliatesFile = Storage::disk('local')->get('affiliates-data/affiliates.txt');

        $dublinGeoPoint = new GeoPoint(53.3340285, -6.2535495);
        $maximumDistance = 100;

        $affiliates = fetchAffiliatesByProximity($affiliatesFile, $dublinGeoPoint, $maximumDistance);

        return view('index', compact('affiliates'));
    }
}
