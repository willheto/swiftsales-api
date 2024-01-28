<?php

namespace Tests;

use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Organization;

abstract class TestCase extends BaseTestCase
{
    protected static $dbInitialized = false;
    protected static $dbSeeded = false;
    protected static $clearDb = false;
    protected static $testAccount;
    protected static $testUser;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    protected function setUp(): void
    {
        parent::setUp();

        if (env('APP_ENV') !== 'testing') {
            exit('Wrong environment, should be testing. Current environment is ' . env('APP_ENV'));
        }


        $this->initDatabase();
        $this->seedDatabase();
    }

    protected static function getTestUser(): User
    {
        if (empty(self::$testUser)) {
            $testUserEmail = 'bob.swift@swiftsals.fi';
            self::$testUser = User::where('email', $testUserEmail)->first();
        }
        return self::$testUser;
    }

    private static function initDatabase(): void
    {
        Artisan::call('migrate:fresh');
    }

    private static function seedDatabase(): void
    {
        $testOrganization = Organization::factory()->create([
            'organizationName' => 'Bobs organization',
            'licenseType' => 'basic'
        ]);


        User::factory()->create([
            'organizationID' => $testOrganization->organizationID,
            'firstName' => 'Bob',
            'lastName' => 'Swift',
            'email' => 'bob.swift@swiftsales.fi',
            'password' => password_hash('test', PASSWORD_BCRYPT),
        ]);
    }
}
