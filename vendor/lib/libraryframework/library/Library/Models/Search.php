<?php
namespace Library\Models;
use Library\Models\Base\BaseClassModel;
use Library\Database;
class Search extends BaseClassModel {
    protected $id;
    protected $id_community;
    protected $done;
    protected $date_start;
    protected  $date_end;
    
    
   public function __construct() {
         parent::__construct();
   }
   
     /*
     * get community by field id_community
     */
    public function get_by_id_community(){
          $connection=Database::$connect;
          $table_names=Database::$options['tables'];
          $query="SELECT * FROM ".$table_names['Search']." Where `id_community`=".$this->id_community." ORDER BY  `id` DESC  LIMIT 1";
          $execute= $connection->prepare($query);
          $execute->execute();
          $answer= $execute->fetchAll();
          $this->setData($answer[0]);
          return $this;
    }
    public function get_by_id(){
          $query="SELECT * FROM ".Database::$options['tables']['Search']." WHERE `id`=".$this->id." ORDER BY  `id` DESC  LIMIT 1";
          $execute= Database::$connect->prepare($query);
          $execute->execute();
          $answer= $execute->fetchAll();
          $this->setData($answer[0]);
          return $this;
    }
    
    
    /*
     * get members groups rating.From most popular(count of our members)->less popular
     * return array of community + members_count(for every community)
     */
  
    /* OLD VERSION
     * public function get_by_percent($count){
            $query="    SELECT Groups. * , groups_ids.counts / Groups.members_count AS persent
                        FROM (  SELECT COUNT( connect.gid_community ) AS counts, connect. * 
                                FROM  `Users_In_Communities` AS connect
                                INNER JOIN (
                                            SELECT u. * 
                                            FROM  `".Database::$options['tables']['Users_In_Communities']."` AS connect
                                            LEFT OUTER JOIN  `".Database::$options['tables']['Community']."` AS gr ON gr.gid = connect.gid_community
                                            LEFT OUTER JOIN  `".Database::$options['tables']['User']."` AS u ON u.uid = connect.uid_user
                                            WHERE connect.gid_community =:community_id  ) AS Users ON Users.uid = connect.uid_user
                                GROUP BY connect.gid_community
                                ORDER BY counts DESC ) AS groups_ids
                        INNER JOIN  `".Database::$options['tables']['Community']."` AS Groups ON Groups.gid = groups_ids.gid_community
                        WHERE groups_ids.gid_community <> :community_id
                        AND   groups_ids.counts / Groups.members_count <=1
                        AND   Groups.members_count >= 5
                        ORDER BY persent DESC 
                     LIMIT :count ";
        
            $execute= Database::$connect->prepare($query);
            $execute->bindValue(':community_id', $this->id_community,\PDO::PARAM_INT);        
            $execute->bindValue(':count', +$count,\PDO::PARAM_INT);        
            $execute->execute();
            $answer= $execute->fetchAll();
            return $answer;
    }
    
    
    public function get_by_count_members($count){
         $query="    SELECT groups.* FROM
                            (SELECT  connect.gid_community
                            FROM  `".Database::$options['tables']['Users_In_Communities']."` AS connect
                            INNER JOIN (    SELECT u . * 
                                            FROM  `".Database::$options['tables']['Users_In_Communities']."` AS connect
                                            LEFT OUTER JOIN  `".Database::$options['tables']['Community']."` AS gr ON gr.gid = connect.gid_community
                                            LEFT OUTER JOIN  `".Database::$options['tables']['User']."` AS u ON u.uid = connect.uid_user
                                            WHERE connect.gid_community =:community_id  ) AS Users ON Users.uid = connect.uid_user
                             GROUP BY connect.gid_community  ) as groups_id
                     INNER JOIN `".Database::$options['tables']['Community']."` AS groups ON groups.gid = groups_id.gid_community
                     ORDER BY groups.members_count DESC
                     LIMIT :count";
        
            $execute= Database::$connect->prepare($query);
            $execute->bindValue(':community_id', $this->id_community,\PDO::PARAM_INT);        
            $execute->bindValue(':count', +$count,\PDO::PARAM_INT);        
            $execute->execute();
            $answer= $execute->fetchAll();
            return $answer;
    }
    public function get_members_by_communities_count($count){
            $query="SELECT Count(Users.uid) as communities_count , Users.*
                    FROM (SELECT u. * 
                                FROM  `".Database::$options['tables']['Users_In_Communities']."` AS connect
                                LEFT OUTER JOIN  `".Database::$options['tables']['Community']."` AS gr ON gr.gid = connect.gid_community
                                LEFT OUTER JOIN  `".Database::$options['tables']['User']."` AS u ON u.uid = connect.uid_user
                                WHERE connect.gid_community = :community_id  ) AS Users
                    INNER JOIN `".Database::$options['tables']['Users_In_Communities']."` AS connect ON connect.uid_user= Users.uid
                    GROUP BY Users.uid
                    ORDER BY communities_count DESC 
                     LIMIT :count ";
         
            $execute= Database::$connect->prepare($query);
            $execute->bindValue(':community_id', $this->id_community,\PDO::PARAM_INT);        
            $execute->bindValue(':count', +$count,\PDO::PARAM_INT);        
            $execute->execute();
            $answer= $execute->fetchAll();
            return $answer;
    }
     * 
     */
    
    /*for new DB where every search answrers is in new table Users_In_Communities_*(search id)
     * 
     */
      public function get_by_percent($count){
            $query="    SELECT Groups. * , groups_ids.counts / Groups.members_count AS persent
                        FROM (
                            SELECT COUNT( connect.gid_community ) AS counts, connect. * 
                            FROM  `".Database::$options['tables']['Users_In_Communities']."` AS connect
                            GROUP BY connect.gid_community
                            ORDER BY counts DESC
                        ) AS groups_ids
                        INNER JOIN   `".Database::$options['tables']['Community']."` AS Groups ON Groups.gid = groups_ids.gid_community
                        WHERE groups_ids.gid_community <> :community_id
                        AND groups_ids.counts / Groups.members_count <=1
                        AND Groups.members_count >=5
                        ORDER BY persent DESC 
                        LIMIT :count ";
        
            $execute= Database::$connect->prepare($query);
            $execute->bindValue(':community_id', $this->id_community,\PDO::PARAM_INT);        
            $execute->bindValue(':count', +$count,\PDO::PARAM_INT);        
            $execute->execute();
            $answer= $execute->fetchAll();
            return $answer;
     }
       public function get_by_count_members($count){
             $query="   SELECT Groups. * ,groups_ids.counts
                        FROM (
                            SELECT COUNT( connect.gid_community ) AS counts, connect. * 
                            FROM  `".Database::$options['tables']['Users_In_Communities']."` AS connect
                            WHERE connect.gid_community <> :community_id
                            GROUP BY connect.gid_community
                            ORDER BY counts DESC
                            LIMIT :count
                        ) AS groups_ids
                        INNER JOIN  `".Database::$options['tables']['Community']."` AS Groups ON Groups.gid = groups_ids.gid_community
                     ";
        
            $execute= Database::$connect->prepare($query);
            $execute->bindValue(':community_id', $this->id_community,\PDO::PARAM_INT);        
            $execute->bindValue(':count', +$count,\PDO::PARAM_INT);        
            $execute->execute();
            $answer= $execute->fetchAll();
            return $answer;
           
           
       }
         public function get_members_by_communities_count($count){
            $query="SELECT   users.*,users_need.communities_count   FROM
						(SELECT Count(connect.uid_user) as communities_count,connect.uid_user as `uid` 
                               		         FROM   `".Database::$options['tables']['Users_In_Communities']."` AS connect
                                                 GROUP BY connect.uid_user
                                                  ORDER BY communities_count DESC 
                                                  LIMIT :count) as `users_need`
                     Inner JOIN  `".Database::$options['tables']['User']."` as users ON users.uid=users_need.uid";
         
            $execute= Database::$connect->prepare($query);
            $execute->bindValue(':count', +$count,\PDO::PARAM_INT);        
            $execute->execute();
            $answer= $execute->fetchAll();
            return $answer;
    }
  
   
    
}
