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
