

include ../Makefile

HEVEA       = hevea
HEVEAOPTS   = -fix -exec xxdate.exe
HACHA       = hacha

DOC         = main
CODEDIR     = 
############# Achtung: die Versionen �ndern sich!

main.dvi: main.tex
	latex main


main-small.ps:
	psnup -2 main.ps > main-small.ps

index.html: $(DOC).html
	$(HACHA) -o index.html $(DOC).html

html: $(DOC).tex
	$(HEVEA) $(HEVEAOPTS) ../tex/inputs/macros.tex ../tex/inputs/macros.hva -o $(DOC)-sec.html $(DOC).tex
	$(HACHA) -o index.html $(DOC)-sec.html




www:
	cp *.html *.ps *.gif *.pdf $(WWWDIR)/requirements/v2




