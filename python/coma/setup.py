#! /usr/bin/python
#
# This little application will be used to set up coma.

import sys
import pg

def welcome(scr):
    """This is the initial welcome screen."""
    scr.clear()
    scr.addstr(1,1,'Welcome to the CoMa Set up Program.')
    scr.refresh()
    

def save_config(filename, settings):
    config = """#! /usr/bin/python
#
\"\"\"Settings for the data base.\"\"\"

dbname = \'%(dbname)s\'
user = \'%(user)s'
password = \'%(passwd)s\'
host = %(host)s
port = %(port)s
opt = %(opt)s
tty = %(tty)s
conference = %(conf)s

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
    file = open('filename', 'w')
    file.write(config)
    file.close()

def test_pgsql_config(settings):
    while True:
        try:
            db = pg.connect(dbname = settings['dbname'],
                            user = settings['user'],
                            passwd = settings['passwd'],
                            host = settings['host'],
                            port = settings['port'])
        except:
            print
            settings = ask_for_settings()
        else:
            break

def main():
    """Ask for some settings."""
    print "Welcome to the CoMa Conference Manager set-up program."
    print
    print "Before you can start using CoMa, we first need to check some settings."
    print


if (__name__ == "__main__"):
    main()
