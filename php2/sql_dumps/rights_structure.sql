# phpMyAdmin MySQL-Dump
# version 2.2.3
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
#
# Host: localhost
# Generation Time: Dec 05, 2004 at 02:20 PM
# Server version: 3.23.47
# PHP Version: 4.1.1
# Database : `coma`
# --------------------------------------------------------

#
# Table structure for table `modules`
#

CREATE TABLE modules (
  module_id int(10) unsigned NOT NULL auto_increment,
  module varchar(15) NOT NULL default '',
  action varchar(20) default NULL,
  isLink tinyint(3) unsigned zerofill NOT NULL default '000',
  alternative varchar(31) default NULL,
  PRIMARY KEY  (module_id),
  UNIQUE KEY module_id (module_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `rights`
#

CREATE TABLE rights (
  role_id int(10) unsigned NOT NULL default '0',
  module_id int(10) unsigned NOT NULL default '0'
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `roledescription`
#

CREATE TABLE roledescription (
  role_id int(10) unsigned NOT NULL auto_increment,
  rolename varchar(20) NOT NULL default '',
  PRIMARY KEY  (role_id),
  UNIQUE KEY role_id (role_id)
) TYPE=MyISAM;

