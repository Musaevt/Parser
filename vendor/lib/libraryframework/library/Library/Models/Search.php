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
  
   
    
}
