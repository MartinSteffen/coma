#! /usr/bin/python
#

"""Data model and PostgreSQL connector."""

import datetime




##############################################################################
#
# Data Model
#
##############################################################################

class Conference:
    def __init__(self, abbrev, name, description, homepage, asd, psd, rd, nd,
                 vfd, cs, ce, mrp):
        self.abbrev = abbrev
        self.name = name
        self.description = description
        self.homepage = homepage
        self.abstract_submission_date = asd
        self.paper_submission_deadline = psd
        self.review_deadline = rd
        self.notification_deadline = nd
        self.final_version_deadline = fvd
        self.conference_start = cs
        self.conference_end = ce
        self.min_reviews_per_paper = mrp
        
    def __init__(self, row):
        """Create the object from a row tuple."""
        self.abbrev = row[0]
        self.name = row[1]
        self.description = row[2]
        self.homepage = row[3]
        self.abstract_submission_date = row[4] # parse date
        self.paper_submission_deadline = row[5] # parse date
        self.review_deadline = row[6] # parse date
        self.notification_deadline = row[7] # parse date
        self.final_version_deadline = row[8] # parse date
        self.conference_start = row[9] # parse date
        self.conference_end = row[10] # parse date
        self.min_reviews_per_paper = row[11] 

    def as_dict(self, *dict):
        """Put the necessary info of the conference into the dictionary for
        template processing"""
        dict['abbreviation'] = self.abbrev
        dict['name'] = self.name
        dict['description'] = self.description
        dict['homepage'] = self.homepage
        if self.abstract_submission_deadline:
            dict['abstract_submission_deadline'] = \
                      self.abstract_submission_deadline.strftime("%B %d, %Y")
        else:
            dict['abstract_submission_deadline'] = None
        dict['review_deadline'] = self.review_deadline.strftime("%B %d, %Y")
        dict['notification_deadline'] = \
                             self.notification_deadline.strftime("%B %d, %Y")
        dict['final_version_deadline'] = \
                            self.final_version_deadline.strftime("%B %d, %Y")
        dict['conference_start'] = self.conference_start.strftime("%B %d, %Y")
        dict['conference_end'] = self.conference_end.strftime("%B %d, %Y")
        dict['min_reviews_per_paper'] = "%d" % (self.min_reviews_per_paper)





class User:
    title = ['Mr', 'Ms', 'Mrs', 'Dr', 'Prof', 'Prof Dr']

    def __init__(self, login, realname, password, roles, affiliation,
                 phone_number, fax_number, street, postal_code):
	self.email = login
	self.realname = realname
	self.password = password
	self.roles = roles
        self.affiliation = affiliation
        self.phone_number = phone_number
        self.fax_number = fax_number
        self.street = street
        self.postal_code = postal_code
        self.city = city
        self.state = state
        self.country = country
        self.password = password

    def __init__(self, row):
        """Initialize the object from a mysql row"""
        self.email = row[0]
        self.conference = row[1]
        self.roles = row[2]
        self.firstname = row[3]
        self.lastname = row[4]
        self.title = row[5]
        self.affiliation = row[6]
        self.phone_number = row[7]
        self.fax_number = row[8]
        self.street = row[9]
        self.postal_code = row[10]
        self.city = row[11]
        self.state = row[12]
        self.country = row[13]
        self.password = row[14]



class Forum:
    def __init__(self, conference, title, description):
        """Create a new forum.  The id parameter is deliberately omitted,
        because the data base will assign a new id."""
        self.id = None
        self.conference = conference
        self.title = tile
        self.description = description
        self.threads = None

    def __init__(self, row):
        """Create a new forum from a table row."""
        self.id = row[0]
        self.conference = get_conference(row[1])
        self.title = row[2]
        self.description = row[3]
        self.threads = None

    def to_dict(self, *dict):
        """Add paper information to the dictionary for processing.  Omit
        the conference."""
        dict['id'] = self.id
        dict['title'] = self.title
        dict['forum_description'] = self.description



class Paper(Forum):
    def __init__(self, user, authors, title, abstract, file, topics,
		 author_is_pc):
	self.user = user
	self.authors = authors
	self.title = title
	self.abstract = abstract
	self.file = file
	self.topics = topics
	self.author_is_pc = author_is_pc





##############################################################################
#
# Helpers for connecting to the data base.
#
##############################################################################

import pg
import config

class ComaDB:
    """Objects of this class provide a connection to the data base."""

    def __init__(self):
	"""Create a new object."""
	self.connection = pg.connect(dbname = config.dbname,
                                     user = config.user,
				     passwd = config.password)

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
