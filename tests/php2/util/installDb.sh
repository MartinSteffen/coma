#!/bin/sh
#
#     This script installs a new db in the mysql server.
# Actually, it just calls the appropriate scripts to do that
#
# @author Thiago Tonelli Bartolomei
#

# Source configs
. config/tests.conf

# Run the sql scripts
mysql -u$DB_USER -p$DB_PASS $DB < sql/drop.sql
mysql -u$DB_USER -p$DB_PASS $DB < sql/install.sql
# We do not need basedata because we will create everything we need from scratch
#mysql -u$DB_USER -p$DB_PASS $DB < sql/basedata.sql

