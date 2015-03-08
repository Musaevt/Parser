<?php
namespace Library\Models;
class VK_User{
    private $uid;
    private $first_name;
    private $last_name;
    private $deactivated;
    private $hidden;
    private $photo_id;
    private $verified;
    private $blacklisted;
    private $sex;
    private $bdate;
    private $city ;
    private $country;
    private $home_town;
    private $photo_max_orig;
    private $online;
    private $lists;
    private $domain;
    private $groups_count;
    private $has_mobile; 
    private $contacts;
    private $site;
    private $education;
    private $is_parsed;
    private $gid_by_added_group;
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
    private $status;
    private $last_seen;
    private $followers_count;
    private $friends_count;
    private $occupation;
    /*
     * type — может принимать значения work, school, university; 
    строка
    id — идентификатор школы, вуза, группы компании (в которой пользователь работает); 
    положительное число
    name — название школы, вуза или места работы; 
    строка
     */
   private $nickname;
   private $relation;
   private  $activities;
   private  $interests;
   private  $music;
   private  $about;
   function __construct(){
    
 }
public function setData($data){
 //    $this->setId($data["id"]);
  foreach ($data as $key=>$argum){
       $method='set'.ucfirst($key);
      $this->$method($argum);
    }
    return $this;
}
public function setLast_seen($argument){
    $this->last_seen=0;
}
public function setOccupation($argument){
    $this->occupation=0;
}
public function setContacts($argument){
    $this->contacts=0;
}
public function setCountry($argument){
    $this->country=0;
}
public function setCounters($argument){
    $this->friends_count=$argument->friends;
    $this->groups_count=($argument->groups)?$argument->groups:$this->get_count_pages_VK();
    $this->followers_count=$argument->followers;
}

public function setCity($argument){
    $this->city=0;
}
 public function __call($method_name, $argument)
   {
        $args = preg_split('/(?<=\w)(?=[A-Z])/', $method_name);
        $action = array_shift($args);
        $property_name = strtolower(implode('_', $args));
        //имя свойства
       
        switch ($action)
        {
            case 'get':
                return isset($this->$property_name) ? $this->$property_name : null;
 
            case 'set':{
              property_exists ($this,$property_name)?$this->$property_name=$argument[0]:0;
                 return $this;
              }
               
        }
    }
    
public function save($connection,$table_name,$parametrs=NULL){
    $query='INSERT INTO '.$table_name.' (';
    $questions=' VALUES (';
        foreach($this as $key=>$value){
            $query.=$key.',';
            $questions.=':'.$key.',';
        }
    $query=substr($query,0,-1).')';
    $questions=substr($questions,0,-1).');';
    $query.=$questions;
     //var_dump($this);
     $execute= $connection->prepare($query);
     if($parametrs==NULL){  
        foreach($this as $key=>$value){
            $execute->bindValue(':'.$key, $value==null?"":$value);
        }
     }else{
          foreach ($parametrs as $parametr)
            {
                if(property_exists($this,$parametr))
                $execute->bindValue(':'.$parametr, $this->$parametr);
            }
            
            
        }
     $execute->execute();
 
     return 1;       
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
}