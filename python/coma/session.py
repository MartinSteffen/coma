#! /usr/bin/python
#
# Very simple session management.
#
import time
import random
import string
import os
import md5
import pg


class Session:
    """The session object is used to store information on a connection on
    the server side.  This is done to avoid cookies.

    Fields are
	user:  A reference to the user who intiated the session.
	expires:  The time after which the session expires, in seconds.

    The sessions are stored in the backend data base.
    """

    def __init__(self, id, user = None, expires = 30*60, time = time.time()):
	self.id = id
	self.user = user
	self.expires = float(expires)
	self.time = float(time)

    def expired(self):
	"""Check whether the session has already expired."""
	return time.time() > self.time + self.expires

def _new_sid():
    """Make a number based on current time, pid, remote ip, and two
    random ints, then hash with md5.  This should be fairly unique and
    very difficult to guess."""
    t = long(time.time() * 10000)
    pid = os.getpid()
    g = random.Random()
    r1 = g.randint(0, 999999)
    r2 = g.randint(0, 999999)
    ip = "127.0.0.1" # XXXX: This should be obtainable from the environment?
    return md5.new("%d%d%d%d%s" % (t, pid, r1, r2, ip)).hexdigest()

def get(db, sid):
    """Fetch the user data from the session."""
    __result__ = db.connection.query(
	"""SELECT * FROM sessions WHERE sid = '%s';""" % (sid)).getresult()[0]
    if __result__:
	__result__ = Session(__result__[0], __result__[1], __result__[2])
	update(db, __result__)
    return __result__

def put(db, session):
    """Put a session into the data base."""
    db.connection.query("""INSERT INTO Sessions (sid, login, expires, last)
		    VALUES ('%s', '%s', %s, %s)""" %
		    (session.id, session.user, session.expires, session.time))

def new(db, expires = 30*60, user = None):
    """Create a new session.  By default, sessions expire after 30 Minutes."""
    while True:
	_result = Session(_new_sid(), user, expires)
	try:
	    put(db, _result)    # Throws an exception if the session-id exists.
	except pg.error:
	    raise
	else:
	    return _result

def delete(db, sid):
    """Delete a session.  Do not check whether this session really
    exists."""
    db.query("DELETE FROM Sessions WHERE sid = %s;" % (sid))

def update(db, session):
    """Update the time stamp of a session and synchronize the data base."""
    session.last = time.time()
    db.query("UPDATE Sessions SET last = %s WHERE sid = '%s';" %
	     (session.last, session.id))

def change_login(db, session, email):
    """Update the session to mark a user logged in."""
    session.login = email
    session.last = time.time()
    db.query("UPDATE Sessions SET login = '%s', last = %s WHERE sid = '%s';" %
	     (session.login, session.last, session.id))

def main(db):
    """Test the session manager."""
    print db

if (__name__ == "__main__"):
    import comadb
    db = comadb.ComaDB()
    main(db)
