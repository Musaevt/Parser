<?php
function get_vk_access_token(){
    if(!$_SESSION['access_token']){

       $redirect='http://localhost'.$_SERVER['PHP_SELF'];
       Access_token::init($redirect);

      //Получение code
   (!$_GET['code'])?Access_token::get_code():  Access_token::$code=$_GET['code'];

       //Получение Token
       Access_token::get_access_token();    
   }
}