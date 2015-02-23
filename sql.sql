CREATE TABLE IF NOT EXISTS `vk_Users` (

  `id` 				int(11) NOT NULL AUTO_INCREMENT,
  `uid`			    int(11)      DEFAULT NULL,
  `first_name`		varchar(250) DEFAULT NULL,
  `last_name` 		varchar(250) DEFAULT NULL,
  `deactivated`	    varchar(40) DEFAULT NULL,
  `hidden` 			varchar(40) DEFAULT NULL,
  `photo_id`	    varchar(250) DEFAULT NULL,
  `verified` 		int(11) DEFAULT NULL,
  `blacklisted` 	int(11) DEFAULT NULL,
  `sex`			    int(11) DEFAULT NULL,
  `bdate` 			DATE DEFAULT NULL,
  `city` 			int(11) DEFAULT NULL,
  /*
  id
  title
  */
  `country`		    int(11) DEFAULT NULL,
  /*
	id
	title
  */

  `home_town` 		varchar(250) DEFAULT NULL,
  `photo_max_orig`  varchar(1000) DEFAULT NULL,
  `online` 			varchar(4) DEFAULT NULL,
  `lists` 			int(11) DEFAULT NULL,
  /*
  разделенные запятой идентификаторы списков друзей, в которых состоит пользователь.
   Поле доступно только для метода friends.get. 
  */
  `domain` 			varchar(250) DEFAULT NULL,
  `has_mobile`	    int(4) DEFAULT NULL,
  `contacts` 		int(11) DEFAULT NULL,
  /*
  mobile_phone 
  home_phone 

  */
  `site` 			varchar(1000) DEFAULT NULL,

  `education` 		int(11) DEFAULT NULL,
  /*
  university  
  university_name  
  faculty 
  faculty_name 
  graduation 
  */
 /* 
    `universities`    varchar(40) DEFAULT NULL,
	список высших учебных заведений, в которых учился текущий пользователь. Возвращается массив universities, содержащий объекты university со следующими полями:
	
	id — идентификатор университета; 
	country — идентификатор страны, в которой расположен университет; 
	city — идентификатор города, в котором расположен университет; 
	name — наименование университета; 
	faculty — идентификатор факультета; 
	faculty_name — наименование факультета; 
	chair — идентификатор кафедры; 
	chair_name — наименование кафедры; 
	graduation — год окончания обучения. 


  `schools` 		int(11) DEFAULT NULL,
  список школ, в которых учился пользователь.

     id — идентификатор школы; 
	country — идентификатор страны, в которой расположена школа; 
	city — идентификатор города, в котором расположена школа; 
	name — наименование школы; 
	year_from — год начала обучения; 
	year_to — год окончания обучения; 
	year_graduated — год выпуска; 
	class — буква класса; 
	speciality — специализация; 
	type — идентификатор типа; 
	type_str — название типа. 
  */
  `status`		    varchar(40) DEFAULT NULL,
  `last_seen` 		int(11) DEFAULT NULL,
  /*
  time 
  platform 
  */
  `followers_count` int(11) DEFAULT NULL,
  `groups_count`    INT( 11 )  DEFAULT NULL,
  `friends_count`    INT(11)  DEFAULT NULL
  `occupation`	    int(11) DEFAULT NULL,
  /* информация о текущем роде занятия пользователя.
  type 
  id 
  name 
  */
  `nickname`       varchar(250) DEFAULT NULL,
  
  `relation`	    int(11) DEFAULT NULL,
  `activities` 		varchar(250) DEFAULT NULL,
  `interests`	    varchar(250) DEFAULT NULL,
  `music` 			varchar(250) DEFAULT NULL,
  `about` 			varchar(250) DEFAULT NULL,
  `is_parsed`        BOOLEAN NOT NULL DEFAULT FALSE,
   `gid_by_added_group` int (11)  DEFAULT NULL

   PRIMARY KEY (`id`),
   UNIQUE (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `vk_Groups` (

  `id` 					int(11) NOT NULL AUTO_INCREMENT,
  `gid` 				int(11)    DEFAULT NULL,
  `name`			    varchar(250) DEFAULT NULL,
  `screen_name`			varchar(250) DEFAULT NULL,
  `is_closed` 			int(11) DEFAULT NULL,
  `deactivated`	  	    varchar(40) DEFAULT NULL,
  `type` 				varchar(40) DEFAULT NULL,
  `photo_big`	  	    varchar(250) DEFAULT NULL,
  `start_date` 			DATE        DEFAULT NULL, 
  `city` 				varchar(60)  DEFAULT NULL,
  `country`			    varchar(60)      DEFAULT NULL,
  `description`			varchar(2000) DEFAULT NULL,
  `wiki_page` 			varchar(250) DEFAULT NULL,
  `members_count`	    int(11) DEFAULT NULL,
  `status` 				varchar(250) DEFAULT NULL,
  /*
  `links`	    		int(250) DEFAULT NULL,
  */
  `verified` 			int(5) DEFAULT NULL, 
  `site` 				varchar(250) DEFAULT NULL, 
  `contacts` 			int(40) DEFAULT NULL, 
  PRIMARY KEY (`id`),
  UNIQUE (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `Users_In_Groups`(
`gid`			    int(11)     ,
`uid`			    int(11)     ,
PRIMARY KEY (`gid`,`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
