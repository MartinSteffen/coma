
SUBDIRS			= coma
python_PYTHON		= coma-setup.py coma-procmail.py
dist_pkgdata_DATA	= coma.sql coma-aux.sql coma-del.sql
EXTRA_DIST		= pycoma.spec

MOSTLYCLEANFILES	= *~
CLEANFILES		= *.pyc *.pyo

all-local: selinux

# If we have selinux, we have to lable the files...
.PHONY: selinux
selinux:
	${CHCON} -u user_u -t httpd_sys_content_t coma
