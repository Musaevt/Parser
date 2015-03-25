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
    public function get_by_percent($count){
            $query=" SELECT  COUNT( gr.gid ) as our_members_count,gr.* from `".Database::$options['tables']['Users_In_Communities']."` as connect
                     LEFT OUTER JOIN 
                     (SELECT u.* from  `".Database::$options['tables']['Users_In_Communities']."` as connect
                     LEFT OUTER JOIN `".Database::$options['tables']['Community']."` as gr ON gr.gid=connect.gid_community
                     LEFT OUTER JOIN `".Database::$options['tables']['User']."` as u ON u.uid=connect.uid_user
                     WHERE connect.gid_community= :community_id ) as Users ON Users.uid=connect.uid_user
 
                     LEFT OUTER JOIN `".Database::$options['tables']['Community']."` as gr ON gr.gid=connect.gid_community   
 
                     WHERE gr.gid != :community_id
 
                     GROUP BY gr.gid 
                     ORDER BY COUNT( gr.gid ) DESC
                     LIMIT :count";
        
            $execute= Database::$connect->prepare($query);
            $execute->bindValue(':community_id', $this->id_community,\PDO::PARAM_INT);        
            $execute->bindValue(':count', $count,\PDO::PARAM_INT);        
            $execute->execute();
            $answer= $execute->fetchAll();
            return $answer;
    }
    
    
    public function get_by_count_members($count){
         $query=" SELECT gr.* from `".Database::$options['tables']['Users_In_Communities']."` as connect
                     LEFT OUTER JOIN 
                     (SELECT u.* from  `".Database::$options['tables']['Users_In_Communities']."` as connect
                     LEFT OUTER JOIN `".Database::$options['tables']['Community']."` as gr ON gr.gid=connect.gid_community
                     LEFT OUTER JOIN `".Database::$options['tables']['User']."` as u ON u.uid=connect.uid_user
                     WHERE connect.gid_community= :community_id ) as Users ON Users.uid=connect.uid_user
 
                     LEFT OUTER JOIN `".Database::$options['tables']['Community']."` as gr ON gr.gid=connect.gid_community   
 
                     WHERE gr.gid != :community_id
 
                     GROUP BY gr.gid 
                     ORDER BY gr.members_count  DESC
                     LIMIT :count";
        
            $execute= Database::$connect->prepare($query);
            $execute->bindValue(':community_id', $this->id_community,\PDO::PARAM_INT);        
            $execute->bindValue(':count', $count,\PDO::PARAM_INT);        
            $execute->execute();
            $answer= $execute->fetchAll();
            return $answer;
    }
  
   
    
}
