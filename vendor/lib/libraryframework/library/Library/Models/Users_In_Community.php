<?php
namespace Library\Models;
use  \Library\Models\Base\BaseClass;
class Users_In_Community extends BaseClass{
   protected $id;
   protected $id_community;
   protected $id_user;
   protected $date;


    public function __construct() {
        
    }
   
    static function get_count_ouruser_in_group($connection,$table,$gid){
        $query="SELECT Count(*) FROM ".$table['table_Users_In_Groups']." WHERE `gid`=:gid";
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