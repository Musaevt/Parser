<?php

class Access_token{
    public static $App_Id = APP_ID;
    public static $App_pass = APP_PASS;
    public static $redirect_Uri;
    public static $code;    

    public static $access_token;
    public static $expires_in;
    
    public function __construct() {
        
    }
    public static function init($redirect_Uri,$code=null)
    {
       
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
     if(isset($obj->access_token))
     {
        $_SESSION['access_token']=$obj->access_token;
        $_SESSION['expires_in']=$obj->expires_in;
        self::$access_token=$obj->access_token;
        self::$access_token=$obj->expires_in;
        return 1;
     }
    else {
         echo ($obj->error)?$obj->error_description:"";  
         return -1;    
     }
       
     }
  
}