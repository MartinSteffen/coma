#! /usr/bin/python
#
# This module handles paper submissions.
#

import cgi
import cgitb

cgitb.enable()

import coma
import comadb
import session

def main():
    form = cgi.FieldStorage()
    db = comadb.ComaDB()
    sid = coma.check_session(form, db)
    sid = session.change_login(db, sid, None)

    _actions = coma.setup_actions(sid)
    coma.process_template("./templates/logout.xml",
                          { 'actions': _actions,
                            'sid' : sid.id })




if (__name__ == '__main__'):
    main()
