<?php

namespace App\Http\Controllers;

use App\Services\GeoPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GeoController extends Controller
{
    public function index(Request $request)
    {
        $usersFile = Storage::disk('local')->get('affiliates-data/affiliates.txt');

        $dublinGeoPoint = new GeoPoint(53.3340285, -6.2535495);
        $maximumDistance = 100;

        $users = fetchUsersByProximity($usersFile, $dublinGeoPoint, $maximumDistance);

        return view('index', compact('users'));
    }
}
