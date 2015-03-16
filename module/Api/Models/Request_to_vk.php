<?php
namespace Api\Models;
Class Request_to_vk{
    
          private $path;
          private $MethodName; 
          private $http;
          private $parametrs;
          private $response_type;
          private $answer;
          
   
          function __construct($path,$MethodName,$parametrs=array(),$response_type="json") {
              $this->path=$path;
              $this->MethodName=$MethodName;
              $this->parametrs=$parametrs;
              $this->http=$this->path.$this->MethodName."?";
              $this->response_type=$response_type;
               }
          public function push_request(){
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $this->http);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
              curl_setopt($ch, CURLOPT_POST, 1);

              curl_setopt($ch, CURLOPT_POSTFIELDS, $this->parametrs);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // On dev server only!
              $result = curl_exec($ch);
              $this->answer= ($this->response_type=="json")?$result:json_decode($result);
         
          
              if($this->answer->error){
                  ($this->answer->error->error_code==5)?\Library\Application::$config['access_token']=0:0;
                   echo $this->answer->error->error_msg;
                      }
               return $this;
          } 
          public function get_answer(){
              return $this->answer;
          }
}