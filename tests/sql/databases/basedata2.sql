-- phpMyAdmin SQL Dump
-- version 2.6.0-pl3
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 19. Januar 2005 um 16:13
-- Server Version: 3.23.58
-- PHP-Version: 4.3.10
-- 
-- Datenbank: `coma1`
-- 

-- 
-- Daten für Tabelle `Conference`
-- 

INSERT INTO `Conference` VALUES (12, 'Conference0', 'Homepage0', 'Description0', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', NULL);

-- 
-- Daten für Tabelle `Criterion`
-- 

INSERT INTO `Criterion` VALUES (2, 5, 'Inhalt', 'Inhalt des Papers allgemein', 6, 60);
INSERT INTO `Criterion` VALUES (3, 5, 'Rechtschreibung', 'Rechtschreibung des Papers', 6, 10);
INSERT INTO `Criterion` VALUES (4, 5, 'Ausdruck', 'Wie zu alten Schulzeiten', 6, 25);
INSERT INTO `Criterion` VALUES (5, 5, 'Schrift', 'Sauklauen raus!', 6, 5);
INSERT INTO `Criterion` VALUES (10, 1, 'Rübenqualität', '', 0, 0);
INSERT INTO `Criterion` VALUES (11, 1, 'HARTES Kriterium', '', 0, 0);

-- 
-- Daten für Tabelle `IsAboutTopic`
-- 

INSERT INTO `IsAboutTopic` VALUES (5, 5);
INSERT INTO `IsAboutTopic` VALUES (6, 3);
INSERT INTO `IsAboutTopic` VALUES (6, 6);
INSERT INTO `IsAboutTopic` VALUES (8, 5);

-- 
-- Daten für Tabelle `IsCoAuthorOf`
-- 

INSERT INTO `IsCoAuthorOf` VALUES (NULL, 5, 'Meister Lampe');
INSERT INTO `IsCoAuthorOf` VALUES (NULL, 5, 'Mr. X');
INSERT INTO `IsCoAuthorOf` VALUES (5, 2, NULL);
INSERT INTO `IsCoAuthorOf` VALUES (4, 2, NULL);
INSERT INTO `IsCoAuthorOf` VALUES (1, 2, NULL);
INSERT INTO `IsCoAuthorOf` VALUES (NULL, 2, 'John Kerry');
INSERT INTO `IsCoAuthorOf` VALUES (NULL, 2, 'Co Author Name');
INSERT INTO `IsCoAuthorOf` VALUES (NULL, 4, 'Mr. X');

-- 
-- Daten für Tabelle `Paper`
-- 

INSERT INTO `Paper` VALUES (2, 1, 1, 'ein machwerk', '', '0000-00-00 00:00:00', NULL, '', 0, '');
INSERT INTO `Paper` VALUES (4, 1, 1, 'Neueste Rezepte', 'Ein abstraktes Abstract...', '0000-00-00 00:00:00', NULL, '', 0, '');
INSERT INTO `Paper` VALUES (5, 1, 1, 'Neueste Rezepte II', 'Ein abstraktes Abstract...', NULL, NULL, '', 0, '');
INSERT INTO `Paper` VALUES (6, 5, 39, 'Insomnia, Probleme eines Studenten', 'Reasons for insomnia\r\nInsomnia in the University\r\nHelp in case of Insomnia', '2005-01-12 00:00:00', 1, 'insomnia.tex', 0, '??');
INSERT INTO `Paper` VALUES (7, 5, 40, 'Schlaflos in Seattle', 'A lovestory\r\nInterpretation\r\nCast on student cases', '2005-01-11 00:00:00', 2, 'seattle.doc', 1, 'any');
INSERT INTO `Paper` VALUES (8, 5, 41, 'Hypnotics, ways to escape students insomnia', 'Definition de Hypnotique\r\ntraumata de la sylvesteer\r\nhypnotique avec le studante\r\nhypnotique pour le traumata\r\nfrancaise anglaise dictionaise', '2003-01-01 00:00:00', 27, 'hypnotique.pdf', 3, 'alcoholic');

-- 
-- Daten für Tabelle `Person`
-- 

INSERT INTO `Person` VALUES (1, 'Robby', 'Rabbit!', 'Der schnell F****', 'keine', 'rr@hase.de', 'asd', '', 'Hans & Franz StraÃŸe', 'ÃŸ', 'Ã¤Ã„Ã¶Ã–Ã¼Ãœ', ''' hallo '' huhu " bla ÃŸ " '' sad', '$x', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073');
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

-- 
-- Daten für Tabelle `PrefersPaper`
-- 

INSERT INTO `PrefersPaper` VALUES (1, 6);
INSERT INTO `PrefersPaper` VALUES (4, 4);
INSERT INTO `PrefersPaper` VALUES (5, 5);

-- 
-- Daten für Tabelle `PrefersTopic`
-- 

INSERT INTO `PrefersTopic` VALUES (1, 1);
INSERT INTO `PrefersTopic` VALUES (1, 3);
INSERT INTO `PrefersTopic` VALUES (1, 4);
INSERT INTO `PrefersTopic` VALUES (4, 4);
INSERT INTO `PrefersTopic` VALUES (5, 5);

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
INSERT INTO `Rating` VALUES (3, 3, 2, 'Comment22');
INSERT INTO `Rating` VALUES (4, 2, 4, 'Comment31');
INSERT INTO `Rating` VALUES (4, 3, 3, 'dhdhdfh');
INSERT INTO `Rating` VALUES (4, 4, 1, 'tr');
INSERT INTO `Rating` VALUES (4, 5, 3, 'drei is besser als keins');

-- 
-- Daten für Tabelle `ReviewReport`
-- 

INSERT INTO `ReviewReport` VALUES (1, 6, 35, 'Nothing to add', 'Some remarks', 'What''s a confidential?');
INSERT INTO `ReviewReport` VALUES (2, 7, 36, 'My personal summary', 'My personal remarks', 'Hmm...');
INSERT INTO `ReviewReport` VALUES (3, 8, 38, 'The summary', 'The remarks', 'The confidential');
INSERT INTO `ReviewReport` VALUES (4, 8, 37, 'The summary 2', 'The remarks 2', 'The confidential 2');
-- 
-- Daten für Tabelle `Role`
-- 

INSERT INTO `Role` VALUES (1, 1, 2, NULL);
INSERT INTO `Role` VALUES (1, 1, 3, 1);
INSERT INTO `Role` VALUES (1, 1, 4, 1);
INSERT INTO `Role` VALUES (1, 1, 5, 1);

-- 
-- Daten für Tabelle `Topic`
-- 

INSERT INTO `Topic` VALUES (1, 1, 'Heuqualitätserkennungsmaßstäbe (Update) (Update) (Update)');
INSERT INTO `Topic` VALUES (2, 5, 'Testkonferenz with full data');
INSERT INTO `Topic` VALUES (3, 5, 'Insomnia');
INSERT INTO `Topic` VALUES (4, 5, 'Seattle');
INSERT INTO `Topic` VALUES (5, 5, 'Hypnotique');