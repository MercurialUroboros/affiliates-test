<?php

namespace Tests\Feature;

use App\Classes\GeoPoint;
use App\Classes\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

$dublinGeoPoint = new GeoPoint(53.3340285, -6.2535495);
$maximumDistance = 100;

class GeoControllerTest extends TestCase
{
    /**
     * Test users not in range of distance.
     *
     * @return void
     */
    public function test_user_not_in_range()
    {
        global $dublinGeoPoint;
        global $maximumDistance;
        $usersNotInRange = fetchUsersByProximity($this->createRandomUsersFileNotInRange(), $dublinGeoPoint, $maximumDistance);
        $this->assertTrue(count($usersNotInRange) == 0);
    }

    /**
     * Test users in range of distance.
     *
     * @return void
     */
    public function test_user_in_range()
    {
        global $dublinGeoPoint;
        global $maximumDistance;
        $usersInRange = fetchUsersByProximity($this->createRandomUsersFileInRange(), $dublinGeoPoint, $maximumDistance);

        $this->assertTrue(count($usersInRange) == 10);
    }

    /**
     * Test users from file
     *
     * @return void
     */
    public function test_file_affiliates()
    {
        global $dublinGeoPoint;
        global $maximumDistance;
        $usersFile = Storage::disk('local')->get('affiliates-data/affiliates.txt');

        $usersInRange = fetchUsersByProximity($usersFile, $dublinGeoPoint, $maximumDistance);

        $this->assertTrue(count($usersInRange) == 16);
    }

    /**
     * Creating users with an arbitrary big latitude/longitude so not to fall within 100km
     */
    public function createRandomUsersFileNotInRange()
    {
        $usersString = '';

        for ($x = 0; $x <= 10; $x++) {
            $randomUser = new User(Str::random($strlentgh = 16), (string) rand(2, 50), (mt_rand() * 100) / mt_getrandmax(), (mt_rand() * 100) / mt_getrandmax());
            $usersString .= $randomUser->__toString() . "\r\n";
        }

        return $usersString;
    }

    /**
     * Generating 10 users always in range
     */
    public function createRandomUsersFileInRange()
    {
        $usersString = '';

        for ($x = 0; $x < 10; $x++) {
            $latitude = mt_rand(53.0 * 10, 53.9 * 10) / 10;
            $longitude = mt_rand(-6.9 * 10, -6.0 * 10) / 10;
            $randomUser = new User(Str::random($strlentgh = 16), (string) rand(2, 50), $latitude, $longitude);
            $usersString .= $randomUser->__toString() . "\r\n";
        }

        return $usersString;
    }
}
