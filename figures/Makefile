########################################################
# $Id: Makefile,v 1.1 2004/08/21 12:58:03 ms Exp $ # 
########################################################




PSFILES  = $(subst .fig,.ps,  $(wildcard *.fig))
PNGFILES = $(subst .fig,.png, $(wildcard *.fig))

%.ps : %.fig
	fig2dev -Leps $<  $@

%.png : %.fig
	fig2dev -Lpng $<  $@

all: $(PNGFILES) $(PSFILES)
	true	


clean:
	echo $(PSFILES)
	rm $(PNGFILES)
	rm $(PSFILES)


