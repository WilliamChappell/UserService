<?php
namespace Tests;

use Seeds\DatabaseSeeder;
use Services\UserService;
use PHPUnit\Framework\TestCase;
use Database\Connection;
use Helpers\PasswordHasher;
use Models\UserModel;

final class UserTest extends TestCase
{
    public function testCreateUser()
    {
        self::callSeed();
        UserService::create("James", "K", "J_K@email.com", "password1");
        $latestUser = UserService::getLatestUser();

        $this->assertTrue($latestUser instanceof UserModel);
        $this->assertEquals("James", $latestUser->getFirstName());
        $this->assertEquals("K", $latestUser->getLastName());
        $this->assertEquals("J_K@email.com", $latestUser->getEmail());
        $this->assertTrue(PasswordHasher::validatePassword("password1", $latestUser->getPassword()));
    }

    public function testListUsers()
    {
        self::callSeed();

        $list = UserService::list();
        self::testCreateUser();
        $this->assertEquals(count($list) +1, count(UserService::list()));

        foreach($list as $item)
        {
            $this->assertTrue($item instanceof UserModel);
        }
    }

    public function testFetchUser()
    {
        self::callSeed();
        UserService::create("James", "K", "J_K@email.com", "password1");
        $latestUser = UserService::getLatestUser();
        $user = UserService::get($latestUser->getId());

        $this->assertTrue($user instanceof UserModel);
        $this->assertEquals("James", $latestUser->getFirstName());
        $this->assertEquals("K", $latestUser->getLastName());
        $this->assertEquals("J_K@email.com", $latestUser->getEmail());
        $this->assertTrue(PasswordHasher::validatePassword("password1", $latestUser->getPassword()));
    }

    public function testValidateUser()
    {
        self::callSeed();
        $user = UserService::create("James", "K", "J_K@email.com", "password1");

        $this->assertTrue(UserService::validate("J_K@email.com", "password1"));
        $this->assertFalse(UserService::validate("J_K@email.com", "password2"));
    }

    public function testupdateUser()
    {
        self::callSeed();
        $user = UserService::create("James", "K", "J_K@email.com", "password1");

        UserService::updatePassword($user, "password2");
        $this->assertFalse(UserService::validate("J_K@email.com", "password1"));
        $this->assertTrue(UserService::validate("J_K@email.com", "password2"));
    }

    private function callSeed()
    {
        DatabaseSeeder::destroyTables();
        DatabaseSeeder::createTables();
        DatabaseSeeder::seedUsers();
    }
}
