<?php
namespace Err404\Controller;

use Library\AbstractController;
use Library\View;

class Controller extends AbstractController {
    
    public function indexAction(){
        $view = new View('Err404');
        $view->setContent('content');
        $view->generate();
    }
}
