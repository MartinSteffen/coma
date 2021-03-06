USE coma2;
DROP TABLE IF EXISTS conference
;
 CREATE TABLE conference ( id int(11) NOT NULL auto_increment, name varchar(127) NOT NULL default '', homepage varchar(127) default NULL, description text, abstract_submission_deadline date default NULL, paper_submission_deadline date default NULL, review_deadline date default NULL, final_version_deadline date default NULL, notification date default NULL, conference_start date default NULL, conference_end date default NULL, min_reviews_per_paper int(11) default NULL, PRIMARY KEY (id) ) TYPE=InnoDB
;
DROP TABLE IF EXISTS person
;
 CREATE TABLE person ( id int(11) NOT NULL auto_increment, first_name varchar(127) default NULL, last_name varchar(127) NOT NULL default '', title varchar(32) default NULL, affiliation varchar(127) default NULL, email varchar(127) NOT NULL default '', phone_number varchar(20) default NULL, fax_number varchar(20) default NULL, street varchar(127) default NULL, postal_code varchar(20) default NULL, city varchar(127) default NULL, state varchar(127) default NULL, country varchar(127) default NULL, password varchar(127) NOT NULL default '', PRIMARY KEY (id), UNIQUE KEY email (email) ) TYPE=InnoDB
;
DROP TABLE IF EXISTS topic
;
 CREATE TABLE topic ( id int(11) NOT NULL auto_increment, conference_id int(11) NOT NULL default '0', name varchar(127) NOT NULL default '', PRIMARY KEY (id), KEY conference_id (conference_id), FOREIGN KEY (`conference_id`) REFERENCES `conference` (`id`) ON DELETE CASCADE ) TYPE=InnoDB
;
DROP TABLE IF EXISTS criterion
;
 CREATE TABLE criterion ( id int(11) NOT NULL auto_increment, conference_id int(11) NOT NULL default '0', name varchar(127) NOT NULL default '', description text, max_value int(11) default NULL, quality_rating int(11) default NULL, PRIMARY KEY (id), KEY conference_id (conference_id), FOREIGN KEY (`conference_id`) REFERENCES `conference` (`id`) ON DELETE CASCADE ) TYPE=InnoDB
;
DROP TABLE IF EXISTS paper
;
 CREATE TABLE paper ( id int(11) NOT NULL auto_increment, conference_id int(11) NOT NULL default '0', author_id int(11) NOT NULL default '0', title varchar(127) NOT NULL default '', abstract text, last_edited datetime default NULL, version int(11) default NULL, filename varchar(127) default NULL, state int(11) NOT NULL default '0', mime_type varchar(127) default NULL, PRIMARY KEY (id), KEY conference_id (conference_id), KEY author_id (author_id), FOREIGN KEY (`conference_id`) REFERENCES `conference` (`id`) ON DELETE CASCADE, FOREIGN KEY (`author_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ) TYPE=InnoDB
;
DROP TABLE IF EXISTS deniespaper
;
 CREATE TABLE deniespaper ( person_id int(11) NOT NULL default '0', paper_id int(11) NOT NULL default '0', PRIMARY KEY (person_id,paper_id), KEY person_id (person_id), KEY paper_id (paper_id), FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE, FOREIGN KEY (`paper_id`) REFERENCES `paper` (`id`) ON DELETE CASCADE ) TYPE=InnoDB
;
DROP TABLE IF EXISTS excludespaper
;
 CREATE TABLE excludespaper ( person_id int(11) NOT NULL default '0', paper_id int(11) NOT NULL default '0', PRIMARY KEY (person_id,paper_id), KEY person_id (person_id), KEY paper_id (paper_id), FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE, FOREIGN KEY (`paper_id`) REFERENCES `paper` (`id`) ON DELETE CASCADE ) TYPE=InnoDB
;
DROP TABLE IF EXISTS forum
;
 CREATE TABLE forum ( id int(11) NOT NULL auto_increment, conference_id int(11) NOT NULL default '0', title varchar(127) NOT NULL default '', forum_type int(11) NOT NULL default '0', paper_id int(11) default NULL, PRIMARY KEY (id), KEY conference_id (conference_id), KEY forum_type (forum_type), FOREIGN KEY (`conference_id`) REFERENCES `conference` (`id`) ON DELETE CASCADE ) TYPE=InnoDB
;
DROP TABLE IF EXISTS isabouttopic
;
 CREATE TABLE isabouttopic ( paper_id int(11) NOT NULL default '0', topic_id int(11) NOT NULL default '0', PRIMARY KEY (paper_id,topic_id), KEY paper_id (paper_id), KEY topic_id (topic_id), FOREIGN KEY (`paper_id`) REFERENCES `paper` (`id`) ON DELETE CASCADE, FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id`) ON DELETE CASCADE ) TYPE=InnoDB
;
DROP TABLE IF EXISTS iscoauthorof
;
 CREATE TABLE iscoauthorof ( person_id int(11) default NULL, paper_id int(11) NOT NULL default '0', name varchar(127) default NULL, KEY paper_id (paper_id), FOREIGN KEY (`paper_id`) REFERENCES `paper` (`id`) ON DELETE CASCADE ) TYPE=InnoDB
;
DROP TABLE IF EXISTS message
;
 CREATE TABLE message ( id int(11) NOT NULL auto_increment, forum_id int(11) default NULL, reply_to int(11) default NULL, sender_id int(11) NOT NULL default '0', send_time datetime default NULL, subject varchar(127) default NULL, text text, PRIMARY KEY (id), KEY sender_id (sender_id), FOREIGN KEY (`sender_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ) TYPE=InnoDB
;
DROP TABLE IF EXISTS preferspaper
;
 CREATE TABLE preferspaper ( person_id int(11) NOT NULL default '0', paper_id int(11) NOT NULL default '0', PRIMARY KEY (person_id,paper_id), KEY person_id (person_id), KEY paper_id (paper_id), FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE, FOREIGN KEY (`paper_id`) REFERENCES `paper` (`id`) ON DELETE CASCADE ) TYPE=InnoDB
;
DROP TABLE IF EXISTS preferstopic
;
 CREATE TABLE preferstopic ( person_id int(11) NOT NULL default '0', topic_id int(11) NOT NULL default '0', PRIMARY KEY (person_id,topic_id), KEY person_id (person_id), KEY topic_id (topic_id), FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE, FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id`) ON DELETE CASCADE ) TYPE=InnoDB
;
DROP TABLE IF EXISTS reviewreport
;
 CREATE TABLE reviewreport ( id int(11) NOT NULL auto_increment, paper_id int(11) NOT NULL default '0', reviewer_id int(11) NOT NULL default '0', summary text, remarks text, confidential text, PRIMARY KEY (id), KEY paper_id (paper_id), KEY reviewer_id (reviewer_id), FOREIGN KEY (`paper_id`) REFERENCES `paper` (`id`) ON DELETE CASCADE, FOREIGN KEY (`reviewer_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ) TYPE=InnoDB
;
DROP TABLE IF EXISTS rating
;
 CREATE TABLE rating ( review_id int(11) NOT NULL default '0', criterion_id int(11) NOT NULL default '0', grade int(11) NOT NULL default '0', comment text, PRIMARY KEY (review_id,criterion_id), KEY review_id (review_id), KEY criterion_id (criterion_id), FOREIGN KEY (`review_id`) REFERENCES `reviewreport` (`id`) ON DELETE CASCADE, FOREIGN KEY (`criterion_id`) REFERENCES `criterion` (`id`) ON DELETE CASCADE ) TYPE=InnoDB
;
DROP TABLE IF EXISTS role
;
 CREATE TABLE role ( conference_id int(11) NOT NULL default '0', person_id int(11) NOT NULL default '0', role_type int(11) NOT NULL default '0', state int(11) default NULL, PRIMARY KEY (conference_id,person_id,role_type), KEY conference_id (conference_id), KEY person_id (person_id), FOREIGN KEY (`conference_id`) REFERENCES `conference` (`id`) ON DELETE CASCADE, FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ) TYPE=InnoDB
;
DROP TABLE IF EXISTS modules
;
 CREATE TABLE modules ( module_id int(10) unsigned NOT NULL auto_increment, module varchar(15) NOT NULL default '', action varchar(20) default NULL, isLink tinyint(3) unsigned zerofill NOT NULL default '000', alternative varchar(31) default NULL, PRIMARY KEY (module_id), UNIQUE KEY module_id (module_id) ) TYPE=MyISAM
;
DROP TABLE IF EXISTS rights
;
 CREATE TABLE rights ( role_id int(10) unsigned NOT NULL default '0', module_id int(10) unsigned NOT NULL default '0' ) TYPE=MyISAM
;
DROP TABLE IF EXISTS roledescription
;
 CREATE TABLE roledescription ( role_id int(10) unsigned NOT NULL auto_increment, rolename varchar(20) NOT NULL default '', PRIMARY KEY (role_id), UNIQUE KEY role_id (role_id) ) TYPE=MyISAM
;
DROP TABLE IF EXISTS rejectedpapers
;
 CREATE TABLE rejectedpapers ( person_id  INT NOT NULL, paper_id   INT NOT NULL, PRIMARY KEY (person_id, paper_id), INDEX (person_id), INDEX (paper_id), FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE, FOREIGN KEY (paper_id) REFERENCES paper (id) ON DELETE CASCADE ) TYPE = INNODB
;
