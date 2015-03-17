<?php
namespace Library\Models;
use Library\Database;
class User extends \Library\Models\Base\BaseClassModel{
    protected $id;
    protected $uid;
    protected $first_name;
    protected $last_name;
    protected $deactivated;
    protected $hidden;
    protected $photo_max_orig;
    protected $verified;
    protected $sex;
    protected $bdate;
    protected $city ;
    protected $country;
    protected $home_town;
    protected $domain;
    protected $has_mobile; 
    protected $mobile_phone;
    protected $site;
  
    /*
     * university — идентификатор университета; 
        положительное число
        university_name — название университета; 
        строка
        faculty — идентификатор факультета; 
        положительное число
        faculty_name — название факультета; 
        строка
        graduation — год окончания. 
        положительное число
     */
    
    // private $universities;
    
    /*
     * id — идентификатор университета; 
        положительное число
        country — идентификатор страны, в которой расположен университет; 
        положительное число
        city — идентификатор города, в котором расположен университет; 
        положительное число
        name — наименование университета; 
        строка
        faculty — идентификатор факультета; 
        положительное число
        faculty_name — наименование факультета; 
        строка
        chair — идентификатор кафедры; 
        положительное число
        chair_name — наименование кафедры; 
        строка
        graduation — год окончания обучения. 
        положительное число
     */
    
   // private $schools;
    
    /*
     * id — идентификатор школы; 
        положительное число
        country — идентификатор страны, в которой расположена школа; 
        положительное число
        city — идентификатор города, в котором расположена школа; 
        положительное число
        name — наименование школы; 
        строка
        year_from — год начала обучения; 
        положительное число
        year_to — год окончания обучения; 
        положительное число
        year_graduated — год выпуска; 
        положительное число
        class — буква класса; 
        строка
        speciality — специализация; 
        строка
        type — идентификатор типа; 
        положительное число
        type_str — название типа. 
     */
    protected $status;
    protected $followers_count;
    protected $groups_count;
    protected $friends_count;
    protected $occupation;
    /*
     * type — может принимать значения work, school, university; 
    строка
    id — идентификатор школы, вуза, группы компании (в которой пользователь работает); 
    положительное число
    name — название школы, вуза или места работы; 
    строка
     */
   protected  $nickname;
   protected  $relation;
   protected  $activities;
   protected  $interests;
   protected  $music;
   protected  $about;
   protected  $date_update;
           function __construct(){
    
 }



public function setOccupation($argument){
    $this->occupation=isset($argument['id'])?$argument['id']:$argument;
}
public function setCountry($argument){
    $this->country=isset($argument['id'])?$argument['id']:$argument;
}
public function setCounters($argument){
    $this->friends_count=$argument['friends'];
    $this->groups_count=($argument['groups'])?$argument['groups']:$this->get_count_pages_VK();
    $this->followers_count=$argument['followers'];
}

public function setCity($argument){
    $this->city=isset($argument['id'])?$argument['id']:$argument;;
}
     

public function update($connection,$table_name){
    $query='UPTATE '.$table_name.' SET ';
       foreach($this as $key=>$value){
           ($value)?$query.=$key=':'.$key.',':0;
           }
     
         $query=substr($query,0,-1);
         $query.=" WHERE `uid`=".$this->uid;
        $execute= $connection->prepare($query);
           foreach($this as $key=>$value){
            ($value)?$execute->bindValue(':'.$key,$value):0;
           }
            $execute->execute();
 
     return 1;       
           
}
public function save_Group($connection,$table_name,$group_id){
     $query='INSERT INTO '.$table_name.' (gid,uid) VALUES (:gid,:uid)';
     $execute= $connection->prepare($query);
      $execute->bindValue(':gid',$group_id,PDO::PARAM_INT);
       $execute->bindValue(':uid',$this->uid,PDO::PARAM_INT);
       $execute->execute();
        //var_dump($execute->errorInfo());
   
       return 1;
 }
 public function get_all_User_group($connect,$table_Name_g,$table_Name_u_i_g,$parametrs,$groups=array()){
/*
 * $table_Name_u  название таблицы пользователей
 * $table_Name_u_i_g таблица совместимости групп и пользователй
 * 4 параметр группы используесться для рекурсивного вызова
 */
  $path="https://api.vk.com/method/";
  $MethodName='groups.get';
  $parametrs['access_token']=$_SESSION['access_token'];

         
    $requst=new Request_to_vk($path,$MethodName, $parametrs);
    $answer=$requst->push_request()
                   ->get_answer();
          
    
    if($answer->error||$groups==-1){
               echo  ($answer->error)? $answer->error->error_msg:"";
               return -1;
           }
       if(isset($answer->response->items)){
      
                  if(($parametrs['offset']+$parametrs['count'])<=$answer->response->count){
                            $parametrs['offset']+=$parametrs['count'];
                           $groups= $this->get_all_User_group ($connect,$table_Name_g,$table_Name_u_i_g,$parametrs,$groups);
                       }
                       
                    foreach ($answer->response->items as $group)
                    {
                        if($group->type=='group'||$group->type=='event'||$group->type=='page'){
                        $group->gid=$group->id;
                        $groupVk=new VK_Community();
                        $groupVk->setData($group);
                        array_push($groups, $groupVk);
                        $groupVk->save($connect,$table_Name_g);
                        $this->save_Group($connect,$table_Name_u_i_g ,$groupVk->getGid());
                        }
                    }
                   
        }
             
        return $groups;
     
 }
 
 static public function get_all_Users($connection,$table_name){
     $query='SELECT * FROM '.$table_name;
     $execute= $connection->prepare($query);
     $execute->execute();
     $answers=$execute->fetchAll();
     $users=array();
     foreach($answers as $answer){
         $user=new VK_User();
         $user->setData($answer);
         array_push($users,$user);
         }
         return $users;
 }
 public function get_id_is_not_parsed($connection,$table_name){
      $query='SELECT `uid` FROM '.$table_name.' WHERE `is_parsed`= false LIMIT 1';
      $execute= $connection->prepare($query);
      $execute->execute();
      $answer=$execute->fetchAll();
      return (!!$answer)?+$answer[0]['uid']:0;     
 }
  public function get_is_not_parsed($connection,$table_name){
      $query='SELECT * FROM '.$table_name.' WHERE `is_parsed`= false LIMIT 1';
      $execute= $connection->prepare($query);
      $execute->execute();
      $answer=$execute->fetchAll();
      return ($answer)?$answer[0]:0;     
 }
 public function get_by_uid($connection,$table_name,$uid){
      $query='SELECT * FROM '.$table_name.' WHERE `uid`='.$uid.' LIMIT 1';
      $execute= $connection->prepare($query);
      $execute->execute();
      $answer=$execute->fetchAll();
      return ($answer)?$answer[0]:0;     
 }
 public function Update_bd($connection,$table_name){
     $set="";
     foreach($this as $key=>$value){
         $set.=$key."= :".$key.',';         
     }
      $set=substr($set,0,-1);
           
      $query='UPDATE '.$table_name.' SET '.$set.' WHERE `uid`='.$this->uid;
     
      $execute= $connection->prepare($query);
      foreach($this as $key=>$value){
        $execute->bindValue(':'.$key, $value==null?"":$value);         
     }
      $success=$execute->execute();
       if(!$success){
           $fail=$execute->errorInfo();
           $fail[2]."</br>";
          }
        return $this;
 }
public function get_count_pages_VK()
{
    $path="https://api.vk.com/method/";
    $MethodName='users.getSubscriptions';
    $Parametrs=array(
        "user_id"    =>$this->uid,
        "extended"   =>0,
        'v'          =>5.28,
       );
    //запрос
    $requst=new Request_to_vk($path,$MethodName, $Parametrs);
    $answer=$requst->push_request()
                   ->get_answer()->response;
    
    return (!!$answer->groups->count)?$answer->groups->count:0;
}
 public function get_JSON($param=array()){
   $answer=array();
   $arr=array(
      'id'                   =>(isset($this->id))?$this->id:"",                                 
      'uid'                  =>(isset($this->uid))?$this->uid:"",                               
      'first_name'           =>(isset($this->first_name))?$this->first_name:"",                       
      'last_name'            =>(isset($this->last_name))?$this->last_name:"",                 
      'deactivated'          =>(isset($this->deactivated))?$this->deactivated:"",            
      'hidden'               =>(isset($this->hidden))?$this->hidden:"",                        
      'photo_max_orig'       =>(isset($this->photo_max_orig))?$this->photo_max_orig:"",      
      'verified'             =>(isset($this->verified))?$this->verified:"",                    
      'sex'                  =>(isset($this->sex))?$this->sex:"",                                  
      'bdate'                =>(isset($this->bdate))?$this->bdate:"",                             
      'city'                 =>(isset($this->city))?$this->city:"",                                
      'country'              =>(isset($this->country))?$this->country:"",                           
      'home_town'            =>(isset($this->home_town))?$this->home_town:"",                 
      'domain'               =>(isset($this->domain))?$this->domain:"",                           
      'has_mobile'           =>(isset($this->has_mobile))?$this->has_mobile:"",            
      'mobile_phone'         =>(isset($this->mobile_phone))?$this->mobile_phone:"",           
      'site'                 =>(isset($this->site))?$this->site:"",                            
      'status'               =>(isset($this->status))?$this->status:"",                    
      'followers_count'      =>(isset($this->followers_count))?$this->followers_count:"",     
      'groups_count'         =>(isset($this->groups_count))?$this->groups_count:"",         
      'friends_count'        =>(isset($this->friends_count))?$this->friends_count:"",      
      'occupation'           =>(isset($this->occupation))?$this->occupation:"",            
      'nickname'             =>(isset($this->nickname))?$this->nickname:"",                 
      'relation'             =>(isset($this->relation))?$this->relation:"",                 
      'activities'           =>(isset($this->activities))?$this->activities:"",              
      'interests'            =>(isset($this->interests))?$this->interests:"",               
      'music'                =>(isset($this->music))?$this->music:"",                       
      'about'                =>(isset($this->about))?$this->about:"",                       
      'date_update'          =>(isset($this->date_update))?$this->date_update:"",          
       );
       
       foreach ($param as $value)
       {
           if(array_key_exists($value, $arr))
              $arr[$value]=$arr[$value];
       }
        if(count($param)==0)
            $answer=$arr;
      
      return $answer;
}
}