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

    coma.process_template('./templates/login.xml',
                          { 'actions': setup_actions(session),
                            'sid' : session.id })




if (__name__ == '__main__'):
    main()
