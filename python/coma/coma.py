#!/usr/bin/python
#
# This is the main cgi script.
#
import cgi
import sys
import session
import comadb




# The mimetype 'application/xhtml+xml' yields somewhat broken results.
#
#
def process_template(file, dictionary, mimetype = 'text/html'):
    """Read a template xml file, process it, and then print it."""
    template = open(file).read()
    print "Content-Type: %s" % (mimetype)
    print
    print template % dictionary





def download_paper(file, mimetype = 'application/octet-stream'):
    """When the user wants to download a paper, send it to him.  Also, set
    the correct mime type.

	file:  The name of the file to download.
	mimetype:  The mime type of the file.  This usually should be set, but
	defaults to application/octet-stream (arbitrary binary data).
    """
    print mimetype
    print
    sys.stdout.write(open(file, 'rb').read())



def build_action(label, action, session):
    return ('<tr><td><a href="%s.py?session=%s">%s</a></td></tr>' %
	    (action, session.id, label))



def setup_actions(session):
    assert session
    if session.user and session.user <> 'None':
	result = build_action('Log Out', 'logout', session)
	result += build_action('View Papers', 'list-papers', session)
    else:
	result = build_action('Log In', 'login', session)
	result += build_action('Create Account', 'new-account', session)
    return result




def dologin(session, db, email, password):
    """This function is responsible for logging a user in and sending him
    to the correct next page"""
    _user = db.get_user(email)
    if not _user:
	# The user does not exist.
	process_template("./templates/login.xml",
			 { 'title' : 'E-Mail Address not known.',
			   'error' : """The e-mail address (%s) you entered
			   seems to be incorrect.  Please try again.""" %
			   email,
			   'actions' : setup_actions(session),
			   'sid' : session.id })
	return
    if not _user.password == password:
	# Password differs, try again.
	process_template("./templates/login.xml",
			 { 'title' : 'Incorrect Password',
			   'error' : """The password you entered seems
			   to be incorrect.  Please try again.""",
			  'actions': setup_actions(session),
			  'sid' : session.id})
	return
    # Everything is okay.  Send him to his index page.
    session.change_login(db, session, email)
    index(session, db)

def logout(session, db):
    """Log a user out."""
    session.change_login(db, session, None)
    process_template("./templates/logout.xml",
		     { 'actions': setup_actions(session) })


def bad_action(session, db, message):
    """Show a generic error page."""
    process_template('./templates/bad-page.xml',
		     { 'error' : message,
		       'actions': setup_actions(session) })

def process_new_user(sid, db, form):
    """Validate the form submitted to create a new user.  If validation
    succeeds, this function returns a list in the format returned by the
    data base connector functions."""
    _errors = []
    _result = []

    if form.has_key('email') and form['email'].value:
	email = form['email'].value
	_user = db.get_user(email)
	if not _user:
	    _result.append(email)
	else:
	    _errors.append("""There already exists a user with the e-mail
	    address %s""" % (email))
    else:
	_errors.append('You have to enter an e-mail address')

    if form.has_key('password') and form['password'].value:
	if form.has_key('password2') and form['password2'].value:
	    if form['password'].value == form['password2'].value:
		_result.append(form['password'].value)
	    else:
		_errors.append('The password and its confirmation differ.')
	else:
	    _errors.append('You have to confirm your password.')
    else:
	_errors.append('You have to enter a password.')

    if form.has_key('title') and form['title'].value:
	_result.append(form['title'].value)
    else:
	_errors.append('You have to enter your title.')

    if form.has_key('first-name') and form['first-name'].value:
	_result.append(form['first-name'].value)
    else:
	_errors.append('You have to enter your first name.')

    if form.has_key('last-name') and form['last-name'].value:
	_result.append(form['last-name'].value)
    else:
	_errors.append('You have to enter your last name.')

    if form.has_key('affiliation') and form['affiliation'].value:
	_result.append(form['affiliation'].value)
    else:
	_errors.append('You have to enter your affiliation.')

    if form.has_key('phone-number'):
	_result.append(form['phone-number'].value)
    else:
	_result.append(None)

    if form.has_key('fax-number'):
	_result.append(form['fax-number'].value)
    else:
	_result.append(None)

    if form.has_key('street'):
	_result.append(form['street'].value)
    else:
	_result.append(None)

    if form.has_key('postal-code'):
	_result.append(form['postal-code'].value)
    else:
	_result.append(None)
    
    if form.has_key('city'):
	_result.append(form['city'].value)
    else:
	_result.append(None)

    if form.has_key('state'):
	_result.append(form['state'].value)
    else:
	_result.append(None)

    if form.has_key('country'):
	_result.append(form['country'].value)
    else:
	_result.append(None)

    # We have now checked the form.  If there were errors, we send an error
    # message.  Otherwise we put the result into the data base and send him
    # to the next page.
    if _errors:
	# We had errors.
	_result = None
	_message = ''
	for each in _errors:
	    _message += '<li class="error">%s</li>\n' % each
	process_template('./templates/new-account-error.xml',
			 { 'errors' : _message })
	sys.exit()
    else:
	# We don't have errors.  Add him to the data base.
        _user = comadb.User(_result)
	db.put_user(_user)
	# Update the session.
	sid.user = email
	session.update(db, sid)
	# Show him his index.
	process_template('./templates/index.xml',
			 { 'actions' : setup_actions(sid),
                           'firstname' : _user.firstname})





def check_session(form, db):
    """Given the input from a form check whether it declares a sesssion
    identifier and check whether this identifier is valid.  In case
    of a valid session identifier, this function returns a session object
    identifying the session.  Otherwise it will show a corresponding error
    message, generate a fresh session identifier, and exit the cgi
    script."""
    if form.has_key('session'):
	sid = session.get(db, form["session"].value)
	if not sid:
	    sid = session.new(db)
	    process_template('./templates/bad-session.xml',
			     { 'actions': setup_actions(sid) } )
	    sys.exit()
	if sid.expired():
	    sid = session.new(db)
	    process_template('./templates/expired-session.xml',
			     { 'actions': setup_actions(session) })
	    sys.exit()
	session.update(db, sid)
    else:
	sid = session.new(db)
    return sid




if (__name__ == "__main__"):
    print "Hacking attempt."
