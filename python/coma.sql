-- Until the students come up with a decent implementation of the data
-- model, we define our own one here.  I do not know whether this is
-- suitable for MySQL as I wrote it for PostgreSQL.
--




-- The description is better put onto the home page.  We are only concerned
-- with paper submission.
--
-- minimum number of reviews defaults to 1 and may never be null.
--
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
    last_name                    varchar(127) not null
);






-- Associate topics to papers
CREATE TABLE AboutTopics (
    paper_id                     integer     references Papers,
    topic                        varchar(20) references Topics,
    UNIQUE(paper_id, topic)
);





-- Associate topics to reviewers
CREATE TABLE PreferedTopics (
    email                        varchar(127) references Users,
    topic                        varchar(127) references Topics,
    UNIQUE(email, topic)
);





-- Associate papers to reviewers
CREATE TABLE PreferedPapers (
    email                        varchar(127) references Users,
    paper_id                     integer references Papers,
    UNIQUE(email, paper_id)
);





-- Associate papers to reviewers
CREATE TABLE ExcludedPapers (
    email                        varchar(127) references Users,
    paper_id                     integer references Papers,
    UNIQUE(email, paper_id)
);





-- Associate papers to reviewers
CREATE TABLE DeniedPapers (
    email                        varchar(127) references Users,
    paper_id                     integer references Papers,
    UNIQUE(email, paper_id)
);





-- ---------------------------------------------------------------------------
-- Review reports.
-- ---------------------------------------------------------------------------

CREATE TABLE ReviewReport (
    id                           integer primary key,
    conference                   varchar(20) references Conferences,
    paper_id                     integer references Papers,
    responsible                  varchar(127) references Users,
    summary                      Text,
    remarks                      Text,
    confidential                 Text,
    UNIQUE(paper_id, responsible)
);

CREATE TABLE Criterion (
    name                         varchar(20) primary key,
    description                  text,
    conference                   varchar(20) references Conference,
    max_value                    integer
);

CREATE TABLE Rating (
    criterion                    varchar(20) refereces Criterion primary key,
    grade                        integer,
    description                  text,
    UNIQUE(criterion, grade)
);

CREATE TABLE PaperRating (
    paper_id                     integer references Papers,
    criterion                    varchar(20) references Criterion,
    rating                       integer,
    UNIQUE(paper_id, criterion)
);
