<?php
namespace Seeds;

use Database\Connection;
use Models\UserModel;
use Helpers\PasswordHasher;

class DatabaseSeeder
{
    public static function destroyTables()
    {
        Connection::getInstance()->query("drop table if exists users;");
    }

    public static function createTables()
    {
        Connection::getInstance()->query("CREATE TABLE IF NOT EXISTS users(
            id int AUTO_INCREMENT,
            FirstName varchar(255),
            LastName varchar(255),
            Email varchar(255),
            Password varchar(255),
            PRIMARY KEY(id)
        );");
    }

    public static function seedUsers()
    {
        $params = [];
        $params[] = ["William", "Chappell", "william_chappell@hotmail.co.uk", PasswordHasher::hashPassword("Password")];
        $params[] = ["W", "Chappell", "w_chappell@hotmail.co.uk", PasswordHasher::hashPassword("Password1")];
        $params[] = ["W", "C", "W_C@hotmail.co.uk", PasswordHasher::hashPassword("Password2")];

        foreach($params as $set){
            Connection::getInstance()->query("INSERT INTO users (FirstName, LastName, Email, Password) VALUES (?, ?, ?, ?);", $set);
        }
    }
}