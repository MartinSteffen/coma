-- ------------------------------------------------------------------------
-- Skript zur Erstellung der PHP1-spezifischen DB-Aenderungen
-- ------------------------------------------------------------------------
-- - 08.01.05, 13:15: erstellt (Tom).
-- - 03.02.05, 11:13; geaendert (Jan)
-- ------------------------------------------------------------------------

-- Tabellenstruktur für Tabelle 'Session'
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



CREATE TABLE IF NOT EXISTS Distribution
(
   paper_id                     INT NOT NULL,
   reviewer_id                  INT NOT NULL,
   PRIMARY KEY (paper_id, reviewer_id),   
   FOREIGN KEY (paper_id) REFERENCES Paper (id)
       ON DELETE CASCADE,
   FOREIGN KEY (reviewer_id) REFERENCES Person (id)
       ON DELETE CASCADE
) TYPE = INNODB;



CREATE TABLE IF NOT EXISTS PaperData
(
   paper_id                     INT NOT NULL,
   filesize                     INT NOT NULL,
   file                         MEDIUMBLOB NOT NULL,
   PRIMARY KEY (paper_id),
   FOREIGN KEY (paper_id) REFERENCES Paper (id)
       ON DELETE CASCADE
) TYPE = INNODB;


-- Sonst bekommt man Message Leichen, die man per Hand aufräumen muesste!!
-- ist nicht mehr selbe DB fuer alle!!
ALTER TABLE Message CHANGE forum_id forum_id INT NOT NULL;
ALTER TABLE Message ADD INDEX (forum_id);
ALTER TABLE Message ADD FOREIGN KEY (forum_id) REFERENCES Forum (id) ON DELETE CASCADE;
