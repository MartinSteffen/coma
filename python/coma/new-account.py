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

    if form.has_key('create') and form['create'].value == 'True':
        coma.process_new_user(session, db, form)
    else:
        coma.process_template('./templates/new-account.xml',
                              { 'actions': coma.setup_actions(session),
                                'sid' : session.id })




if (__name__ == '__main__'):
    main()
