#! /usr/bin/python
#
# Very simple session management.
#
import time
import random
import string

class Session:
    """The session object is used to store information on a connection on
    the server side.  This is done to avoid cookies.

    Fields are
	user:  A reference to the user who intiated the session.
	expires:  The time after which the session expires, in seconds.

    The sessions are stored in the backend data base.
    """

    def __init__(self, user, expires = None):
	self.id = '1'
	self.user = user
	self.time = time

class SessionManager:
    """This connects to the data base and maintains a list of sessions."""

    def _cleanup(self, db):
        """Clear all expired sessions from the data base."""
        
    def create(self, expires, user = None):
        """Create a new session."""

    def delete(self, db, sid):
        """Delete a session.  Do not check whether this session really
        exists."""
        db.query("DELETE FROM Sessions WHERE sid = %s;" % (sid))

    def fetch(self, db, sid):
        """Fetch the user data from the session."""
        if sid:
            result = db.query("SELECT * FROM Sessions WHERE sid = %s;" % (sid))
            if result:
                db.query("UPDATE Sessions SET last = %s WHERE sid = %s;" % \
                         (sid))
            else:
                return None
        else:
            return None

def main(db):
    """Test the session manager."""
    print db

if (__name__ == "__main__"):
    import comadb
    db = comadb.ComaDB()
    main(db)
