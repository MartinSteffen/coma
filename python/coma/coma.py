#!/usr/bin/python
#
# This is the main cgi script.
#
import cgi
import sys
import session





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



def build_action(label, action, session = None):
    if session:
        return '<tr><td><a href="index.py?action=%s&session=%s">' \
               '%s</a></td></tr>' % (action, session.id, label)
    else:
        return '<tr><td><a href="index.py?action=%s">%s</a></td></tr>' % \
               (action, label)



def setup_actions(session = None):
    if session:
        result = build_action('Log Out', 'logout', session)
        result += build_action('View Papers', 'list-papers', session)
    else:
        result = build_action('Log In', 'login')
    return result




if (__name__ == "__main__"):
    print "Hacking attempt."
