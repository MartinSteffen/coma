# This library is used to set up an instance of coma.

import sys
import os
from stat import *
import datetime
import curses
import curses.wrapper
import curses.textpad
import pg


###############################################################################
#
# These are our global variables.
#
###############################################################################

_delete = False

_comaconf = """#! /usr/bin/python
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





_apacheconf = """# -*- apache -*-
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




###############################################################################
#
# This dictionary contains all our default choices.
#
###############################################################################

defaults = { # Description of this particular instance
	     'instance'   : 'coma',
             # The smtp server settings.
             'smtpserver' : 'localhost',
             # Whether the host where coma runs on uses SELinux?
             'selinux'    : False,
	     # The administration information.
             'adminmail'  : 'mky@localhost',
             'adminname'  : 'Marcel',
             # Where coma finds its files.
	     'comaroot'   : '/var/www/html/coma',
	     'comalibs'   : '/usr/local/share/coma',
	     'templates'  : '/usr/local/share/coma/templates',
	     'papers'     : '/usr/local/share/coma/papers' }





def _await_return(stdscr):
    """Await a return on the screen."""
    while True:
        c = stdscr.getch()
        if c == curses.KEY_ENTER:
            break
        elif c == 10:
            break
        elif c == 13:
            break

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





def test_pgsql_config(defaults):
    database = pg.connect(dbname = defaults['dbname'],
                          user   = defaults['dbuser'],
                          passwd = defaults['dbpasswd'],
                          host   = defaults['dbhost'],
                          port   = defaults['dbport'])
    return database





def import_pgsql_schema(filename, database):
    """Import the necessary data base schemas"""
    schema_file = open(filename, 'r')
    schema = schema_file.read()
    schema_file.close()
    _result = database.query(schema)





def _input_line(_win, py, px, ly, lx, _validator = None):
    """read a line and return its result"""
    curses.curs_set(1)
    _text_win = _win.derwin(py, px, ly, lx)
    _text_win.bkgd(' ', curses.color_pair(2))
    _text_win.clear()
    _win.refresh()
    _text = curses.textpad.Textbox(_text_win)
    _text.stripspaces = True
    _result = _text.edit(_validator)
    _text_win.bkgd(' ', curses.color_pair(1))
    _text_win.refresh()
    _win.refresh()
    del _text_win
    curses.curs_set(0)
    return _result




def _get_password(_win, py, px, ly, lx):
    """Read a password"""
    _res = ''
    curses.curs_set(1)
    _text_win = _win.derwin(py, px, ly, lx)
    _text_win.bkgd(' ', curses.color_pair(2))
    _text_win.clear()
    _win.refresh()
    while True:
        _text_win.move(0, len(_res))
        c = _text_win.getch()
        if len(_res) >= 5 and (c == 10 or c == 13 or c == curses.KEY_ENTER):
            break
#       elif (c == curses.KEY_BACKSPACE or c == curses.KEY_DC or
#             c == curses.KEY_SDC):
#           if _res:
#               _text_win.addch(0, len(_res), ord(' '))
#               _res = _res[0:-1]
#               _text_win.refresh()
        elif len(_res) < lx and 31 < c and c < 128:
            _text_win.addch(0, len(_res), ord('*'))
            _res += chr(c)
            _text_win.refresh()
    return _res



def _setup_database(stdscr, defaults):
    """Query and set up the data base."""
    # Ask for the settings.
    defaults['database'] = 'POSTGRESQL'
    defaults['dbhost'] = 'localhost'
    defaults['dbport'] = -1
    curses.curs_set(0)
    _win = stdscr.subwin(17, 60, 1, 10)
    while True:
        stdscr.clear()
        _win.bkgd(' ', curses.color_pair(1))
        _win.border()
        _win.addstr(0, 1, 'Database Connection', curses.A_STANDOUT)
        _win.addstr(0, 48, 'Step 1 of 9', curses.A_STANDOUT)
        _win.addstr(1, 1,
                    'Please enter the relevant information to connect to the')
        _win.addstr(2, 1, 'data base.')
        _win.addstr(4, 1,
                    'Warning:  We assume that you have already created a data-')
        _win.addstr(5, 1,
                    'base user and a database on a database server useable by')
        _win.addstr(6, 1,
                    'COMA.  If you have not done this, please quit this program')
        _win.addstr(7, 1,
                    'by typing Ctrl+C, create the data base, and restart this')
        _win.addstr(8, 1, 'setup process.')
    
        _win.addstr(10, 1, '[1]  Database (toggle)')
        _win.addstr(11, 1, '[2]  Host')
        _win.addstr(12, 1, '[3]  Port (default: -1)')
        _win.addstr(13, 1, '[4]  User')
        _win.addstr(14, 1, '[5]  Password')
        _win.addstr(15, 1, '[6]  Name')

        _return_ok = False
        while True:
            if defaults.has_key('database'):
	        _win.addstr(10, 31, defaults['database'])
            if defaults.has_key('dbhost'):
	        _win.addstr(11, 31, defaults['dbhost'])
            if defaults.has_key('dbport'):
	        _win.addstr(12, 31, str(defaults['dbport']))
            if defaults.has_key('dbuser'):
	        _win.addstr(13, 31, str(defaults['dbuser']))
            if defaults.has_key('dbpasswd'):
	        _win.addstr(14, 31, str(defaults['dbpasswd']))
            if defaults.has_key('dbname'):
	        _win.addstr(15, 31, str(defaults['dbname']))
            stdscr.refresh()
            c = _win.getch()
            if c == ord('1'):
                pass
#               if defaults['database'] == 'postgresql':
#                   defaults['database'] = 'mysql'
#               elif defaults['databse' == 'mysql':
#                   defaults['database'] = 'postgresql'
#               else:
#                   pass
            elif c == ord('2'):
                defaults['dbhost'] = _input_line(_win, 1, 28, 11, 31)
            elif c == ord('3'):
                defaults['dbport'] = int(_input_line(_win, 1, 28, 12, 31))
                if defaults['dbport'] == 0:
                    del defaults['dbport']
            elif c == ord('4'):
                defaults['dbuser'] = _input_line(_win, 1, 28, 13, 31)
            elif c == ord('5'):
                defaults['dbpasswd'] = _input_line(_win, 1, 28, 14, 31)
            elif c == ord('6'):
                defaults['dbname'] = _input_line(_win, 1, 28, 15, 31)
            elif _return_ok and (c == 10 or c == 13 or c == curses.KEY_ENTER):
                break
            if (defaults.has_key('database') and defaults.has_key('dbhost') and
                defaults.has_key('dbport') and defaults.has_key('dbuser') and
                defaults.has_key('dbpasswd') and defaults.has_key('dbname')):
                _return_ok = True
                _win.addstr(16, 35, 'Press Return to continue',
                            curses.A_STANDOUT)
                _win.refresh()
        # Now configure coma for the data base.
        if defaults['database'] == 'POSTGRESQL':
            try:
                database = test_pgsql_config(defaults)
            except pg.InternalError, e:
                _error = stdscr.subwin(4,60,19,10)
                _error.clear()
                _error.bkgd(' ', curses.color_pair(3))
                _error.border()
                _error.addstr(0, 1, '[Error]', curses.A_STANDOUT)
                _error.addstr(1, 1, str(e), curses.A_BOLD)
                _error.addstr(3, 35, 'Press Return to continue',
                              curses.A_STANDOUT)
                _error.refresh()
                del _error
                _await_return(_error)
            else:
                try:
                    import_pgsql_schema('coma-psql.sql', database)
                    import_pgsql_schema('coma-psql-aux.sql', database)
                except pg.ProgrammingError, e:
                    _error = stdscr.subwin(4,60,19,10)
                    _error.clear()
                    _error.bkgd(' ', curses.color_pair(3))
                    _error.addstr(1, 1, str(e), curses.A_BOLD)
                    _error.border()
                    _error.addstr(0, 1, '[Error]', curses.A_STANDOUT)
                    _error.addstr(3, 35, 'Press Return to continue',
                                  curses.A_STANDOUT)
                    _error.refresh()
                    _await_return(stdscr)
                    del _error
                    return defaults, database
                else:
                    return defaults, database
#       elif database == 'MYSQL':
#           pass
        else:
            pass



def _setup_administrator(stdscr, defaults, database):
    """Set up the first user.  This will be called after the data base
    has been set up."""
    _result = { }
    _return_ok = False
    stdscr.clear()
    curses.curs_set(0)
    _win = stdscr.subwin(20, 60, 2, 10)
    _win.clear()
    _win.bkgd(' ', curses.color_pair(1))
    _win.border()
    _win.addstr(0, 2, 'Create Site Administrator Account', curses.A_STANDOUT)
    _win.addstr(0, 48, 'Step 2 of 9', curses.A_STANDOUT)
    _win.addstr(1, 1, 'Please enter the information of your site administrator.')
    _win.addstr( 2, 1, 'All fields are mandatory.  Please visit the adminis-')
    _win.addstr( 3, 1, 'trator\'s settings to add the missing values.')
    _win.addstr( 5, 1, 'Type the code before the field you want to change.')
    _win.addstr( 6, 1, 'Once all mandatory information is present, you may type')
    _win.addstr( 7, 1, 'return to continue to the next form.')
    _win.addstr( 9, 1, '[0] e-mail address (*)')
    _win.addstr(10, 1, '[1] First name (*)')
    _win.addstr(11, 1, '[2] Last name (*)')
    _win.addstr(12, 1, '[3] Affiliation (*)')

    while True:
        c = stdscr.getch()
        if c == ord('0'):
            _result['email'] = _input_line(_win, 1, 32, 9, 25)
        elif c == ord('1'):
            _result['firstname'] = _input_line(_win, 1, 32, 10, 25)
        elif c == ord('2'):
            _result['lastname'] = _input_line(_win, 1, 32, 11, 25)
        elif c == ord('3'):
            _result['affiliation'] = _input_line(_win, 1, 32, 12, 25)
        elif _return_ok and (c == 10 or c == 13 or c == curses.KEY_RETURN):
            break
        if (_result.has_key('email') and _result.has_key('firstname') and
            _result.has_key('lastname') and _result.has_key('affiliation')):
            _win.addstr(19, 35, 'Press Return to continue', curses.A_STANDOUT)
	    _win.refresh()
            _return_ok = True
    # Ask for a password.
    _win.clear()
    _win.refresh()
    del _win
    _win = stdscr.subwin(8, 60, 8, 10)
    _win.bkgd(' ', curses.color_pair(1))
    _win.border()
    while True:
        _win.addstr(0, 1, 'Set Site Administrator Account', curses.A_STANDOUT)
        _win.addstr(0, 48, 'Step 3 of 9', curses.A_STANDOUT)
        _win.addstr(1, 1, 'Welcome, %s.' % _result['firstname'])
        _win.addstr(3, 1, 'Please enter your password.')
        _win.refresh()
        _pw1 = _get_password(_win, 1, 16, 3, 31)
        _win.addstr(4, 1, 'Please re-enter your password.')
        _win.refresh()
        _pw2 = _get_password(_win, 1, 16, 4, 31)
        if _pw1 == _pw2:
            _result['password'] = _pw1
            break
        else:
            _win.clear()
            _win.bkgd(' ', curses.color_pair(1))
            _win.border()
            _win.addstr(6, 1, 'The passwords have you entered are different!',
                        curses.A_BOLD)
    # Now we have checked the necessary data.  We insert the new user
    # into the data base and grant him administrative rights.
    _dbres = database.query(
"""INSERT INTO users(email, password, title, first_name, last_name, 
   affiliation, sys_role) VALUES ('%(email)s', '%(password)s', 0,
   '%(firstname)s', '%(lastname)s', '%(affiliation)s', B'11')""" % _result)
    return defaults




def _setup_root(stdscr, defaults, database):
    """Having set up the necessary information, we define the place where
    this coma is to be installed."""
    stdscr.clear()
    _win = stdscr.subwin(10, 60, 1, 10)
    _win.clear()
    _win.bkgd(' ', curses.color_pair(1))
    _win.border()
    _return_ok = False
    while True:
        _win.addstr(0, 1, 'Define Document Root', curses.A_STANDOUT)
        _win.addstr(0, 48, 'Step 4 of 9', curses.A_STANDOUT)
        _win.addstr(1, 1, 'On this screen we define where the coma web page will')
        _win.addstr(2, 1, 'be installed and where the uploaded papers will be')
        _win.addstr(3, 1, 'stored.')
        _win.addstr(5, 1, '[0] Web Page Root')
        _win.addstr(6, 1, '[1] Paper Repository')
        stdscr.refresh()
        c = stdscr.getch()
        if c == ord('0'):
            defaults['documentroot'] = _input_line(_win, 1, 32, 5, 25)
        elif c == ord('1'):
            defaults['paperrepo'] = _input_line(_win, 1, 32, 6, 25)
        elif _return_ok and (c == 10 or c == 13 or c == curses.KEY_RETURN):
            break
        if defaults.has_key('documentroot') and defaults.has_key('paperrepo'):
            _win.addstr(9, 35, 'Press Return to continue', curses.A_STANDOUT)
	    _win.refresh()
            _return_ok = True
    return defaults




def _setup_review(stdscr, defaults, database):
    """Finish the setup process by reviewing all settings and saving them."""
    curses.curs_set(0)
    stdscr.clear()
    _win = stdscr.subwin(22, 60, 1, 10)
    _win.clear()
    _win.bkgd(' ', curses.color_pair(1))
    _win.border()
    _win.addstr(0, 1, 'Review Settings', curses.A_STANDOUT)
    _win.addstr(1, 1, 'On this page you are able to review all your settings.')
    _win.addstr(2, 1, 'If something is wrong, type Ctrl-C and restart.')
    _win.addstr(4, 1, 'Database')
    _win.addstr(4, 30, defaults['database'])
    _win.addstr(5, 1, 'Database Host')
    _win.addstr(5, 30, defaults['dbhost'])
    _win.addstr(6, 1, 'Database Port')
    _win.addstr(6, 30, str(defaults['dbport']))
    _win.addstr(7, 1, 'Database User')
    _win.addstr(7, 30, defaults['dbuser'])
    _win.addstr(8, 1, 'Database Password')
    _win.addstr(8, 30, defaults['dbpasswd'])
    _win.addstr(9, 1, 'Database Name')
    _win.addstr(9, 30, defaults['dbname'])
    _win.addstr(11, 1, 'CoMa Libraries')
    _win.addstr(12, 5, defaults['libraries'])
    _win.addstr(13, 1, 'CoMa Templates')
    _win.addstr(14, 5, defaults['templates'])
    _win.addstr(15, 1, 'CoMa Document Root')
    _win.addstr(16, 5, defaults['documentroot'])
    _win.addstr(17, 1, 'CoMa Paper Repository')
    _win.addstr(18, 5, defaults['paperrepo'])
    _win.addstr(21, 35, 'Press Return to continue', curses.A_STANDOUT)
    stdscr.refresh()
    _await_return(stdscr)
    return defaults
    




def _setup_main(stdscr, defaults):
    """Greet the user and walk him through the wizard."""
    curses.init_pair(1, curses.COLOR_WHITE, curses.COLOR_BLUE)
    curses.init_pair(2, curses.COLOR_BLACK, curses.COLOR_YELLOW)
    curses.init_pair(3, curses.COLOR_WHITE, curses.COLOR_RED)
    _win = stdscr.subwin(10, 60, 7, 10)
    _win.bkgd(' ', curses.color_pair(1))
    _win.clear()
    _win.border()
    _win.addstr(2, 2, 'Welcome to CoMa Set Up')
    _win.addstr(9, 35, 'Press Return to continue', curses.A_STANDOUT)
    stdscr.refresh()
    _await_return(stdscr)
    _win.clear()
    _win.refresh()
    del _win
    defaults, database = _setup_database(stdscr, defaults)
    defaults = _setup_administrator(stdscr, defaults, database)
    defaults = _setup_root(stdscr, defaults, database)
    defaults = _setup_review(stdscr, defaults, database)




def _setup_delete():
    """This procedure removes an existing instance of coma."""
    # XXX: Import the current coma config.
    import comaconfig

    # XXX: Copy coma-config to defaults.

    # Delete the data base.
    if comaconfig.database == 'POSTGRESQL':
        test_pgsql_config(defaults)
        # Now the global settings.
        import_pgsql_schema('coma-psql-del.sql', defaults)
    elif database == 'MYSQL':
        pass
    else:
        pass




def _setup_argv(argv):
    # Parse options.
    while argv:
        if argv[0] == '-instance':
            if argv[1]:
                if argv[1][0] != '-':
                    defaults['instance'] = argv[1]
                    argv = argv[2:]
                else:
                    print "The name of the instance must not start with '-'"
                    argv = argv[1:]
            else:
                print "Option -instance requires an argument."
                argv = argv[1:]
        elif argv[0] == '-mysql':
            defaults['database'] = 'MYSQL'
	    argv = argv[1:]
        elif argv[0] == '-psql':
            defaults['database'] = 'POSTGRESQL'
	    argv = argv[1:]
        elif argv[0] == '-delete':
            _delete = True
            argv = argv[1:]
        else:
            print "Option '%s' not understood." % (argv[0])





def main(defaults, argv = []):
    """Parse arguments, set up curses and call the real setup."""
    _setup_argv(argv)
    if _delete:
        _setup_delete(defaults)
    else:
        curses.wrapper(_setup_main, defaults)
    sys.exit(0)
