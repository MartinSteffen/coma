#!/bin/sh
#
#     This script converts the templates in the TEMPLATES dir
# to run in a specfic CoMa installation.
#
#
# @author Thiago Tonelli Bartolomei
#

# Source configs
. config/tests.conf

echo "Converting $BASEURL in $TARGET..."

# Run sed on each template from the templates dir
for file in $TEMPLATES/*.plc
do
	sed -e "{
		s|$BASEURL|$TARGET|g
	}" $file > $TESTCASES/`basename $file`
done

