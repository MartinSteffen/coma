-- ------------------------------------------------------------------------
-- Skript zur Erstellung der PHP1-spezifischen DB-Aenderungen
-- ------------------------------------------------------------------------
-- - 08.01.05, 13:15: erstellt (Tom).
-- ------------------------------------------------------------------------

-- Tabellenstruktur f�r Tabelle 'Session'
-- sid normalerweise 128 oder 160
--
CREATE TABLE IF NOT EXISTS Session (
  sid VARCHAR(255) NOT NULL DEFAULT '',
  sname VARCHAR(25) NOT NULL DEFAULT '',
  sdata TEXT,
  stime TIMESTAMP(14) NOT NULL,
  PRIMARY KEY  (sid, sname),
  KEY stime (stime)
) TYPE=MyISAM COMMENT='Session-Verwaltung';



CREATE TABLE ConferenceConfig
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
