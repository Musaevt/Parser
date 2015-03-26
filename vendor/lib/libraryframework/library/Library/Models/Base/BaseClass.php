<?php
namespace Library\Models\Base;
use Library\Database;
class BaseClass {
   
   public function setData($data){
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
            
 
            case 'set':
                property_exists ($this,$property_name)?$this->$property_name=$argument[0]:0;
                return $this;
        }
    }
  
    
}
