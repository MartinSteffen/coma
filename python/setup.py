#! /usr/bin/python
#
# This little application will be used to set up coma.

import sys
import os
from stat import *
import pg
import datetime

comaconf = """#! /usr/bin/python
#
\"\"\"Settings for the data base.\"\"\"

dbname = \'%(dbname)s\'
user = \'%(user)s'
password = \'%(passwd)s\'
host = '%(host)s'
port = %(port)s
opt = %(opt)s
tty = %(tty)s
comaroot = \"%(comaroot)s\"
adminmail = \"%(adminmail)s\"
adminname = \"%(adminname)s\"

if (__name__ == \"__main__\"):
    import sys
    sys.stderr.write(\"Hacking attempt.\\n\")
    print \"Content-Type: text/html\"
    print
    print \"\"\"<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<html>
  <head>
    <title>Nothing to see here</title>
  </head>
  <body>
    <p>Nothing to see here</p>
  </body>
</html>
\"\"\"
"""





apacheconf = """# -*- apache -*-
# Example httpd.conf snippet for my python implementation of the coma
# conference manager.
#
Alias /coma \"%(comaroot)s\"

<Directory %(comaroot)s>
    SetHandler mod_python
    PythonHandler mod_python.publisher
    PythonDebug on
</Directory>

<Directory %(comaroot)s/papers>
    AllowOverride	None
    Order		deny,allow
    Deny from all
</Directory>

<Directory %(comaroot)s/templates>
    AllowOverride	None
    Order		deny,allow
    Deny from all
</Directory>
"""

defaults = { 'dbname' : 'coma',
	     'user' : 'coma' ,
	     'passwd' : '$$coma04',
	     'host' : 'localhost',
	     'port' : -1,
	     'opt' : None,
	     'tty' : None,
             'comaroot' : '/home/mky/projects/coma/python/coma',
             'adminmail' : 'mky@localhost',
             'adminname' : 'Marcel',
             'smtpserver' : 'localhost' }

def save_config(filename, config, settings):
    try:
        os.remove(filename)
    except:
	pass
    file = open(filename, 'w')
    file.write(config % settings)
    file.close()
    os.chmod(filename, S_IRUSR | S_IWUSR | S_IRGRP | S_IROTH)
    try:
	os.system('chcon -u user_u -t httpd_sys_script_ro_t %s' % filename)
    except:
	pass

def test_pgsql_config(settings):
    while True:
        try:
            db = pg.connect(dbname = settings['dbname'],
                            user = settings['user'],
                            passwd = settings['passwd'],
                            host = settings['host'],
                            port = settings['port'])
        except:
	    raise
        else:
            break

def import_pgsql_schema(filename, settings):
    """Import the necessary data base schemas"""
    print "Importing schema '%s'" % (filename)
    schema_file = open(filename, 'r')
    schema = schema_file.read()
    schema_file.close()
    db = pg.connect(dbname = settings['dbname'],
                    user = settings['user'],
                    passwd = settings['passwd'],
                    host = settings['host'],
                    port = settings['port'])
    db.query(schema)
    print

def main():
    """Ask for some settings."""
    print "Welcome to the CoMa Conference Manager set-up program."
    print
    print "Before you can start using CoMa, we first need to check some settings."
    print
    test_pgsql_config(defaults)
    save_config('./coma/comaconf.py', comaconf, defaults)
    save_config('./coma.conf', apacheconf, defaults)
    import_pgsql_schema('coma.sql', defaults)
    import_pgsql_schema('coma-aux.sql', defaults)

if (__name__ == "__main__"):
    main()
