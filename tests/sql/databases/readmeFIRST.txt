Die hier vorhandenen Skripte sind geeignet um eine Datenbank mit Testdaten zu versehen. 
Die Skripte werden noch weiter bearbeitet und W�nsche f�r spezielle Daten k�nnen gerne
an gub75@gmx.de geschickt werden.

-basedata.sql schreibt Table und Daten in eine Datenbank coma1, der Datenbank Name
              kann bei Bedarf oben im Skript ge�ndert werden. Vorhandene Table werden
              ersetzt, Gruppenspezifische Table werden ignoriert.
              
-testdata.sql Enth�lt 'b�se' daten, die nach der Spec zul�ssg sind, aber nicht unbedingt
              erwartet werden. Die einzelnen Programme sollen sp�ter mit diesen Daten
              umgehen k�nnen oder von vornherein das Auftreten solcher Daten in der
              Daenbank verhindern.
              
-emptyDB.sql  Ist eine KOPIE von /sql/dbSchema.sql und dient nur der Vollst�ndigkeit. Die
              Datei ist unter umst�nden nicht immer aktuell. Verbindlich ist das Skript im
              Verzeichnis /trunk/sql!!!
              
-php1addon    Enth�lt besondere table und constraints f�r de Gruppe php1