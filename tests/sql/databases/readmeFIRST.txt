Die hier vorhandenen Skripte sind geeignet um eine Datenbank mit Testdaten zu versehen. 
Die Skripte werden noch weiter bearbeitet und W�nsche f�r spezielle Daten k�nnen gerne
an gub75@gmx.de geschickt werden.

-basedata.sql schreibt Daten in die aktive Datenbank. Vorhandene Table werden
              geleert, Gruppenspezifische Table werden ignoriert. Person 32 hat mehrere
              Rollen und nutzt diese unter anderem um ein eigenes Paper zu reviewen.
              Paper 9 wird von insgesamt 4 Reviewern bewertet, wobei der Report 5 vom
              Autor selbst (32) stammt. Viel Spa� damit ;)
              
basedata2.sql Wie basedata.sql nur nicht generisch sondern mit weniger aber Handgeschriebenen
              Daten von gl�cklichen freilaufenden Praktikumsteilnehmern. Dient als backup des
              alten Datenbestandes von PHP1.
              
-testdata.sql Enth�lt 'b�se' daten, die nach der Spec zul�ssg sind, aber nicht unbedingt
              erwartet werden. Die einzelnen Programme sollen sp�ter mit diesen Daten
              umgehen k�nnen oder von vornherein das Auftreten solcher Daten in der
              Daenbank verhindern.
              
-emptyDB.sql  Ist eine KOPIE von /sql/dbSchema.sql und dient nur der Vollst�ndigkeit. Die
              Datei ist unter Umst�nden nicht immer aktuell. Verbindlich ist das Skript im
              Verzeichnis /trunk/sql!!!
              
-php1addon    Enth�lt besondere table und constraints f�r de Gruppe php1

-delete_data.sql L�scht die Daten aus alles globalen tablen.

Nochmal zum mitschreiben:

Erstens tablestruktur mit emptyDB bzw dem Original aus dem sql repos erstellen
Zweitens delete_data.sql ausf�hren (Wichtig, da sonst beziehungen der Table nicht consistent!!)
Drittens mit basedata.sql viele viele Daten einlesen

mit passwort pw und einem benutzernamen ( Xemail@mail.de | X aus [0,99] ) einloggen.

Viel Spa�,

Oliver
