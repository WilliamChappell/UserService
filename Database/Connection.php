<?php
namespace Database;

use PDO;

class Connection
{
    private static $_db;
    private $_pdo;

    public function __construct($host = "localhost", $user = "root", $pass = "", $dbName = "users")
    {
        try{
            $this->_pdo = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);
        }catch(PDOException $e){
            die($e->getMessage());
        }
    }

    public static function getInstance()
    {
        if(!isset(self::$_db)){
            self::$_db = new Connection();
        }

        return self::$_db;
    }

    public function query($sql, $pars = [])
    {
        $q = $this->_pdo->prepare($sql);

        $i = 1;
        foreach ($pars as $p)
        {
            $q->bindvalue($i, $p);
            $i++;
        }

        try{
            $q->execute();
        }catch(PDOException $e){
            die($e->getMessage());
        }

        return $q->fetchAll(PDO::FETCH_OBJ);
    }

    public function getLastInsertedId()
    {
        return $this->_pdo->lastInsertId();
    }
}