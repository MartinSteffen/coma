
dist_pkgdata_DATA	= bad-page.xml \
			  error-login.xml error-user.xml \
			  index.xml \
			  login-error.xml login-success.xml login.xml \
			  logout.xml \
			  register-error.xml register-success.xml register.xml \
			  new-conference-error.xml new-conference.xml \
			  new-paper-error.xml new-paper.xml \
			  redirect.xml \
			  session-expired.xml session-missing.xml \
			  summary.xml \
			  register-mail.txt \
			  coma.css

MOSTLYCLEANFILES	= *~
CLEANFILES		= *.pyc *.pyo

all-local: selinux

.PHONY: selinux
selinux:
	for i in ${XML_TEMPLATES} ${TXT_TEMPLATES} ${CSS_FILES} ; do \
	    chmod 0644 $$i ; \
	    ${CHCON} -u user_u -t httpd_sys_script_ro_t $$i ; \
	done
