
XML_TEMPLATES	= bad-page.xml \
		  index-guest.xml \
		  index-user.xml \
		  login.xml \
		  logout.xml \
		  new-account-error.xml \
		  new-account.xml \
		  new-conference-error.xml \
		  new-conference.xml \
		  new-paper-error.xml \
		  new-paper.xml \
		  session-expired.xml

EXTRA_DIST	= ${XML_TEMPLATES}

MOSTLYCLEANFILES	= *~
CLEANFILES		= *.pyc *.pyo

all-local: selinux

.PHONY: selinux
selinux:
	for i in ${XML_TEMPLATES} ; do \
	    chmod 0644 $$i ; \
	    ${CHCON} -u user_u -t httpd_sys_script_ro_t $$i ; \
	done
