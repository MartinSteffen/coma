#! /usr/bin/python
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
    user = db.get_user(session.user)

    if (form.has_key('create-conference') and
	form['create-conference'].value == 'True'):
	coma.process_new_conference(session, db, form)
	coma.index(db, session, user)
    else:
	coma.process_template('./templates/new-conference.xml',
			      { 'actions': coma.setup_actions(session),
				'sid' : session.id })





if __name__ == "__main__":
    main()
