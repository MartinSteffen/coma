-- ------------------------------------------------------------------------
-- Skript zur Erstellung der gemeinsamen DB
-- erstellt von Tom, 16.12.04, auf Basis von db_schema.txt V. 816
-- ------------------------------------------------------------------------
-- Bemerkungen:
-- - optionale Felder werden mit erzeugt (ohne NOT-NULL-Bedingung)
-- - Fremdschluesselbeziehungen und optionale Tabellen muessen durch
--   zusaetzliche Skripte erzeugt werden
-- ------------------------------------------------------------------------
-- Aenderungen im Vergleich zu db_schema.txt V. 816:
-- - Role: Primaerschluessel geaendert
-- - IsCoAuthorOf: NOT-NULL-Constraint bei name entfernt (Redundanz, falls
--   person_id NOT NULL); theoretischer Constraint: NOT NULL person_id OR
--   NOT NULL name
-- - IsAboutTopic: Primaerschluessel hinzugefuegt
-- - PrefersTopic: Primaerschluessel hinzugefuegt
-- - PrefersPaper/DeniesPaper/ExcludesPaper: Primaerschluessel hinzugefuegt
-- - Rating: Primaerschluessel hinzugefuegt
-- ------------------------------------------------------------------------
-- Fragen / Diskussion:
-- - ReviewReport: Wozu id? Primaerschluessel waere ebensogut
--   (paper_id, reviewer_id). Dann braeuchte man aber bei FK-Verweis auf
--   ReviewReport ueber review_id den Verweis auf (paper_id, reviewer_id).
-- ------------------------------------------------------------------------

CREATE TABLE Conference
(
   id                           INT NOT NULL AUTO_INCREMENT,
   name                         VARCHAR(127) NOT NULL,
   homepage                     VARCHAR(127),
   description                  TEXT,
   abstract_submission_deadline DATE,
   paper_submission_deadline    DATE,
   review_deadline              DATE,
   final_version_deadline       DATE,
   notification                 DATE,
   conference_start             DATE,
   conference_end               DATE,
   min_reviews_per_paper        INT,
   PRIMARY KEY (id)
) TYPE = INNODB;

CREATE TABLE Person
(
   id           INT NOT NULL AUTO_INCREMENT,
   first_name   VARCHAR(127),
   last_name    VARCHAR(127) NOT NULL,
   title        VARCHAR(32),
   affiliation  VARCHAR(127),
   email        VARCHAR(127) UNIQUE NOT NULL, -- UNIQUE wird ignoriert (?)
   phone_number VARCHAR(20),
   fax_number   VARCHAR(20),
   street       VARCHAR(127),
   postal_code  VARCHAR(20),
   city         VARCHAR(127),
   state        VARCHAR(127),
   country      VARCHAR(127),
   password     VARCHAR(32) NOT NULL,
   PRIMARY KEY (id)
) TYPE = INNODB;

CREATE TABLE Role
(
   conference_id  INT NOT NULL,
   person_id      INT NOT NULL,
   role_type      INT NOT NULL, -- optional: Enum oder FK auf Tabelle Roles
   state          INT, -- optional
   PRIMARY KEY (conference_id, person_id, role_type),
   INDEX (conference_id),
   INDEX (person_id),
   FOREIGN KEY (conference_id) REFERENCES Conference (id)
       ON DELETE CASCADE,
   FOREIGN KEY (person_id) REFERENCES Person (id)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE Paper
(
   id              INT NOT NULL AUTO_INCREMENT,
   conference_id   INT NOT NULL,
   author_id       INT NOT NULL,
   title           VARCHAR(127) NOT NULL,
   abstract        TEXT,
   last_edited     DATETIME,
   version         INT,
   filename        VARCHAR(127),
   state           INT NOT NULL,
   mime_type       VARCHAR(127),
   PRIMARY KEY (id),
   INDEX (conference_id),
   INDEX (author_id),   
   FOREIGN KEY (conference_id) REFERENCES Conference (id)
       ON DELETE CASCADE,
   FOREIGN KEY (author_id) REFERENCES Person (id)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE IsCoAuthorOf
(   
   person_id  INT,
   paper_id   INT NOT NULL,
   name       VARCHAR(127),
   INDEX (paper_id),
   FOREIGN KEY (paper_id) REFERENCES Paper (id)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE Topic
(
   id            INT NOT NULL AUTO_INCREMENT,
   conference_id INT NOT NULL,
   name          VARCHAR(127) NOT NULL,
   PRIMARY KEY (id),
   INDEX (conference_id),
   FOREIGN KEY (conference_id) REFERENCES Conference (id)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE IsAboutTopic
(
   paper_id INT NOT NULL,
   topic_id INT NOT NULL,
   PRIMARY KEY (paper_id, topic_id),
   INDEX (paper_id),
   INDEX (topic_id),
   FOREIGN KEY (paper_id) REFERENCES Paper (id)
       ON DELETE CASCADE,
   FOREIGN KEY (topic_id) REFERENCES Topic (id)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE PrefersTopic
(
   person_id  INT NOT NULL,
   topic_id   INT NOT NULL,
   PRIMARY KEY (person_id, topic_id),
   INDEX (person_id),
   INDEX (topic_id),
   FOREIGN KEY (person_id) REFERENCES Person (id)
       ON DELETE CASCADE,       
   FOREIGN KEY (topic_id) REFERENCES Topic (id)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE PrefersPaper
(
   person_id  INT NOT NULL,
   paper_id   INT NOT NULL,
   PRIMARY KEY (person_id, paper_id),
   INDEX (person_id),
   INDEX (paper_id),
   FOREIGN KEY (person_id) REFERENCES Person (id)
       ON DELETE CASCADE,       
   FOREIGN KEY (paper_id) REFERENCES Paper (id)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE DeniesPaper
(
   person_id  INT NOT NULL,
   paper_id   INT NOT NULL,
   PRIMARY KEY (person_id, paper_id),
   INDEX (person_id),
   INDEX (paper_id),
   FOREIGN KEY (person_id) REFERENCES Person (id)
       ON DELETE CASCADE,       
   FOREIGN KEY (paper_id) REFERENCES Paper (id)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE ExcludesPaper
(
   person_id  INT NOT NULL,
   paper_id   INT NOT NULL,
   PRIMARY KEY (person_id, paper_id),
   INDEX (person_id),
   INDEX (paper_id),
   FOREIGN KEY (person_id) REFERENCES Person (id)
       ON DELETE CASCADE,       
   FOREIGN KEY (paper_id) REFERENCES Paper (id)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE ReviewReport
(
   id           INT NOT NULL AUTO_INCREMENT,
   paper_id     INT NOT NULL,
   reviewer_id  INT NOT NULL,
   summary      TEXT,
   remarks      TEXT,
   confidential TEXT,
   PRIMARY KEY (id),
   INDEX (paper_id),
   INDEX (reviewer_id),
   FOREIGN KEY (paper_id) REFERENCES Paper (id)
       ON DELETE CASCADE,
   FOREIGN KEY (reviewer_id) REFERENCES Person (id)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE Criterion
(
   id             INT NOT NULL AUTO_INCREMENT,
   conference_id  INT NOT NULL,
   name           VARCHAR(127) NOT NULL,
   description    TEXT,
   max_value      INT,
   quality_rating INT,
   PRIMARY KEY (id), 
   INDEX (conference_id),
   FOREIGN KEY (conference_id) REFERENCES Conference (id)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE Rating
(
   review_id    INT NOT NULL,
   criterion_id INT NOT NULL,
   grade        INT NOT NULL,
   comment      TEXT,
   PRIMARY KEY (review_id, criterion_id),
   INDEX (review_id),
   INDEX (criterion_id),
   FOREIGN KEY (review_id) REFERENCES ReviewReport (id)
       ON DELETE CASCADE,
   FOREIGN KEY (criterion_id) REFERENCES Criterion (id)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE Forum
(
   id             INT NOT NULL AUTO_INCREMENT,
   conference_id  INT NOT NULL,
   title          VARCHAR(127) NOT NULL,
   forum_type     INT NOT NULL,
   paper_id       INT,
   PRIMARY KEY (id),
   INDEX (conference_id),
   INDEX (forum_type),
   FOREIGN KEY (conference_id) REFERENCES Conference (id)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE Message
(
   id        INT NOT NULL AUTO_INCREMENT,
   forum_id  INT,
   reply_to  INT,
   sender_id INT NOT NULL,
   send_time DATETIME,
   subject   VARCHAR(127),
   text      TEXT,
   PRIMARY KEY (id),
   INDEX (sender_id),
   FOREIGN KEY (sender_id) REFERENCES Person (id)
      ON DELETE CASCADE
) TYPE = INNODB;
