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

DELETE FROM `Conference`;

-- 
-- Daten für Tabelle `Conference`
-- 

INSERT INTO `Conference` VALUES (5, 'schlafen bis Mittag', 'www.studierinformatik.de', 'ueber die Verlangsamung des Alterns waerend des Studiums', '2005-01-30', '2005-02-10', '2005-02-14', '2005-02-28', '2005-03-05', '2005-03-15', '2005-03-20', 9999);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `Criterion`
-- 

DELETE FROM `Criterion`;

-- 
-- Daten für Tabelle `Criterion`
-- 

INSERT INTO `Criterion` VALUES (2, 5, 'Inhalt', 'Inhalt des Papers allgemein', 6, 60);
INSERT INTO `Criterion` VALUES (3, 5, 'Rechtschreibung', 'Rechtschreibung des Papers', 6, 10);
INSERT INTO `Criterion` VALUES (4, 5, 'Ausdruck', 'Wie zu alten Schulzeiten', 6, 25);
INSERT INTO `Criterion` VALUES (5, 5, 'Schrift', 'Sauklauen raus!', 6, 5);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `DeniesPaper`
-- 

DELETE FROM `DeniesPaper`;

-- 
-- Daten für Tabelle `DeniesPaper`
-- 

INSERT INTO `DeniesPaper` VALUES (35, 6);
INSERT INTO `DeniesPaper` VALUES (37, 7);
INSERT INTO `DeniesPaper` VALUES (38, 7);
INSERT INTO `DeniesPaper` VALUES (32, 7);
INSERT INTO `DeniesPaper` VALUES (32, 9);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `ExcludesPaper`
-- 

DELETE FROM `ExcludesPaper`;

-- 
-- Daten für Tabelle `ExcludesPaper`
-- 

INSERT INTO `ExcludesPaper` VALUES (38, 8);
INSERT INTO `ExcludesPaper` VALUES (38, 7);


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `Forum`
-- 

DELETE FROM `Forum`;

-- 
-- Daten für Tabelle `Forum`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `IsAboutTopic`
-- 

DELETE FROM `IsAboutTopic`;

-- 
-- Daten für Tabelle `IsAboutTopic`
-- 

INSERT INTO `IsAboutTopic` VALUES (6, 3);
INSERT INTO `IsAboutTopic` VALUES (7, 4);
INSERT INTO `IsAboutTopic` VALUES (8, 5);
INSERT INTO `IsAboutTopic` VALUES (9, 5);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `IsCoAuthorOf`
-- 

DELETE FROM `IsCoAuthorOf`;

-- 
-- Daten für Tabelle `IsCoAuthorOf`
-- 


INSERT INTO `IsCoAuthorOf` VALUES (5, 2, NULL);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `Message`
-- 

DELETE FROM `Message`;

-- 
-- Daten für Tabelle `Message`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `Paper`
-- 

DELETE FROM `Paper`;

-- 
-- Daten für Tabelle `Paper`
-- 

INSERT INTO `Paper` VALUES (6, 5, 39, 'Insomnia, Probleme eines Studenten', 'Reasons for insomnia\r\nInsomnia in the University\r\nHelp in case of Insomnia', '2005-01-12 00:00:00', 1, 'insomnia.tex', 0, '??');
INSERT INTO `Paper` VALUES (7, 5, 40, 'Schlaflos in Seattle', 'A lovestory\r\nInterpretation\r\nCast on student cases', '2005-01-11 00:00:00', 2, 'seattle.doc', 1, 'any');
INSERT INTO `Paper` VALUES (8, 5, 41, 'Hypnotics, ways to escape students insomnia', 'Definition de Hypnotique\r\ntraumata de la sylvesteer\r\nhypnotique avec le studante\r\nhypnotique pour le traumata\r\nfrancaise anglaise dictionaise', '2003-01-01 00:00:00', 27, 'hypnotique.pdf', 3, 'alcoholic');
INSERT INTO `Paper` VALUES (9, 5, 32, 'Hypnotics, ways to escape students insomnia', 'Definition de Hypnotique\r\ntraumata de la sylvesteer\r\nhypnotique avec le studante\r\nhypnotique pour le traumata\r\nfrancaise anglaise dictionaise', '2003-01-01 00:00:00', 27, 'hypnotique2.pdf', 3, 'alcoholic');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `Person`
-- 

DELETE FROM `Person`;

-- 
-- Daten für Tabelle `Person`
-- 

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

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `PrefersPaper`
-- 

DELETE FROM `PrefersPaper`;

-- 
-- Daten für Tabelle `PrefersPaper`
-- 

INSERT INTO `PrefersPaper` VALUES (35, 6);
INSERT INTO `PrefersPaper` VALUES (36, 7);
INSERT INTO `PrefersPaper` VALUES (37, 8);
INSERT INTO `PrefersPaper` VALUES (32, 8);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `PrefersTopic`
-- 

DELETE FROM `PrefersTopic`;

-- 
-- Daten für Tabelle `PrefersTopic`
-- 

INSERT INTO `PrefersTopic` VALUES (39, 3);
INSERT INTO `PrefersTopic` VALUES (40, 4);
INSERT INTO `PrefersTopic` VALUES (41, 5);
INSERT INTO `PrefersTopic` VALUES (32, 5);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `Rating`
-- 

DELETE FROM `Rating`;

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
INSERT INTO `Rating` VALUES (5, 2, 1, 'tr');
INSERT INTO `Rating` VALUES (5, 3, 1, 'tr');
INSERT INTO `Rating` VALUES (5, 4, 1, 'tr');
INSERT INTO `Rating` VALUES (5, 5, 1, 'tr');
INSERT INTO `Rating` VALUES (6, 2, 2, 'tr');
INSERT INTO `Rating` VALUES (6, 3, 3, 'tr');
INSERT INTO `Rating` VALUES (6, 4, 4, 'tr');
INSERT INTO `Rating` VALUES (6, 5, 5, 'tr');
INSERT INTO `Rating` VALUES (7, 2, 6, 'tr');
INSERT INTO `Rating` VALUES (7, 3, 5, 'tr');
INSERT INTO `Rating` VALUES (7, 4, 4, 'tr');
INSERT INTO `Rating` VALUES (7, 5, 3, 'tr');
INSERT INTO `Rating` VALUES (8, 2, 2, 'tr');
INSERT INTO `Rating` VALUES (8, 3, 1, 'tr');
INSERT INTO `Rating` VALUES (8, 4, 3, 'tr');
INSERT INTO `Rating` VALUES (8, 5, 6, 'tr');


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `ReviewReport`
-- 

DELETE FROM `ReviewReport`;

-- 
-- Daten für Tabelle `ReviewReport`
-- 

INSERT INTO `ReviewReport` VALUES (1, 6, 35, 'Nothing to add', 'Some remarks', 'What''s a confidential?');
INSERT INTO `ReviewReport` VALUES (2, 7, 36, 'My personal summary', 'My personal remarks', 'Hmm...');
INSERT INTO `ReviewReport` VALUES (3, 8, 38, 'The summary', 'The remarks', 'The confidential');
INSERT INTO `ReviewReport` VALUES (4, 8, 37, 'The summary 2', 'The remarks 2', 'The confidential 2');
INSERT INTO `ReviewReport` VALUES (5, 9, 32, 'The summary for my own paper', 'The remarks for my own paper', 'The confidential for my own paper');
INSERT INTO `ReviewReport` VALUES (6, 9, 35, 'The summary for my own paper', 'The remarks for my own paper', 'The confidential for my own paper');
INSERT INTO `ReviewReport` VALUES (7, 9, 36, 'The summary for my own paper', 'The remarks for my own paper', 'The confidential for my own paper');
INSERT INTO `ReviewReport` VALUES (8, 9, 37, 'The summary for my own paper', 'The remarks for my own paper', 'The confidential for my own paper');


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `Role`
-- 

DELETE FROM `Role`;

-- 
-- Daten für Tabelle `Role`
-- 

INSERT INTO `Role` VALUES (5, 32, 5, 3);
INSERT INTO `Role` VALUES (5, 32, 2, 3);
INSERT INTO `Role` VALUES (5, 32, 3, 3);
INSERT INTO `Role` VALUES (5, 32, 4, 3);
INSERT INTO `Role` VALUES (5, 33, 2, 1);
INSERT INTO `Role` VALUES (5, 34, 2, 2);
INSERT INTO `Role` VALUES (5, 35, 3, 1);
INSERT INTO `Role` VALUES (5, 36, 3, 0);
INSERT INTO `Role` VALUES (5, 37, 3, 2);
INSERT INTO `Role` VALUES (5, 38, 3, 1);
INSERT INTO `Role` VALUES (5, 39, 4, 1);
INSERT INTO `Role` VALUES (5, 40, 4, 2);
INSERT INTO `Role` VALUES (5, 41, 4, 3);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `Topic`
-- 

DELETE FROM `Topic`;

-- 
-- Daten für Tabelle `Topic`
-- 

INSERT INTO `Topic` VALUES (2, 5, 'Testkonferenz with full data');
INSERT INTO `Topic` VALUES (3, 5, 'Insomnia');
INSERT INTO `Topic` VALUES (4, 5, 'Seattle');
INSERT INTO `Topic` VALUES (5, 5, 'Hypnotique');