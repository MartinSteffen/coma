CREATE TABLE Conference 
(
   abbreviation                 VARCHAR(20) NOT NULL,
   name                         VARCHAR(127) NOT NULL,
   description                  TEXT,
   homepage                     VARCHAR(127),
   abstract_submission_deadline DATE,
   paper_submission_deadline    DATE,
   review_deadline              DATE,
   final_version_deadline       DATE,
   notification                 DATE,
   conference_start             DATE,
   conference_end               DATE,
   min_reviews_per_paper        INT,
   PRIMARY KEY (abbreviation)
) TYPE = MYISAM;
	
CREATE TABLE Person
(
   email        VARCHAR(127) NOT NULL,
   conference   VARCHAR(29) NOT NULL,
   roles        INT,
   first_name   VARCHAR(127),
   last_name    VARCHAR(127),
   title        INT,
   affiliation  VARCHAR(127),
   phone_number VARCHAR(20),
   fax_number   VARCHAR(20),
   street       VARCHAR(127),
   postal_code  VARCHAR(20),
   city         VARCHAR(127),
   state        VARCHAR(127),
   country      VARCHAR(127),
   password     VARCHAR(32) NOT NULL,
   PRIMARY KEY (email),
   FOREIGN KEY (conference) REFERENCES Conference(abbreviation)
         ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE Paper
(
   id           BIGINT NOT NULL AUTO_INCREMENT,
   conference   VARCHAR(20) NOT NULL,
   author       VARCHAR(127) NOT NULL,
   title        VARCHAR(127) NOT NULL,
   abstract     MEDIUMTEXT,
   last_edited  DATETIME, /*wird von CoMa automatisch gesetzt*/
   version      INT,      /*wird von CoMa automatisch gesetzt*/
   filename     VARCHAR(127),
   state        INT,
   format       INT,
   PRIMARY KEY (id),
   FOREIGN KEY (conference) REFERENCES Conference(abbreviation)
       ON DELETE CASCADE,
   FOREIGN KEY (author) REFERENCES Person(email)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE IsCoAuthorOf
(
   conference  VARCHAR(20) NOT NULL,
   paper_id    BIGINT NOT NULL,
   name        VARCHAR(127) NOT NULL,
   email       VARCHAR(127),
   FOREIGN KEY (conference) REFERENCES Conference(abbreviation)
       ON DELETE CASCADE,
   FOREIGN KEY (paper_id) REFERENCES Paper(id)
       ON DELETE CASCADE,
   FOREIGN KEY (email) REFERENCES Person(email)
) TYPE = INNODB;

CREATE TABLE Topic
(
   id         BIGINT NOT NULL AUTO_INCREMENT,
   conference VARCHAR(20) NOT NULL,
   name       VARCHAR(127) NOT NULL,
   UNIQUE(id),
   PRIMARY KEY (id),
   FOREIGN KEY (conference) REFERENCES Conference(abbreviation)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE IsAboutTopic
(
   paper_id BIGINT NOT NULL,
   topic    VARCHAR(127) NOT NULL,
   PRIMARY KEY (paper_id, topic),
   FOREIGN KEY (paper_id) REFERENCES Paper(id)
       ON DELETE CASCADE,
   FOREIGN KEY (topic) REFERENCES Topic(name)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE PrefersTopic
(
   person     VARCHAR(127) NOT NULL,
   paper_id   BIGINT NOT NULL,
   FOREIGN KEY (person) REFERENCES Person(email)
       ON DELETE CASCADE,       
   FOREIGN KEY (paper_id) REFERENCES Paper(id)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE ReviewReport
(
   id           BIGINT NOT NULL AUTO_INCREMENT,
   conference   VARCHAR(20) NOT NULL,
   paper_id     BIGINT NOT NULL,
   reviewer     VARCHAR(127) NOT NULL,
   summary      MEDIUMTEXT,
   remarks      TEXT,
   confidential TEXT,
   PRIMARY KEY (id),
   FOREIGN KEY (conference) REFERENCES Conference(abbreviation)
       ON DELETE CASCADE,
   FOREIGN KEY (paper_id) REFERENCES Paper(id)
       ON DELETE CASCADE,
   FOREIGN KEY (reviewer) REFERENCES Person(email)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE Rating
(
   review_id BIGINT NOT NULL,
   grade     INT NOT NULL,
   comment   TEXT,
   FOREIGN KEY (review_id) REFERENCES ReviewReport(id)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE Criterion
(
   id          BIGINT NOT NULL AUTO_INCREMENT,
   conference  VARCHAR(20) NOT NULL,
   name        VARCHAR(127) NOT NULL,
   description TEXT,
   max_value   INT,
   PRIMARY KEY (id),
   FOREIGN KEY (conference) REFERENCES Conference(abbreviation)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE Forum
(
   id          BIGINT AUTO_INCREMENT,
   conference  VARCHAR(20) NOT NULL,
   title       VARCHAR(127) NOT NULL,
   description TEXT,
   PRIMARY KEY (id),
   FOREIGN KEY (conference) REFERENCES Conference(abbreviation)
       ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE ParticipatesInForum
(
   forum_id     BIGINT NOT NULL,
   participant  VARCHAR(127) NOT NULL,
   PRIMARY KEY (forum_id, participant),
   FOREIGN KEY (participant) REFERENCES Person(email)
      ON DELETE CASCADE,
   FOREIGN KEY (forum_id) REFERENCES Forum(id) 
      ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE Message
(
   id        VARCHAR(127) NOT NULL,
   sender    VARCHAR(127) NOT NULL,
   send_time DATETIME,  /*wird von coma automatisch gesetzt*/
   subject   VARCHAR(127),
   text      MEDIUMTEXT,
   PRIMARY KEY (id),
   FOREIGN KEY (sender) REFERENCES Person(email)
      ON DELETE CASCADE
) TYPE = INNODB;

CREATE TABLE HasThread
(
   forum_id   BIGINT NOT NULL,
   message_id VARCHAR(127) NOT NULL,
   FOREIGN KEY (forum_id) REFERENCES Forum(id)
       ON DELETE CASCADE,
   FOREIGN KEY (message_id) REFERENCES Message(id)
       ON DELETE CASCADE
) TYPE = INNODB;

/* Sollen Enumerations wirklich als eigene Tabellen realisiert werden? */

CREATE TABLE Title
(
   id          INT NOT NULL,
   designation VARCHAR(50),
   PRIMARY KEY (id)
) TYPE = MYISAM;

CREATE TABLE PaperState
(
   id          INT NOT NULL,
   designation VARCHAR(20),
   PRIMARY KEY (id)
) TYPE = MYISAM;

CREATE TABLE MimeType
(
   id          INT NOT NULL, 
   designation VARCHAR(10),
   PRIMARY KEY (id)
) TYPE = MYISAM;

