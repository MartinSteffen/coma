<?php
/**
 * Install Skript für Coma1
 *
 * Stand der Tabellen 23.01.2005
 *
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/

/**
 * Wichtig, damit Config Dateie eingebunden werden kann
 *
 * @ignore
 */
define('IN_COMA1', true);
// MySQL Daten bereit stellen
require_once('./include/config.inc.php');

$conn = @mysql_connect($sqlServer, $sqlUser , $sqlPassword);
if (empty($conn)) {
  echo("Could not connect to MySQL Server $sqlServer!<br>");
  echo("Please check 'include/config.inc.php' for correct values!<br><br>");
}
if (!mysql_select_db($sqlDatabase, $conn)) {
  echo(mysql_error($conn).'<br>');
  echo("Please check 'include/config.inc.php' for correct values!<br><br>");
}

$sql = 'DROP TABLE IF EXISTS `Conference`, `ConferenceConfig`, `Criterion`, '.
       '                     `DeniesPaper`, `Distribution`, `ExcludesPaper`,'.
       '                     `Forum`, `IsAboutTopic`, `IsCoAuthorOf`, `Message`,'.
       '                     `Paper`, `Person`, `PrefersPaper`, `PrefersTopic`,'.
       '                     `Rating`, `ReviewReport`, `Role`, `Session`, `Topic`';
$result = mysql_query($sql, $conn);
if (empty($result)) {
  echo('Dropping Tables:<br>');
  echo(mysql_error($conn).'<br><br>');
}

// BEGIN SQL DATA
$sql = "
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
   email        VARCHAR(127) UNIQUE NOT NULL,
   phone_number VARCHAR(20),
   fax_number   VARCHAR(20),
   street       VARCHAR(127),
   postal_code  VARCHAR(20),
   city         VARCHAR(127),
   state        VARCHAR(127),
   country      VARCHAR(127),
   password     VARCHAR(127) NOT NULL,
   PRIMARY KEY (id)
) TYPE = INNODB;


CREATE TABLE Role
(
   conference_id  INT NOT NULL,
   person_id      INT NOT NULL,
   role_type      INT NOT NULL,
   state          INT,
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


CREATE TABLE Session (
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
   auto_activate_account        INT NOT NULL DEFAULT '1',
   auto_open_paper_forum        INT NOT NULL DEFAULT '1',
   auto_add_reviewers           INT NOT NULL DEFAULT '1',
   number_of_auto_add_reviewers INT NOT NULL,
   PRIMARY KEY (id)
) TYPE = INNODB;

CREATE TABLE Distribution
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
";
// END SQL DATA

$result = mysql_query($sql, $conn);
if (empty($result)) {
  echo('Creating Tables:<br>');
  echo(mysql_error($conn).'<br><br>');
}
?>