-- phpMyAdmin SQL Dump
-- version 2.5.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 06. Januar 2005 um 11:01
-- Server Version: 4.0.18
-- PHP-Version: 4.3.6
-- 
-- Datenbank: `coma1`
-- 

-- --------------------------------------------------------

--
-- Daten für Tabelle `conference`
--

INSERT INTO `conference` VALUES (1, 'Testkonferenz 1 mit NULL Feldern', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `conference` VALUES (3, 'Testkonferenz 2, mit unerwarteten Felder', 'no valid homepage', 'Hier kann man wohl nict viel falsch machen?', '2005-12-01', '2020-05-12', '0000-00-00', '2005-02-30', '2001-05-20', '2001-05-20', '2030-02-20', 0);

-- --------------------------------------------------------

--
-- Daten für Tabelle `criterion`
--

INSERT INTO `criterion` VALUES (1, 1, 'Kriterium mit NULL Werten', NULL, NULL, NULL);
INSERT INTO `criterion` VALUES (2, 3, 'Kriterium mit unerwarteten Werten', 'Was kann an einer Beschreibung unerwartet sein? Vielleicht Umlaute? ü Ü ß', 0, 101);

-- --------------------------------------------------------

--
-- Daten für Tabelle `deniespaper`
--

INSERT INTO `deniespaper` VALUES (0, 2);
INSERT INTO `deniespaper` VALUES (1, 1);
INSERT INTO `deniespaper` VALUES (2, 0);

-- --------------------------------------------------------

--
-- Daten für Tabelle `excludespaper`
--

INSERT INTO `excludespaper` VALUES (0, 2);
INSERT INTO `excludespaper` VALUES (1, 1);
INSERT INTO `excludespaper` VALUES (2, 0);

-- --------------------------------------------------------

--
-- Daten für Tabelle `forum`
--

INSERT INTO `forum` VALUES (1, 1, 'Das NULL Forum', 0, NULL);
INSERT INTO `forum` VALUES (2, 3, 'Forum mit unerwarteten Werten', 23564574, 2);
INSERT INTO `forum` VALUES (3, 3, 'Nochmal unerwartete Werte', 123, 0);

-- --------------------------------------------------------

--
-- Daten für Tabelle `isabouttopic`
--

INSERT INTO `isabouttopic` VALUES (0, 2);
INSERT INTO `isabouttopic` VALUES (1, 1);
INSERT INTO `isabouttopic` VALUES (2, 0);

-- --------------------------------------------------------

--
-- Daten für Tabelle `iscoauthorof`
--

INSERT INTO `iscoauthorof` VALUES (NULL, 1, NULL);
INSERT INTO `iscoauthorof` VALUES (2, 0, 'Invalid paper');
INSERT INTO `iscoauthorof` VALUES (2, 2, 'Some name');

-- --------------------------------------------------------

--
-- Daten für Tabelle `message`
--

INSERT INTO `message` VALUES (1, NULL, NULL, 0, NULL, NULL, NULL);
INSERT INTO `message` VALUES (2, 2, 1, 2, '2005-12-01 00:00:00', 'Some Subject', 'Some Text');

-- --------------------------------------------------------

--
-- Daten für Tabelle `paper`
--

INSERT INTO `paper` VALUES (1, 1, 1, 'NULL Paper', NULL, NULL, NULL, NULL, 0, NULL);
INSERT INTO `paper` VALUES (2, 3, 2, 'Unexpected values Paper', 'Ein Abstract, kurz und bündig', '2005-12-01 00:00:00', 0, 'invalid filename', 1234, 'my own mime');

-- --------------------------------------------------------

--
-- Daten für Tabelle `person`
--

INSERT INTO `person` VALUES (1, NULL, 'NULL', NULL, NULL, 'no valid email', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `person` VALUES (2, 'Oliver', 'Niemann', 'Evil Tester', 'What\'s an affiliation?', 'gub75@gmx.de', '0431/1490509', 'no fax', 'Westring 312', '24116', 'Kiel', 'Schleswig Holstein', 'Germany', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');

-- --------------------------------------------------------

--
-- Daten für Tabelle `preferspaper`
--

INSERT INTO `preferspaper` VALUES (0, 1);
INSERT INTO `preferspaper` VALUES (1, 0);
INSERT INTO `preferspaper` VALUES (2, 1);

-- --------------------------------------------------------

--
-- Daten für Tabelle `preferstopic`
--

INSERT INTO `preferstopic` VALUES (0, 1);
INSERT INTO `preferstopic` VALUES (1, 0);
INSERT INTO `preferstopic` VALUES (2, 2);

-- --------------------------------------------------------

--
-- Daten für Tabelle `rating`
--

INSERT INTO `rating` VALUES (1, 1, 345, NULL);
INSERT INTO `rating` VALUES (2, 0, 0, 'Some comment');
INSERT INTO `rating` VALUES (0, 2, 1, 'Another comment');

-- --------------------------------------------------------

--
-- Daten für Tabelle `reviewreport`
--

INSERT INTO `reviewreport` VALUES (1, 1, 1, NULL, NULL, NULL);
INSERT INTO `reviewreport` VALUES (2, 2, 0, 'Some summary', 'Some remarks', 'Some confidential');
INSERT INTO `reviewreport` VALUES (3, 0, 2, 'Another summary', 'Another remark', 'No confidential');

-- --------------------------------------------------------

--
-- Daten für Tabelle `role`
--

INSERT INTO `role` VALUES (1, 1, 1, 1);
INSERT INTO `role` VALUES (3, 2, 1, 3);

-- --------------------------------------------------------

--
-- Daten für Tabelle `topic`
--

INSERT INTO `topic` VALUES (1, 1, 'Some topic for NULL Conference');
INSERT INTO `topic` VALUES (2, 3, 'Unexpected Topic');
INSERT INTO `topic` VALUES (3, 1, 'NULL Topic');
INSERT INTO `topic` VALUES (4, 3, 'Some Topic');
