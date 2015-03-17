<?php
namespace Library;

use Library\Application;

class Database {
    static public $connect;
    static public $options;
    static public $dsn ;
    static public $user ;
    static public $password ;
    
    public function init() {
        self::$options=Application::$config['db_options'];
        self::$dsn='mysql:dbname='.self::$options['database'].';host='.self::$options['host'];
        self::$user=self::$options['user'];
        self::$password=self::$options['password'];
        Database::get_ready_connetction();
        return new self();
    }
    private function get_ready_connetction(){
        $connection= Database::getConnection();
        $connection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        $connection->query("SET NAMES UTF8;");
        self::$connect=$connection;
    }
    private function getConnection()  {
       
        try {
            $newconnect = new \PDO(self::$dsn, self::$user, self::$password);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
        return $newconnect;
    }
   
    public function __construct() {}
   
}
