#! /usr/bin/python

"""The CoMa Conference Manager Main module."""

__version__ = "0.1.0"

#import cgi
import coma
import session

def main():
    """Show the index page."""
    coma.process_template('./templates/index.xml',
                          {'actions': coma.setup_actions(session.Session('user'))})

if (__name__ == '__main__'):
    main()
