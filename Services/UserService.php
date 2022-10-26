<?php
namespace Services;

use Helpers\PasswordHasher;
use Database\Connection;
use Models\UserModel;

class UserService
{
    public static function create($firstName, $lastName, $email, $password)
    {
        $user = new UserModel($firstName, $lastName, $email, PasswordHasher::hashPassword($password));

        $params = [
            $user->getFirstName(), 
            $user->getLastName(),
            $user->getEmail(),
            $user->getPassword()
        ];

        Connection::getInstance()->query("INSERT INTO users (FirstName, LastName, Email, Password) VALUES (?, ?, ?, ?);", $params);
        $user->setId(Connection::getInstance()->getLastInsertedId());

        return $user;
    }

    public static function get($id)
    {
        $result = Connection::getInstance()->query("SELECT * FROM users where id = ? limit 1;", [$id]);
        if(empty($result)){
            return null;
        }

        return new UserModel($result[0]->FirstName, $result[0]->LastName, $result[0]->Email, $result[0]->Password, $result[0]->id);
    }

    public static function getLatestUser()
    {
        $result = Connection::getInstance()->query("SELECT * FROM users ORDER BY id desc limit 1;");
        if(empty($result)){
            return null;
        }

        return new UserModel($result[0]->FirstName, $result[0]->LastName, $result[0]->Email, $result[0]->Password, $result[0]->id);
    }

    public static function list()
    {
        $results = Connection::getInstance()->query("SELECT id, FirstName, LastName, Email, Password FROM users;");
        $list = [];
        foreach($results as $r)
        {
            $list[] = new UserModel($r->FirstName, $r->LastName, $r->Email, $r->Password, $r->id);
        }

        return $list;
    }

    public static function validate($email, $password)
    {
        $result = Connection::getInstance()->query("SELECT * FROM users where Email = ? limit 1;", [$email]);
        if(empty($result)){
            return false;
        }
        $user = new UserModel($result[0]->FirstName, $result[0]->LastName, $result[0]->Email, $result[0]->Password, $result[0]->id);

        return PasswordHasher::validatePassword($password, $user->getPassword());
    }

    public static function updatePassword(UserModel $user, $password)
    {
        $user->setPassword(PasswordHasher::hashPassword($password));

        $params = [
            $user->getPassword(),
            $user->getId()
        ];

        Connection::getInstance()->query("UPDATE users set Password = ? where id = ? limit 1;", $params);

        return $user;
    }
}