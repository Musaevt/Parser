<?php
namespace Library\Models;
class Functions{
        
/*Получение всех полей пользователя    
 * Аргументы  (PDA,Название табл,id пользоват)
 */
public function method_Update_Users($connect,$table_Name,$uid=NULL){
    
    //update all users in bd which is `is_parsed`=false
   
  
   $userbefore=new VK_User();
   $user_data=(!$uid)?$userbefore->get_is_not_parsed($connect, $table_Name):$userbefore->get_by_uid($connect,$table_Name,$uid);
   
   
   
   if(!$user_data&&!$uid)
   {
       return ("ALL IS PARSED") ;
   }else{
       $userbefore->setData($user_data);
   }
   
   $user=new VK_User();
   $user->setUid($userbefore->getUid());
    
    $path="https://api.vk.com/method/";
    $MethodName='users.get';
    $Parametrs=array(
       "user_ids"    => $user->getUid(),
        "fields"     =>'sex, bdate, city, country, photo_50, photo_100, photo_200_orig, photo_200, photo_400_orig, photo_max, photo_max_orig, photo_id, online, online_mobile, domain, has_mobile, contacts, connections, site, education, universities, schools, can_post, can_see_all_posts, can_see_audio, can_write_private_message, status, last_seen, common_count, relation, relatives, counters, screen_name, maiden_name, timezone, occupation,activities, interests, music, movies, tv, books, games, about, quotes, personal, friends_status ',
        'v'          =>5.28,
        "access_token"=> $_SESSION['access_token'],
        );
    //запрос
    $requst=new Request_to_vk($path,$MethodName, $Parametrs);
    $answer=$requst->push_request()
                   ->get_answer();
    if(!$answer->error){
       $user->setData($answer->response[0])
            ->setIs_parsed(true)
            ->setGid_by_added_group($userbefore->getGid_by_added_group())
            ->Update_bd($connect, $table_Name);
       
       echo (!$uid)?"<script>setTimeout('window.location.reload()', 500);</script>":""; 
       return "<H1>".$user->getUid()."</H1>";
        }
}





  //ассоциативный массив названий таблиц в БД
/* пример аргуммента $tables
 * $tables=  array(
       "table_Users"          =>"vk_Users_2",
       "table_Groups"         =>"vk_Groups_2",
       "table_Users_In_Groups"=>"Users_In_Groups_2",
   );
 * агумент $max_Group_Count =макс кол-во групп у пользователей (пользователей с большим значением обрабатываеть не будет)
  
 */
public function method_Get_User_Groups($connect,$tables,$max_Group_Count){
   //get all groups from users attribute `is_parsed`=false
      
   $user=new VK_User();
   $user_is_not_parsed=$user->get_is_not_parsed($connect, $tables['table_Users']);
  
   if(!$user_is_not_parsed){
       echo "ALL IS PARSED";
       return -1;
   }
   $user->setData($user_is_not_parsed);
   
   if($max_Group_Count<$user->getGroups_count()){
    $user ->setIs_parsed(true)
          ->Update_bd($connect, $tables['table_Users']);
    echo "<script> setTimeout('window.location.reload()', 500);</script>";
        return -1;
    }  
   $Parametrs=array(
      'user_id'    =>$user->getUid(),
      'extended'   =>1,
       'offset'     =>0,
       'count'      =>200,
       'fields'     =>'city,country,description,wiki_page,members_count,status,contacts,verified,site',
         'v'        =>5.28      
   );

 
   $groups=$user->get_all_User_group($connect,$tables['table_Groups'],$tables['table_Users_In_Groups'],$Parametrs);
   if(!($groups==-1)){
    $user ->setIs_parsed(true)
          ->Update_bd($connect, $tables['table_Users']);
     echo "User uid:".$user->getUid()." Groups count:".count($groups)."</br>";
    echo "<script> setTimeout('window.location.reload()', 500);</script>";
     return count($groups);
   }  else {
       return -1;    
   }
  
   
  }
   
public function method_Get_Users_From_Group($connect,$tables,$Group_Name){
    $group=new VK_Community();
    $group->setScreen_name($Group_Name);
    $group->get_community_byID($connect);


    $callvk="https://api.vk.com/method/";
    $MethodName='groups.getMembers';
    $Parametrs=array(
       "group_id" => $group->getGid(),
       "offset"   =>isset($_SESSION['offset'])?$_SESSION['offset']:0,
        "count"    =>1000,
        "fields"   =>'sex,bdate,city,country,photo_50,photo_100,photo_200_orig,photo_200,photo_400_orig,photo_max,photo_max_orig,online,online_mobile,lists,domain,has_mobile,contacts,connections,site,education,universities,schools,can_post,can_see_all_posts,can_see_audio,can_write_private_message,status,last_seen,relation,relatives,counters',
        "access_token"=> $_SESSION['access_token'],
            );
    $http=$callvk.$MethodName.'?';
    //запрос
    $requst=new Request_to_vk($callvk,$MethodName, $Parametrs);
    $answer=$requst->push_request()
                   ->get_answer();
          
    $users=array();
    foreach ($answer->response->users as $user)
    {
        $userVk=new VK_User();
        $userVk->setData($user);
        $userVk->setGid_by_added_group($group->getGid());
        array_push($users, $userVk);
        $userVk->save($connect, $tables['table_Users']);
        $userVk->save_Group($connect, $tables['table_Users_In_Groups'],$group->getGid());
    }
    if($answer->response->count>1000&&$answer->response->count>=$_SESSION['offset']+1000){
    $_SESSION['offset']=isset($_SESSION['offset'])?$_SESSION['offset']+1000:1000;
    }
    return $users;

}

//gid передаёться в Get запросе
public function method_Get_Groupinfo($connect,$tables){
    $callvk="https://api.vk.com/method/";
    $MethodName='groups.getById';
    $group_Name=  is_string($_GET['group'])?$_GET['group']:"";

    $Parametrs=array(
        "group_id" =>  $group_Name,
         "fields"   =>'city,country,place,description,wiki_page,members_count,counters,start_date,finish_date,activity,status,contacts,links,fixed_post,verified,site',
        );
    $http=$callvk.$MethodName.'?';
    //запрос
    $req=new Request_to_vk($callvk, $MethodName,$Parametrs);
    $answer=$req->push_request()
                ->get_answer();

    $group=new VK_Community();
    $group->setData($answer->response[0])
          ->save($connect, $tables['table_Groups']);
    return $group;
}






}
?>