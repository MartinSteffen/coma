#!/bin/sh
#
#     This script converts a basedata.sql that fits the php1 database to 
# a new basedata.sql that fits better to the php2 group database.
#
#
# @author Thiago Tonelli Bartolomei
#

# Source configs
. config/tests.conf

# Insert use database to be sure where we are messing
echo "USE $DB;" > $BASEDATA

# Rules: 
#    1) Convert the old password to the word "pass" in php2 style (MD5)
#    2) Convert all the database names to lowercase
#    3) Remove the data for tables "reviewreport" and "rating"
#    4) Convert the NULL values to 1. This will affect only the role table.
#
sed -e '{
s/f73f614d3d6a5597bbf5274485f64e4443038005/1a1dc91c907325c69271ddf0c944bc72/g 
s/Conference/conference/g
s/Criterion/criterion/g
s/DeniesPaper/deniespaper/g
s/ExcludesPaper/excludespaper/g
s/Forum/forum/g
s/IsAboutTopic/isabouttopic/g
s/IsCoAuthorOf/iscoauthorof/g
s/Message/message/g
s/Modules/modules/g
s/Paper/paper/g
s/Person/person/g
s/Preferspaper/preferspaper/g
s/PrefersTopic/preferstopic/g
s/Rating/rating/g
s/RejectedPapers/rejectedpapers/g
s/ReviewReport/reviewreport/g
s/Rights/rights/g
s/Role/role/g
s/RoleDescription/roledescription/g
s/Topic/topic/g
s/NULL/1/g
}' $BASEDATA_SRC | grep -v rating | grep -v reviewreport >> $BASEDATA

