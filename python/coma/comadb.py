#! /usr/bin/python
#

"""The data model.
"""

class User:
    def __init__(self, login, realname, password, roles):
	self.login = login
	self.realname = realname
	self.password = password
	self.roles = roles

class Paper:
    def __init__(self, user, authors, title, abstract, file, topics,
		 author_is_pc)
	self.user = user
	self.authors = authors
	self.title = title
	self.abstract = abstract
	self.file = file
	self.topics = topics
	self.author_is_pc = author_is_pc

