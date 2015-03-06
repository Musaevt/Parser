<?php
class VK_Community{
private $gid;
private $name;
private $screen_name;
private $is_closed;
private $deactivated;
private $type;
private $photo_big;
private $start_date;
private $city;
private $country;
private $description;
private $wiki_page;
private $members_count;
private $members=array();
private $status;
private $contacts;
//private $links;
private $verified;
private $site;

function __construct(){
    
 }
 public function setData($data){
     
    foreach ($data as $key=>$argum){
       $method='set'.ucfirst($key);
      $this->$method($argum);
    }
    return $this;
}
public function getMembers_count(){
    return $this->members_count;
}

public function setContacts($argument){
    $this->contacts=isset($argument[0])?$argument[0]->user_id:0;
}
public function setCountry($argument){
    $this->country=isset($argument)?$argument->title:"";
}
public function setCity($argument){
      $this->city=isset($argument)?$argument->title:"";
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
            
 
            case 'set':
                property_exists ($this,$property_name)?$this->$property_name=$argument[0]:0;
                return $this;
        }
    }
    
    
public function get_community_byID($connection){
    //проверка на получние из BD если нет получение из Vk Api

        
        
        $callvk="https://api.vk.com/method/";
        $MethodName='groups.getById';
        $group_Name= isset($this->gid)?$this->gid:$this->screen_name;

        $Parametrs=array(
            "group_id" =>  $group_Name,
             "fields"   =>'city,country,place,description,wiki_page,members_count,counters,start_date,finish_date,activity,status,contacts,links,fixed_post,verified,site',
            );
        $http=$callvk.$MethodName.'?';
        //запрос
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $http);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $Parametrs);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // On dev server only!
        $result = curl_exec($ch);
        $answer=  json_decode($result);    
        $this->setData($answer->response[0]);
        return $this;
        
    }
public function save($connection,$table_name){
    $query='INSERT INTO '.$table_name.' (';
    $questions=' VALUES (';
        foreach($this as $key=>$value){
            $query.=$key.',';
            $questions.=':'.$key.',';
        }
    $query=substr($query,0,-1).')';
    $questions=substr($questions,0,-1).');';
    $query.=$questions;
    $execute= $connection->prepare($query);
        foreach($this as $key=>$value){
          $execute->bindValue(':'.$key, $value);
        }

        $success=$execute->execute();
        if(!$success)
        { $fail=$execute->errorInfo();
            echo $fail[2]."</br>";
        }
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

public static function get_top_groups($connection,$table,$fields=array()){
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
              LIMIT 30";
         
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

public function get_JSON($param=array()){
   $arr=array(
       'gid'          =>(isset($this->gid))?$this->gid:"",
       'name'         =>(isset($this->name))?$this->name:"",
       'screen_name'  =>(isset($this->screen_name))?$this->screen_name:"",
//       'is_closed'    =>(isset($this->is_closed))?$this->is_closed:"",
//       'deactivated'  =>(isset($this->deactivated))?$this->deactivated:"",
//        'type'        =>(isset($this->type))?$this->type:"",
//        'photo_big'   =>(isset($this->photo_big))?$this->photo_big:"",
//        'start_date'  =>(isset($this->start_date))?$this->start_date:"",
//        'city'        =>(isset($this->city))?$this->city:"",
//        'country'     =>(isset($this->country))?$this->country:"",
//        'description' =>(isset($this->description))?$this->description:"",
//        'wiki_page'   =>(isset($this->wiki_page))?$this->wiki_page:"",
        'members_count'=>(isset($this->members_count))?$this->members_count:"",
//        'status'      =>(isset($this->status))?$this->status:"",
//        'contacts'    =>(isset($this->contacts))?$this->contacts:"",
//         'links'      =>(isset($this->links))?$this->links:"",
//        'verified'    =>(isset($this->verified))?$this->verified:"",
//        'site'        =>(isset($this->site))?$this->site:"",
       );
       foreach ($param as $key=>$value)
       {
           $arr[$key]=$value;
       }
   
      // return json_encode($arr,JSON_UNESCAPED_UNICODE);
      return $arr;
}

}


