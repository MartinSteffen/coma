#! /usr/bin/python
#
# This little application will be used to set up coma.

import sys
import os
from stat import *
import pg
import datetime

defaults = { 'dbname' : 'coma',
	     'user' : 'coma' ,
	     'passwd' : '$$coma04',
	     'host' : 'localhost',
	     'port' : -1,
	     'opt' : None,
	     'tty' : None }

def save_config(filename, settings):
    config = """#! /usr/bin/python
#
\"\"\"Settings for the data base.\"\"\"

dbname = \'%(dbname)s\'
user = \'%(user)s'
password = \'%(passwd)s\'
host = '%(host)s'
port = %(port)s
opt = %(opt)s
tty = %(tty)s

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
""" % settings
    try:
        os.remove(filename)
    except:
	pass
    file = open(filename, 'w')
    file.write(config)
    file.close()
    os.chmod(filename, S_IRUSR | S_IWUSR | S_IRGRP | S_IROTH)
    try:
	os.system('chcon -u user_u -t httpd_sys_script_ro_t ./coma/config.py')
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
    save_config('./coma/config.py', defaults)
    import_pgsql_schema('coma.sql', defaults)
    import_pgsql_schema('coma-aux.sql', defaults)

if (__name__ == "__main__"):
    main()
