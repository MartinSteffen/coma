## 
# Universitaet Kiel
# Programming in the Many - WS 2004 / 2005
#
# README for the Test Package of the Php2 Group
#
# Author    : Thiago Tonelli Bartolomei
##

 ########################
 # 1. Table of Contents #
 ########################

 1. Table of Contents
 2. Pre-requisites
 3. Installing the Test Suites
 4. Running the Test Suites
 5. Appendices 

 #####################
 # 2. Pre-requisites #
 #####################

 This document assumes you have already installed:
	1) Apache web server version >= 2.0.52
	2) MySql server version >= 3.23.58
	3) Php version >= 4.3.10
 
 It also assumes that:
	1) you have configured the apache web server to use the php module
	2) you have write access to a database in the mysql server
	3) you have a copy of the php2 bundle in the regular "htdocs" of the 
apache web server (so you have "htdocs/php2/"). These directories are is from now on 
as "$HTDOCS" and "$PHP"". If you are on the trunk tree of the subversion server, the following codes will do it:

	"cp -r php2 $HTDOCS"

	For example, if your apache is at "/opt/apache2", you should use:

	"cp -r php2 /opt/apache2/htdocs/"


 #################################
 # 3. Installing the Test Suites #
 #################################

 To install the test suites you must copy this directory to the same directory
where you installed the php2 bundle. If you are on the trunk tree of the
subversion server, use the following command:

	"cd tests/php2; cp -r web $HTDOCS"

	For example, if your apache is at "/opt/apache2", you should use:

	"cd tests/php2; cp -r web /opt/apache2"

 ##############################
 # 4. Running the Test Suites #
 ##############################

 To run the test suites, start the web server and open your browser on the
directory where you install the test suite. For example:

	"http://localhost/web"

 There you have a link for the possible tests.

 # 4.1 Running the Unit Test Suites #

 	1) In the index page, click on the link "Unit Tests". The main page for the unit
tests will open.  
	2) Select the suites you want to run in the checkboxes
	3) Select if you want to see only tests that failed or also tests that
passed
	4) Click on "run tests"


 #################
 # 5. Appendices #
 #################

 # 5.1 configure command for Php4 #

 This command was used to compile php4. It has the needed libraries included.
If you experience problems in the "RequiredFunctions" you should try use it.
Just check for the right prefixes.

 './configure' '--host=i386-redhat-linux' '--build=i386-redhat-linux'
'--target=i386-redhat-linux-gnu' '--program-prefix=' '--prefix=/opt/php4'
'--enable-force-cgi-redirect' '--disable-debug' '--enable-pic'
'--disable-rpath' '--enable-inline-optimization' '--with-bz2' '--with-gd'
'--enable-gd-native-ttf' '--without-gdbm' '--with-gettext' '--with-ncurses'
'--with-gmp' '--with-iconv' '--with-openssl' '--with-png'
'--with-regex=system' '--with-xml' '--with-zlib' '--with-layout=GNU'
'--enable-bcmath' '--enable-exif' '--enable-ftp' '--enable-magic-quotes'
'--enable-safe-mode' '--enable-sockets' '--enable-sysvsem' '--enable-sysvshm'
'--enable-track-vars' '--enable-yp' '--enable-wddx' '--with-kerberos'
'--with-ldap=shared' '--with-mysql' '--enable-memory-limit' '--enable-bcmath'
'--enable-shmop' '--enable-calendar' '--enable-dbx' '--enable-dio'
'--enable-mcal' '--enable-mbstring=shared' '--enable-mbregex'
'--with-apxs2=/opt/apache2/bin/apxs'
 

