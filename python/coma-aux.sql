-- This module contains the necessary auxilluary data structures needed for
-- our implementation.  They are not part of the core data structure.
--
CREATE TABLE Sessions (
    sid        varchar(32)  PRIMARY KEY,
    login      varchar(255) NOT NULL REFERENCES Users
        ON DELETE CASCADE ON UPDATE CASCADE,
    expires    float        NOT NULL,
    last       float        NOT NULL,
    conference varchar(20)  NOT NULL REFERENCES Conferences
        ON DELETE CASCADE ON UPDATE CASCADE
);


-- Insert the guest user into the data base.  He is necessary to have
-- an initial session where the user is not yet loggen in.  If the user
-- is not logged in, the session references this one.  The empty password
-- actually means that the user cannot log in.
--
INSERT INTO Users
    (email, password, first_name, last_name, title, affiliation)
  VALUES
    ('None', '', 'Guest', 'Guest', 0, '');


-- Insert into the conferences data base an initial conference.  This
-- is necessary to have an initial session, where the user has not yet
-- chosen a conference.  If no conference has been chosen, the Session
-- references this one.
--
INSERT INTO Conferences
    (abbreviation, name, homepage, paper_submission_deadline,
     review_deadline, notification_deadline, final_version_deadline,
     conference_start, conference_end)
  VALUES
    ('None', 'None', 'None', date '1901-01-01', date '1901-01-01',
     date '1901-01-01', date '1901-01-01', date '1901-01-01',
     date '1901-01-01');
