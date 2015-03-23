<?php
namespace Index\Controller;

use Library\Application;
use Library\AbstractController;
use Library\Database;
use Library\Models\Search;
use Library\Models\Community;
use Library\View;

use Api\Models\Api;

class Controller extends AbstractController{
    
    public function IndexAction(){
         $view = new View('Index');
        $view->setContent('content');
        $view->generate(); 
       
//      $Request= new API("get_rating_groups",$_POST);
//      $answ=$Request->send_request($connect, $tables);
//        $str=json_encode($answ);
//        echo $str;
    }
    public function SearchAction(){
        //post param group_name
       
        Application::$request_variables['post']['group_name']=(isset(Application::$request_variables['post']['community_id']))?Application::$request_variables['post']['community_id']:null;
        if( Application::$request_variables['post']['group_name']){
            $community_name=  Application::$request_variables['post']['group_name'];
            $api=new Api("get_community_by_id_vk", array("group_name"=>$community_name));
            $community=new Community();
            $community->setData($api->send_request()->getResponse());
            
            $search=new Search();
            $search= $search->setData(array('id_community'=>$community->getGid()))->save(array("id","id_community"))->get_by_id_community();
            $view = new View('Index');
            $view->setContent('search');
            $view->setData(array("id"=>$search->getId()));
            $view->generate(); 
        }
        
        
       
    }
    public function exampleAction(){
        $date="";
        $string= explode(".", $date);
        $birhdate=new \DateTime();
        switch (count($string)){
        case 2: checkdate($string[1], $string[0], 1)?$birhdate->setDate(1, $string[1], $string[0]):0;break;
        case 3: checkdate($string[1], $string[0], $string[2])?$birhdate->setDate( $string[2], $string[1], $string[0]):0;break;
        default :$birhdate=null;break;
    }
    var_dump($birhdate->format('Y:m:d'));
    
    }
}
