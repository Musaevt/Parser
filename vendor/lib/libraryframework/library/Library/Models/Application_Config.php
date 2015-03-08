<?php
namespace Library\Models;
class Application_Config {
    public Static  $configs;
public function __construct() {
}
public function init($conf){
    self::$configs=$conf;
    return new self();
}
public function __get($name) {
 if(isset($this->$name))
     return $this->$name;
 else 
     return 0; 
 
}
public function check_action($module,$action){
     foreach (self::$configs as $key=>$conf)
        if($key==$module)
            foreach($conf as $act)
                 if($act==$action) return true;
     return false;
}
    
}
