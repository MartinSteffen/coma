#
# Dieses Script f�llt eine (bis auf die gesamte Tabellenstruktur) leere Datenbank
# mit den Werten, die "festverdrahtet" sind, also z.B. die Tabelle "Roles" mit den festen
# Rolle-ID-Paaren, die wir nutzen werden.

# Es fehlt noch die Bef�llung f�r die Tabellen:
# -rights
# -roledescription
# -modules
# -rights

# Datenbank: `coma2`

#
# Daten f�r Tabelle `conference`
#


#
# Daten f�r Tabelle `criterion`
#


#
# Daten f�r Tabelle `excludespaper`
#


#
# Daten f�r Tabelle `forum`
#


#
# Daten f�r Tabelle `forumtype`
#

INSERT INTO forumtype (id, type) VALUES (1, 'open forum');
INSERT INTO forumtype (id, type) VALUES (2, 'committee forum');
INSERT INTO forumtype (id, type) VALUES (3, 'paper discussion forum');

#
# Daten f�r Tabelle `isabouttopic`
#


#
# Daten f�r Tabelle `iscoauthorof`
#


#
# Daten f�r Tabelle `message`
#


#
# Daten f�r Tabelle `modules`
#


#
# Daten f�r Tabelle `paper`
#


#
# Daten f�r Tabelle `paperstate`
#


#
# Daten f�r Tabelle `person`
#


#
# Daten f�r Tabelle `preferspaper`
#


#
# Daten f�r Tabelle `preferstopic`
#


#
# Daten f�r Tabelle `properties`
#


#
# Daten f�r Tabelle `rating`
#


#
# Daten f�r Tabelle `reviewreport`
#


#
# Daten f�r Tabelle `rights`
#


#
# Daten f�r Tabelle `role`
#


#
# Daten f�r Tabelle `roledescription`
#


#
# Daten f�r Tabelle `roles`
#

INSERT INTO roles (id, role) VALUES (1, 'Admin');
INSERT INTO roles (id, role) VALUES (2, 'Chair');
INSERT INTO roles (id, role) VALUES (3, 'Reviewer');
INSERT INTO roles (id, role) VALUES (4, 'Author');
INSERT INTO roles (id, role) VALUES (5, 'Participant');

#
# Daten f�r Tabelle `state`
#

INSERT INTO state (id, state) VALUES (1, 'active');
INSERT INTO state (id, state) VALUES (2, 'not active');
INSERT INTO state (id, state) VALUES (3, 'invited');

#
# Daten f�r Tabelle `topic`
#


