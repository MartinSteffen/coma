#
# Helpers for connecting to the data base.
#
import pg
import comaconf
import sys

class Connection:
    """Objects of this class provide a connection to the data base."""

    def __init__(self):
	"""Create a new object."""
	self.connection = pg.connect(dbname = comaconf.dbname,
				     user = comaconf.user,
				     passwd = comaconf.password,
				     host = comaconf.host)

    def query(self, query):
	"""Send a query to the data base and handle errors."""
	__result__ = self.connection.query(query)
	if __result__ and type(__result__) is not long:
	    return __result__.getresult()
	else:
	    return None

    def get_conferences_and_roles(self, email):
	"""Get the conferences a user participates in from the data base.
	Returns a list of pairs, where the first part is a conference
	the user participates in and the second is his role in the
	conference."""
	__query = self.query(
	    """SELECT Conferences.abbreviation, Conferences.name,
		Conferences.homepage, Conferences.abstract_submission_deadline,
		Conferences.paper_submission_deadline,
		Conferences.review_deadline, Conferences.notification_deadline,
		Conferences.final_version_deadline,
		Conferences.conference_start, Conferences.conference_end,
		Conferences.min_reviews_per_paper, Roles.role
	    FROM Conferences INNER JOIN Roles
	      ON  Conferences.abbreviation = Roles.conference
	      AND Conferences.abbreviation <> 'None'
	      AND Roles.email = '%s';""" % (email))
	if __query:
	    __result = []
	    __roles = []
	    for each in __query:
		__result.append(Conference(each[0:10]))
		__roles.append(Role((each[0], email, each[11])))
