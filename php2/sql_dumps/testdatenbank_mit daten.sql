# phpMyAdmin MySQL-Dump
# version 2.2.3
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
#
# Host: localhost
# Erstellungszeit: 29. Dez 2004 um 17:52
# Server Version: 3.23.47
# PHP Version: 4.1.1
# Datenbank : `coma`
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `conference`
#

DROP TABLE IF EXISTS `conference`;
CREATE TABLE `conference` (
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
) TYPE=MyISAM;

#
# Daten für Tabelle `conference`
#

INSERT INTO `conference` (`id`, `name`, `homepage`, `description`, `abstract_submission_deadline`, `paper_submission_deadline`, `review_deadline`, `final_version_deadline`, `notification`, `conference_start`, `conference_end`, `min_reviews_per_paper`) VALUES (2, 'Fuchsjagd', 'www.f.jg', 'Wenn der Fuchs den Jäger beisst', '2005-02-11', NULL, '2006-02-11', '2007-02-11', '2007-05-05', '2008-02-11', '2008-05-05', 2);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `criterion`
#

DROP TABLE IF EXISTS `criterion`;
CREATE TABLE `criterion` (
  `id` int(11) NOT NULL auto_increment,
  `conference_id` int(11) NOT NULL default '0',
  `name` varchar(127) NOT NULL default '',
  `description` text,
  `max_value` int(11) default NULL,
  `quality_rating` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `conference_id` (`conference_id`)
) TYPE=MyISAM;

#
# Daten für Tabelle `criterion`
#

INSERT INTO `criterion` (`id`, `conference_id`, `name`, `description`, `max_value`, `quality_rating`) VALUES (1, 2, 'style', 'kjllkjsdlfkj', 100, 20);
INSERT INTO `criterion` (`id`, `conference_id`, `name`, `description`, `max_value`, `quality_rating`) VALUES (2, 2, 'sprache', 'ioliol', 100, 80);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `deniespaper`
#

DROP TABLE IF EXISTS `deniespaper`;
CREATE TABLE `deniespaper` (
  `person_id` int(11) NOT NULL default '0',
  `paper_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`person_id`,`paper_id`),
  KEY `person_id` (`person_id`),
  KEY `paper_id` (`paper_id`)
) TYPE=MyISAM;

#
# Daten für Tabelle `deniespaper`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `excludespaper`
#

DROP TABLE IF EXISTS `excludespaper`;
CREATE TABLE `excludespaper` (
  `person_id` int(11) NOT NULL default '0',
  `paper_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`person_id`,`paper_id`),
  KEY `person_id` (`person_id`),
  KEY `paper_id` (`paper_id`)
) TYPE=MyISAM;

#
# Daten für Tabelle `excludespaper`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `forum`
#

DROP TABLE IF EXISTS `forum`;
CREATE TABLE `forum` (
  `id` int(11) NOT NULL auto_increment,
  `conference_id` int(11) NOT NULL default '0',
  `title` varchar(127) NOT NULL default '',
  `forum_type` int(11) NOT NULL default '0',
  `paper_id` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `conference_id` (`conference_id`),
  KEY `forum_type` (`forum_type`)
) TYPE=MyISAM;

#
# Daten für Tabelle `forum`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `isabouttopic`
#

DROP TABLE IF EXISTS `isabouttopic`;
CREATE TABLE `isabouttopic` (
  `paper_id` int(11) NOT NULL default '0',
  `topic_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`paper_id`,`topic_id`),
  KEY `paper_id` (`paper_id`),
  KEY `topic_id` (`topic_id`)
) TYPE=MyISAM;

#
# Daten für Tabelle `isabouttopic`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `iscoauthorof`
#

DROP TABLE IF EXISTS `iscoauthorof`;
CREATE TABLE `iscoauthorof` (
  `person_id` int(11) default NULL,
  `paper_id` int(11) NOT NULL default '0',
  `name` varchar(127) default NULL,
  KEY `paper_id` (`paper_id`)
) TYPE=MyISAM;

#
# Daten für Tabelle `iscoauthorof`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `message`
#

DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `id` int(11) NOT NULL auto_increment,
  `forum_id` int(11) default NULL,
  `reply_to` int(11) default NULL,
  `sender_id` int(11) NOT NULL default '0',
  `send_time` datetime default NULL,
  `subject` varchar(127) default NULL,
  `text` text,
  PRIMARY KEY  (`id`),
  KEY `sender_id` (`sender_id`)
) TYPE=MyISAM;

#
# Daten für Tabelle `message`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `modules`
#

DROP TABLE IF EXISTS `modules`;
CREATE TABLE `modules` (
  `module_id` int(10) unsigned NOT NULL auto_increment,
  `module` varchar(15) NOT NULL default '',
  `action` varchar(20) default NULL,
  `isLink` tinyint(3) unsigned zerofill NOT NULL default '000',
  `alternative` varchar(31) default NULL,
  PRIMARY KEY  (`module_id`),
  UNIQUE KEY `module_id` (`module_id`)
) TYPE=MyISAM;

#
# Daten für Tabelle `modules`
#

INSERT INTO `modules` (`module_id`, `module`, `action`, `isLink`, `alternative`) VALUES (1, 'session', 'show', 001, 'show session');
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `paper`
#

DROP TABLE IF EXISTS `paper`;
CREATE TABLE `paper` (
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
) TYPE=MyISAM;

#
# Daten für Tabelle `paper`
#

INSERT INTO `paper` (`id`, `conference_id`, `author_id`, `title`, `abstract`, `last_edited`, `version`, `filename`, `state`, `mime_type`) VALUES (1, 2, 2, 'uilkik', 'ujk,k,', '2004-12-29 16:09:43', 1, NULL, 1, NULL);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `person`
#

DROP TABLE IF EXISTS `person`;
CREATE TABLE `person` (
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
) TYPE=MyISAM;

#
# Daten für Tabelle `person`
#

INSERT INTO `person` (`id`, `first_name`, `last_name`, `title`, `affiliation`, `email`, `phone_number`, `fax_number`, `street`, `postal_code`, `city`, `state`, `country`, `password`) VALUES (1, NULL, 'Reinecke', NULL, NULL, 'reinecke@f.jg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'gans');
INSERT INTO `person` (`id`, `first_name`, `last_name`, `title`, `affiliation`, `email`, `phone_number`, `fax_number`, `street`, `postal_code`, `city`, `state`, `country`, `password`) VALUES (2, NULL, 'Isegrim', NULL, NULL, 'isi@wolf.ca', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'wolf');
INSERT INTO `person` (`id`, `first_name`, `last_name`, `title`, `affiliation`, `email`, `phone_number`, `fax_number`, `street`, `postal_code`, `city`, `state`, `country`, `password`) VALUES (3, NULL, 'Adebar', NULL, NULL, 'Addi@entbindungsstation-berlin-tempelhof.de.vu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'storch');
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `preferspaper`
#

DROP TABLE IF EXISTS `preferspaper`;
CREATE TABLE `preferspaper` (
  `person_id` int(11) NOT NULL default '0',
  `paper_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`person_id`,`paper_id`),
  KEY `person_id` (`person_id`),
  KEY `paper_id` (`paper_id`)
) TYPE=MyISAM;

#
# Daten für Tabelle `preferspaper`
#

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `preferstopic`
#

DROP TABLE IF EXISTS `preferstopic`;
CREATE TABLE `preferstopic` (
  `person_id` int(11) NOT NULL default '0',
  `topic_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`person_id`,`topic_id`),
  KEY `person_id` (`person_id`),
  KEY `topic_id` (`topic_id`)
) TYPE=MyISAM;

#
# Daten für Tabelle `preferstopic`
#

INSERT INTO `preferstopic` (`person_id`, `topic_id`) VALUES (1, 1);
INSERT INTO `preferstopic` (`person_id`, `topic_id`) VALUES (1, 2);
INSERT INTO `preferstopic` (`person_id`, `topic_id`) VALUES (2, 1);
INSERT INTO `preferstopic` (`person_id`, `topic_id`) VALUES (2, 4);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `rating`
#

DROP TABLE IF EXISTS `rating`;
CREATE TABLE `rating` (
  `review_id` int(11) NOT NULL default '0',
  `criterion_id` int(11) NOT NULL default '0',
  `grade` int(11) NOT NULL default '0',
  `comment` text,
  PRIMARY KEY  (`review_id`,`criterion_id`),
  KEY `review_id` (`review_id`),
  KEY `criterion_id` (`criterion_id`)
) TYPE=MyISAM;

#
# Daten für Tabelle `rating`
#

INSERT INTO `rating` (`review_id`, `criterion_id`, `grade`, `comment`) VALUES (1, 1, 89, 'sdfsdf');
INSERT INTO `rating` (`review_id`, `criterion_id`, `grade`, `comment`) VALUES (1, 2, 78, 'sdfrgdfg');
INSERT INTO `rating` (`review_id`, `criterion_id`, `grade`, `comment`) VALUES (2, 1, 34, 'sdfsdf');
INSERT INTO `rating` (`review_id`, `criterion_id`, `grade`, `comment`) VALUES (2, 2, 45, 'asdasd');
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `reviewreport`
#

DROP TABLE IF EXISTS `reviewreport`;
CREATE TABLE `reviewreport` (
  `id` int(11) NOT NULL auto_increment,
  `paper_id` int(11) NOT NULL default '0',
  `reviewer_id` int(11) NOT NULL default '0',
  `summary` text,
  `remarks` text,
  `confidential` text,
  PRIMARY KEY  (`id`),
  KEY `paper_id` (`paper_id`),
  KEY `reviewer_id` (`reviewer_id`)
) TYPE=MyISAM;

#
# Daten für Tabelle `reviewreport`
#

INSERT INTO `reviewreport` (`id`, `paper_id`, `reviewer_id`, `summary`, `remarks`, `confidential`) VALUES (1, 1, 1, NULL, NULL, NULL);
INSERT INTO `reviewreport` (`id`, `paper_id`, `reviewer_id`, `summary`, `remarks`, `confidential`) VALUES (3, 1, 2, NULL, NULL, NULL);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `rights`
#

DROP TABLE IF EXISTS `rights`;
CREATE TABLE `rights` (
  `role_id` int(10) unsigned NOT NULL default '0',
  `module_id` int(10) unsigned NOT NULL default '0'
) TYPE=MyISAM;

#
# Daten für Tabelle `rights`
#

INSERT INTO `rights` (`role_id`, `module_id`) VALUES (1, 1);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `role`
#

DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `conference_id` int(11) NOT NULL default '0',
  `person_id` int(11) NOT NULL default '0',
  `role_type` int(11) NOT NULL default '0',
  `state` int(11) default NULL,
  PRIMARY KEY  (`conference_id`,`person_id`,`role_type`),
  KEY `conference_id` (`conference_id`),
  KEY `person_id` (`person_id`)
) TYPE=MyISAM;

#
# Daten für Tabelle `role`
#

INSERT INTO `role` (`conference_id`, `person_id`, `role_type`, `state`) VALUES (2, 1, 1, 1);
INSERT INTO `role` (`conference_id`, `person_id`, `role_type`, `state`) VALUES (2, 1, 2, 1);
INSERT INTO `role` (`conference_id`, `person_id`, `role_type`, `state`) VALUES (2, 1, 4, 1);
INSERT INTO `role` (`conference_id`, `person_id`, `role_type`, `state`) VALUES (2, 2, 3, 1);
INSERT INTO `role` (`conference_id`, `person_id`, `role_type`, `state`) VALUES (2, 1, 3, 1);
INSERT INTO `role` (`conference_id`, `person_id`, `role_type`, `state`) VALUES (2, 3, 4, 1);
INSERT INTO `role` (`conference_id`, `person_id`, `role_type`, `state`) VALUES (2, 2, 4, 1);
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `roles`
#

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `role_id` tinyint(4) NOT NULL default '0',
  `role_name` varchar(127) NOT NULL default '',
  PRIMARY KEY  (`role_id`)
) TYPE=MyISAM;

#
# Daten für Tabelle `roles`
#

INSERT INTO `roles` (`role_id`, `role_name`) VALUES (1, 'Admin');
INSERT INTO `roles` (`role_id`, `role_name`) VALUES (2, 'Chair');
INSERT INTO `roles` (`role_id`, `role_name`) VALUES (3, 'Reviewer');
INSERT INTO `roles` (`role_id`, `role_name`) VALUES (4, 'Author');
INSERT INTO `roles` (`role_id`, `role_name`) VALUES (5, 'Guest');
# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `topic`
#

DROP TABLE IF EXISTS `topic`;
CREATE TABLE `topic` (
  `id` int(11) NOT NULL auto_increment,
  `conference_id` int(11) NOT NULL default '0',
  `name` varchar(127) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `conference_id` (`conference_id`)
) TYPE=MyISAM;

#
# Daten für Tabelle `topic`
#

INSERT INTO `topic` (`id`, `conference_id`, `name`) VALUES (1, 2, 'Pferdequälerei auf der Fuchsjagd');
INSERT INTO `topic` (`id`, `conference_id`, `name`) VALUES (2, 2, 'Techniken des Blattschusses in Theorie und Praxis am Jäger');
INSERT INTO `topic` (`id`, `conference_id`, `name`) VALUES (3, 2, 'Das Verspeisen des Jägers am Abend und die gastrischen Folgen (Blähungen)');
INSERT INTO `topic` (`id`, `conference_id`, `name`) VALUES (4, 2, 'Elementare Quantenphysik');

    