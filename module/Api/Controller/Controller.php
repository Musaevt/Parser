<?php
namespace Api\Controller;

use Library\AbstractController;
use Library\Database;
use Api\Models;

class Controller extends AbstractController{
    
    public function IndexAction(){
   $connect= Database::init()->getConnection();
   $connect->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
   $connect->query("SET NAMES UTF8;");   
   $tables= \Library\Application::$config['tables'];
   
   $api=new API($_POST['request_name'],$_POST);
   echo $api->send_request($connect, $tables);
   
      
        
//      $Request= new API("get_rating_groups",$_POST);
//      $answ=$Request->send_request($connect, $tables);
//        $str=json_encode($answ);
//        echo $str;
    }
}
