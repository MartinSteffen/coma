#! /usr/bin/python
#
# This module handles paper submissions.
#

import cgi
import cgitb

cgitb.enable()

import coma
import comadb

def main():
    form = cgi.FieldStorage()
    db = comadb.ComaDB()
    session = coma.check_session(form, db)

    assert session

    if form.has_key('dologin'):
        user, session = coma.process_login(session, db, form)
        coma.index(db, session, user)
    else:
        coma.process_template('./templates/login.xml',
                              { 'sid' : session.id })




if (__name__ == '__main__'):
    main()
