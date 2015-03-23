<div class="container">
    
    <div id="search_container">
  
            <div id="start">Поиск начался</div>
                <img id="logo" src=<?php echo "\"".$this->data['group']->getPhoto_big()."\""?></img>
            <div id="info">
              <div id="community_name"><?php echo $this->data['group']->getName()?></div>
                <h2>Your search key:</h2>
                     <div id="search_key"><?php echo $this->data['search']->getId()?></div>
                <h2>Community Id</h2>
                     <div id="community_gid"><?php echo $this->data['group']->getGid()?></div>
                <h2>Members Count</h2>
                     <div id="community_members_count"><?php echo $this->data['group']->getMembers_count()?></div>
                    
            </div>

 
        </div>

        
</div>