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
//private $activity;
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


}