<?php
namespace Search\Models;

use Library\Models\Base\BaseClass;
use Library\Models\Community;
use Library\Models\User;
use Library\Models\Users_In_Communities;
use Api\Models\Api;

class Search extends BaseClass {
    public  $param;
    public  $answer;


    public function get_info_about_community()
    {
     if(!isset($this->param['community_id'])){
         throw new \Exception("Community id isn`t set");
     }
     $community_info= new Api('get_community_by_id_vk',array('group_name'=>$this->param['community_id']));
     $community_info=$community_info->send_request()->getResponse();
  
     $community=new Community();
     $this->answer=$community->setData(json_decode($community_info),true)->save();
     return $this;
    }
    /*
     * Return all Users Uid`s in this group 
     */
    public function get_members_of_community()
    {
        set_time_limit(0);
        ignore_user_abort(true);
        ini_set('memory_limit', '-1');
       
        if(!isset($this->param['community_id'])){
            throw new \Exception("Community id isn`t set");
        }
        if(!is_integer($this->param['community_id'])){
            $this->param['community_id']=$this->get_info_about_community()->answer->getGid();
          }
         $user=new User();
         $relations=new Users_In_Communities();
         $data=array('group_name'=>$this->param['community_id']);
         $users_info= new Api('get_community_members',$data);
         $user_uids['uids']=array();
         
        $count=NULL;
        $data['offset']=NULL;
        while(!isset($data['offset'])||$count>$data['offset']+1000){
            $data['offset']=isset($data['offset'])?$data['offset']+=1000:0;
             $users_info->data=$data;
             $users_info->send_request();
             $response=json_decode($users_info->getResponse(),TRUE);
             $count=is_array($response)?count($response['response']):(array)$response->response->count;
             $members= is_array($response)?$response:(array)$response->response->users;
              foreach($members as $member){
                $user->setData($member)->save();
                $relations->setData(array('gid_community'=>$this->param['community_id'],'uid_user'=>$user->getUid()))->save();
                array_push($user_uids['uids'], $user->getUid());
                }
                
       }
       $this->answer=$user_uids;
   
       return $this;
    }
    /*
     * must have array of users uid`s in param attribut
     */
    public function get_users_communities(){
        set_time_limit(0);
        ignore_user_abort(true);
        ini_set('memory_limit', '-1');
       
        if(!isset($this->answer['uids']))
             throw new \Exception ('Haven`t users to parse');
         
         $community=new Community();
         $relations=new Users_In_Communities();
         $gids['gids']=array();
         
         foreach($this->answer['uids'] as $uid){
                $data=array('uid'=>$uid,'max_count'=>700);
                $communities_info= new Api('get_user_community',$data);
                $communities_info->send_request();
                $response=json_decode($communities_info->getResponse(),TRUE);
                if(is_array($response)){
                    foreach ($response as $group){
                       $community->setData($group)->save(array("gid","name","screen_name","is_closed","deactivated","type","photo_big","start_date","city","country","description","wiki_page","members_count","status","contacts","verified","site","date_update"));
                       $relations->setData(array('gid_community'=>$community->getGid(),'uid_user'=>$uid))->save();
                       var_dump($relations);
                       array_push($gids['gids'], $community->getGid());
                              
                    }
                }
                  
            
         }
       
         $this->answer=$gids;
    
         return $this;
       
    }
    
    
}
