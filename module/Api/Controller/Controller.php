<?php
namespace Api\Controller;

use Library\AbstractController;
use Library\Database;
use Api\Models\Api;

class Controller extends AbstractController{
    
    public function IndexAction(){
   //     var_dump($_SERVER);
    //    C:/ATI/OpenServer/domains/localhost/vkparser
   $connect= Database::init()->getConnection();
   $connect->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
   $connect->query("SET NAMES UTF8;");   
   $tables= \Library\Application::$config['tables'];
   
   $api=new API($_,array('uid'=>'108397577'));
   echo $api->send_request();

    }
    public function MethodAction(){
    $api=new Api(\Library\Application::$request_variables['post']['method_name'],\Library\Application::$request_variables['post']['data'],"json");
    echo $api->send_request()->getResponse();
    }
}
