#! /usr/bin/python
#
import session

def make_action(link, label):
    """Format a single action as html."""
    return '<th><a href="%s">%s</a></th>' % (link, label)

def make_action_menu(session):
    """Make an action menu for a session."""
    if session:
        return None
    else:
        return make_action("coma/?action=login", "Log&nbsp;In")

