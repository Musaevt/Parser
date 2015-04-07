<?php
namespace Search\Controller;

use Library\AbstractController;
use Library\Database;
use Library\View;
use Search\Models\Search;
use Library\Application;
use Library\Models\Search as Search_model;
use Api\Models\Access_token;

class Controller extends AbstractController{
    
    public function IndexAction(){
   
    if(isset(Application::$request_variables['get']['community_id'])||isset(Application::$request_variables['session']['community_id'])){
    $comminity= isset(Application::$request_variables['get']['community_id'])?Application::$request_variables['get']['community_id']:$_SESSION['community_id'] ;
    
    
     $search=new Search();
     $search->param=array('community_id'=>$comminity);
     $search->get_info_about_community();
     $_SESSION['community_id']=isset(Application::$request_variables['get']['community_id'])?Application::$request_variables['get']['community_id']:Application::$request_variables['session']['community_id'];
          
     $search_model= new Search_model();
     $search_model->setData(array('id_community'=>$search->answer->getGid()))->save()->get_by_id_community();
          
    
      Access_token::check_access_token();
     
      $view = new View('Search');
      $view->setContent('content');
      $view->setData(array("group"=>$search->answer,"search"=>$search_model));
      $view->generate(); 
       
   }
  
    }
    public function startAction(){
            ignore_user_abort(true);
            set_time_limit(0);
            ini_set('memory_limit', '-1');
            if(!isset(Application::$request_variables['session']['access_token'])){
                $error=['error'=>'You haven`t authorized'];
                echo json_encode($error);
                exit();
            }
           
            if(isset(Application::$request_variables['get']['community_id'])){
            try{
            $comminity= Application::$request_variables['get']['community_id'] ;
            session_destroy();
            $search=new Search();
            $search->param=array('community_id'=>$comminity);
            $search->get_info_about_community();
        
            $search_model= new Search_model();
            $search_model->setData(array('id_community'=>$search->answer->getGid()))->get_by_id_community();
         
            $search->get_members_of_community()->get_users_communities();
           
           
            $search_model->setDone(true)->setDate_end(date("Y-m-d H:i:s"))->update();
             echo json_encode($search->answer); 
            }  catch (Exception $e)
            {
            echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
            }
        }
      
    }
   
   
}
