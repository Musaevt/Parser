<?php
require_once 'include/common.php';


//echo '<meta charset="utf-8">';
   get_vk_access_token();
    
   $connect= Database::getConnection();
   $connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
   $connect->query("SET NAMES UTF8;");
   
   

    $table_Name="vk_Users_2";
   // echo update_users($connect,$table_Name);

 $funct=new Functions();
 echo $funct->method_update_users($connect, $table_Name);
        


