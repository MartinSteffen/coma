Die hier vorhandenen Skripte sind geeignet um eine Datenbank mit Testdaten zu versehen. 
Die Skripte werden noch weiter bearbeitet und Wünsche für spezielle Daten können gerne
an gub75@gmx.de geschickt werden.

-basedata.sql schreibt Table und Daten in eine Datenbank coma1, der Datenbank Name
              kann bei Bedarf oben im Skript geändert werden. Vorhandene Table werden
              ersetzt, Gruppenspezifische Table werden ignoriert.
              
-testdata.sql Enthält 'böse' daten, die nach der Spec zulässg sind, aber nicht unbedingt
              erwartet werden. Die einzelnen Programme sollen später mit diesen Daten
              umgehen können oder von vornherein das Auftreten solcher Daten in der
              Daenbank verhindern.
              
-emptyDB.sql  Ist eine KOPIE von /sql/dbSchema.sql und dient nur der Vollständigkeit. Die
              Datei ist unter umständen nicht immer aktuell. Verbindlich ist das Skript im
              Verzeichnis /trunk/sql!!!
              
-php1addon    Enthält besondere table und constraints für de Gruppe php1