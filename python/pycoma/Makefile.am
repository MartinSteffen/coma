
SUBDIRS			= templates
dist_pkgdata_DATA	= index.py

BUILT_SOURCES		= config.py
pkgpython_PYTHON	= coma.py comadb.py comapsql.py comamysql.py config.py

MOSTLYCLEANFILES	= *~
CLEANFILES		= *.pyc *.pyo
DISTCLEANFILES		=
MAINTAINERCLEANFILES	= comaconf.py coma.conf Makefile.in

do_subst        = sed -e 's,[@]pkgpythondir[@],$(pkgpythondir),g' \
                      -e 's,[@]pkgdatadir[@],$(pkgdatadir),g' \
                      -e 's,[@]PYTHON[@],$(PYTHON),g' \
                      -e 's,[@]PACKAGE[@],$(PACKAGE),g' \
                      -e 's,[@]VERSION[@],$(VERSION),g' \
                      -e 's,[@]CHCON[@],$(CHCON),g'

config.py: $(srcdir)/config.py.in Makefile
	$(do_subst) $(srcdir)/config.py.in > config.py
