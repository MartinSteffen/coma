#! /usr/bin/python
#

"""Data model and PostgreSQL connector."""

import comaconf

class ConfigurationError:
    """This class describes the situation if no connection has been defined."""
    def __init__(self, arg):
        """Standard constructor."""
        value = arg


if comaconfig.database == 'POSTGRESQL':
    from connpsql import Connection
elif comaconfig.database == 'MYSQL':
    from connmysql import Connection
else:
    raise ConfigurationError, "No database connection configured"





import datetime
import re





def quote(v, default = False):
    """Small utility function to quote strings before we put them into
    the data base.  I always wonder why nobody thought of providing
    this.  This version has been copied and adapted from pgdb.py."""
    if v:
        if isinstance(v, types.StringType):
            return '\'' + string.replace(string.replace(str(v), '\\', '\\\\'),
                                         '\'', '\'\'')
        elif isinstance(v, (types.IntType, types.LongType, types.FloatType)):
            return v
        elif isinstance(x, (types.ListType, types.TupleType)):
            return '(%s)' % string.join(map(lambda v: str(quote(v)), v), ',')
        elif isinstance(v, DateTime.DateTimeType):
            return str(x)
        elif hasattr(v, '__pg_repr__'):
            return x.__pg_repr__()
    else:
        if default:
            return 'DEFAULT'
        else:
            return 'NULL'





_match_date_iso = re.compile(
    "(?P<year>\d\d|\d\d\d\d)-(?P<month>\d\d)-(?P<day>\d\d)")

_match = _match_date_iso.match(date)

def parse_date(date):
    """Use this to parse dates."""
    if not _match:
	_match_date_ger = re.compile(
	    "(?P<day>\d\d)-(?P<month>\d\d)-(?P<year>\d\d|\d\d\d\d)")
	_match = _match_date_ger.match(date)
    _year = int(_match.group('year'))
    _month = int(_match.group('month'))
    _day = int(_match.group('day'))
    if _year < 100:
	_year += 2000
    return datetime.date(_year, _month, _day)





##############################################################################
#
# Data Model
#
##############################################################################

class Conference:
    def __init__(self, row):
	"""Create the object from a row tuple."""
	self.abbreviation = row[0]
	self.name = row[1]
	# self.description = row[2]
	self.homepage = row[2]
	if row[3]:
	    self.abstract_submission_deadline = _parse_date(row[3])
	else:
	    self.abstract_submission_deadline = _parse_date(row[4])
	self.paper_submission_deadline = _parse_date(row[4])
	self.review_deadline = _parse_date(row[5])
	self.notification_deadline = _parse_date(row[6])
	self.final_version_deadline = _parse_date(row[7])
	self.start = _parse_date(row[8])
	self.end = _parse_date(row[9])
	self.min_reviews_per_paper = int(row[10])

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





title_name = ['', 'Mr', 'Ms', 'Mrs', 'Dr', 'Prof', 'Prof Dr']


class User:
    def __init__(self, row):
	"""Initialize the object from a mysql row"""
	self.email = row[0]
	self.password = row[1]
	self.title = row[2]
	self.firstname = row[3]
	self.lastname = row[4]
	self.affiliation = row[5]
	self.phone_number = row[6]
	self.fax_number = row[7]
	self.street = row[8]
	self.postal_code = row[9]
	self.city = row[10]
	self.state = row[11]
	self.country = row[12]
	self.sys_role = row[13]

    def get_title(self):
	return title_name[self.title]

    def as_query_string(self):
	_result = ""
	_result += "'" + self.email + "', "
	_result += "'" + self.password + "', "
	_result += self.title.__str__() + ', '
	_result += "'" + self.firstname + "', "
	_result += "'" + self.lastname + "', "
	_result += "'" + self.affiliation + "', "
	_result += _optional(self.phone_number) + ', '
	_result += _optional(self.fax_number) + ', '
	_result += _optional(self.street) + ', '
	_result += _optional(self.postal_code) + ', '
	_result += _optional(self.city) + ', '
	_result += _optional(self.state) + ', '
	_result += _optional(self.country) + ', '
	if self.sys_role:
	    _result += "B'"
	    for each in self.sys_role:
		if each:
		    _result += '1'
		else:
		    _result += '0'
	    _result += "'"
	else:
	    _result += "DEFAULT"
	return _result




rolename = ['Admin', 'Chair', 'PC Member', 'Author']

class Role:
    def __init__(self, _row):
	self.email = _row[0]
	self.conference = _row[1]
	self.role = _row[2]

    def as_string(self):
	"""Convert a role to a string"""
	_result = ""
	for each in self.role:
	    if each:
		_result += '1'
	    else:
		_result += '0'
	return _result

    def as_text(self):
	"""Convert a role to html."""
	for each in range(4):
	    if self.role[each]:
		_result += rolename[each]
		if each < 3 and self.role[each + 1]:
		    _result += ', '
	return _result


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




class DataBase:
    """This abstracts from the implementation of a data base and provides
    a unified interface to the rest of the tool."""

    def __init__(self):
	"""Create a new database connection"""
	self.connection = Connection()

    def get_user_by_mail(self, email):
	"""Get a user by his e-mail address."""
	_rows = self.connection.query(
	    "SELECT * FROM users WHERE email = '%s';" % (email))
	if _rows and _rows[0]:
	    return User(_rows[0])
	else:
	    return None

    def put_user(self, user):
	"""Insert a user into the database"""
	_result = self.connection.query(
	    """INSERT INTO users (email, password, title, first_name,
	    last_name, affiliation, phone_number, fax_number, street,
	    postal_code, city, state, country, sys_role) VALUES (%s)""" %
	    (user.as_query_string()))





def get_database_connection():
    """Get a database connection"""
    return DataBase()
