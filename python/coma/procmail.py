#! /usr/bin/env python
#
#

""" Mock-up of a module which reads an e-mail, parses it, and inserts
it into a data base.  The mail is read from standard-input.  This
makes it convenient to call the script from mail-aliases or
similar."""

import sys
import email
import smtplib
import MySQLdb

class EMail:
    """The email object we may read or write to the data base.  We do not
    handle attachements, yet and we hope that nobody will send html-messages.
    If html messages are sent, then the code here will break down."""
    def __init__(self, _from_, _to_, _subject_, _message_id_, _in_reply_to_,
                 _references_, _content_):
        self._from = _from_
        self._to = _to_
        self._subject = _subject_
        self._message_id = _message_id_
        self._in_reply_to = _in_reply_to_
        self._references = _references_
        self._content = _content_

def mail_from_message(msg):
    """Create an Email Object from a message"""
    return EMail(msg['From'], msg['To'], msg['Subject'], msg['Message-Id'],
                 msg['In-Reply-To'], msg['References'], msg.get_payload())

def mail_from_database(row):
    """Create an EMail object from a data base row"""
    return EMail(row[0], row[1], row[2], row[3], row[4], row[5], row[7])

def main():
    msg = email.message_from_file(sys.stdin)
    assert not msg.is_multipart()

    db = MySQLdb.connect(host='localhost', user='user', passwd='secret',
                         db='conference')
    cursor = db.cursor()

    # Get the relevant header information.  This is from, to, subject,
    # message-id, in-reply-to, references, and content.
    # All other header information is thrown away.
    #
    mail = mail_from_message(msg)

    # If we have never seen this mail yet, then we put into the data base and
    # resent it to all interested parties.
    #
    # We assume that if we sent the message and it comes back to us,
    # indicated by the presence of an X-Loop header, then it is already in
    # the data base.  In this case we do nothing.
    #
    # Needs some tweaking if there is a reply-to, but the message is
    # not in the data base.  We assume that this will not happen.
    # 
    if msg['X-Loop'] != config.my_address:
        result = cursor.execute("INSERT INTO mails (fields) VALUES (%s, %s)",
                                (mail._message_id, mail._from, mail._to,
                                 mail._subject, mail._in_reply_to,
                                 mail._content))
        # A new message in the data base also means that we have to explode
        # it.  We mark in the mail that we have already seen the message and
        # tweak the from and to members to the corresponding entities.
        #
        msg['X-Loop'] = config.my_address
        msg['From'] = config.my_address
        msg['To'] = exploded_receivers
        s = smtplib.SMTP()
        s.connect()
        s.sendmail(config.my_address, exploded_receivers, msg.as_string())
        s.close()
