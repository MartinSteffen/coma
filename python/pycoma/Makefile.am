
SUBDIRS			= templates
dist_pkgdata_DATA	= index.py

BUILT_SOURCES		= config.py
pkgpython_PYTHON	= coma.py database.py procmail.py setup.py \
                          connpsql.py connmysql.py

pkgsysconfdir		= $(sysconfdir)/$(PACKAGE)
pkgsysconf_DATA		= config.py

MOSTLYCLEANFILES	= *~
CLEANFILES		= *.pyc *.pyo
DISTCLEANFILES		=
MAINTAINERCLEANFILES	= config.py coma.conf Makefile.in

do_subst        = sed -e 's,[@]pkgpythondir[@],$(pkgpythondir),g' \
                      -e 's,[@]pythondir[@],$(pythondir),g' \
                      -e 's,[@]pkgdatadir[@],$(pkgdatadir),g' \
                      -e 's,[@]pkgsysconfdir[@],$(pkgsysconfdir),g' \
                      -e 's,[@]PYTHON[@],$(PYTHON),g' \
                      -e 's,[@]PACKAGE[@],$(PACKAGE),g' \
                      -e 's,[@]VERSION[@],$(VERSION),g' \
                      -e 's,[@]CHCON[@],$(CHCON),g'

config.py: $(srcdir)/config.py.in Makefile
	$(do_subst) $(srcdir)/config.py.in > config.py

index.py: $(srcdir)/index.py.in Makefile
	$(do_subst) $(srcdir)/index.py.in > index.py
