#!/bin/sh
#
#     This script runs all the scripts in this directory,
# using the database name given by $1 or comadb as default.
#
# @author Thiago Tonelli Bartolomei
#

DB=comadb

if [ "$1x" != "x" ];then
        DB=$1;
fi

createDrop.sh $DB
convertBaseData.sh $DB
convertInstall.sh $DB

