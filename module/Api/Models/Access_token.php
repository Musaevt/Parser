<?php
namespace Api\Models;

use Library\Application;
use Api\Models\Request_to_vk;

class Access_token{
    
    public static $App_Id ;
    public static $App_pass;
    public static $redirect_Uri;
    public static $code;    

    public static $access_token;
    public static $expires_in;
    
    public function __construct() {
       
        
    }
    public static function init($redirect_Uri,$code=null)
    {
        self::$App_Id   = Application::$config['resource_options']['application']['APP_ID'];
        self::$App_pass = Application::$config['resource_options']['application']['APP_PASS'];
       
        self::$redirect_Uri=$redirect_Uri;
        ($code)?self::$code=$code:0;
        return new self();
    }
    public static function get_code(){
   
    $url = 'https://oauth.vk.com/authorize?client_id='.self::$App_Id.'&redirect_uri='.self::$redirect_Uri.'&display=page';
    header('Location: '.$url);        
    }
    
    public static function get_access_token(){
    $url='https://oauth.vk.com/access_token?';
    $methodname='access_token';
    $parametrs=array(
          'client_id'       =>self::$App_Id,  
          'client_secret'   =>self::$App_pass,
          'code'            =>self::$code,
          'redirect_uri'    =>self::$redirect_Uri,
           
    );
    $requst=new Request_to_vk($url,$methodname, $parametrs);
    $obj=$requst->push_request()
                 ->get_answer();
     $obj=  json_decode($obj);    
     if(isset($obj->access_token))
     {
        self::$access_token=$obj->access_token;
        self::$expires_in=$obj->expires_in;
        Application::$request_variables['session']['access_token']=$obj->access_token;
        Application::$request_variables['session']['expires_in']=$obj->expires_in;
        $_SESSION['access_token']=$obj->access_token;
        $_SESSION['expires_in']=$obj->expires_in;
        return TRUE;
     }
    else {
         echo ($obj->error)?$obj->error_description:"";  
         return false;    
     }
       
     }
     
public static function check_access_token(){
   $redirect='http://localhost'.Application::$request_variables['server']['REDIRECT_URL'];//временно при выгрузке на сервер изменить на верх!
   self::init($redirect);
        if(!isset(Application::$request_variables['session']['access_token'])){
            //Получение code
           if(!isset(Application::$request_variables['get']['code'])){
               $_SESSION=array_merge($_SESSION, $_GET);
                self::get_code();
           }
           else{  
              self::$code=Application::$request_variables['get']['code'];
              self::get_access_token();
               }
             }
         else{
              self::$access_token= Application::$request_variables['session']['access_token'];
              self::$expires_in=  Application::$request_variables['session']['expires_in'];
           }
     
       
        
   }
  
}