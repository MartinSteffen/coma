#!/bin/sh

# Environment Configurations (FEEL FREE TO CHANGE - COMMAND LINE OVERWRITE!)
WEB_ROOT=/home/wprguest4/public_html/php2
CONFIG_INC=config/config.inc.php
PACKAGE=../../php2
PACKAGE_IN_SERVER=php2
TESTS_IN_SERVER=php2tests

# Internal Configurations (DON'T CHANGE)
WEB=web

# Internal Variables (DON'T CHANGE)
silence=0
create_config=0

# HTML "templates"
HEADER="<html>
<head>
<title>Tests for Php2</title>
</head>
<body>
<center>

<h2>Build generated for Php2</h2>
<h4>`date`</h4>

<p/>
"

FOOTER="
<a href=\"phpunit\">Unit Tests</a>
</table>
</center>
</body>
</html>"

install_php2() {
	if [ $silence = 0 ]; then 
		echo "Installing the php2 package on the webserver..."
	fi
	# remove the old package in server
	rm -rf $WEB_ROOT/$PACKAGE_IN_SERVER
	# copy the new package to the server
	cp -r $PACKAGE $WEB_ROOT/$PACKAGE_IN_SERVER 2> /dev/null
}

install_tests() {
	if [ $silence = 0 ]; then
		echo "Installing the web tests package on the webserver..."
	fi
	# remove the old tests dir in server
	rm -rf $WEB_ROOT/$TESTS_IN_SERVER
	# copy the new tests dir to the server
	cp -r $WEB $WEB_ROOT/$TESTS_IN_SERVER 2> /dev/null
}

install_config() {
	if [ $silence = 0 ]; then 
		echo "Installing configuration on the webserver..."
	fi
	# remove the install directory
	rm -rf $WEB_ROOT/$PACKAGE_IN_SERVER/install
	# copy the config file to the server
	cp $CONFIG_INC $WEB_ROOT/$PACKAGE_IN_SERVER/includes/ 2> /dev/null
}

generate_index() {
	if [ $silence = 0 ]; then
		echo "Generating index.html on the webserver..."
	fi
	# create the index.html file and put the header
	echo $HEADER > $WEB_ROOT/$TESTS_IN_SERVER/index.html

	# put the sub version info in the file
	echo "<i><pre>" >> $WEB_ROOT/$TESTS_IN_SERVER/index.html
	cd $PACKAGE > /dev/null
	svn info 1>> $WEB_ROOT/$TESTS_IN_SERVER/index.html
	cd - > /dev/null
	echo "</pre></i>" >> $WEB_ROOT/$TESTS_IN_SERVER/index.html

	# put the path to the actual build
	echo "<a href=\"../$PACKAGE_IN_SERVER\">Php2 build used for tests</a><br>" >> $WEB_ROOT/$TESTS_IN_SERVER/index.html

	# put the footer int the file
	echo $FOOTER >> $WEB_ROOT/$TESTS_IN_SERVER/index.html
	
}

##
# Prints the usage
##
print_usage() {
    echo $"Usage: $0 [-hsn] [-c config_file] [-w webroot_dir] [-t trunk_dir] [-p package_dir]"
    echo
    echo $" -h              Help"
    echo $" -s              Disable messages       [ON]"
    echo $" -n              Disable configuration  [ON]"
    echo $" -c config_file  Use config_file        [config/config.inc.php]" 
    echo $" -w webroot_dir  Use webroot_dir        [/home/wprguest4/public_html/php2]" 
    echo $" -p package_dir  Use package_dir        [../../php2]"
    echo $" -d server_dir   Use package_in_server  [php2]"
    echo $" -t test_dir     Use test_in_server     [php2tests]" 
}

##
# Get the user options
##
while getopts "hsnc:w:t:p:" args $OPTIONS;
  do
  
  # Help - print usage and exit
  if [ "$args" = "h" ]; then
      print_usage
      exit 0
  fi

  # Silence - avoid messages
  if [ "$args" = "s" ]; then
      silence=1
  fi

  # Do not create configuration
  if [ "$args" = "n" ]; then
      create_config=1
  fi 

  # Get a new config file
  if [ "$args" = "c" ]; then
      CONFIG_INC="${OPTARG}"
  fi

  # Get a new webroot
  if [ "$args" = "w" ]; then
      WEB_ROOT="${OPTARG}"
  fi

  # Get a new package directory
  if [ "$args" = "p" ]; then
      PACKAGE="${OPTARG}"
  fi

  # Get a new package in server directory
  if [ "$args" = "d" ]; then
      PACKAGE_IN_SERVER="${OPTARG}"
  fi

  # Get a new tests in server directory
  if [ "$args" = "t" ]; then
      TESTS_IN_SERVER="${OPTARG}"
  fi
done

##
# Execute the script
##
install_php2
install_tests

if [ $create_config = 0 ]; then
    install_config
fi

generate_index
