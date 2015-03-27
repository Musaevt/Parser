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
      parent::__construct();
 }



public function setOccupation($argument){
    $this->occupation=isset($argument['id'])?$argument['id']:$argument;
}
public function setCountry($argument){
    $this->country=isset($argument['id'])?$argument['id']:$argument;
}
public function setCounters($argument){
    $this->friends_count=$argument['friends'];
    $this->groups_count=$argument['groups'];
    $this->followers_count=$argument['followers'];
}

public function setCity($argument){
    $this->city=isset($argument['id'])?$argument['id']:$argument;;
}
public function setBdate($argument){
        $string= explode(".", $argument);
        $birhdate=new \DateTime();
        switch (count($string)){
        case 2: checkdate($string[1], $string[0], 1)?$birhdate->setDate(1, $string[1], $string[0]):0;break;
        case 3: checkdate($string[1], $string[0], $string[2])?$birhdate->setDate( $string[2], $string[1], $string[0]):0;break;
        default :$birhdate=null;break;
    }
   $this->bdate=($birhdate)?$birhdate->format('Y:m:d'):"";
}
public function get_by_uid(){
      $query="SELECT * FROM ".Database::$options['tables']['User']." WHERE `uid`=".$this->uid." ORDER BY  `id` DESC  LIMIT 1";
      $execute= Database::$connect->prepare($query);
      $execute->execute();
      $answer= $execute->fetchAll();
      $this->setData($answer[0]);
      return $this;
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
              $answer[$value]=$arr[$value];
       }
        if(count($param)==0)
            $answer=$arr;
      
      return json_encode($answer);
}
}