
SUBDIRS			= . templates pycoma
bin_SCRIPTS		= comasetup
pkglibexecdir		= $(libexecdir)/$(PACKAGE)
pkglibexec_SCRIPTS	= comaprocmail

dist_pkgdata_DATA	= coma-mysql.sql coma-mysql-del.sql \
			  coma-psql.sql coma-psql-del.sql

EXTRA_DIST		= pycoma.spec comasetup.in comaprocmail.in

MOSTLYCLEANFILES	= *~
CLEANFILES		= *.pyc *.pyo
DISTCLEANFILES		= comasetup comaprocmail
MAINTAINERCLEANFILES	= configure Makefile.in pycoma.spec \
			  aclocal.m4 install-sh py-compile \
			  COPYING missing

do_subst	= sed -e 's,[@]pkgpythondir[@],$(pkgpythondir),g' \
		      -e 's,[@]pkgdatadir[@],$(pkgdatadir),g' \
		      -e 's,[@]PYTHON[@],$(PYTHON),g' \
		      -e 's,[@]PACKAGE[@],$(PACKAGE),g' \
		      -e 's,[@]VERSION[@],$(VERSION),g' \
		      -e 's,[@]CHCON[@],$(CHCON),g'

comasetup: $(srcdir)/comasetup.in Makefile
	$(do_subst) < $(srcdir)/comasetup.in > comasetup
	chmod +x comasetup

comaprocmail: $(srcdir)/comaprocmail.in Makefile
	$(do_subst) < $(srcdir)/comaprocmail.in > comaprocmail
	chmod +x comaprocmail

pycoma.conf: $(srcdir)/pycoma.conf.in Makefile
	$(do_subst) < $(srcdir)/comaprocmail.in > comaprocmail
