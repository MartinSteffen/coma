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
mysql -u$DB_USER -p$DB_PASS $DB < sql/basedata.sql

