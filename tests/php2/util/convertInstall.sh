#!/bin/sh
#
#     This script converts a install.sql.php file from the php
# group to a real mysql script
#
#
# @author Thiago Tonelli Bartolomei
#

SRC=../../../php2/install/install.sql.php
DEST=../sql/install.sql
DB=comadb

if [ "$1x" != "x" ];then
        DB=$1;
fi

# Insert use database to be sure where we are messing
echo "USE $DB;" > $DEST

# Read each line and put a ";" in the end
sed -e'a;' $SRC >> $DEST


