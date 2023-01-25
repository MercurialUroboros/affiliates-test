<?php

namespace App\Http\Controllers;

use App\Classes\GeoPoint;
use App\Classes\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GeoController extends Controller
{
    public function index(Request $request)
    {
        $dublinGeoPoint = new GeoPoint(53.3340285,-6.2535495);
        $maximumDistance = 100;

        $users = $this->fetchUsersByProximity($dublinGeoPoint, $maximumDistance);
        Log::debug($users);

        return view('index', compact('users'));
    }

    public function fetchUsersByProximity(GeoPoint $edgePoint, float $maximumDistance)
    {
        $usersFile = Storage::disk('local')->get('users.txt');

        $usersArray = [];

        foreach (explode("\n", $usersFile) as $key => $line) {
            $jsonUser = json_decode($line);
            $usersArray[$key] = new User($jsonUser->name, $jsonUser->affiliate_id, $jsonUser->latitude, $jsonUser->longitude);
            $usersArray[$key]->setCloseToProximity($this->getDistanceFromLatLonInKm($usersArray[$key], $edgePoint) <= $maximumDistance);
        }

        // Filtering by who is eligible for the proximity check
        $usersCloseToProximity = array_filter($usersArray, function ($item) {
            return $item->getIsCloseToProximity() === true;
        });

        usort($usersCloseToProximity, array($this, 'cmp'));
        return $usersCloseToProximity;
    }

    public function cmp (User $a, User $b)
    {
        return $a->getAffiliateId() - $b->getAffiliateId();
    }

    function getDistanceFromLatLonInKm(GeoPoint $point1, GeoPoint $point2) {
        $lat1 = $point1->getLatitude();
        $lat2 = $point2->getLatitude();
        $lon1 = $point1->getLongitude();
        $lon2 = $point2->getLongitude();

        $earthRadius = 6371;
        $dLat = $this->convertDegToRad($lat2 - $lat1);
        $dLon = $this->convertDegToRad($lon2 - $lon1);
        
        $squarehalfChordLength =
          sin($dLat / 2) * sin($dLat / 2) +
          cos($this->convertDegToRad($lat1)) * cos($this->convertDegToRad($lat2)) *
          sin($dLon / 2) * sin($dLon / 2);
      
        $angularDistance = 2 * atan2(sqrt($squarehalfChordLength), sqrt(1 - $squarehalfChordLength));
        $distance = $earthRadius * $angularDistance;
        return $distance;
    }

    public function convertDegToRad(float $deg)
    {
        return ($deg * pi()) / 180;
    }
}
