#! /usr/bin/python
#
"""The postgresql layer.

This connects the data model to postgresql.
"""

import comadb
import pg
import comaconfig

connection = connect(comaconfig.dbname, comaconfig.host, comaconfig.port,
		     comaconfig.opt, comaconfig.tty, comaconfig.user,
		     comaconfig.passwd)

class CoMaDB:
    """Objects of this class provide a connection to the data base."""

    def __init__(self):
	"""Create a new object."""
	self.dbname = comaconfig.dbname
	self.user = comaconfig.user
	self.password = comaconfig.password
	self.connection = pg.connect(dbname = self.dbname, user = self.user,
				     password = self.password)

    def __query__(self, query):
	"""Send a query to the data base and handle errors."""
	__result__ = self.connection.query(query)
	if __result__:
	    return __result__.dictresult()
	else:
	    return None

    def get_user(self, login):
	"""Get a user from the data base."""
	__result__ = self.__query__("" % { 'login' : login })
	if __result__:
	    return comadb.User()
	else:
	    return None

    def put_user(self, user):
	"""Put a new user into the data base."""
	return None

    def get_paper(self, number):
	"""Get a paper from the data base."""
	return None
