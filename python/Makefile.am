
SUBDIRS			= coma
bin_SCRIPTS		= comasetup
sbin_SCRIPTS		= comaprocmail

pkgpython_PYTHON	= coma_setup.py coma_procmail.py
dist_pkgdata_DATA	= coma.sql coma-aux.sql coma-del.sql

EXTRA_DIST		= pycoma.spec comasetup.in comaprocmail.in

MOSTLYCLEANFILES	= *~
CLEANFILES		= *.pyc *.pyo

do_subst	= sed -e 's,[@]pkgpythondir[@],$(pkgpythondir),g' \
		      -e 's,[@]PYTHON[@],$(PYTHON),g' \
		      -e 's,[@]PACKAGE[@],$(PACKAGE),g' \
		      -e 's,[@]VERSION[@],$(VERSION),g'

all-local: selinux

comasetup: comasetup.in Makefile
	$(do_subst) < $(srcdir)/comasetup.in > comasetup
	chmod +x comasetup

comaprocmail: comaprocmail.in Makefile
	$(do_subst) < $(srcdir)/comaprocmail.in > comaprocmail
	chmod +x comaprocmail

# If we have selinux, we have to lable the files...
.PHONY: selinux
selinux:
	${CHCON} -u user_u -t httpd_sys_content_t coma
