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

    if session.user:
	# User is logged in, show him his tasks and possibilities.
        _actions = coma.setup_actions(session)
        _conferences = coma.get_conferences(session.user)
        _papers = coma.get_papers(session.user)
        _reviews = coma.get_review(session.user)
	coma.process_template('./templates/index-user.xml',
			      { 'actions': _actions,
                                'conferences' : _conferences,
                                'papers' : _papers,
                                'reviews' : _reviews })
    else:
	# User not logged in, so just show him the login page.
	coma.process_template('./templates/index-guest.xml',
			      { 'actions': coma.setup_actions(session),
				'firstname' : 'Guest' })

if (__name__ == '__main__'):
    main()
