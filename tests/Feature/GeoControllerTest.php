<?php

namespace Tests\Feature;

use App\Services\GeoPoint;
use App\Services\Affiliate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class GeoControllerTest extends TestCase
{
    protected $maximumDistance = 100;
    protected $dublinGeoPoint;
    /**
     * Test users not in range of distance.
     *
     * @return void
     */
    public function test_user_not_in_range()
    {
        $usersNotInRange = fetchUsersByProximity($this->createRandomUsersFileNotInRange(), $this->dublinGeoPoint, $this->maximumDistance);
        $this->assertTrue(count($usersNotInRange) == 0);
    }

    /**
     * Test users in range of distance.
     *
     * @return void
     */
    public function test_user_in_range()
    {
        $usersInRange = fetchUsersByProximity($this->createRandomUsersFileInRange(), $this->dublinGeoPoint, $this->maximumDistance);

        $this->assertTrue(count($usersInRange) == 10);
    }

    /**
     * Test users from file
     *
     * @return void
     */
    public function test_file_affiliates()
    {
        $usersFile = Storage::disk('local')->get('affiliates-data/affiliates.txt');

        $usersInRange = fetchUsersByProximity($usersFile, $this->dublinGeoPoint, $this->maximumDistance);

        $this->assertTrue(count($usersInRange) == 16);
    }

    /**
     * Creating users with an arbitrary big latitude/longitude so not to fall within 100km
     */
    public function createRandomUsersFileNotInRange()
    {
        $usersString = '';

        for ($x = 0; $x <= 10; $x++) {
            $randomUser = new Affiliate(Str::random($strlentgh = 16), (string) rand(2, 50), (mt_rand() * 100) / mt_getrandmax(), (mt_rand() * 100) / mt_getrandmax());
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
            $randomUser = new Affiliate(Str::random($strlentgh = 16), (string) rand(2, 50), $latitude, $longitude);
            $usersString .= $randomUser->__toString() . "\r\n";
        }

        return $usersString;
    }

    protected function setUp() : void {
        parent::setUp();
        $this->dublinGeoPoint = new GeoPoint(53.3340285, -6.2535495);
    }
}
