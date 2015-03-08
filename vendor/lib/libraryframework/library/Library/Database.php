<?php
namespace Library;

use Library\Application;

class Database {
    static public $options;
    static public $dsn ;
    static public $user ;
    static public $password ;
    
    public function init() {
        self::$options=Application::$config['db_options'];
        self::$dsn='mysql:dbname='.self::$options['database'].';host='.self::$options['host'];
        self::$user=self::$options['user'];
        self::$password=self::$options['password'];
        return new self();
    }
    public function __construct() {}
     public  function getConnection()  {
       
        try {
            $newconnect = new \PDO(self::$dsn, self::$user, self::$password);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
        return $newconnect;
    }
}
