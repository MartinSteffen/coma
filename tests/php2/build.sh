#!/bin/sh
##
# Universitaet Kiel
# Programming in the Many - WS 2004 / 2005
#
# This script controls the generation and execution
# of test scripts for the CoMa php2 project 
#
# Author    : Thiago Tonelli Bartolomei
##

# Configurations for the script are taken from:
# 1) config/tests.conf file
# 2) command line, which overwrite!
#
. config/tests.conf

# Internal Variables (DON'T CHANGE)
silence=0
create_config=0
run_tests=1
cleanup=1

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
<a href=\"phpunit\">Unit Tests</a><br>
<a href=\"regression\">Regression Tests Results</a>
</table>
</center>
</body>
</html>"

install_db() {
        if [ $silence = 0 ]; then
                echo "Installing the database scripts..."
        fi
	# generate sql scripts
	util/createDrop.sh
	util/convertInstall.sh
	util/convertBaseData.sh

	# run the sql scripts
	util/installDb.sh
}

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

run_testcases() {
        if [ $silence = 0 ]; then
                echo "Running testcases..."
        fi
	
	# Create testcases from templates
	util/convertTemplates.sh

	# Execute the testcases script
	util/runTestcases.sh
}

clean_up() {
        if [ $silence = 0 ]; then
                echo "Clean up..."
        fi
	# Remove generated testcases
	rm -f testcases/*
	rm -f tmp/*
	rm -f web/regression/index.html

	# Remove the sql scripts
	rm -f sql/*.sql

}

##
# Prints the usage
##
print_usage() {
    echo $"Usage: $0 [-hmsnr] [-c config_file] [-w webroot_dir] [-t trunk_dir] [-p package_dir]"
    echo
    echo $" -h              Help"
    echo $" -m              Cleanup and Make dirs  [OFF]"
    echo $" -s              Disable messages       [ON]"
    echo $" -n              Disable configuration  [ON]"
    echo $" -r              Run testcases          [OFF]"
    echo $" -c config_file  Use config_file        [config/config.inc.php]" 
    echo $" -w webroot_dir  Use webroot_dir        [/home/wprguest4/public_html/php2]" 
    echo $" -p package_dir  Use package_dir        [../../php2]"
    echo $" -d server_dir   Use package_in_server  [php2]"
    echo $" -t test_dir     Use test_in_server     [php2tests]" 
}

##
# Get the user options
##
while getopts "hsmnrc:w:t:p:" args $OPTIONS;
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

  # Run tests
  if [ "$args" = "r" ]; then
      run_tests=0
  fi

  # Cleanup
  if [ "$args" = "m" ]; then
      cleanup=0
  fi


done

##
# Execute the script
##
# Cleanup is used only to clean and get out
if [ $cleanup = 0 ]; then
    clean_up
    echo "Done."
else

    # Clean the database
    install_db

    # Install the php2 sources in the server
    install_php2

    # Check if we should use our configuration
    if [ $create_config = 0 ]; then
        install_config
    fi

    # Check if we should run the testcases
    if [ $run_tests = 0 ]; then
        run_testcases
    fi

    # Install test results and unit tests in the server
    install_tests

    # Generate the index file
    generate_index

    # Done
    if [ $silence = 0 ]; then
    	echo "Done."
    fi
fi
