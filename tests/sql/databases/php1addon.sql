-- phpMyAdmin SQL Dump
-- version 2.6.0-pl3
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 06. Januar 2005 um 08:23
-- Server Version: 3.23.58
-- PHP-Version: 4.3.10
-- 
-- Datenbank: `coma1`
-- 

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `ConferenceConfig`
-- 

CREATE TABLE IF NOT EXISTS ConferenceConfig
(
   id                           INT NOT NULL,
   default_reviews_per_paper    INT NOT NULL,
   min_number_of_papers         INT NOT NULL,
   max_number_of_papers         INT NOT NULL,
   critical_variance            FLOAT NOT NULL DEFAULT '.5',
   auto_activate_account        INT NOT NULL DEFAULT '1',    -- 0 = FALSE, 1 (bzw. <>0) = TRUE
   auto_open_paper_forum        INT NOT NULL DEFAULT '1',    -- 0 = FALSE, 1 (bzw. <>0) = TRUE
   auto_add_reviewers           INT NOT NULL DEFAULT '1',    -- 0 = FALSE, 1 (bzw. <>0) = TRUE
   number_of_auto_add_reviewers INT NOT NULL,
   PRIMARY KEY (id)
) TYPE = INNODB;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `Session`
-- 

CREATE TABLE IF NOT EXISTS Session (
  sid VARCHAR(255) NOT NULL DEFAULT '',
  sname VARCHAR(25) NOT NULL DEFAULT '',
  sdata TEXT,
  stime TIMESTAMP(14) NOT NULL,
  PRIMARY KEY  (sid, sname),
  KEY stime (stime)
) TYPE=MyISAM COMMENT='Session-Verwaltung';
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS Distribution
(
   paper_id                     INT NOT NULL,
   reviewer_id                  INT NOT NULL,
   PRIMARY KEY (paper_id, reviewer_id),
   INDEX (paper_id),
   INDEX (reviewer_id),
   FOREIGN KEY (paper_id) REFERENCES Paper (id)
       ON DELETE CASCADE,
   FOREIGN KEY (reviewer_id) REFERENCES Person (id)
       ON DELETE CASCADE
) TYPE = INNODB;

-- --------------------------------------------------------


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
