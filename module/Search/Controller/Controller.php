<?php
namespace Search\Controller;

use Library\AbstractController;
use Library\Database;
use Library\Models\Community;
use Library\Models\User;
use Library\Models\Users_In_Communities;
use Api\Models\Api;
use Library\View;

class Controller extends AbstractController{
    
    public function IndexAction(){
        
    $connect= Database::$connect;
    $view = new View('Search');
    $view->setContent('content');
    $view->generate(); 
  
    }
    public function startAction(){
   //     $community=$this->get_info_about_community('55293029');
      $users=  $this->get_members_of_community("1");  
       
    
    }
    private function get_info_about_community($community_id)
    {
     $community_info= new Api('get_community_by_id_vk',array('group_name'=>$community_id));
     $community_info=$community_info->send_request()->getResponse();
     $community=new Community();
     $community->setData($community_info);
     return $community;
    }
    private function get_members_of_community($community_id)
    {
         $user=new User();
         $relations=new Users_In_Communities();
         $data=array('group_name'=>$community_id);
         $users_info= new Api('groups_getMembers_Vk',$data);
               
        $count=NULL;
        $data['offset']=NULL;
        while(!isset($data['offset'])||$count>$data['offset']+1000){
            $data['offset']=isset($data['offset'])?$data['offset']+=1000:0;
             $users_info->data=$data;
             $users_info->send_request();
             $response=json_decode($users_info->getResponse(),TRUE);
             $count=is_array($response)?$response['response']['count']:(array)$response->response->count;
             $members= is_array($response)?$response['response']['users']:(array)$response->response->users;
                foreach($members as $member){
                $user->setData($member)->save();
               
                $relations->setData(array('gid_community'=>$community_id,'uid_user'=>$user->getUid()))->save();
                
                }
                
       }
       $this->response=  $members;
//        
        
        
        
        
//     $users_info= new Api('get_community_members_vk',array('group_name'=>$community_id));
//     $users_info=$users_info->send_request()->getResponse();
//     var_dump($users_info);
  //   var_dump($users_info);
     return $users_info;
    }
   
}
