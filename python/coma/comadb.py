#! /usr/bin/python
#

"""Data model and PostgreSQL connector."""

import datetime
import re



##############################################################################
#
# Data Model
#
##############################################################################

def _parse_date(date):
    _match_date_iso = re.compile(
	"(?P<year>\d\d|\d\d\d\d)-(?P<month>\d\d)-(?P<day>\d\d)")
    _match = _match_date_iso.match(date);
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





