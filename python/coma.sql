-- Until the students come up with a decent implementation of the data
-- model, we define our own one here.  I do not know whether this is
-- suitable for MySQL as I wrote it for PostgreSQL.
--
CREATE TABLE Conferences (
    abbreviation                 varchar(20) primary key,
    name                         varchar(127) not null,
    description                  Text,
    homepage                     varchar(127),
    abstract_submission_deadline date,
    paper_submission_deadline    date,
    review_deadline              date,
    notification_deadline        date,
    final_version_deadline       date,
    conference_start             date,
    conference_end               date,
    min_reviews_per_paper        smallint
);

CREATE TABLE Users (
    email                        varchar(127) primary key,
    conference                   varchar(20),
    roles
    first_name                   varchar(20),
    last_name                    varchar(20),
    title                        Title,
    affiliation                  varchar(127),
    phone_number                 varchar(20),
    fax_number                   varchar(20),
    street                       varchar(127),
    postal_code                  varchar(20),
    city                         varchar(127),
    state                        varchar(127),
    country                      varchar(127),
    password                     varchar(20)
);

-- -------------------------------------------------------------------------
-- Fora.
-- -------------------------------------------------------------------------

CREATE TABLE Message (
    id                            varchar(127) primary key,
    in_reply_to                   varchar(127) null,
    send_time                     timestamp,
    subject                       varchar(127),
    contents                      text
);

CREATE TABLE Forum (
    id                            integer autoincrement primary key,
    conference                    varchar(20) references Conference,
    title                         varchar(127) unique not null,
    description                   text
);

CREATE TABLE Threads (
    forum_id                      integer references Forum,
    message_id                    varchar(127) references Message,
    UNIQUE(forum_id, message_id)
);

-- ---------------------------------------------------------------------------
-- Papers
-- ---------------------------------------------------------------------------

CREATE TABLE Papers (
    contactauthor                varchar(127), -- references Users
    abstract                     Text,
    filename                     varchar(127) unique,
    state                        integer,
    format                       integer
) INHERITS(Forum);

CREATE TABLE CoAuthors (
    conference                   varchar(20),
    paper_id                     integer references Papers,
    name                         varchar(127)
);

CREATE TABLE Topics (
    name                         varchar(127) primary key,
    conference                   varchar(20) references Conference
);

CREATE TABLE AboutTopic (
    paper_id                     integer     references Papers,
    topic                        varchar(20) references Topics
);

CREATE TABLE PrefersTopic (
    email                        varchar(127) references Users,
    topic                        varchar(127) references Topics,
    UNIQUE(email, topic)
);

CREATE TABLE PrefersPaper (
    email                        varchar(127) references Users,
    paper_id                     integer references Papers,
    UNIQUE(email, paper_id)
);

CREATE TABLE ExcludedPaper (
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
