#!/usr/bin/python
#

"""This is the main library of coma.  Most methods and functions are
defined here, except for the data base part.  This is defined in the
module comadb"""

import sys
import comaconf
import comadb
import random

from mod_python import Session




# XXX: The mimetype 'application/xhtml+xml' yields somewhat broken results.

def process_template(req, file, dictionary = { }, mimetype = 'text/html'):
    """Read a template xml file, process it, and return it."""
    req.content_type = mimetype
    return open(comaconf.templates + '/' + file).read() % dictionary





def send_mail(sender, receiver, file, dictionary = { }):
    """Process a template and send it as an email to the receiver."""
    mailbody = open(comaconf.templates + '/' + file).read() % dictionary
    conn = smtplib.SMTP(comaconf.smtpserver)
    conn.sendmail(comaconf.administrator, [email], mailbody)
    conn.quit()





def download_paper(filename, localname, mimetype = 'application/octet-stream'):
    """When the user wants to download a paper, send it to him.  Also, set
    the correct mime type.

	file:  The name of the file to download.
	mimetype:  The mime type of the file.  This usually should be set, but
	defaults to application/octet-stream (arbitrary binary data).
    """
    _stat = os.stat(localname)
    if _stat:
	length = _stat.st_size
    else:
	redirect("download-error.xml")

    print """Pragma: public
Expires: 0
Cache-Control: must-revalidate, post-check=0, pre-check=0
Cache-Control: private
Content-Description: File Transfer
Content-Type: %(mimetype)s
Content-Disposition: attachement; filename="%(filename)s";
Content-Transfer-Encoding: binary
Content-Length: %(length)d""" % { 'mimetype' : mimetype,
				  'filename' : filename,
				  'length' : length }
    print
    sys.stdout.write(open(localname, 'rb').read())





def build_action(label, action, clss = None):
    if clss:
	return ('<tr class="%s"><td><a href="%s">%s</a></td></tr>' %
		(clss, action, label))
    else:
	return '<tr><td><a href="%s">%s</a></td></tr>' % (action, label)




def action_menu(user = None):
    """Create an action menu."""
    _result = ""
    if not user:
	_result += build_action('Log In', 'login', 'guest')
	_result += build_action('Register', 'register', 'guest')
    elif user.email <> 'None':
	_result += build_action('Log Out', 'logout', 'user')
	_result += build_action('Participate in Conference', 'participate',
				'user')
	if user.role.role[3]:
	    _result += build_action('Submit Paper', 'submit', 'author')
	    _result += build_action('View Papers', 'list_paper', 'author')
	if user.role.role[2]:
	    _result += build_action('View Reviews', 'reviews', 'reviewer')
	if user.role.role[1]:
	    _result += build_action('Assign Review', 'assign', 'chair')
	if user.role.role[0]:
	    _result += build_action('Edit User', 'edit_user', 'chair')
	if user.role[0]:
	    _result += build_action('Edit Users', 'edit_user', 'global')
	if user.role[1]:
	    _result += build_action('Create Conference', 'createconf',
				    'global')
    return _result





def error_list(errors):
    """Format a list of errors to HTML"""
    result = ''
    for each in errors:
	result += '<li class="error">' + each + '</li>\n'
    return result





def password():
    c = '!\"#$%&/()=0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    g = random.Random()
    result = ''
    for each in range(0,8):
	result += c[g.randint(0, len(c)-1)]
    return result





def setup(req, sid):
    session = Session.Session(req, sid = sid, timeout = 1800)
    db = comadb.get_database_connection()
    if not session.is_new():
	if not session.has_key('email'):
	    user = None
	else:
	    user = db.get_user_by_mail(session['email'])
    else:
	user = None
    return session, user, db





def createaccount(req, sid, email, title, first_name, last_name, affiliation,
		  phone_number, fax_number, street, city, postal_code, state,
		  country, sys_role = [ False, False ]):
    """Process a register form by creating a new account."""
    session, user, db = setup(req, sid)
    errors = []
    if not email:
	errors.append('You have to enter an e-mail address.')
    else:
	user = db.get_user_by_mail(email)
	if user:
	    errors.append('A user with the e-mail address %s already exists' %
			  (email))
    if not first_name:
	errors.append('You have to enter your first name.')
    if not last_name:
	last_name = "" # Handle the case of robby.
    if not affiliation:
	errors.append('You have to enter your affiliation.')
    if errors:
	return process_template(req, 'register-error.xml',
                                { 'errors' : error_list(errors) })
    # Generate a new password.
    _password = password()
    # Enter the new user into the data base.
    _result = [ email, _password, int(title), first_name, last_name,
                affiliation, phone_number, fax_number, street, postal_code,
                city, state, country, sys_role ]
    db.put_user(comadb.User(_result))
    # Update the session.
    session['email'] = email
    session.save()
    # Send the confirmation mail.
    maildict = { 'email' : email,
                 'title' : comadb.title_name[int(title)],
                 'firstname' : first_name,
                 'lasname' : last_name,
		 'password' : _password,
                 'administrator' : comaconf.adminmail,
		 'signature' : comaconf.adminname }
    send_mail(comaconf.administrator, [email], 'register-mail.txt', maildict)
    # Provide feedback to the user.
    return process_template(req, 'register-success.xml')





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
