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
   email        VARCHAR(127) NOT NULL,
   phone_number VARCHAR(20),
   fax_number   VARCHAR(20),
   street       VARCHAR(127),
   postal_code  VARCHAR(20),
   city         VARCHAR(127),
   state        VARCHAR(127),
   country      VARCHAR(127),
   password     VARCHAR(32) NOT NULL, /*When inserting data use
                     the sql-function encode(str, pattern), to 
                     get the orig. data use decode(str,pattern)*/

   PRIMARY KEY (id)
) TYPE = INNODB;
	
CREATE TABLE Role
(
   conference_id  INT NOT NULL,
   person_id      INT NOT NULL,
   role_type      INT NOT NULL,
   state          INT NOT NULL,

   FOREIGN KEY (conference) REFERENCES Conference(id)
       ON DELETE CASCADE,
   FOREIGN KEY (person_id) REFERENCES Person(id)
       ON DELETE CASCADE,
   FOREIGN KEY (role_type) REFERENCES Roles(id)
       ON DELETE CASCADE,
   FOREIGN KEY (state) REFERENCES Status(id)
       ON DELETE CASCADE
) TYPE = INNODB;


CREATE TABLE Paper
(
   id              INT NOT NULL AUTO_INCREMENT,
   conference_id   INT NOT NULL,
   author_id       INT NOT NULL,
   title           VARCHAR(127) NOT NULL,
   abstract        TEXT,
   last_edited     DATETIME, /*wird von CoMa automatisch gesetzt*/
   version         INT,      /*wird von CoMa automatisch gesetzt*/
   filename        VARCHAR(127),
   state           INT NOT NULL,
   mim_type        VARCHAR(127),

   PRIMARY KEY (id),
   FOREIGN KEY (conference) REFERENCES Conference(id)
       ON DELETE CASCADE,
   FOREIGN KEY (author_id) REFERENCES Person(id)
       ON DELETE CASCADE,
   FOREIGN KEY (state) REFERENCES PaperState(id)
       ON DELETE CASCADE
) TYPE = INNODB;


CREATE TABLE IsCoAuthorOf
(
   person_id  INT,
   paper_id   INT NOT NULL,
   name       VARCHAR(127) NOT NULL,

   FOREIGN KEY (paper_id) REFERENCES Paper(id)
       ON DELETE CASCADE
) TYPE = INNODB;


CREATE TABLE Topic
(
   id            INT NOT NULL AUTO_INCREMENT,
   conference_id INT NOT NULL,
   name          VARCHAR(127) NOT NULL,
   
   PRIMARY KEY (id),
   FOREIGN KEY (conference) REFERENCES Conference(id)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE IsAboutTopic
(
   paper_id INT NOT NULL,
   topic_id INT NOT NULL,

   FOREIGN KEY (paper_id) REFERENCES Paper(id)
       ON DELETE CASCADE,
   FOREIGN KEY (topic_id) REFERENCES Topic(id)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE PrefersTopic
(
   person_id  INT NOT NULL,
   topic_id   INT NOT NULL,

   FOREIGN KEY (person_id) REFERENCES Person(id)
       ON DELETE CASCADE,       
   FOREIGN KEY (topic_id) REFERENCES Topic(id)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE PrefersPaper
(
   person_id  INT NOT NULL,
   paper_id   INT NOT NULL,

   FOREIGN KEY (person_id) REFERENCES Person(id)
       ON DELETE CASCADE,       
   FOREIGN KEY (paper_id) REFERENCES Paper(id)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE ExcludesPaper
(
   person_id  INT NOT NULL,
   paper_id   INT NOT NULL,

   FOREIGN KEY (person_id) REFERENCES Person(id)
       ON DELETE CASCADE,       
   FOREIGN KEY (paper_id) REFERENCES Paper(id)
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
   FOREIGN KEY (paper_id) REFERENCES Paper(id)
       ON DELETE CASCADE,
   FOREIGN KEY (reviewer_id) REFERENCES Person(id)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE Rating
(
   review_id    INT NOT NULL,
   criterion_id INT NOT NULL,
   grade        INT NOT NULL,
   comment      TEXT,

   FOREIGN KEY (review_id) REFERENCES ReviewReport(id)
       ON DELETE CASCADE,
   FOREIGN KEY (criretion_id) REFERENCES Criterion(id)
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
   FOREIGN KEY (conference_id) REFERENCES Conference(id)
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
   FOREIGN KEY (conference_id) REFERENCES Conference(id)
       ON DELETE CASCADE,
   FOREIGN KEY (forum_type) REFERENCES ForumType(id)
       ON DELETE CASCADE

) TYPE = INNODB;

CREATE TABLE Message
(
   id        INT NOT NULL AUTO_INCREMENT,
   forum_id  INT,
   sender_id INT NOT NULL,
   send_time DATETIME,  /*wird von coma automatisch gesetzt*/
   subject   VARCHAR(127),
   text      TEXT,

   PRIMARY KEY (id),
   FOREIGN KEY (sender_id) REFERENCES Person(id)
      ON DELETE CASCADE
) TYPE = INNODB;


/* Sollen Enumerations wirklich als eigene Tabellen realisiert werden? */

CREATE TABLE Roles
(
   id   INT NOT NULL AUTO_INCREMENT,
   role VARCHAR(20) NOT NULL,

   PRIMARY KEY (id)
) TYPE = INNODB;


CREATE TABLE Status
(
   id      INT NOT NULL AUTO_INCREMENT,
   status  VARCHAR(20) NOT NULL,

   PRIMARY KEY (id)
) TYPE = INNODB;

CREATE TABLE PaperState
(
   id     INT NOT NULL AUTO_INCREMENT,
   state  VARCHAR(20) NOT NULL,

   PRIMARY KEY (id)
) TYPE = INNODB;

CREATE TABLE ForumType
(
   id     INT NOT NULL AUTO_INCREMENT,
   type   VARCHAR(127) NOT NULL,

   PRIMARY KEY (id)
) TYPE = INNODB;


