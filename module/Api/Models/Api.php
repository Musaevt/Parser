<?php
namespace Api\Models;

use Library\Application;
use Api\Models\Request_to_vk;
use Library\Models\Base\BaseClass;
use Library\Models\Community;
use Library\Models\User;
use Library\Models\Users_In_Сommunity;

class Api extends BaseClass{
    protected $method_name;
    protected $response_type;
    protected $response;
   
    public $data=array();
    static protected $ERROR='Api haven`t this method';
    
    
    public function __construct($Method_Name,$data=array(),$Response_Type="") {
        $this->method_name=$Method_Name;
        $this->data=$data;
        $this->response_type=$Response_Type;
     }

    public function send_request(){
        $Method_name= mb_strtolower($this->method_name);
        $this->response=method_exists($this,$Method_name)?$this->$Method_name()->response:self::$ERROR;
        if(isset($this->response_type)&&$this->response_type=="json")$answer=json_encode($answer);
         return $this;
    }

    private function get_rating_community(){
        $this->response=array();
        $groups= Community::get_top_groups($connection, $tables, array('gid','name','screen_name','members_count'));
        foreach ($groups as $group)
        {
            if(isset($this->data['without'])){
            $this_group=false;
                    foreach ($this->data['without'] as $without)
                       $this_group=($without==$group->getScreen_name())?true:$this_group;
            if($this_group) continue;
            }
           $count=Users_In_Groups::get_count_ouruser_in_group($connection, $tables, $group->getGid());
           $json=$group->get_JSON(array('our_members'=>$count));
           array_push($this->response, $json);
        }   
        return $this;
   }
   
    private function get_community_by_id_vk(){
    $callvk="https://api.vk.com/method/";
    $MethodName='groups.getById';
    $group_Name=  $this->data['group_name'];
    if(!$group_Name){
        return 0;
    }
    $Parametrs=array(
        "group_id" =>  $group_Name,
         "fields"   =>'photo_medium,city,country,place,description,wiki_page,members_count,counters,start_date,finish_date,activity,status,contacts,links,fixed_post,verified,site',
     //    "v"=>5.29,
        );
    //запрос
    $req=new Request_to_vk($callvk, $MethodName,$Parametrs);
    $answer=$req->push_request()
                ->get_answer();
    $answer=  json_decode($answer);
    if(isset($answer->error)){
     $this->response=array('error_code'=>$answer->error->error_code,'error_msg'=>$answer->error->error_msg);
    }else{
    $group=new Community();
    $this->response=$group->setData($answer->response[0])->get_JSON();
    }
    return $this;
   }
   
    private function get_community_members_vk(){
        /*param to data
        * group_id
        */
        $this->groups_getMembers_Vk();
        $response=json_decode($this->response,TRUE);
        $members= is_array($response)?$response['response']['users']:(array)$response->response->users;
        while($response['response']['count']>$this->data['offset']+1000){
         $this->data['offset']+=1000;
         $this->groups_getMembers_Vk();
         $response=json_decode($this->response,true);
         $members= array_merge($members, $response['response']['users']);
       }
       $this->response=  $members;
      
       return $this;
       
             
   }
    private function get_users_community(){
       /*   param to data
        *   uid(user id)
        */
        $this->groups_get_vk();
        $response=json_decode($this->response);
        $groups= $response->response->items;
       while($response->response->count>$this->data['offset']+1000){
         $this->data['offset']+=1000;
         $this->groups_get_vk();
         $response=json_decode($this->response);
         $groups= array_merge($groups, $response->response->items);
       }
       $this->response=  $groups;
      
       return $this;
       
   }


   private function  groups_getMembers_Vk(){
        $this->check_access_token();   
        (isset($this->data['offset']))?$this->data['offset']:$this->data['offset']=0;
        $callvk="https://api.vk.com/method/";
        $MethodName='groups.getMembers';
        $Parametrs=array(
           "group_id"       => $this->data['group_name'],
           "offset"         => $this->data['offset'],
           "count"          => 1000,
           "fields"         =>'sex,bdate,city,country,photo_50,photo_100,photo_200_orig,photo_200,photo_400_orig,photo_max,photo_max_orig,online,online_mobile,lists,domain,has_mobile,contacts,connections,site,education,universities,schools,can_post,can_see_all_posts,can_see_audio,can_write_private_message,status,last_seen,relation,relatives,counters',
        //   "access_token"   => Access_token::$access_token,
            
            );
        $requst=new Request_to_vk($callvk,$MethodName, $Parametrs);
        $answer=$requst->push_request()
                       ->get_answer();
        $this->response=$answer;
        return $this;  
   }
   
   private function  groups_get_vk(){
       Access_token::check_access_token();
     
        (isset($this->data['offset']))?$this->data['offset']:$this->data['offset']=0;
        $callvk="https://api.vk.com/method/";
        $MethodName='groups.get';
        $Parametrs=array(
           "user_id"        => $this->data['uid'],
           "offset"         => $this->data['offset'],
           "count"          => 1000,
           "extended"       => 1,
           "fields"         => 'city,country,place,description,wiki_page,members_count,counters,start_date,finish_date,activity,status,contacts,links,verified,site',
           "access_token"   =>  Access_token::$access_token,
            "v"             =>5.29
            );
        $requst=new Request_to_vk($callvk,$MethodName, $Parametrs);
        $answer=$requst->push_request()
                       ->get_answer();
        $this->response=$answer;
        return $this;  
   }

  
   
 
   
    
}
