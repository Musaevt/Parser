<?php 
require_once 'include/common.php';

//echo "<script> setTimeout('window.location.reload()', 1000);</script>";
get_vk_access_token();


   $connect= Database::getConnection();
   $connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
   $connect->query("SET NAMES UTF8;");
   
   
   $tables=  array(
       "table_Users"          =>"vk_Users_2",
       "table_Groups"         =>"vk_Groups_2",
       "table_Users_In_Groups"=>"Users_In_Groups_2",
   );
   
    $func=new Functions();
    $func->method_Get_Users_From_Group($connect, $tables, "abbyymobile");

