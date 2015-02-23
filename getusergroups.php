<?php
require_once 'include/common.php';

get_vk_access_token();

$connect= Database::getConnection();
$connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$connect->query("SET NAMES UTF8;");
   
echo '<meta charset="utf-8">';

 $tables=  array(
       "table_Users"          =>"vk_Users_2",
       "table_Groups"         =>"vk_Groups_2",
       "table_Users_In_Groups"=>"Users_In_Groups_2",
   );
  
  get_User_Groups($connect,$tables,790);
  
  $func=new Functions();
  $func->method_Get_User_Groups($connect, $tables, $max_Group_Count);
 