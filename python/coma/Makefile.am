
SUBDIRS		= templates

CGI_SCRIPT	= index.py new-conference.py new-paper.py
CGI_LIB		= comadb.py coma.py
EXTRA_DIST	=

pkgpython_PYTHON =	$(CGI_SCRIPT) $(CGI_LIB)

MOSTLYCLEANFILES	= *~
CLEANFILES		= *.pyc *.pyo

all-local: selinux

# Label files for SELinux and set up the permissions in a sane way.
.PHONY: selinux
selinux:
	for i in ${CGI_SCRIPT} ; do \
	    chmod 0755 $$i ; \
	    ${CHCON} -u user_u -t httpd_sys_script_exec_t $$i ; \
	done
	for i in ${CGI_LIB} $(CSS_FILE); do \
	    chmod 0644 $$i ; \
	    ${CHCON} -u user_u -t httpd_sys_script_ro_t $$i ; \
	done
	chmod 0755 templates ;
	${CHCON} -u user_u -t httpd_sys_script_ro_t templates
