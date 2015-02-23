<?php

class Database  
{
    static public $dsn ='mysql:dbname=vkparser;host=localhost';
    static public $user = 'root';
    static public $password ='';
   
    public static function getConnection()  {
        try {
            $newconnect = new PDO(self::$dsn, self::$user, self::$password);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
        return $newconnect;
    }
} 

?>