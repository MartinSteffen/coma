<?php
/**
 * Install Skript für Coma1
 * 
 * Stand der Tabellen 23.01.2005
 *
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/

/**
 * Wichtig, damit Config Dateie eingebunden werden kann
 *
 * @ignore
 */
define('IN_COMA1', true);
// MySQL Daten bereit stellen
require_once('./include/config.inc.php');

$conn = @mysql_connect($sqlServer, $sqlUser , $sqlPassword);
if (empty($conn)) {
  echo("Could not connect to MySQL Server $sqlServer!<br>");
  echo("Please check 'include/config.inc.php' for correct values!<br><br>");
}
if (!mysql_select_db($sqlDatabase, $conn)) {
  echo(mysql_error($conn).'<br>');
  echo("Please check 'include/config.inc.php' for correct values!<br><br>");
}

// BEGIN SQL DATA
$sql = '
-- Erstellungszeit: 23. Januar 2005 um 20:55
-- Server Version: 3.23.58
-- PHP-Version: 4.3.10
-- 
-- Datenbank: `coma1`
-- 

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `Conference`
-- 

DROP TABLE IF EXISTS `Conference`;
CREATE TABLE IF NOT EXISTS `Conference` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(127) NOT NULL default '',
  `homepage` varchar(127) default NULL,
  `description` text,
  `abstract_submission_deadline` date default NULL,
  `paper_submission_deadline` date default NULL,
  `review_deadline` date default NULL,
  `final_version_deadline` date default NULL,
  `notification` date default NULL,
  `conference_start` date default NULL,
  `conference_end` date default NULL,
  `min_reviews_per_paper` int(11) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=InnoDB;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `ConferenceConfig`
-- 

DROP TABLE IF EXISTS `ConferenceConfig`;
CREATE TABLE IF NOT EXISTS `ConferenceConfig` (
  `id` int(11) NOT NULL default '0',
  `default_reviews_per_paper` int(11) NOT NULL default '0',
  `min_number_of_papers` int(11) NOT NULL default '0',
  `max_number_of_papers` int(11) NOT NULL default '0',
  `critical_variance` float NOT NULL default '0.5',
  `auto_activate_account` int(11) NOT NULL default '1',
  `auto_open_paper_forum` int(11) NOT NULL default '1',
  `auto_add_reviewers` int(11) NOT NULL default '1',
  `number_of_auto_add_reviewers` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=InnoDB;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `Criterion`
-- 

DROP TABLE IF EXISTS `Criterion`;
CREATE TABLE IF NOT EXISTS `Criterion` (
  `id` int(11) NOT NULL auto_increment,
  `conference_id` int(11) NOT NULL default '0',
  `name` varchar(127) NOT NULL default '',
  `description` text,
  `max_value` int(11) default NULL,
  `quality_rating` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `conference_id` (`conference_id`)
) TYPE=InnoDB;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `DeniesPaper`
-- 

DROP TABLE IF EXISTS `DeniesPaper`;
CREATE TABLE IF NOT EXISTS `DeniesPaper` (
  `person_id` int(11) NOT NULL default '0',
  `paper_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`person_id`,`paper_id`),
  KEY `person_id` (`person_id`),
  KEY `paper_id` (`paper_id`)
) TYPE=InnoDB;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `Distribution`
-- 

DROP TABLE IF EXISTS `Distribution`;
CREATE TABLE IF NOT EXISTS `Distribution` (
  `paper_id` int(11) NOT NULL default '0',
  `reviewer_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`paper_id`,`reviewer_id`),
  KEY `paper_id` (`paper_id`),
  KEY `reviewer_id` (`reviewer_id`)
) TYPE=InnoDB;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `ExcludesPaper`
-- 

DROP TABLE IF EXISTS `ExcludesPaper`;
CREATE TABLE IF NOT EXISTS `ExcludesPaper` (
  `person_id` int(11) NOT NULL default '0',
  `paper_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`person_id`,`paper_id`),
  KEY `person_id` (`person_id`),
  KEY `paper_id` (`paper_id`)
) TYPE=InnoDB;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `Forum`
-- 

DROP TABLE IF EXISTS `Forum`;
CREATE TABLE IF NOT EXISTS `Forum` (
  `id` int(11) NOT NULL auto_increment,
  `conference_id` int(11) NOT NULL default '0',
  `title` varchar(127) NOT NULL default '',
  `forum_type` int(11) NOT NULL default '0',
  `paper_id` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `conference_id` (`conference_id`),
  KEY `forum_type` (`forum_type`)
) TYPE=InnoDB;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `IsAboutTopic`
-- 

DROP TABLE IF EXISTS `IsAboutTopic`;
CREATE TABLE IF NOT EXISTS `IsAboutTopic` (
  `paper_id` int(11) NOT NULL default '0',
  `topic_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`paper_id`,`topic_id`),
  KEY `paper_id` (`paper_id`),
  KEY `topic_id` (`topic_id`)
) TYPE=InnoDB;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `IsCoAuthorOf`
-- 

DROP TABLE IF EXISTS `IsCoAuthorOf`;
CREATE TABLE IF NOT EXISTS `IsCoAuthorOf` (
  `person_id` int(11) default NULL,
  `paper_id` int(11) NOT NULL default '0',
  `name` varchar(127) default NULL,
  KEY `paper_id` (`paper_id`)
) TYPE=InnoDB;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `Message`
-- 

DROP TABLE IF EXISTS `Message`;
CREATE TABLE IF NOT EXISTS `Message` (
  `id` int(11) NOT NULL auto_increment,
  `forum_id` int(11) default NULL,
  `reply_to` int(11) default NULL,
  `sender_id` int(11) NOT NULL default '0',
  `send_time` datetime default NULL,
  `subject` varchar(127) default NULL,
  `text` text,
  PRIMARY KEY  (`id`),
  KEY `sender_id` (`sender_id`)
) TYPE=InnoDB;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `Paper`
-- 

DROP TABLE IF EXISTS `Paper`;
CREATE TABLE IF NOT EXISTS `Paper` (
  `id` int(11) NOT NULL auto_increment,
  `conference_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `title` varchar(127) NOT NULL default '',
  `abstract` text,
  `last_edited` datetime default NULL,
  `version` int(11) default NULL,
  `filename` varchar(127) default NULL,
  `state` int(11) NOT NULL default '0',
  `mime_type` varchar(127) default NULL,
  PRIMARY KEY  (`id`),
  KEY `conference_id` (`conference_id`),
  KEY `author_id` (`author_id`)
) TYPE=InnoDB;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `Person`
-- 

DROP TABLE IF EXISTS `Person`;
CREATE TABLE IF NOT EXISTS `Person` (
  `id` int(11) NOT NULL auto_increment,
  `first_name` varchar(127) default NULL,
  `last_name` varchar(127) NOT NULL default '',
  `title` varchar(32) default NULL,
  `affiliation` varchar(127) default NULL,
  `email` varchar(127) NOT NULL default '',
  `phone_number` varchar(20) default NULL,
  `fax_number` varchar(20) default NULL,
  `street` varchar(127) default NULL,
  `postal_code` varchar(20) default NULL,
  `city` varchar(127) default NULL,
  `state` varchar(127) default NULL,
  `country` varchar(127) default NULL,
  `password` varchar(127) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`)
) TYPE=InnoDB;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `PrefersPaper`
-- 

DROP TABLE IF EXISTS `PrefersPaper`;
CREATE TABLE IF NOT EXISTS `PrefersPaper` (
  `person_id` int(11) NOT NULL default '0',
  `paper_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`person_id`,`paper_id`),
  KEY `person_id` (`person_id`),
  KEY `paper_id` (`paper_id`)
) TYPE=InnoDB;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `PrefersTopic`
-- 

DROP TABLE IF EXISTS `PrefersTopic`;
CREATE TABLE IF NOT EXISTS `PrefersTopic` (
  `person_id` int(11) NOT NULL default '0',
  `topic_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`person_id`,`topic_id`),
  KEY `person_id` (`person_id`),
  KEY `topic_id` (`topic_id`)
) TYPE=InnoDB;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `Rating`
-- 

DROP TABLE IF EXISTS `Rating`;
CREATE TABLE IF NOT EXISTS `Rating` (
  `review_id` int(11) NOT NULL default '0',
  `criterion_id` int(11) NOT NULL default '0',
  `grade` int(11) NOT NULL default '0',
  `comment` text,
  PRIMARY KEY  (`review_id`,`criterion_id`),
  KEY `review_id` (`review_id`),
  KEY `criterion_id` (`criterion_id`)
) TYPE=InnoDB;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `ReviewReport`
-- 

DROP TABLE IF EXISTS `ReviewReport`;
CREATE TABLE IF NOT EXISTS `ReviewReport` (
  `id` int(11) NOT NULL auto_increment,
  `paper_id` int(11) NOT NULL default '0',
  `reviewer_id` int(11) NOT NULL default '0',
  `summary` text,
  `remarks` text,
  `confidential` text,
  PRIMARY KEY  (`id`),
  KEY `paper_id` (`paper_id`),
  KEY `reviewer_id` (`reviewer_id`)
) TYPE=InnoDB;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `Role`
-- 

DROP TABLE IF EXISTS `Role`;
CREATE TABLE IF NOT EXISTS `Role` (
  `conference_id` int(11) NOT NULL default '0',
  `person_id` int(11) NOT NULL default '0',
  `role_type` int(11) NOT NULL default '0',
  `state` int(11) default NULL,
  PRIMARY KEY  (`conference_id`,`person_id`,`role_type`),
  KEY `conference_id` (`conference_id`),
  KEY `person_id` (`person_id`)
) TYPE=InnoDB;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `Session`
-- 

DROP TABLE IF EXISTS `Session`;
CREATE TABLE IF NOT EXISTS `Session` (
  `sid` varchar(255) NOT NULL default '',
  `sname` varchar(25) NOT NULL default '',
  `sdata` text,
  `stime` timestamp(14) NOT NULL,
  PRIMARY KEY  (`sid`,`sname`),
  KEY `stime` (`stime`)
) TYPE=MyISAM COMMENT='Session-Verwaltung';

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `Topic`
-- 

DROP TABLE IF EXISTS `Topic`;
CREATE TABLE IF NOT EXISTS `Topic` (
  `id` int(11) NOT NULL auto_increment,
  `conference_id` int(11) NOT NULL default '0',
  `name` varchar(127) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `conference_id` (`conference_id`)
) TYPE=InnoDB;

-- 
-- Constraints der exportierten Tabellen
-- 

-- 
-- Constraints der Tabelle `Criterion`
-- 
ALTER TABLE `Criterion`
  ADD FOREIGN KEY (`conference_id`) REFERENCES `coma1.Conference` (`id`) ON DELETE CASCADE;

-- 
-- Constraints der Tabelle `DeniesPaper`
-- 
ALTER TABLE `DeniesPaper`
  ADD FOREIGN KEY (`person_id`) REFERENCES `coma1.Person` (`id`) ON DELETE CASCADE,
  ADD FOREIGN KEY (`paper_id`) REFERENCES `coma1.Paper` (`id`) ON DELETE CASCADE;

-- 
-- Constraints der Tabelle `Distribution`
-- 
ALTER TABLE `Distribution`
  ADD FOREIGN KEY (`paper_id`) REFERENCES `coma1.Paper` (`id`) ON DELETE CASCADE,
  ADD FOREIGN KEY (`reviewer_id`) REFERENCES `coma1.Person` (`id`) ON DELETE CASCADE;

-- 
-- Constraints der Tabelle `ExcludesPaper`
-- 
ALTER TABLE `ExcludesPaper`
  ADD FOREIGN KEY (`person_id`) REFERENCES `coma1.Person` (`id`) ON DELETE CASCADE,
  ADD FOREIGN KEY (`paper_id`) REFERENCES `coma1.Paper` (`id`) ON DELETE CASCADE;

-- 
-- Constraints der Tabelle `Forum`
-- 
ALTER TABLE `Forum`
  ADD FOREIGN KEY (`conference_id`) REFERENCES `coma1.Conference` (`id`) ON DELETE CASCADE;

-- 
-- Constraints der Tabelle `IsAboutTopic`
-- 
ALTER TABLE `IsAboutTopic`
  ADD FOREIGN KEY (`paper_id`) REFERENCES `coma1.Paper` (`id`) ON DELETE CASCADE,
  ADD FOREIGN KEY (`topic_id`) REFERENCES `coma1.Topic` (`id`) ON DELETE CASCADE;

-- 
-- Constraints der Tabelle `IsCoAuthorOf`
-- 
ALTER TABLE `IsCoAuthorOf`
  ADD FOREIGN KEY (`paper_id`) REFERENCES `coma1.Paper` (`id`) ON DELETE CASCADE;

-- 
-- Constraints der Tabelle `Message`
-- 
ALTER TABLE `Message`
  ADD FOREIGN KEY (`sender_id`) REFERENCES `coma1.Person` (`id`) ON DELETE CASCADE;

-- 
-- Constraints der Tabelle `Paper`
-- 
ALTER TABLE `Paper`
  ADD FOREIGN KEY (`conference_id`) REFERENCES `coma1.Conference` (`id`) ON DELETE CASCADE,
  ADD FOREIGN KEY (`author_id`) REFERENCES `coma1.Person` (`id`) ON DELETE CASCADE;

-- 
-- Constraints der Tabelle `PrefersPaper`
-- 
ALTER TABLE `PrefersPaper`
  ADD FOREIGN KEY (`person_id`) REFERENCES `coma1.Person` (`id`) ON DELETE CASCADE,
  ADD FOREIGN KEY (`paper_id`) REFERENCES `coma1.Paper` (`id`) ON DELETE CASCADE;

-- 
-- Constraints der Tabelle `PrefersTopic`
-- 
ALTER TABLE `PrefersTopic`
  ADD FOREIGN KEY (`person_id`) REFERENCES `coma1.Person` (`id`) ON DELETE CASCADE,
  ADD FOREIGN KEY (`topic_id`) REFERENCES `coma1.Topic` (`id`) ON DELETE CASCADE;

-- 
-- Constraints der Tabelle `Rating`
-- 
ALTER TABLE `Rating`
  ADD FOREIGN KEY (`review_id`) REFERENCES `coma1.ReviewReport` (`id`) ON DELETE CASCADE,
  ADD FOREIGN KEY (`criterion_id`) REFERENCES `coma1.Criterion` (`id`) ON DELETE CASCADE;

-- 
-- Constraints der Tabelle `ReviewReport`
-- 
ALTER TABLE `ReviewReport`
  ADD FOREIGN KEY (`paper_id`) REFERENCES `coma1.Paper` (`id`) ON DELETE CASCADE,
  ADD FOREIGN KEY (`reviewer_id`) REFERENCES `coma1.Person` (`id`) ON DELETE CASCADE;

-- 
-- Constraints der Tabelle `Role`
-- 
ALTER TABLE `Role`
  ADD FOREIGN KEY (`conference_id`) REFERENCES `coma1.Conference` (`id`) ON DELETE CASCADE,
  ADD FOREIGN KEY (`person_id`) REFERENCES `coma1.Person` (`id`) ON DELETE CASCADE;

-- 
-- Constraints der Tabelle `Topic`
-- 
ALTER TABLE `Topic`
  ADD FOREIGN KEY (`conference_id`) REFERENCES `coma1.Conference` (`id`) ON DELETE CASCADE;

';
// END SQL DATA

$result = mysql_query($sql, $conn);
if (empty($result)) {
  echo('Creating Tables:<br>');
  echo(mysql_error($this->mySqlConnection).'<br><br>');
}
?>