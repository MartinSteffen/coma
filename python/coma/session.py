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

    def __init__(self, row):
        self.id = row[0]
        if row[1] == 'None':
            self.user = None
        else:
            self.user = row[1]
        self.expires = float(row[2])
        self.time = float(row[3])
        if row[4] == 'None':
            self.conference = None
        else:
	    self.conference = row[4]

    def expired(self):
	"""Check whether the session has already expired."""
	return time.time() > self.time + self.expires





def __new_sid__():
    """Make a number based on current time, pid, remote ip, and two
    random ints, then hash with md5.  This should be fairly unique and
    very difficult to guess."""
    t = long(time.time() * 10000)
    pid = os.getpid()
    g = random.Random()
    r1 = g.randint(0, 999999)
    r2 = g.randint(0, 999999)
    ip = "127.0.0.1" # XXX: This should be obtainable from the environment
    return md5.new("%d%d%d%d%s" % (t, pid, r1, r2, ip)).hexdigest()





def get(db, sid):
    """Fetch the user data from the session.  This function updates the
    session and returns the new session object."""
    __result__ = db.connection.query(
	"""SELECT * FROM sessions WHERE sid = '%s';""" % (sid)).getresult()[0]
    if __result__:
	__result__ = Session(__result__)
	__result__ = update(db, __result__)
    return __result__





def put(db, sid):
    """Put a session into the data base."""
    db.connection.query(
        """INSERT INTO Sessions (sid, login, expires, last, conference)
	VALUES ('%s', '%s', %s, %s, '%s')""" %
	(sid.id, sid.user, sid.expires, sid.time, sid.conference))





def new(db, _user = None, _expires = 30*60):
    """Create a new session.  By default, sessions expire after 30 Minutes."""
    while True:
	_result = Session((__new_sid__(), None, _expires, time.time(), None))
	try:
	    put(db, _result)
	except pg.error:
            # Session already exists.
	    raise
	else:
	    return _result





def delete(db, sid):
    """Delete a session.  Do not check whether this session really
    exists."""
    db.query("DELETE FROM Sessions WHERE sid = %s;" % (sid))





def update(db, sid):
    """Update the time stamp of a session and synchronize the data base."""
    sid.last = time.time()
    db.query("UPDATE Sessions SET last = %s WHERE sid = '%s';" %
	     (sid.last, sid.id))
    return sid





def change_login(db, sid, email):
    """Update the session to mark a user logged in."""
    sid.login = email
    sid.last = time.time()
    db.query("UPDATE Sessions SET login = '%s', last = %s WHERE sid = '%s';" %
	     (sid.login, sid.last, sid.id))
    sid = get(db, sid.id)
    return sid




def collect(db):
    """Garbage collect sessions.  We collect all sessions which are older
    than 2 hours."""
    _maxage = 2*60*60
    db.query("DELETE FROM Sessions WHERE (time + %d) > %d;" %
	     (_maxage, time.time()))





def main(db):
    """Test the session manager."""
    print db





if (__name__ == "__main__"):
    import comadb
    db = comadb.ComaDB()
    main(db)
