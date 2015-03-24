<?php
namespace Library\Models;

use Library\Models\Base\BaseClassModel;
use Library\Database;

class Users_In_Communities extends BaseClassModel{
   protected $id;
   protected $gid_community;
   protected $uid_user;
   protected $date;


    public function __construct() {
          parent::__construct();
    }
   
    static function get_count_ouruser_in_group($gid){
        $connection=Database::$connect;
        $table=  Database::$options['tables'];
        $query="SELECT Count(*) FROM ".$table['Users_In_Communities']." WHERE `gid`=:gid";
        $execute=$connection->prepare($query);
        $execute->bindValue(":gid",$gid);
        $success=$execute->execute();
        
        if(!$success){
            $fail=$execute->errorInfo();
            echo $fail[2]."</br>";
            return -1;
        }
        $execute=$execute->fetchAll();
         return $execute[0]['Count(*)'];
        
    }
   
    
    
}




?>