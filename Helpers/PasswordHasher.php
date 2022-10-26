<?php
namespace Helpers;

class PasswordHasher
{
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public static function validatePassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
}