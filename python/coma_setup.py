# This library is used to set up an instance of coma.

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
    print "Saving configuration file %s" % (filename)
    print
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

def main(argv = []):
    """Ask for some settings.
    The argument list provides the setup program with some settings.  The
    following are understood:

    -mysql:           The intended data base is mysql.
    -psql:            The intended data base is postgresql.
    -instance name:   The name of the coma instance.
    """
    print "Welcome to the CoMa Conference Manager set-up program."
    print
    print "Before you can start using CoMa, we first need to check some settings."
    print

    database = 'POSTGRESQL'
    instance = 'coma'
    delete = False

    # Parse options.
    while argv:
        if argv[0] == '-instance':
            if argv[1]:
                if argv[1][0] != '-':
                    instance = argv[1]
                    argv = argv[2:]
                else:
                    print "The name of the instance must not start with '-'"
                    argv = argv[1:]
            else:
                print "Option -instance requires an argument."
                argv = argv[1:]
        elif argv[0] == '-mysql':
            database = 'MYSQL'
	    argv = argv[1:]
        elif argv[0] == '-psql':
            database = 'POSTGRESQL'
	    argv = argv[1:]
        elif argv[0] == '-delete':
            delete = True
            argv = argv[1:]
        else:
            print "Option '%s' not understood." % (argv[0])

    # First save the apache settings.
    save_config('%s/%s.conf' % (instance, instance), apacheconf, defaults)

    # Now configure coma for the data base.
    if delete:
        if database == 'POSTGRESQL':
            test_pgsql_config(defaults)
            # Now the global settings.
            import_pgsql_schema('coma-psql-del.sql', defaults)
        elif database == 'MYSQL':
            pass
        else:
            pass
    else:
        if database == 'POSTGRESQL':
            test_pgsql_config(defaults)
            save_config('%s/comaconf.py' % (instance), comaconf, defaults)
            import_pgsql_schema('coma-psql.sql', defaults)
            import_pgsql_schema('coma-psql-aux.sql', defaults)
        elif database == 'MYSQL':
            pass
        else:
            pass
