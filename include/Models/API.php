<?php

class API extends BaseClass{
    private $Method_Name;
    private $Response_Type;
    private $Response;
    private $data=array();
    
    public function __construct($Method_Name,$data=array(),$Response_Type="json") {
        $this->Method_Name=$Method_Name;
        $this->data=$data;
        $this->Response_Type=$Response_Type;
    }




    public function send_request($connection,$tables){
        $Method_name= mb_strtolower($this->Method_Name);
         return (Application_Config::check_action("API",$Method_name))?$this->$Method_name($connection,$tables)->Response:"Haven`t such API Method";
        
    }

    private function get_rating_groups($connection,$tables)
    {
        $this->Response=array();
        $groups= VK_Community::get_top_groups($connection, $tables, array('gid','name','screen_name','members_count'));
        foreach ($groups as $group)
        {
            if(isset($this->data['without'])){
            $this_group=false;
                    foreach ($this->data['without'] as $without)
                       $this_group=($without==$group->getScreen_name())?true:$this_group;
            if($this_group) continue;
            }
           $count=VK_Users_In_Groups::get_count_ouruser_in_group($connection, $tables, $group->getGid());
          // $member_group=$group->getMembers_count();
           $json=$group->get_JSON(array('our_members'=>$count));
           array_push($this->Response, $json);
        }   
        return $this;
            
        
    }
    private function transform_Data_Type($data){
        switch ($this->Response_Type){
            case "json":
                return self::object_to_array($data);
                break;
            case "text":
                if(is_string($data)) return $data;
                return json_encode(self::object_to_array($data));
                break;
                
        } 
    }
    static function object_to_array($var) {
    $result = array();
    $references = array();
 
    // loop over elements/properties
    foreach ($var as $key => $value) {
        // recursively convert objects
        if (is_object($value) || is_array($value)) {
            // but prevent cycles
            if (!in_array($value, $references)) {
                $result[$key] = self::object_to_array($value);
                $references[] = $value;
            }
        } else {
            // simple values are untouched
            $result[$key] = $value;
        }
    }
    return $result;
}
    
}