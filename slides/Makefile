

include ../Makefile




PSFILES  = $(subst .fig,.ps,  $(wildcard *.fig))
PNGFILES = $(subst .fig,.png, $(wildcard *.fig))

%.ps : %.fig
	fig2dev -Leps $<  $@

%.png : %.fig
	fig2dev -Lpng $<  $@



figures: $(PNGFILES) $(PSFILES)
	true	


www:
	cp main-review.pdf $(WWWDIR)/slides




