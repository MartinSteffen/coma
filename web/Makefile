########################################################
## $Id: Makefile,v 1.2 2004/10/26 16:24:47 swprakt Exp $
########################################################


include ../Makefile



TEXDIR      = /home/ms/tex/inputs
TEXDIR2     = /home/ms/dc/tex/inputs
TEXDIR3     = ../tex/inputs
DOC         = main
HEVEA       = hevea
HEVEAOPTS   = -fix -exec xxdate.exe
HACHA       = hacha




.SUFFIXES:
.SUFFIXES: .c


www:    index.html
	cp *.html  $(WWWDIR)
	cp meetings/200*.txt $(WWWDIR)/meetings




html: $(DOC).tex
	$(HEVEA) $(HEVEAOPTS) $(TEXDIR2)/ocamlweb.sty $(TEXDIR3)/macros.hva $(TEXDIR3)/macros.tex $(DOC).tex -o index.html
#	$(HEVEA) $(HEVEAOPTS) $(TEXDIR2)/ocamlweb.sty $(TEXDIR3)/coursedata.tex $(TEXDIR3)/macros.hva $(TEXDIR3)/macros.tex $(DOC).tex -o index.html
#	$(HACHA) -o index-tex.html $(DOC).html



