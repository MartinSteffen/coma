-- This module contains the necessary auxilluary data structures needed for
-- our implementation.  They are not part of the core data structure.
--
CREATE TABLE Sessions (
    sid       varchar(80) PRIMARY KEY,
    login     varchar(80) REFERENCES Users ON DELETE CASCADE ON UPDATE CASCADE,
    expires   float       NOT NULL,
    last      float       NOT NULL
);

INSERT INTO Users
    (email, password, first_name, last_name, title, affiliation)
  VALUES
    ('None', '', 'Guest', '', 0, '');
