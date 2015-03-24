<?php
namespace Library\Models\Base;
use Library\Database;
class BaseClassModel extends BaseClass{
    public static $connection;
    public static $table_names;
         
    public function __construct() {
        self::$connection=Database::$connect;
        self::$table_names=Database::$options['tables'];
    }
  public function save($parametrs=NULL){
    $connection=self::$connection;
    $table_names= self::$table_names;
    $class_name= explode("\\", get_class($this));
    $class_name=$class_name[count($class_name)-1];
  
    $query='INSERT INTO '.$table_names[$class_name].' (';
    $questions=' VALUES (';
       
     if($parametrs==NULL){  
            foreach($this as $key=>$value){
                   $query.=$key.',';
                   $questions.=':'.$key.',';
               }
           $query=substr($query,0,-1).')';
           $questions=substr($questions,0,-1).');';
           $query.=$questions;
           $execute= $connection->prepare($query);
               foreach($this as $key=>$value){
                   $execute->bindValue(':'.$key, $value);
               }
     }else{
          foreach($parametrs as $parametr){
            $query.=$parametr.',';
            $questions.=':'.$parametr.',';
           }
            $query=substr($query,0,-1).')';
            $questions=substr($questions,0,-1).');';
            $query.=$questions;
            $execute= $connection->prepare($query);
          foreach ($parametrs as $parametr){
                if(property_exists($this,$parametr))
                $execute->bindValue(':'.$parametr, $this->$parametr);
            }
            
            
        }
        $execute->execute();
        
       
      return $this;       
}
  public function update(){
        $connection= self::$connection;
        $table_names= self::$table_names;
       
        $class_name= explode("\\", get_class($this));
      $class_name=$class_name[count($class_name)-1];
  
    $query='UPDATE '.$table_names[$class_name].' SET ';
    
    foreach($this as $key=>$value){
                   $query.=$key.'=:'.$key.',';
                }
           $query=substr($query,0,-1);
           $query.=' WHERE `id`='.$this->id;
           $execute= $connection->prepare($query);
          
           foreach($this as $key=>$value){
                   $execute->bindValue(':'.$key, $value);
               }
        $execute->execute();
        $answer= $execute->fetchAll();
        return $this;
    }
    
}
