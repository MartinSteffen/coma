# Tabelle für den PTRA
# Legt die für den Algorithmus notwendige Tabelle an

CREATE TABLE rejectedpapers (
   person_id  INT NOT NULL,
   paper_id   INT NOT NULL,
   PRIMARY KEY (person_id, paper_id),
   INDEX (person_id),
   INDEX (paper_id),
   FOREIGN KEY (person_id) REFERENCES Person (id)
       ON DELETE CASCADE,       
   FOREIGN KEY (paper_id) REFERENCES Paper (id)
       ON DELETE CASCADE
) TYPE = INNODB;
