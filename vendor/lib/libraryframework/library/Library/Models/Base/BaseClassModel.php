<?php
namespace Library\Models\Base;
use Library\Database;
class BaseClassModel extends BaseClass{
    
   
  public function save($connection="",$table_names="",$parametrs=NULL){
    $connection=Database::$connect;
    $table_names=Database::$options['tables'];
    $class_name= explode("\\", get_class($this));
    $class_name=$class_name[count($class_name)-1];
  
    $query='INSERT INTO '.$table_names[$class_name].' (';
    $questions=' VALUES (';
        foreach($this as $key=>$value){
            $query.=$key.',';
            $questions.=':'.$key.',';
        }
    $query=substr($query,0,-1).')';
    $questions=substr($questions,0,-1).');';
    $query.=$questions;
    $execute= $connection->prepare($query);
     if($parametrs==NULL){  
        foreach($this as $key=>$value){
            $execute->bindValue(':'.$key, $value);
        }
     }else{
          foreach ($parametrs as $parametr)
            {
                if(property_exists($this,$parametr))
                $execute->bindValue(':'.$parametr, $this->$parametr);
            }
            
            
        }
        $execute->execute();
      
     return $this;       
}
    
}
