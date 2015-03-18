<?php
namespace Search\Controller;

use Library\AbstractController;
use Library\Database;
use Library\View;
use Search\Models\Search;

class Controller extends AbstractController{
    
    public function IndexAction(){
        
    $connect= Database::$connect;
    $view = new View('Search');
    $view->setContent('content');
    $view->generate(); 
  
    }
    public function startAction(){
   //     $community=$this->get_info_about_community('55293029');
      $search=new Search();
      $search->param=array('community_id'=>"leagueofdevelopers");
      $users=  $search->get_members_of_community()->get_users_communities();
      var_dump($users);
       
    
    }
   
   
}
