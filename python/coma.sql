-- Until the students come up with a decent implementation of the data
-- model, we define our own one here.  I do not know whether this is
-- suitable for MySQL as I wrote it for PostgreSQL.
--

-- General note:  A person does need a first name.  The last name is
-- optional.  This covers for the case of "Robby".

-- ---------------------------------------------------------------------------
-- Conferences:
-- Define a conference.  CoMa may manage multiple conferences.
--
-- The "description" is better put onto the home page.  We are only concerned
-- with paper submission.
--
-- minimum number of reviews defaults to 1 and may never be null.
-- ---------------------------------------------------------------------------

CREATE TABLE Conferences (
    abbreviation     varchar(20)  PRIMARY KEY CHECK (abbreviation <> ''),
    name             varchar(127) NOT NULL CHECK (name <> ''),
    homepage         varchar(127) NOT NULL CHECK (homepage <> ''),
    abstract_submission_deadline  date,
    paper_submission_deadline     date not null,
    review_deadline               date not null,
    notification_deadline         date not null,
    final_version_deadline        date not null,
    conference_start              date not null,
    conference_end                date not null,
    min_reviews_per_paper  smallint DEFAULT 1 NOT NULL
          CHECK(min_reviews_per_paper > 0)
);





CREATE TABLE Topics (
    name         varchar(127) UNIQUE NOT NULL CHECK (name <> ''),
    conference   varchar(20) REFERENCES Conferences
	 ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (name, conference)
) WITHOUT OIDS;





-- ---------------------------------------------------------------------------
-- Users
--
-- title is a number, where
-- 1 is Mr
-- 2 is Ms
-- 3 is Mrs
-- 4 is Dr
-- 5 is Prof
-- 6 is Prof Dr
--
-- The system wide role encodes the following:
--  0 : The user may modify other user's system roles.
--  1 : The user may create or modify a conference.
-- ---------------------------------------------------------------------------

CREATE TABLE Users (
    email                        varchar(255) PRIMARY KEY,
    password                     varchar(20)  NOT NULL,
    title                        smallint     NOT NULL,
    first_name                   varchar(255) NOT NULL,
    last_name                    varchar(255) NOT NULL,
    affiliation                  varchar(127) NOT NULL,
    phone_number                 varchar(20),
    fax_number                   varchar(20),
    street                       varchar(127),
    postal_code                  varchar(20),
    city                         varchar(127),
    state                        varchar(127),
    country                      varchar(127),
    sys_role                     bit(2)       NOT NULL DEFAULT B'00',
);



-- ---------------------------------------------------------------------------
-- Users
--
-- roles is a bit-vector, where index 
-- 1 identifies the user as admin
-- 2 identifies the user as chair
-- 3 identifies the user as reviewer
-- 4 identifies the user as author
-- If all bits are false, the user is not active anymore.
--
-- As an assertion we may want to require that if the users password
-- is empty, he is also not active anymore.
--
-- ---------------------------------------------------------------------------

CREATE TABLE Roles (
    email        varchar(255) REFERENCES Users
        ON DELETE CASCADE ON UPDATE CASCADE,
    conference   varchar(20)  REFERENCES Conferences
        ON DELETE CASCADE ON UPDATE CASCADE,
    role         bit(4) NOT NULL ,
    PRIMARY KEY(email, conference)
);



-- -------------------------------------------------------------------------
-- Fora.
-- -------------------------------------------------------------------------

CREATE TABLE Forum (
    id                            serial primary key,
    conference                    varchar(20) REFERENCES Conferences
        ON DELETE CASCADE ON UPDATE CASCADE,
    title                         varchar(127) not null,
    description                   text not null
);





CREATE TABLE Message (
    id                            varchar(127) primary key,
    in_reply_to                   varchar(127) null,
    send_time                     timestamp not null,
    subject                       varchar(127) not null,
    contents                      text not null
);





CREATE TABLE Threads (
    forum_id                      integer REFERENCES Forum
        ON DELETE CASCADE ON UPDATE CASCADE,
    message_id                    varchar(127) REFERENCES Message
        ON DELETE RESTRICT ON UPDATE RESTRICT,
    UNIQUE(forum_id, message_id)
);





-- ---------------------------------------------------------------------------
-- Papers
--
-- The abstract of a paper is the description inherited from the forum
-- ---------------------------------------------------------------------------

CREATE TABLE Papers (
    contactauthor                varchar(127) REFERENCES Users
        ON DELETE CASCADE ON UPDATE CASCADE,
    confernece                   varchar(20) REFERENCES Conferences
        ON DELETE CASCADE ON UPDATE CASCADE,
    filename                     varchar(127) unique not null,
    state                        smallint not null,
    format                       smallint not null,
    PRIMARY KEY(id)
) INHERITS(Forum);





CREATE TABLE CoAuthors (
    paper_id                     integer REFERENCES Papers
        ON DELETE CASCADE ON UPDATE CASCADE,
    first_name                   varchar(127) not null,
    last_name                    varchar(127)
);






-- Associate topics to papers
CREATE TABLE AboutTopics (
    paper_id                     integer     references Papers
        ON DELETE CASCADE ON UPDATE CASCADE,
    topic varchar(127),
    conference varchar(20),
    FOREIGN KEY (topic, conference) REFERENCES Topics(name, conference)
        ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (paper_id, topic, conference)
);





-- Associate topics to reviewers
CREATE TABLE PreferedTopics (
    email                        varchar(127) REFERENCES Users
        ON DELETE CASCADE ON UPDATE CASCADE,
    topic varchar(127),
    conference varchar(20),
    FOREIGN KEY (topic, conference) REFERENCES Topics(name, conference)
        ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (email, topic, conference)
);





-- Associate papers to reviewers
CREATE TABLE PreferedPapers (
    email                        varchar(127) REFERENCES Users
        ON DELETE CASCADE ON UPDATE CASCADE,
    paper_id                     integer      REFERENCES Papers
        ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (email, paper_id)
);





-- ---------------------------------------------------------------------------
-- ExcludedPapers:
-- Associate papers to reviewers which they must not see or review.
-- subsets SELECT email, paper_id FROM Papers WHERE User is reviewer; 
-- ---------------------------------------------------------------------------

CREATE TABLE ExcludedPapers (
    email                        varchar(127) REFERENCES Users
        ON DELETE CASCADE ON UPDATE CASCADE,
    paper_id                     integer      REFERENCES Papers
        ON DELETE CASCADE ON UPDATE CASCADE,
    explenation                  text NOT NULL CHECK(explenation <> ''),
    PRIMARY KEY(email, paper_id)
);





-- ---------------------------------------------------------------------------
-- DeniedPapers:
-- Associate papers to reviewers which they do not want to review.  Allow an
-- optional explenation.
-- ---------------------------------------------------------------------------

CREATE TABLE DeniedPapers (
    email                        varchar(127) REFERENCES Users
        ON DELETE CASCADE ON UPDATE CASCADE,
    paper_id                     integer      REFERENCES Papers
        ON DELETE CASCADE ON UPDATE CASCADE,
    explenation                  text NOT NULL CHECK(explenation <> ''),
    PRIMARY KEY(email, paper_id)
);





-- ---------------------------------------------------------------------------
-- ReviewReports:
-- Associate a review report of a responsible to each paper.
-- Additional constraint: A review is finished, if and only if the reviewer
-- provides a summary, and remarks, and all grades to Criteria (see below).
-- ---------------------------------------------------------------------------

CREATE TABLE ReviewReports (
    id                           serial       PRIMARY KEY,
    conference                   varchar(20)  REFERENCES Conferences
        ON DELETE CASCADE ON UPDATE CASCADE,
    paper_id                     integer      REFERENCES Papers
        ON DELETE CASCADE ON UPDATE RESTRICT,
    responsible                  varchar(127) REFERENCES Users
        ON DELETE CASCADE ON UPDATE CASCADE,
    reviewer                     varchar(127),
    summary                      text,
    remarks                      text,
    confidential                 text,
    UNIQUE(paper_id, responsible)
);




-- ---------------------------------------------------------------------------
-- Criteria:
-- Associate numeric review criteria to conferences.  max_value is a grade,
-- which has to be bigger than 0, with values ranging from 0 to max_value
-- inclusive.
-- ---------------------------------------------------------------------------

CREATE TABLE Criteria (
    name                         varchar(20) PRIMARY KEY,
    conference                   varchar(20) REFERENCES Conferences
        ON DELETE CASCADE ON UPDATE CASCADE,
    description                  text        NOT NULL,
    max_value                    smallint    NOT NULL CHECK (max_value > 0)
);





-- ---------------------------------------------------------------------------
-- Ratings:
-- Describe a particular rating of a criterion.  grade must be a value from 
-- the range 0 to its criterion's max_value
-- An additional constraint of a rating is:  For each criterion and each of
-- its admissible ratings there exists a row in the table describing it.
-- ---------------------------------------------------------------------------

CREATE TABLE Ratings (
    criterion                    varchar(20) REFERENCES Criteria
        ON DELETE CASCADE ON UPDATE CASCADE,
    grade                        integer     NOT NULL CHECK (grade >= 0),
    description                  text        NOT NULL,
    PRIMARY KEY (criterion, grade)
);




-- ---------------------------------------------------------------------------
-- PaperRating:
-- Associate to each report and criterion a rating.
-- Additional constraints:  For each review report and each criterion there
-- is a rating.
-- ---------------------------------------------------------------------------

CREATE TABLE PaperRating (
    report_id                    integer     REFERENCES ReviewReports
        ON DELETE CASCADE ON UPDATE CASCADE,
    criterion                    varchar(20) REFERENCES Criteria
        ON DELETE CASCADE ON UPDATE CASCADE,
    rating                       integer     NOT NULL CHECK (rating >= 0),
    PRIMARY KEY (report_id, criterion)
);
