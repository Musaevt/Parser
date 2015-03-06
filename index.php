<?php
   require_once 'include/common.php';
 //  echo '<meta charset="utf-8">';
   Application_Config::init(require "/include".APP_CONF);
   $connect= Database::getConnection();
   $connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
   $connect->query("SET NAMES UTF8;");
   
   
   $tables=  array(
       "table_Users"          =>"vk_Users_2",
       "table_Groups"         =>"vk_Groups_2",
       "table_Users_In_Groups"=>"Users_In_Groups_2",
   );
 if(isset($_POST['function'])){  
$Request= new API($_POST['function'],$_POST);
$answ=$Request->send_request($connect, $tables);
$str=json_encode($answ);
echo $str;
 }
 echo "хз";
 var_dump($_SERVER);
 var_dump(dirname(__FILE__));