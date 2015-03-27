<?php
namespace Api\Models;

use Library\Application;
use Api\Models\Request_to_vk;
use Library\Models\Base\BaseClass;
use Library\Models\Community;
use Library\Models\User;
use Library\Models\Users_In_Сommunity;
use Library\Models\Search;

class Api extends BaseClass{
    protected $method_name;
    protected $response_type;
    protected $response;
   
    public $data=array();
    static protected $ERROR='Api haven`t this method';
    
    
    public function __construct($Method_Name,$data=array()) {
        $this->method_name=$Method_Name;
        $this->data=$data;
        $this->response_type=$Response_Type;
     }

    public function send_request(){
        $Method_name= mb_strtolower($this->method_name);
        $this->response=method_exists($this,$Method_name)?$this->$Method_name()->response:self::$ERROR;
     //   if($this->response_type=="json")$this->response=json_encode($this->response);
         return $this;
    }


   /*
    * for getting info from DB
    */
   private function  get_rating_communities_from_search_percent(){
       if(!isset($this->data['search_id']))exit(json_encode (['error'=>'Haven`t search_id']));
       if(!is_numeric($this->data['count']))exit (json_encode (['error'=>'haven`t count or it`s not a number']));
       $search=new Search();
       $search->setId($this->data['search_id'])->get_by_id();
       $answer=$search->get_by_percent($this->data['count']);
       if(isset($this->data['fields'])){
              $fields=explode(",",$this->data['fields']);
              foreach($answer as $key=>$value)
              $answer[$key]=array_intersect_key($answer[$key],  array_flip ($fields));
          }
        
       echo json_encode($answer);
    }
    private function  get_rating_communities_from_search_count(){
       if(!isset($this->data['search_id']))exit(json_encode (['error'=>'Haven`t search_id']));
       if(!is_numeric($this->data['count']))exit (json_encode (['error'=>'haven`t count or it`s not a number']));
       $search=new Search();
       $search->setId($this->data['search_id'])->get_by_id();
       $answer=$search->get_by_count_members($this->data['count']);
        if(isset($this->data['fields'])){
              $fields=explode(",",$this->data['fields']);
              foreach($answer as $key=>$value)
              $answer[$key]=array_intersect_key($answer[$key],  array_flip ($fields));
          }
       echo json_encode($answer);
    }
    private function get_community_db(){
        if(!is_numeric($this->data['community_id']))exit(json_encode (['error'=>'Haven`t community_id or it`s not a number']));
        $community=new Community();
        $community->setGid($this->data['community_id'])->get_by_id();
        $param=(isset($this->data['fields']))?explode(",",$this->data['fields']):[];
        echo $community->get_JSON($param, true);
    }
    private function get_user_db(){
        if(!is_numeric($this->data['user_id']))exit(json_encode (['error'=>'Haven`t user_id or it`s not a number']));
        $user=new User();
        $user->setUid($this->data['user_id'])->get_by_uid();
        $param=(isset($this->data['fields']))?explode(",",$this->data['fields']):[];
        echo $user->get_JSON($param, true);
    }

    private function get_rating_members(){
          if(!isset($this->data['search_id']))exit(json_encode (['error'=>'Haven`t search_id']));
          if(!is_numeric($this->data['count']))exit (json_encode (['error'=>'haven`t count or it`s not a number']));
          $search=new Search();
          $search->setId($this->data['search_id'])->get_by_id();
          $answer=$search->get_members_by_communities_count($this->data['count']);
          if(isset($this->data['fields']))
          {
              $fields=explode(",",$this->data['fields']);
              foreach($answer as $key=>$value)
              $answer[$key]=array_intersect_key($answer[$key],  array_flip ($fields));
          }
          echo json_encode($answer);
       
    }
   
    

    private function get_community_members(){
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
    private function get_user_community(){
       /*   param to data
        *   uid(user id),  max_count(count max community,not neccessary)
        */
        $this->groups_get_vk();
        $response=json_decode($this->response,true);
        //переделать изменения id на gid
        
       for ($i=0;$i<count($response['response']['items']);$i++){
        $response['response']['items'][$i]['gid']=$response['response']['items'][$i]['id'];// version 5.29
        }
        
      $groups= $response['response']['items'];
      if(!isset($this->data['max_count'])||($response['response']['count']<=$this->data['max_count'])){
       while($response['response']['count']>$this->data['offset']+1000){
         $this->data['offset']+=1000;
         $this->groups_get_vk();
         $response=json_decode($this->response,true);
         
       for ($i=0;$i<count($response['response']['items']);$i++){
        $response['response']['items'][$i]['gid']=$response['response']['items'][$i]['id'];// version 5.29
        }
        
         $groups= array_merge($groups, $response['response']['items']);
       }
       $this->response=  $groups;
      }
      else{
          $this->response=NULL;
      }
       return $this;
       
   }


   private function  groups_getMembers_Vk(){
      
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
        $answer=  json_decode($answer,true);
        if(isset($answer['error'])){
         $this->response=array('error_code'=>$answer['error']['error_code'],'error_msg'=>$answer['error']['error_msg']);
        }else{
         $this->response=$answer['response'][0];
        }
    return $this;
   }
   
   
   //don`t use untill log in
   private function groups_search(){
        $callvk="https://api.vk.com/method/";
        $MethodName='groups.search';
        $string=  $this->data['string'];
        $count=($this->data['count'])?$this->data['count']:10;
        $token=(Access_token::$access_token)?Access_token::$access_token:exit('haven`t access token');
         $Parametrs=array(
            "q" =>  $string,
            "count"   =>$count,
            "access_token"   => $token,
             "v"=>5.29,
            );
        //запрос
        $req=new Request_to_vk($callvk, $MethodName,$Parametrs);
        $answer=$req->push_request()
                    ->get_answer();
        $answer=  json_decode($answer,true);
        var_dump($answer);
        if(isset($answer['error'])){
         $this->response=array('error_code'=>$answer['error']['error_code'],'error_msg'=>$answer['error']['error_msg']);
        }else{
         $this->response=$answer['response']['items'];
        }
    return $this;
   }
   
  
   
 
   
    
}
