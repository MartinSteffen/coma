-- phpMyAdmin SQL Dump
-- version 2.6.0-pl3
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 16. Januar 2005 um 15:39
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

-- 
-- Daten für Tabelle `Conference`
-- 

INSERT INTO `Conference` VALUES (1, 'Rammlerzuchtveranstaltung RamCon 2005', 'http://ramcon.org', '', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', 0);
INSERT INTO `Conference` VALUES (2, 'Schneckenjagd', 'http://flitz.org', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `Conference` VALUES (3, 'Gummihuhnsammler', 'www.huhn.de', 'Sammler von GummihÃ¼hnern', '1913-01-01', '1914-12-15', '2010-12-23', '2017-12-27', '2020-11-23', '2010-12-14', '2020-12-25', 3333);
INSERT INTO `Conference` VALUES (5, 'schlafen bis Mittag', 'www.studierinformatik.de', 'ueber die Verlangsamung des Alterns waerend des Studiums', '2005-01-30', '2005-02-10', '2005-02-14', '2005-02-28', '2005-03-05', '2005-03-15', '2005-03-20', 9999);
INSERT INTO `Conference` VALUES (6, 'Buh', 'BÃ¤h', ' Test', '2005-12-11', '2005-12-12', '2005-12-13', '2005-12-14', '2005-12-15', '2005-12-16', '2005-12-17', 99);
INSERT INTO `Conference` VALUES (7, 'Test', 'test', ' test2', '2005-12-12', '2006-12-13', '2006-12-14', '2006-12-16', '2006-12-17', '2006-12-18', '2006-12-19', 99);
INSERT INTO `Conference` VALUES (9, 'Angebranntes Sommerheu und andere Betaeubungsmittel', '', '', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', NULL);

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

-- 
-- Daten für Tabelle `Criterion`
-- 

INSERT INTO `Criterion` VALUES (2, 5, 'Inhalt', 'Inhalt des Papers allgemein', 6, 60);
INSERT INTO `Criterion` VALUES (3, 5, 'Rechtschreibung', 'Rechtschreibung des Papers', 6, 10);
INSERT INTO `Criterion` VALUES (4, 5, 'Ausdruck', 'Wie zu alten Schulzeiten', 6, 25);
INSERT INTO `Criterion` VALUES (5, 5, 'Schrift', 'Sauklauen raus!', 6, 5);
INSERT INTO `Criterion` VALUES (10, 1, 'Rübenqualität', '', 0, 0);
INSERT INTO `Criterion` VALUES (11, 1, 'HARTES Kriterium', '', 0, 0);

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

-- 
-- Daten für Tabelle `DeniesPaper`
-- 

INSERT INTO `DeniesPaper` VALUES (35, 6);
INSERT INTO `DeniesPaper` VALUES (37, 7);

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

-- 
-- Daten für Tabelle `ExcludesPaper`
-- 

INSERT INTO `ExcludesPaper` VALUES (38, 8);

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

-- 
-- Daten für Tabelle `Forum`
-- 


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

-- 
-- Daten für Tabelle `IsAboutTopic`
-- 

INSERT INTO `IsAboutTopic` VALUES (2, 1);
INSERT INTO `IsAboutTopic` VALUES (2, 4);
INSERT INTO `IsAboutTopic` VALUES (6, 3);
INSERT INTO `IsAboutTopic` VALUES (7, 4);
INSERT INTO `IsAboutTopic` VALUES (8, 5);

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

-- 
-- Daten für Tabelle `IsCoAuthorOf`
-- 

INSERT INTO `IsCoAuthorOf` VALUES (NULL, 4, 'Mr. X');
INSERT INTO `IsCoAuthorOf` VALUES (NULL, 5, 'Meister Lampe');
INSERT INTO `IsCoAuthorOf` VALUES (NULL, 5, 'Mr. X');
INSERT INTO `IsCoAuthorOf` VALUES (5, 2, NULL);
INSERT INTO `IsCoAuthorOf` VALUES (4, 2, NULL);
INSERT INTO `IsCoAuthorOf` VALUES (1, 2, NULL);
INSERT INTO `IsCoAuthorOf` VALUES (NULL, 2, 'John Kerry');

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

-- 
-- Daten für Tabelle `Message`
-- 


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

-- 
-- Daten für Tabelle `Paper`
-- 

INSERT INTO `Paper` VALUES (2, 1, 1, 'ein machwerk', '', '0000-00-00 00:00:00', NULL, '', 0, '');
INSERT INTO `Paper` VALUES (4, 1, 1, 'Neueste Rezepte', 'Ein abstraktes Abstract...', NULL, NULL, '', 0, '');
INSERT INTO `Paper` VALUES (5, 1, 1, 'Neueste Rezepte II', 'Ein abstraktes Abstract...', NULL, NULL, '', 0, '');
INSERT INTO `Paper` VALUES (6, 5, 39, 'Insomnia, Probleme eines Studenten', 'Reasons for insomnia\r\nInsomnia in the University\r\nHelp in case of Insomnia', '2005-01-12 00:00:00', 1, 'insomnia.tex', 0, '??');
INSERT INTO `Paper` VALUES (7, 5, 40, 'Schlaflos in Seattle', 'A lovestory\r\nInterpretation\r\nCast on student cases', '2005-01-11 00:00:00', 2, 'seattle.doc', 1, 'any');
INSERT INTO `Paper` VALUES (8, 5, 41, 'Hypnotics, ways to escape students insomnia', 'Definition de Hypnotique\r\ntraumata de la sylvesteer\r\nhypnotique avec le studante\r\nhypnotique pour le traumata\r\nfrancaise anglaise dictionaise', '2003-01-01 00:00:00', 27, 'hypnotique.pdf', 3, 'alcoholic');

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

-- 
-- Daten für Tabelle `Person`
-- 

INSERT INTO `Person` VALUES (1, 'Robby', 'Rabbit', 'Der schnell F****', '', 'rr@hase.de', '', '', '', '', '', '', '', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (4, 'Grinse', 'Katz', NULL, NULL, 'gk@grin.se', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (5, 'Mieze', 'Kater', NULL, NULL, 'mk@puss.de', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '4gf');
INSERT INTO `Person` VALUES (12, 'Gummi', 'Huhn', '', '', 'gh@chicks.org', '', '', '', '', '', '', '', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (14, 'Irmgard', 'HasenfuÃŸ', 'Dr.', '', 'schnell@hase.de', '0431/888-0', '0431/888-1', 'Leibnitzstr. 33', '24116', 'Kiel', '', '', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (16, 'Luise', 'RÃ¼benesser', '', '', 'l@hase.de', '0431/78789', '', '', '', '', '', '', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (19, 'Foo', 'Foo', 'Mr', '', 'foo@foo.com', '', '', '', '', '', '', '', '0beec7b5ea3f0fdbc95d0dd47f3c5bc275da8a33');
INSERT INTO `Person` VALUES (22, 'Heribert', 'Dunekacke', '', '', 'op@oss.um', '', '', 'Der Weg 1', '12345', 'Dreckloch', '', '', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (26, 'Hertha', 'Pickel', 'Frau', '', 'pik@preussen.de', '', '', '', '', 'Teutschtorff', '', '', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (27, '', 'TesterJan1', '', '', 'bla@notme.de', '', '', '', '', '', '', '', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (28, '', 'TesterJan2', '', '', 'bl2a@notme.de', '', '', '', '', '', '', '', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (29, '', 'TesterJan3', '', '', 'bl3a@notme.de', '', '', '', '', '', '', '', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (30, '', 'TesterJan4', '', '', 'bla4@notme.de', '', '', '', '', '', '', '', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (31, '', 'TesterJan5', '', '', 'bla5@notme.de', '', '', '', '', '', '', '', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (32, 'Oliver', 'Niemann', 'Tester', 'Was ist das?', 'gub75@gmx.de', '0431/1490509', 'none', 'Westring 312', '24116', 'Kiel', 'Schleswig Holstein', 'Germany', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (33, 'Charly', 'Chair', 'Chair1', '...', 'chair1chair.de', '2353464545', '234534636', 'Stuhlstr. 1', '24116', 'Kiel', 'SH', 'Germany', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (34, 'Charlotte', 'Chair', 'Chair2', '...', 'chair2@chair.de', '3634636', '566734535', 'Stuhlstr. 2', '24116', 'Kiel', 'SH', 'Germany', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (35, 'Rudy', 'Review', 'Reviewer1', '...', 'review1@reviewer.de', '4534', '5433453', 'Schauenburgerstr. 1', '24116', 'Kiel', 'SH', 'Germany', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (36, 'Randy', 'Review', 'Reviewer 2', '...', 'review2@review.de', '4534', '3453978', 'Schauenburgerstr. 2', '24116', 'Kiel', 'SH', 'Germany', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (37, 'Rita', 'Review', 'reviewer3', '...???!!!bah', 'reviewer3@reviewer.de', '2395', '3294782893', 'Schauenburgerstr. 3', '24116', 'Kiel', 'SH', 'Germany', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (38, 'Romeo', 'Review', 'reviewer4', 'AAAAHHHH', 'review4@reviewer.de', '4478902347', '2347', 'Schauenburgerstr. 4', '24116', 'Kiel', 'SH', 'Germany', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (39, 'Arnold', 'Author', 'Author1', 'ti ztz', 'author1@authors.de', '823789238947', '239472374', 'Schlaufuchsweg 1', '24116', 'Kiel', 'SH', 'Germany', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (40, 'Anke', 'Author', 'author2', 'ying yang', 'author2@authors.de', '574578', '08154711', 'Anderer Weg 2', '23628', 'Krummesse', 'SH', 'Germany', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (41, 'Aloise', 'Author', 'Author3', 'francaise', 'author3@author.de', '0815/4711', '322343452', 'Chaussee 1828', '23454', 'Paris', 'Paris', 'France', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (46, '', 'Robby', '', '', 'robby@Robbenland.ganz.weit.weg', '', '', '', '', '', '', '', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (47, 'Martin', 'Steffen', 'X', 'Kiel', 'ms@informatik.uni-kiel.de', '111', '222', 'HRstraÃŸe', '21105', 'Kiel', '', '', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8');

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

-- 
-- Daten für Tabelle `PrefersPaper`
-- 

INSERT INTO `PrefersPaper` VALUES (35, 6);
INSERT INTO `PrefersPaper` VALUES (36, 7);
INSERT INTO `PrefersPaper` VALUES (37, 8);

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

-- 
-- Daten für Tabelle `PrefersTopic`
-- 

INSERT INTO `PrefersTopic` VALUES (39, 3);
INSERT INTO `PrefersTopic` VALUES (40, 4);
INSERT INTO `PrefersTopic` VALUES (41, 5);

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

-- 
-- Daten für Tabelle `Rating`
-- 

INSERT INTO `Rating` VALUES (1, 2, 1, 'none');
INSERT INTO `Rating` VALUES (1, 3, 2, 'bla');
INSERT INTO `Rating` VALUES (1, 4, 3, 'blub');
INSERT INTO `Rating` VALUES (1, 5, 6, 'Rächtschreibung konte er net');
INSERT INTO `Rating` VALUES (2, 2, 6, 'ddfh');
INSERT INTO `Rating` VALUES (2, 3, 4, 'rzdrz');
INSERT INTO `Rating` VALUES (2, 4, 2, NULL);
INSERT INTO `Rating` VALUES (2, 5, 1, 'Immerhinn');
INSERT INTO `Rating` VALUES (3, 2, 7, 'Test, weil 7 nicht zugelassen eigentlich');
INSERT INTO `Rating` VALUES (4, 3, 3, 'dhdhdfh');
INSERT INTO `Rating` VALUES (4, 4, 1, 'tr');
INSERT INTO `Rating` VALUES (4, 5, 3, 'drei is besser als keins');

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

-- 
-- Daten für Tabelle `ReviewReport`
-- 

INSERT INTO `ReviewReport` VALUES (1, 6, 35, 'Nothing to add', 'Some remarks', 'What''s a confidential?');
INSERT INTO `ReviewReport` VALUES (2, 7, 36, 'My personal summary', 'My personal remarks', 'Hmm...');
INSERT INTO `ReviewReport` VALUES (3, 8, 38, 'The summary', 'The remarks', 'The confidential');
INSERT INTO `ReviewReport` VALUES (4, 8, 37, 'The summary 2', 'The remarks 2', 'The confidential 2');

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

-- 
-- Daten für Tabelle `Role`
-- 

INSERT INTO `Role` VALUES (1, 1, 2, NULL);
INSERT INTO `Role` VALUES (1, 1, 3, 1);
INSERT INTO `Role` VALUES (1, 1, 4, 1);
INSERT INTO `Role` VALUES (1, 1, 5, 1);
INSERT INTO `Role` VALUES (1, 14, 0, NULL);
INSERT INTO `Role` VALUES (2, 16, 0, NULL);
INSERT INTO `Role` VALUES (3, 16, 0, NULL);
INSERT INTO `Role` VALUES (5, 32, 5, 3);
INSERT INTO `Role` VALUES (5, 33, 2, 1);
INSERT INTO `Role` VALUES (5, 34, 2, 2);
INSERT INTO `Role` VALUES (5, 35, 3, 1);
INSERT INTO `Role` VALUES (5, 36, 3, 0);
INSERT INTO `Role` VALUES (5, 37, 3, 2);
INSERT INTO `Role` VALUES (5, 38, 3, 1);
INSERT INTO `Role` VALUES (5, 39, 4, 1);
INSERT INTO `Role` VALUES (5, 40, 4, 2);
INSERT INTO `Role` VALUES (5, 41, 4, 3);
INSERT INTO `Role` VALUES (6, 16, 3, NULL);
INSERT INTO `Role` VALUES (7, 16, 2, NULL);

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
-- Daten für Tabelle `Topic`
-- 

INSERT INTO `Topic` VALUES (1, 1, 'Heuqualitätserkennungsmaßstäbe (Update)');
INSERT INTO `Topic` VALUES (2, 5, 'Testkonferenz with full data');
INSERT INTO `Topic` VALUES (3, 5, 'Insomnia');
INSERT INTO `Topic` VALUES (4, 5, 'Seattle');
INSERT INTO `Topic` VALUES (5, 5, 'Hypnotique');