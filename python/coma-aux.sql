-- This module contains the necessary auxilluary data structures needed for
-- our implementation.  They are not part of the core data structure.
--
CREATE TABLE Sessions (
    sid         varchar(80) primary key,
    login       varchar(80) references Users,
    last        timestamp,
    expires     timestamp
);
