<?php
namespace Library\Models;

use Library\Models\Base\BaseClassModel;
use Library\Database;

//sresial array for save in v >5.19
//array("gid","name","screen_name","is_closed","deactivated","type","photo_big","start_date","city","country","description","wiki_page","members_count","status","contacts","verified","site","date_update");

class Community extends BaseClassModel{
protected $id;
protected $gid;
protected $name;
protected $screen_name;
protected $is_closed;
protected $deactivated;
protected $type;
protected $photo_big;
protected $start_date;
protected $city;
protected $country;
protected $description;
protected $wiki_page;
protected $members_count;
protected $status;
protected $contacts;//контактная инфа(id пользоавтеля)
protected $verified;
protected $site;
protected $date_update;//последнии обновление группы в БД

function __construct(){
    parent::__construct();
 }

public function setContacts($argument){
    if(!is_object($argument[0])||is_numeric($argument))
    $this->contacts=  is_numeric($argument)?$argument:(isset($argument[0]['user_id'])?$argument[0]['user_id']:0);
    else
      $this->contacts=  $argument[0]->user_id;   
 }
public function setCountry($argument){
     $this->country=is_array($argument)?($argument['id'])?$argument['id']:NULL:$argument;
       
}
public function setCity($argument){
    $this->city=  is_array($argument)?($argument['id'])?$argument['id']:NULL:$argument;
}
public function setPhoto_200($argument){
    $this->photo_big=$argument;
}
public function setWiki_page($argument){
    $this->wiki_page=$argument;
 }

 public function get_by_id(){
      $query="SELECT * FROM ".Database::$options['tables']['Community']." WHERE `gid`=".$this->gid." ORDER BY  `id` DESC  LIMIT 1";
      $execute= Database::$connect->prepare($query);
      $execute->execute();
      $answer= $execute->fetchAll();
      $this->setData($answer[0]);
      return $this;
 }

 public function get_all_Members($connection,$table){
    $query="SELECT Users.* "
   . "FROM ".$table['table_Users']." as Users LEFT OUTER JOIN ".$table['table_Users_In_Groups']." as Jointable "
   . "on Users.uid=Jointable.uid "
   . "Where Jointable.gid=".$this->gid;
    
        $execute= $connection->prepare($query);
        $success=$execute->execute();
        if(!$success)
        { $fail=$execute->errorInfo();
            echo $fail[2]."</br>";
            return $this;
        }
        foreach($execute as $answer){
        $user=new VK_User();
        $user->setData($answer);
        array_push($this->members, $user) ;
        }
        return $this;
        
    
}
//get all groups as array of objects(class Vk_Comunity)
public static function get_All_Groups($connection,$table,$parametrs=array(),$fields=array())
 {
     $query="SELECT ";
             if(count($fields)==0)
             $query.="Groups.* ";
              else{
                    foreach ($fields as $key=>$value)
                     $query.="Groups.".$value.",";
                $query=substr($query,0,-1);
              }
   $query.=" FROM ".$table['table_Groups']." as Groups";
     if(count($parametrs)>0){
         $query .= "Where ";
        foreach ($parametrs as $key=>$value){
            $query .="Groups.".$key."=".$value.",";         
        }
        $query=substr($query,0,-1);
     }
     
     
     $execute= $connection->prepare($query);
     $success=$execute->execute();
     if(!$success){ 
         $fail=$execute->errorInfo();
            echo $fail[2]."</br>";
            return -1;
      }
      $groups=array();
      foreach ($execute as $value){
          $group=new VK_Community();
          $group->setData($value);
          array_push($groups, $group);
      }
      
      return $groups;
      
}

public static function get_top_groups($count,$fields=array()){
    $connection=Database::$connect;
    $table=  Database::$options['tables'];
     $query="SELECT ";
             if(count($fields)==0)
             $query.="Groups.* ";
              else{
                    foreach ($fields as $key=>$value)
                     $query.="Groups.".$value.",";
                $query=substr($query,0,-1);
              }
   $query.=" FROM ".$table['table_Groups']." as Groups"
           . " LEFT OUTER JOIN ".$table['table_Users_In_Groups']." AS Members ON Groups.gid = Members.gid
              GROUP BY GROUPS.gid
              ORDER BY COUNT( GROUPS.gid ) DESC 
              LIMIT ".$count;
         
     $execute= $connection->prepare($query);
     $success=$execute->execute();
     if(!$success){ 
         $fail=$execute->errorInfo();
            echo $fail[2]."</br>";
            return -1;
      }
      $groups=array();
      foreach ($execute as $value){
          $group=new VK_Community();
          $group->setData($value);
          array_push($groups, $group);
      }
      
      return $groups;
}


public function get_JSON($param=array(),$json=false){
   $answer=array();
   $arr=array(
       'gid'          =>(isset($this->gid))?$this->gid:"",
       'name'         =>(isset($this->name))?$this->name:"",
       'screen_name'  =>(isset($this->screen_name))?$this->screen_name:"",
       'is_closed'    =>(isset($this->is_closed))?$this->is_closed:"",
       'deactivated'  =>(isset($this->deactivated))?$this->deactivated:"",
        'type'        =>(isset($this->type))?$this->type:"",
        'photo_big'   =>(isset($this->photo_big))?$this->photo_big:"",
       'photo_medium' =>(isset($this->photo_medium))?$this->photo_medium:"",
        'start_date'  =>(isset($this->start_date))?$this->start_date:"",
        'city'        =>(isset($this->city))?$this->city:"",
        'country'     =>(isset($this->country))?$this->country:"",
        'description' =>(isset($this->description))?$this->description:"",
        'wiki_page'   =>(isset($this->wiki_page))?$this->wiki_page:"",
        'members_count'=>(isset($this->members_count))?$this->members_count:"",
        'status'      =>(isset($this->status))?$this->status:"",
        'contacts'    =>(isset($this->contacts))?$this->contacts:"",
         'links'      =>(isset($this->links))?$this->links:"",
        'verified'    =>(isset($this->verified))?$this->verified:"",
        'site'        =>(isset($this->site))?$this->site:"",
       );
       
       foreach ($param as $value)
       {
           if(array_key_exists($value, $arr))
              $answer[$value]=$arr[$value];
       }
        if(count($param)==0)
            $answer=$arr;
   
      return ($json)?json_encode($answer,JSON_UNESCAPED_UNICODE):$answer;
   
}

}


