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



def setup_actions(sid, user = None, role = None):
    assert sid
    if not sid.user:
	_result  = build_action('Log In', 'login', sid)
	_result += build_action('Create Account', 'new-account', sid)
	return _result
    # User logged in.
    assert user and sid.user == user.email and sid.user <> 'None'
    _result  = build_action('Log Out', 'logout', sid)
    _result += build_action('Participate in Conference', 'conference', sid)
    # _result += build_action('View Papers', 'list-papers', sid)
    if role:
        assert role and sid.conference == role.conference and \
            sid.conference == 'None'
        if role.role[3]:  # The user is an author of this conference.
            _result += build_action('Submit Paper', 'paper-new', sid)
            _result += build_action('Update Submission', 'paper-edit', sid)
            _result += build_action('View Review', 'paper-new', sid)
	if role.role[2]:  # The user is a programm commitee member.
            _result += build_action('Update Review', 'review-edit', sid)
        if role.role[1]:  # The user is a programm chair.
            _result += build_action('Assign Review', 'review-assign', sid)
        if role.role[0]:  # The user is the administrator of the conference.
            _result += build_action('Edit User', 'conf-user-edit', sid)
    if user.sys_role[0]:  # The user has the right to view and modify users.
	_result += build_action('Edit Users', 'user-edit', sid)
    if user.sys_role[1]:  # The user has the right to create a new conference.
        _result += build_action('Create a Conference', 'new-conference', sid)
    return _result





##############################################################################
# Show the index page.
##############################################################################

def index(db, sid, user):
    #assert sid and sid.user and sid.user <> 'None'
    #assert user and sid.user == user.email

    _actions = setup_actions(sid)
    _conferences = get_conferences(db, user)
    _papers = get_papers(db, user)
    _reviews = get_reviews(db, user)
    process_template('./templates/index-user.xml',
		     { 'actions': _actions,
		       'firstname' : user.firstname,
		       'conferences' : _conferences,
		       'papers' : _papers,
		       'reviews' : _reviews })





##############################################################################
# Generate reports
##############################################################################

def get_conferences(db, user):
    """Get the list of all conferences a user is registered for and
    format it in a nice way."""
    _query = db.get_conferences_and_roles(user)
    if _query:
	_result = """<table class=\"conferences\">
	<tr>
        <th>Conference</th>
        <th>Role</th>
        </tr>"""
	for each in _result:
	    _result += (
		"""<tr class="conference">
		<td><a href=\"%s\">%s</a></td>
		<td>%s</td>
		</tr>""" %
		(each[0].homepage, each[0].name, each[1].as_text()))
	_result += "</table>"
    else:
	_result = "<p>You are not participating in any conference.</p>"
    return _result


def get_papers(db, user):
    """Get the list of all papers a user has submitted and format
    it in a nice way"""
    _result = db.get_papers(user)
    if not _result:
	return "<p>You do not have any papers submitted to any conference.</p>"

def get_reviews(db, user):
    """Get a list of all reviews a user has to submit or that he has
    submitted."""
    _result = db.get_reviews(user)
    if not _result:
	return "<p>You have no reviews assigned to you.</p>"
    result = """<table class=\"reviews\">"""
    for each in _result:
	pass
    result += """</table>"""
    return result





##############################################################################
# Process forms.
##############################################################################

def __form_get_value(form, key, result, error, message, default = None):
    """Get a value from the form.  If the value is present in the form,
    the value will be appended to the variable result.  If the value is
    not present, then the value of default will be appended to result
    and message will be appended to error."""
    if form.has_key(key) and form[key].value:
	__result = form[key].value
	__error = None
    else:
	__result = None
	__error = message
    if __result:
	result.append(__result)
    if __error:
	error.append(__error)
    return result, error




def __format_errors(_page, _errors):
    _message = ''
    for each in _errors:
	_message += '<li class="error">%s</li>\n' % each
    process_template(_page, { 'errors' : _message })
    sys.exit()


def check_session(session, db):
    """Given the input from a form check whether it declares a sesssion
    identifier and check whether this identifier is valid.  In case
    of a valid session identifier, this function returns a session object
    identifying the session.  Otherwise it will show a corresponding error
    message, generate a fresh session identifier, and exit the cgi
    script."""
    sid = session.get(db, form["session"].value)
    if not sid:
	new_sid = session.new(db)
	process_template('./templates/session-missing.xml',
			 { 'actions': setup_actions(new_sid) } )
	session.collect(db)
	sys.exit()
	if sid.expired():
	    new_sid = session.new(db)
	    session.collect(db)
	    process_template('./templates/session-expired.xml',
			     { 'actions': setup_actions(new_sid) })
	    sys.exit()
	session.update(db, sid)
    else:
	sid = session.new(db)
    return sid







def process_login(sid, db, form):
    """Validate the login form and try to log him in."""
    if form['email'].value:
	if form['password'].value:
	    _user = db.get_user(form['email'].value)
	    if _user:
		if _user.password == form['password'].value:
		    sid = session.change_login(db, sid, form['email'].value)
		    return _user, sid
		else:
		    process_template('./templates/login-failed.xml',
				     { 'error' :
				       'The password does not match '
				       'the login' })
	    else:
		process_template('./templates/login-failed.xml',
				 { 'error' : 'This user does not exist' })
	else:
	    process_template('./templates/login-failed.xml',
			     { 'error' : 'You must enter a password' })
    else:
	process_template('./templates/login-failed.xml',
			 { 'error' : 'You must enter an e-mail address.'})
    sys.exit()


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

#    if form.has_key('password') and form['password'].value:
#	if form.has_key('password2') and form['password2'].value:
#	    if form['password'].value == form['password2'].value:
#		_result.append(form['password'].value)
#	    else:
#		_errors.append('The password and its confirmation differ.')
#	else:
#	    _errors.append('You have to confirm your password.')
#    else:
#	_errors.append('You have to enter a password.')

    if form.has_key('title') and form['title'].value:
	_result.append(int(form['title'].value))
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
	__format_errors('./templates/new-account-error.xml', _errors)
	sys.exit()
    else:
	# We don't have errors.  Add him to the data base.
	_user = comadb.User(_result)
	db.put_user(_user)
	# Update the session.
	sid.user = email
	session.update(db, sid)
	# Show him his index.
	index(db, sid, _user)


def process_submit_paper(sid, db, form):
    """Process a paper submission form."""
    assert sid and sid.user
    _error = []
    _result = {}
    # Do not look for id, as it is automatically set.
    if form.has_key('conference') and form['conference'].value:
	_result.append(form['conference'].value)
    else:
	_error.append('No conference given.')

    if form.has_key('title') and form['title'].value:
	if not form['title'].value == '':
	    _result.append(form['title'].value)
	else:
	    _error.append('You must enter a title.')
    else:
	_error.append('You must enter a title.')

    if form.has_key('abstract') and form['abstract'].value:
	if form['abstract'].value.len() > 0:
	    if form['abstract'].value.len() < 10000:
		_result.append(form['abstract'].value)
	    else:
		_error.append('The abstract is too long.')
	else:
	    _error.append('You have to supply an abstract')
    else:
	_error.append('You have to supply an abstract')

    # contact author is the user.
    _result.append(sid.user)

    if form.has_key('filename') and form['filename'].value:
	_result.append(form['filename'].value)
    else:
	_error.append('You have to supply a file name.')

    _result.append(0) # The initial state is always 0
    _result.append(0) # The format is not handled yet.

    # Get the paper.

    # Write the paper to disk.

    # Show the result page.





def process_new_conference(sid, db, form):
    _error = []
    _result = []
    if form['abbreviation'].value:
	_result.append(form['abbreviation'].value)
    else:
	_error.append('You have to enter an abbreviation')

    if form['name'].value:
	_result.append(form['name'].value)
    else:
	_error.append('You have to enter an name of the conference')

    if form['homepage'].value:
	_result.append(form['homepage'].value)
    else:
	_error.append("You have to enter an URI for the conferences homepage.")

    if form['abstract-submission-deadline'].value:
	_result.append(form['abstract-submission-deadline'].value)
    else:
	_result.append(None)

    if form['paper-submission-deadline'].value:
	_result.append(form['paper-submission-deadline'].value)
    else:
	_error.append('You have to enter a paper submission deadline.')

    _result, _error = __form_get_value(
	form, 'review-deadline', _result, _error,
	'You have to enter a review deadline')
    _result, _error = __form_get_value(
	form, 'notification-deadline', _result, _error,
	'You have to enter a final version deadline')
    _result, _error = __form_get_value(
	form, 'final-version-deadline', _result, _error,
	'You have to enter a final version deadline')
    _result, _error = __form_get_value(
	form, 'conference-start', _result, _error,
	'You have to enter a the starting date of the conference')
    _result, _error = __form_get_value(
	form, 'conference-end', _result, _error,
	'You have to enter a the ending date of the conference')
    _result, _error = __form_get_value(
	form, 'min-reviews-per-paper', _result, _error, None, 1)

    # XXX: Check whether all dates entered are sane.  This needs to be done.

    if _error:
	__format_errors('./templates/new-conference-error.xml', _error)
    else:
	_conf = comadb.Conference(_result)
	_c = db.get_conference(_conf.abbreviation)
	if _c:
	    __format_errors('./templates/new-conference-error.xml',
			    ['A conference with the abbreviation %s already '
			     'exists' % (_c.abbreviation)])
	db.put_conference(_conf)
	# After we have created the conference we make the conference
	# administrator of this conference.
	_role = comadb.Role((sid.user, _result[0], 
			     [True, True, False, False]))
	db.put_role(_role)





if (__name__ == "__main__"):
    print "Hacking attempt."
