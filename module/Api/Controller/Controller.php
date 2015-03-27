<?php
namespace Api\Controller;

use Library\AbstractController;
use Library\Database;
use Library\Application;
use Api\Models\Api;

class Controller extends AbstractController{
    
    public function IndexAction(){
 
   $data= Application::$request_variables['get'];
   $api=new API($data['method_name'],$data);
   echo $api->send_request()->getResponse();

    }
    public function MethodAction(){
    $api=new Api(\Library\Application::$request_variables['post']['method_name'],\Library\Application::$request_variables['post']['data'],"json");
    echo $api->send_request()->getResponse();
    }
}
