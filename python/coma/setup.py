#! /usr/bin/python
#
# This little application will be used to set up coma.

import sys

def save_config(filename, settings):
    config = """#! /usr/bin/python
#
\"\"\"Settings for the data base.
\"\"\"

dbname = \'%(dbname)s\'
user = \'%(user)s'
passwd = \'%(passwd)s\'
host = %(host)s
port = %(port)s
opt = %(opt)s
tty = %(tty)s

if (__name__ == \"__main__\"):
    import sys
    sys.stderr.write(\"Hacking attempt.\n\")
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
    print config

def main():
    defaults = {"dbname": "coma", "user": "coma", "passwd": "$$coma04",
		"host": "None", "port": "-1", "opt": "None", "tty": "None"}
    save_config("config.py", defaults)

if (__name__ == "__main__"):
    main()
