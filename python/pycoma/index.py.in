#! /usr/bin/python

"""The CoMa Conference Manager Main module."""

__version__ = "0.1.0"

from mod_python import apache
from mod_python import util
from mod_python import Session

import coma
import comadb
import random
import smtplib

def stylesheet(req):
    """Return the stylesheet associated to the web-site"""
    import comaconf
    req.content_type = "text/css"
    return open(comaconf.templates + '/coma.css').read()





def test(req, sid = None):
     """Just a stupid test function."""
     return coma.process_template(req, 'index-guest.xml',
				  { 'actions': coma.action_menu() })







def register(req, sid = None):
    """Register as a new user"""
    session = Session.Session(req, sid = sid, timeout = 1800)
    return coma.process_template(req, 'register.xml')





def createaccount(req, sid = None, email = None, title = 0, first_name = None,
		  last_name = None, affiliation = None, phone_number = None,
		  fax_number = None, street = None, postal_code = None,
		  city = None, state = None, country = None):
    """Process a register form by creating a new account."""
    return coma.createaccount(req, sid, email, title, first_name, last_name,
			      affiliation, phone_number, fax_number, street,
			      postal_code, city, state, country)





def summary(req, sid = None):
    """Show the summary information of a logged in user."""
    session, user, db = coma.setup(req, sid)
    if session.is_new() or not session.has_key['email']:
	return coma.process_template(req, 'error-login.xml',
				     { 'actions' : coma.action_menu() })
    elif not user:
	return coma.process_template(req, 'error-user.xml',
				     { 'actions' : coma.action_menu(),
				       'email' : session['email'],
				       'adminmail' : comaconf.adminmail })
    # Okay.  Construct his summary.
    return "here."





def dologin(req, email = None, password = None, sid = None):
    session = Session.Session(req, sid = sid, timeout = 1800)
    db = comadb.ComaDB()
    errors = []
    user = None
    if not email:
	errors.append('You have to enter an e-mail address to log in.')
    else:
	user = db.get_user(email)
	if not user:
	    errors.append('The e-mail address %s is unknown.' % (email))
    if not password:
	errors.append('You have to enter your password to log in.')
    else:
	if user and user.password <> password:
	    errors.append('The password is not correct.')
    if errors:
	return coma.process_template(req, 'login-error.xml',
				     { 'errors' : error_list(errors) } )
    session['email'] = email
    return coma.process_template(req, 'login-success.xml',
				 { 'actions' : coma.action_menu() })






def login(req, sid = None):
    """Have the user login."""
    return coma.process_template(req, 'login.xml',
				 { 'actions' : coma.action_menu() })




def logout(req, sid = None):
    """Have the user log out."""
    session, user, id = coma.setup(req, sid)
    if not user:
	return coma.process_template(req, 'error-login.xml',
				     { 'actions' : coma.action_menu() })
    del session['email']
    session.save()
    return coma.process_template(req, 'logout.xml',
				 { 'actions' : coma.action_menu() })



def index(req, sid = None):
    """Provides the index page"""
    session, user, db = coma.setup(req, sid)
    if user:
	util.relocate(req, 'summary')
    else:
	return coma.process_template(req, 'index.xml',
				     { 'actions' : coma.action_menu() })
