<?php
namespace Library\Models;
class VK_Users_In_Groups{
    public $gid;
    public $uid;
    
    public function __construct() {
        
    }
    public function setData($data){
 //    $this->setId($data["id"]);
  foreach ($data as $key=>$argum){
       $method='set'.ucfirst($key);
      $this->$method($argum);
    }
    return $this;
}

public function __call($method_name, $argument)
   {
        $args = preg_split('/(?<=\w)(?=[A-Z])/', $method_name);
        $action = array_shift($args);
        $property_name = strtolower(implode('_', $args));
        //имя свойства
       
        switch ($action)
        {
            case 'get':
                return isset($this->$property_name) ? $this->$property_name : null;
 
            case 'set':{
              property_exists ($this,$property_name)?$this->$property_name=$argument[0]:0;
                 return $this;
              }
               
        }
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