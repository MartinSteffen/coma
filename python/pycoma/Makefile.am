
BUILT_SOURCES		= __init__.py config.py index.py
pkgsysconfdir		= $(sysconfdir)/$(PACKAGE)
pkgsysconf_DATA		= config.py
pkgdata_DATA		= index.py
pkgpython_PYTHON	= __init__.py coma.py database.py procmail.py \
			  setup.py connpsql.py connmysql.py

EXTRA_DIST		= __init__.py.in index.py.in config.py.in

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

__init__.py: $(srcdir)/__init__.py.in Makefile
	$(do_subst) $(srcdir)/__init__.py.in > __init__.py

config.py: $(srcdir)/config.py.in Makefile
	$(do_subst) $(srcdir)/config.py.in > config.py

index.py: $(srcdir)/index.py.in Makefile
	$(do_subst) $(srcdir)/index.py.in > index.py
