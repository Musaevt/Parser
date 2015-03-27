<?php
namespace Results\Controller;

use Library\AbstractController;
use Library\View;
use Library\Application;
class Controller extends AbstractController{
    
    public function IndexAction() {
        $view=new View('Results');
        $view->setContent('content');
        $view->generate(); 
    }
    public function ShowAction(){
        $data["search_id"]=(Application::$request_variables['get']['search_id'])?Application::$request_variables['get']['search_id']:0;
        $view=new View('Results');
        $view->setContent('show');
        $view->setData($data);
        $view->generate(); 
    }
}