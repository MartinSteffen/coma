#!/bin/sh
#
#     This script creates a drop.sql file to drop the tables from 
# the database
#
# @author Thiago Tonelli Bartolomei
#

# Source configs
. config/tests.conf

echo "
--
-- Drop all the tables from the database "$DB"
--
USE $DB;

DROP TABLE IF EXISTS conference;
DROP TABLE IF EXISTS criterion;
DROP TABLE IF EXISTS deniespaper;
DROP TABLE IF EXISTS excludespaper;
DROP TABLE IF EXISTS forum;
DROP TABLE IF EXISTS isabouttopic;
DROP TABLE IF EXISTS iscoauthorof;
DROP TABLE IF EXISTS message;
DROP TABLE IF EXISTS modules;
DROP TABLE IF EXISTS paper;
DROP TABLE IF EXISTS person;
DROP TABLE IF EXISTS preferspaper;
DROP TABLE IF EXISTS preferstopic;
DROP TABLE IF EXISTS rating;
DROP TABLE IF EXISTS rejectedpapers;
DROP TABLE IF EXISTS reviewreport;
DROP TABLE IF EXISTS rights;
DROP TABLE IF EXISTS role;
DROP TABLE IF EXISTS roledescription;
DROP TABLE IF EXISTS topic;" > $DROP

