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
-- Daten für Tabelle `Conference`
--

INSERT INTO `Conference` VALUES (1, 'Testkonferenz 1 mit NULL Feldern', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `Conference` VALUES (3, 'Testkonferenz 2, mit unerwarteten Felder', 'no valid homepage', 'Hier kann man wohl nict viel falsch machen?', '2005-12-01', '2020-05-12', '0000-00-00', '2005-02-30', '2001-05-20', '2001-05-20', '2030-02-20', 0);

-- --------------------------------------------------------

--
-- Daten für Tabelle `Criterion`
--

INSERT INTO `Criterion` VALUES (1, 1, 'Kriterium mit NULL Werten', NULL, NULL, NULL);
INSERT INTO `Criterion` VALUES (2, 3, 'Kriterium mit unerwarteten Werten', 'Was kann an einer Beschreibung unerwartet sein? Vielleicht Umlaute? ü Ü ß', 0, 101);

-- --------------------------------------------------------

--
-- Daten für Tabelle `Deniespaper`
--

INSERT INTO `Deniespaper` VALUES (0, 2);
INSERT INTO `Deniespaper` VALUES (1, 1);
INSERT INTO `Deniespaper` VALUES (2, 0);

-- --------------------------------------------------------

--
-- Daten für Tabelle `Excludespaper`
--

INSERT INTO `Excludespaper` VALUES (0, 2);
INSERT INTO `Excludespaper` VALUES (1, 1);
INSERT INTO `Excludespaper` VALUES (2, 0);

-- --------------------------------------------------------

--
-- Daten für Tabelle `Forum`
--

INSERT INTO `Forum` VALUES (1, 1, 'Das NULL Forum', 0, NULL);
INSERT INTO `Forum` VALUES (2, 3, 'Forum mit unerwarteten Werten', 23564574, 2);
INSERT INTO `Forum` VALUES (3, 3, 'Nochmal unerwartete Werte', 123, 0);

-- --------------------------------------------------------

--
-- Daten für Tabelle `Isabouttopic`
--

INSERT INTO `Isabouttopic` VALUES (0, 2);
INSERT INTO `Isabouttopic` VALUES (1, 1);
INSERT INTO `Isabouttopic` VALUES (2, 0);

-- --------------------------------------------------------

--
-- Daten für Tabelle `Iscoauthorof`
--

INSERT INTO `Iscoauthorof` VALUES (NULL, 1, NULL);
INSERT INTO `Iscoauthorof` VALUES (2, 0, 'Invalid paper');
INSERT INTO `Iscoauthorof` VALUES (2, 2, 'Some name');

-- --------------------------------------------------------

--
-- Daten für Tabelle `Message`
--

INSERT INTO `Message` VALUES (1, NULL, NULL, 0, NULL, NULL, NULL);
INSERT INTO `Message` VALUES (2, 2, 1, 2, '2005-12-01 00:00:00', 'Some Subject', 'Some Text');

-- --------------------------------------------------------

--
-- Daten für Tabelle `Paper`
--

INSERT INTO `Paper` VALUES (1, 1, 1, 'NULL Paper', NULL, NULL, NULL, NULL, 0, NULL);
INSERT INTO `Paper` VALUES (2, 3, 2, 'Unexpected values Paper', 'Ein Abstract, kurz und bündig', '2005-12-01 00:00:00', 0, 'invalid filename', 1234, 'my own mime');

-- --------------------------------------------------------

--
-- Daten für Tabelle `Person`
--

INSERT INTO `Person` VALUES (1, NULL, 'NULL', NULL, NULL, 'no valid email', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
INSERT INTO `Person` VALUES (2, 'Oliver', 'Niemann', 'Evil Tester', 'What\'s an affiliation?', 'gub75@gmx.de', '0431/1490509', 'no fax', 'Westring 312', '24116', 'Kiel', 'Schleswig Holstein', 'Germany', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');

-- --------------------------------------------------------

--
-- Daten für Tabelle `Preferspaper`
--

INSERT INTO `Preferspaper` VALUES (0, 1);
INSERT INTO `Preferspaper` VALUES (1, 0);
INSERT INTO `Preferspaper` VALUES (2, 1);

-- --------------------------------------------------------

--
-- Daten für Tabelle `Preferstopic`
--

INSERT INTO `Preferstopic` VALUES (0, 1);
INSERT INTO `Preferstopic` VALUES (1, 0);
INSERT INTO `Preferstopic` VALUES (2, 2);

-- --------------------------------------------------------

--
-- Daten für Tabelle `Rating`
--

INSERT INTO `Rating` VALUES (1, 1, 345, NULL);
INSERT INTO `Rating` VALUES (2, 0, 0, 'Some comment');
INSERT INTO `Rating` VALUES (0, 2, 1, 'Another comment');

-- --------------------------------------------------------

--
-- Daten für Tabelle `Reviewreport`
--

INSERT INTO `Reviewreport` VALUES (1, 1, 1, NULL, NULL, NULL);
INSERT INTO `Reviewreport` VALUES (2, 2, 0, 'Some summary', 'Some remarks', 'Some confidential');
INSERT INTO `Reviewreport` VALUES (3, 0, 2, 'Another summary', 'Another remark', 'No confidential');

-- --------------------------------------------------------

--
-- Daten für Tabelle `Role`
--

INSERT INTO `Role` VALUES (1, 1, 1, 1);
INSERT INTO `Role` VALUES (3, 2, 1, 3);

-- --------------------------------------------------------

--
-- Daten für Tabelle `Topic`
--

INSERT INTO `Topic` VALUES (1, 1, 'Some topic for NULL Conference');
INSERT INTO `Topic` VALUES (2, 3, 'Unexpected Topic');
INSERT INTO `Topic` VALUES (3, 1, 'NULL Topic');
INSERT INTO `Topic` VALUES (4, 3, 'Some Topic');
