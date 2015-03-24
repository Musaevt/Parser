CREATE TABLE IF NOT EXISTS `Search` (

  `id` 					int(11) NOT NULL AUTO_INCREMENT,
  `id_community`    	int(11)    DEFAULT NULL,
  `done`			    BOOL DEFAULT false,
  `date_start`		    	  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 `date_end`                      DATETIME NULL,

  PRIMARY KEY (`id`)
) ENGINE=MyISAM  CHARSET=utf8 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `Community` (

  `id` 					int(11) NOT NULL AUTO_INCREMENT,
  `gid` 				int(11)    DEFAULT NULL,
  `name`                                varchar(250) DEFAULT NULL,
  `screen_name`                         varchar(250) DEFAULT NULL,
  `is_closed` 			int(11) DEFAULT NULL,
  `deactivated`	  	    varchar(40) DEFAULT NULL,
  `type` 				varchar(40) DEFAULT NULL,
  `photo_big`	  	    varchar(1000) DEFAULT NULL,
  `start_date` 			DATE        DEFAULT NULL, 
  `city` 				varchar(60)  DEFAULT NULL,
  `country`			    varchar(60)      DEFAULT NULL,
  `description`			varchar(2000) DEFAULT NULL,
  `wiki_page` 			varchar(250) DEFAULT NULL,
  `members_count`	    int(11) DEFAULT NULL,
  `status` 				varchar(250) DEFAULT NULL,
  `verified` 			int(11) DEFAULT NULL, 
  `site` 				varchar(250) DEFAULT NULL, 
  `contacts` 			int(40) DEFAULT NULL, 
  `date_update`         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE (`gid`)
) ENGINE=MyISAM  CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `User` (

  `id` 				int(11) NOT NULL AUTO_INCREMENT,
  `uid`			    int(11)      DEFAULT NULL,
  `first_name`		varchar(250) DEFAULT NULL,
  `last_name` 		varchar(250) DEFAULT NULL,
  `deactivated`	    varchar(40) DEFAULT NULL,
  `hidden` 			varchar(40) DEFAULT NULL,
  `photo_max_orig`  varchar(1000) DEFAULT NULL,
  `verified` 		bool DEFAULT false,
  `sex`			    int(11) DEFAULT NULL,
  `bdate` 			DATE DEFAULT NULL,
  `city` 			int(11) DEFAULT NULL,
  `country`		    int(11) DEFAULT NULL,
  `home_town` 		varchar(40) DEFAULT NULL,
  `domain` 			varchar(250) DEFAULT NULL,
  `has_mobile`	    bool DEFAULT false,
  `mobile_phone` 	varchar(40) DEFAULT NULL,
  `site` 			varchar(1000) DEFAULT NULL,
  `status`		    varchar(40) DEFAULT NULL,
  `followers_count` int(11) DEFAULT NULL,
  `groups_count`    INT( 11 )  DEFAULT NULL,
  `friends_count`    INT(11)  DEFAULT NULL,
  `occupation`	    int(11) DEFAULT NULL,
  `nickname`       varchar(250) DEFAULT NULL,
  `relation`	    int(11) DEFAULT NULL,
  `activities` 		varchar(250) DEFAULT NULL,
  `interests`	    varchar(250) DEFAULT NULL,
  `music` 			varchar(250) DEFAULT NULL,
  `about` 			varchar(1000) DEFAULT NULL,
   `date_update`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`),
   UNIQUE (`uid`)
) ENGINE=MyISAM  CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `Users_In_Communities` (

  `id` 				int(11) NOT NULL AUTO_INCREMENT,
  `gid_community`         int(11)      DEFAULT NULL,
  `uid_user`		    int(11)      DEFAULT NULL,
  `date` 	   	  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
   PRIMARY KEY (`id`),
   UNIQUE (`gid_community`,`uid_user`)
 ) ENGINE=MyISAM  CHARSET=utf8 AUTO_INCREMENT=1 ;



TRUNCATE TABLE  `Community`;
TRUNCATE TABLE  `Search`;
TRUNCATE TABLE  `User`;
