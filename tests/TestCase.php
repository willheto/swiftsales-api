<?php

namespace Tests;

use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use App\Managers\AuthManager\AuthManager;
use App\Models\Organization;

abstract class TestCase extends BaseTestCase
{
    protected static $dbInitialized = false;
    protected static $dbSeeded = false;
    protected static $clearDb = false;
    protected static $testUser;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        putenv('APP_ENV=testing');
        return require __DIR__ . '/../bootstrap/app.php';
    }

    protected function setUp(): void
    {
        parent::setUp();

        if (env('APP_ENV') !== 'testing') {
            exit('Wrong environment, should be testing. Current environment is ' . env('APP_ENV'));
        }

        if (!self::$dbInitialized) {
            self::$dbInitialized = true;
            $this->initDatabase();
        }

        if (!self::$dbSeeded) {
            self::$dbSeeded = true;
            $this->seedDatabase();
        }
    }

    protected static function getTestUser(): User
    {
        if (empty(self::$testUser)) {
            $testUserEmail = 'bob.swift@swiftsales.fi';
            self::$testUser = User::where('email', $testUserEmail)->first();
        }
        return self::$testUser;
    }

    protected function createAuthorizationHeaders(User $user): array
    {
        $authManager = new AuthManager();
        $jwt = $authManager->createUserJwt($user->userID);
        $headers = ['Authorization' => 'Bearer ' . $jwt];
        return $headers;
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
