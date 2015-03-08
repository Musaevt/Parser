<?php
namespace Index\Controller;

use Library\AbstractController;
use Library\Database;
use Library\Models\API;
use Library\View;

class Controller extends AbstractController{
    
    public function IndexAction(){
   $connect= Database::init()->getConnection();
   $connect->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
   $connect->query("SET NAMES UTF8;");
   
    $tables=  array(
       "table_Users"          =>"vk_Users_2",
       "table_Groups"         =>"vk_Groups_2",
       "table_Users_In_Groups"=>"Users_In_Groups_2",
   );
       $view = new View('Index');
        $view->setContent('content');
        $view->generate(); 
        
//      $Request= new API("get_rating_groups",$_POST);
//      $answ=$Request->send_request($connect, $tables);
//        $str=json_encode($answ);
//        echo $str;
    }
}
