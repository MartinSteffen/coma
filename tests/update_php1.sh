#! /bin/sh
if test -d ~/public_html/php1/coma1 ; then
    svn update ~/public_html/php1/coma1
else
    svn checkout http://localhost:8080/svn/coma/trunk/php1/coma1 ~/public_html/php1/coma1
fi 
