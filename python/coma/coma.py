#!/usr/bin/python
#
# This is the main cgi script.
#
import cgi
import sys





def process_template(file, dictionary, mimetype = 'application/xhtml+xml'):
    """Read a template xml file, process it, and then print it."""
    print mimetype
    print
    print open(file).read() % dictionary





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





if (__name__ == "__main__"):
    sys.path.insert(0, "/home/mky/public_html/coma")
    sys.stderr = sys.stdout
    cgi.test()
