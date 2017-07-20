# Top-level Makefile, containing a number of common targets
# and declarations.

WWWDIR      = /home/info/www/inf/deRoever/WS0405/PITM/coma







DVIFILES = $(subst .tex,.dvi, $(wildcard handout*.tex uebung*.tex))
PSFILES  = $(subst .dvi,.ps, $(DVIFILES))
PDFFILES = $(subst .ps,.pdf, $(PSFILES))

TEX=latex  # for the implicit rule for .dvi



%.ps : %.dvi
	dvips -Pwww $< -o $@

%.pdf : %.ps
	ps2pdf $<



dvi: $(DVIFILES)
	true	
ps: $(PSFILES)
	true

pdf: $(PDFFILES)
	true

all: dvi ps pdf
	true





clean:
	rm -f *.aux *.log *.dvi main.ps *.pdf *.bbl *.blg *~ main.out *.nav *.snm *.toc *.glo *.htoc


#####################################

.SECONDARY:  %.dvi %.ps

#rotate:
#	for file in $(basename $(notdir $(ROTPICS))); do\
#	  convert -rotate 90 src/$$file.jpg src/$$file\_r1.jpg;\
#        done

############################################################
## $Id: Makefile,v 1.4 2004/10/21 16:29:24 swprakt Exp $ ##
############################################################







