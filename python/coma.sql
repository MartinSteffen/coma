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
    abbreviation                 varchar(20) primary key,
    name                         varchar(127) not null,
    homepage                     varchar(127) not null,
    abstract_submission_deadline date null,
    paper_submission_deadline    date not null,
    review_deadline              date not null,
    notification_deadline        date not null,
    final_version_deadline       date not null,
    conference_start             date not null,
    conference_end               date not null,
    min_reviews_per_paper        smallint not null default 1
);





CREATE TABLE Topics (
    name                         varchar(127) primary key,
    conference                   varchar(20) references Conferences
);





-- ---------------------------------------------------------------------------
-- Users
--
-- roles is a bit-vector, where index 
-- 1 identifies the user as admin
-- 2 identifies the user as chair
-- 3 identifies the user as reviewer
-- 4 identifies the user as author
--
-- title is a number, where
-- 0 is Mr
-- 1 is Ms
-- 2 is Mrs
-- 3 is Dr
-- 4 is Prof
-- 5 is Prof Dr
--
-- ---------------------------------------------------------------------------

CREATE TABLE Users (
    email                        varchar(127) primary key,
    conference                   varchar(20) references Conferences,
    roles			 bit(4) not null,
    first_name                   varchar(20) not null,
    last_name                    varchar(20),
    title                        smallint not null,
    affiliation                  varchar(127) not null,
    phone_number                 varchar(20),
    fax_number                   varchar(20),
    street                       varchar(127),
    postal_code                  varchar(20),
    city                         varchar(127),
    state                        varchar(127),
    country                      varchar(127),
    password                     varchar(20) not null
);





-- -------------------------------------------------------------------------
-- Fora.
-- -------------------------------------------------------------------------

CREATE TABLE Forum (
    id                            serial primary key,
    conference                    varchar(20) references Conference,
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
    forum_id                      integer references Forum,
    message_id                    varchar(127) references Message,
    UNIQUE(forum_id, message_id)
);





-- ---------------------------------------------------------------------------
-- Papers
--
-- The abstract of a paper is the description inherited from the forum
-- ---------------------------------------------------------------------------

CREATE TABLE Papers (
    contactauthor                varchar(127) references Users,
    filename                     varchar(127) unique not null,
    state                        smallint not null,
    format                       smallint not null
) INHERITS(Forum);





CREATE TABLE CoAuthors (
    conference                   varchar(20) references Conferences,
    paper_id                     integer references Papers,
    first_name                   varchar(127) not null,
    last_name                    varchar(127) null
);






-- Associate topics to papers
CREATE TABLE AboutTopics (
    paper_id                     integer     references Papers,
    topic                        varchar(20) references Topics,
    PRIMARY KEY (paper_id, topic)
);





-- Associate topics to reviewers
CREATE TABLE PreferedTopics (
    email                        varchar(127) REFERENCES Users,
    topic                        varchar(127) REFERENCES Topics,
    PRIMARY KEY (email, topic)
);





-- Associate papers to reviewers
CREATE TABLE PreferedPapers (
    email                        varchar(127) REFERENCES Users,
    paper_id                     integer      REFERENCES Papers,
    PRIMARY KEY (email, paper_id)
);





-- ---------------------------------------------------------------------------
-- ExcludedPapers:
-- Associate papers to reviewers which they must not see or review.
-- subsets SELECT email, paper_id FROM Papers WHERE User is reviewer; 
-- ---------------------------------------------------------------------------

CREATE TABLE ExcludedPapers (
    email                        varchar(127) REFERENCES Users,
    paper_id                     integer      REFERENCES Papers,
    explenation                  text         NULL,
    PRIMARY KEY(email, paper_id)
);





-- ---------------------------------------------------------------------------
-- DeniedPapers:
-- Associate papers to reviewers which they do not want to review.  Allow an
-- optional explenation.
-- ---------------------------------------------------------------------------

CREATE TABLE DeniedPapers (
    email                        varchar(127) REFERENCES Users,
    paper_id                     integer      REFERENCES Papers,
    explenation                  text         NULL,
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
    conference                   varchar(20)  NOT NULL REFERENCES Conferences,
    paper_id                     integer      NOT NULL REFERENCES Papers,
    responsible                  varchar(127) NOT NULL REFERENCES Users,
    reviewer                     varchar(127) NULL,
    summary                      text         NULL,
    remarks                      text         NULL,
    confidential                 text         NULL,
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
    conference                   varchar(20) REFERENCES Conference,
    description                  text        NOT NULL,
    max_value                    smallint    NOT NULL CHECK (max_value > 0)
);





-- ---------------------------------------------------------------------------
-- Rating:
-- Describe a particular rating of a criterion.  grade must be a value from 
-- the range 0 to its criterion's max_value
-- An additional constraint of a rating is:  For each criterion and each of
-- its admissible ratings there exists a row in the table describing it.
-- ---------------------------------------------------------------------------

CREATE TABLE Rating (
    criterion                    varchar(20) REFERENCES Criterion,
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
    report_id                    integer     REFERENCES ReviewReport,
    criterion                    varchar(20) REFERENCES Criterion,
    rating                       integer     NOT NULL CHECK (rating >= 0),
    PRIMARY KEY (report_id, criterion)
);
