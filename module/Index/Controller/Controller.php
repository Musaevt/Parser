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
   
   
       $view = new View('Index');
        $view->setContent('content');
        $view->generate(); 
        
//      $Request= new API("get_rating_groups",$_POST);
//      $answ=$Request->send_request($connect, $tables);
//        $str=json_encode($answ);
//        echo $str;
    }
    public function SearchAction(){
         $view = new View('Index');
        $view->setContent('search');
        $view->generate(); 
        
        
        var_dump($_POST);
    }
}
