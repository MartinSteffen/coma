#! /usr/bin/python

"""The CoMa Conference Manager Main module."""

__version__ = "0.1.0"

import sys
import time
import random
import string
import os
import cgi

def header(title='CoMa Conference Manager'):
    """Print the header of the document.  The only configurable part is
    is the title of the document.
    """

    print "Content-Type: text/html"
    print
    print """<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>%s</title>
    <link rel="stylesheet" type="text/css" media="screen" href="coma.css"/>
  </head>
  <body>""" % (title)




def action_url(action, session):
    """Construct an URL used to access an action."""

    result = "index.py"
    if action:
	result = result + "?action=" + action
    if session:
	result = result + "?session=" + session.id
    return result



def session_menu(session = None):
    """Show the state of the session and create an action menu."""

    print "<hr/>"
    if session:
	print "To be done"
    else:
	print """<p>You are not logged in.</p>"""
	print """<p>Actions: <a href="%s">Login</a></p>""" % \
	      (action_url('login', session))





def footer(validator='http://localhost/w3c-validator/check?uri=referer'):
    """Print the footer of a document.  The parameter validator designates
    the url which may be used to validate the document.  The output is not
    pretty-printed."""

    if validator != '' or validator != None:
	print """<hr/><p><a href="%s">Valid XHTML 1.1</a></p>""" % (validator)
    print """</body></html>"""




def do_submit_paper(form, name, path, *args):
    if form.has_key(name):
	path = os.path.join(path, os.path.basename(form[name].filename))
	for each in args:
	    if path.endswith(each) and not os.path.isfile(path):
		file(path, 'wb').write(str(form[name].value))
		return True




def submit_paper(session):
    """Show the submit paper form and initiate the upload."""

    header('Submit a Paper')
    if session:
	print """
    <form name="submit" method="POST"
	  action="index.py?action=upload?session=%s"
	  enctype="multipart/form-data">
    <input name="upload" type="file"/><input type="submit" name="submit"/>
    </form>""" % (session.id)
    else:
	print """<p>You need to log in first</p>"""
    session_menu(session)
    footer(session)

    if session:
	form = cgi.FieldStorage()
	print 'Content-Type: text/htmln'
	if do_submit_papers(form, 'upload', '', '.txt', '.zip'):
	    print 'Finished uploading file...'
	else:
	    print 'Failed to upload file. Please visit our help center at...'



def test():
    """General testing page."""

    print """    <h1>COMA Conference Manager</h1>
    <p>This is a test</p>"""





def main():
    header()
    test()
    session_menu()
    footer()

if __name__ == '__main__':
    main()
