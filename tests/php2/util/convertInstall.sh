#!/bin/sh
#
#     This script converts a install.sql.php file from the php
# group to a real mysql script
#
#
# @author Thiago Tonelli Bartolomei
#

# Source configs
. config/tests.conf

# Insert use database to be sure where we are messing
echo "USE $DB;" > $INSTALL

# Read each line and put a ";" in the end
sed -e'a;' $INSTALL_SRC >> $INSTALL


