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
USE coma1;

-- --------------------------------------------------------


-- 
-- Tabellenstruktur f�r Tabelle `Session`
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

-- 
-- Daten f�r Tabelle `Session`
-- 

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
