#
# Dieses Script füllt eine (bis auf die gesamte Tabellenstruktur) leere Datenbank
# mit den Werten, die "festverdrahtet" sind, also z.B. die Tabelle "Roles" mit den festen
# Rolle-ID-Paaren, die wir nutzen werden.

# Es fehlt noch die Befüllung für die Tabellen:
# -rights
# -roledescription
# -modules
# -rights

# Datenbank: `coma2`

#
# Daten für Tabelle `conference`
#


#
# Daten für Tabelle `criterion`
#


#
# Daten für Tabelle `excludespaper`
#


#
# Daten für Tabelle `forum`
#


#
# Daten für Tabelle `forumtype`
#

INSERT INTO forumtype (id, type) VALUES (1, 'open forum');
INSERT INTO forumtype (id, type) VALUES (2, 'committee forum');
INSERT INTO forumtype (id, type) VALUES (3, 'paper discussion forum');

#
# Daten für Tabelle `isabouttopic`
#


#
# Daten für Tabelle `iscoauthorof`
#


#
# Daten für Tabelle `message`
#


#
# Daten für Tabelle `modules`
#


#
# Daten für Tabelle `paper`
#


#
# Daten für Tabelle `paperstate`
#


#
# Daten für Tabelle `person`
#


#
# Daten für Tabelle `preferspaper`
#


#
# Daten für Tabelle `preferstopic`
#


#
# Daten für Tabelle `properties`
#


#
# Daten für Tabelle `rating`
#


#
# Daten für Tabelle `reviewreport`
#


#
# Daten für Tabelle `rights`
#


#
# Daten für Tabelle `role`
#


#
# Daten für Tabelle `roledescription`
#


#
# Daten für Tabelle `roles`
#

INSERT INTO roles (id, role) VALUES (1, 'Admin');
INSERT INTO roles (id, role) VALUES (2, 'Chair');
INSERT INTO roles (id, role) VALUES (3, 'Reviewer');
INSERT INTO roles (id, role) VALUES (4, 'Author');
INSERT INTO roles (id, role) VALUES (5, 'Participant');

#
# Daten für Tabelle `state`
#

INSERT INTO state (id, state) VALUES (1, 'active');
INSERT INTO state (id, state) VALUES (2, 'not active');
INSERT INTO state (id, state) VALUES (3, 'invited');

#
# Daten für Tabelle `topic`
#


