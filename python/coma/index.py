#! /usr/bin/python

"""The CoMa Conference Manager Main module."""

__version__ = "0.1.0"

import cgi
import cgitb

cgitb.enable()

import coma
import comadb

def main():
    """See whether the page provides us with something useful, and on this
    select a page to show or an action to perform."""

    form = cgi.FieldStorage()
    db = comadb.ComaDB()
    session = coma.check_session(form, db)

    if session.user and session.user <> 'None':
	# User is logged in, show him his tasks and possibilities.
        user = db.get_user(session.user)
        coma.index(db, sid, user)
    else:
	# User not logged in, so just show him the login page.
	coma.process_template('./templates/index-guest.xml',
			      { 'actions': coma.setup_actions(session) })

if (__name__ == '__main__'):
    main()
