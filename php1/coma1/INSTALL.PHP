<?php
/**
 * Install Skript für Coma1
 *
 * Stand der Tabellen 25.01.2005
 *
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/
$STEP = 0;
if (isset($_GET['1'])) {
  $STEP = 1;
}
elseif (isset($_GET['2'])) {
  $STEP = 2;
}
elseif (isset($_GET['3'])) {
  $STEP = 3;
}
elseif (isset($_GET['4'])) {
  $STEP = 4;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>CoMa - Your Conference Manager - INSTALL</title>
  <meta http-equiv="Content-Style-Type" content="text/css">
  <style type="text/css">
  <!--
  body { font-family: "Trebuchet MS" , Arial, Helvetica, Sans-Serif; font-size: 12px; 
    vertical-align: top; background-color: #FFFFFF; margin: 0px; text-align: center;
    padding: 30px; }
  a { text-decoration: none; font-weight: bold; }
  a:link { color: #444444; }
  a:visited { color: #444444; }
  a:active { color: #000000; }
  a:hover { color: #000000; }
  div { font-family: "Trebuchet MS" , Arial, Helvetica, Sans-Serif; 
    font-size: 13px; font-weight: bold; text-align: center; vertical-align: top; 
    margin: 0px; padding: 10px; color: #222222; background-color: #dddddd; 
    border: 1px solid #222222; }
  p { font-family: "Trebuchet MS" , Arial, Helvetica, Sans-Serif; font-size: 13px; 
    font-weight: bold; vertical-align: top; margin: 10px; padding: 4px 12px; }
  p.menu { color: #111111; background-color: #EEEEEE; border: 1px solid #FFFFFF; 
    text-align: left; }
  p.message-failed { color: #FF2600; background-color: #FFF0E0; border: 1px solid #A00000; 
    text-align: center; }
  p.message-ok { color: #20A028; background-color: #E0FFF0; border: 1px solid #80A080; 
    text-align: center; }
  -->
  </style>
</head>
<body>
  <div>
    <h1>Installation of CoMa - Your Conference Manager</h1>
    <p class="menu">
      1. <a href="INSTALL.PHP?1">Generate Configuration</a><br>
      2. <a href="INSTALL.PHP?2">Create Database</a><br>
      3. <a href="INSTALL.PHP?3">Delete INSTALL.PHP</a><br>
      <br>
      Tutorial: <a href="INSTALL.PHP?3">Add Tutorial Data</a><br>
    </p>
    <p>
      Please select your next Step of Installation from the table above...<br>
      Your current step is: <?php echo $STEP; ?> <br>
    </p>
<?php
if ($STEP == 0){
  echo "    <p class=\"message-ok\">\n";
  echo "      Installation started!";
  echo "    </p>\n";
}
elseif ($STEP == 1){
  // Create Configuration
  /**@ignore */
  define('IN_COMA1', true);
  require_once('./include/config.inc.php');
  // Create ConfigFile
  $confFile = "<?php\n/**\n * Configuration of the Database Server\n *\n * @package coma1\n * @subpackage Configuration\n */\n/***/\nif ( !defined('IN_COMA1') )\n{\n  die('Hacking attempt');\n}\n\n// Change these Values\n\$sqlServer = '$sqlServer';\n\$sqlUser = '$sqlUser';\n\$sqlPassword = '$sqlPassword';\n\$sqlDatabase = '$sqlDatabase';\n\n?>";

  $htmlFile = $confFile;

  //$htmlFile = htmlentities($confFile, ENT_QUOTES, 'UTF-8');
  //$htmlFile = str_replace(" ", "&nbsp;", $htmlFile);
  //$htmlFile = str_replace("\n", "<br>\n      ", $htmlFile);
  $htmlFile = highlight_string($htmlFile, true);
  $htmlFile = preg_replace('#<font color="([^\']*)">([^\']*)</font>#', '<span style="color: \\1">\\2</span>', $htmlFile);
  $htmlFile = preg_replace('#<font color="([^\']*)">([^\']*)</font>#U', '<span style="color: \\1">\\2</span>', $htmlFile);
  
  echo "    <p class=\"menu\">\n";
  echo "      " . $htmlFile;
  echo "    </p>\n";

  echo "    <p class=\"message-failed\">\n";
  echo "      Not yet implemented!";
  echo "    </p>\n";
}
elseif ($STEP == 2) {
  // Block neuanlegen der Datenbank
  /**@ignore */
  define('IN_COMA1', true);
  require_once('./include/config.inc.php');
  
  $errors = array();;
  $conn = @mysql_connect($sqlServer, $sqlUser , $sqlPassword);
  if (empty($conn)) {
    $errors[] = "Could not connect to MySQL Server $sqlServer!";
  }
  elseif (!mysql_select_db($sqlDatabase, $conn)) {
    $errors[] = mysql_error($conn);
  }

  $sql = array();
  $sql[] = "DROP TABLE IF EXISTS Conference";
  $sql[] = "CREATE TABLE Conference (id INT NOT NULL AUTO_INCREMENT, name VARCHAR(127) NOT NULL, homepage VARCHAR(127), description TEXT, abstract_submission_deadline DATE, paper_submission_deadline DATE, review_deadline DATE, final_version_deadline DATE, notification DATE, conference_start DATE, conference_end DATE, min_reviews_per_paper INT, PRIMARY KEY (id) ) TYPE = INNODB";
  $sql[] = "DROP TABLE IF EXISTS Person";
  $sql[] = "CREATE TABLE Person ( id INT NOT NULL AUTO_INCREMENT, first_name VARCHAR(127), last_name VARCHAR(127) NOT NULL, title VARCHAR(32), affiliation VARCHAR(127), email VARCHAR(127) UNIQUE NOT NULL, phone_number VARCHAR(20), fax_number VARCHAR(20), street VARCHAR(127), postal_code VARCHAR(20), city VARCHAR(127), state VARCHAR(127), country VARCHAR(127), password VARCHAR(127) NOT NULL, PRIMARY KEY (id) ) TYPE = INNODB";
  $sql[] = "DROP TABLE IF EXISTS Role";
  $sql[] = "CREATE TABLE Role ( conference_id INT NOT NULL, person_id INT NOT NULL, role_type INT NOT NULL, state INT, PRIMARY KEY (conference_id, person_id, role_type), INDEX (conference_id), INDEX (person_id), FOREIGN KEY (conference_id) REFERENCES Conference (id) ON DELETE CASCADE, FOREIGN KEY (person_id) REFERENCES Person (id) ON DELETE CASCADE ) TYPE = INNODB";
  $sql[] = "DROP TABLE IF EXISTS Paper";
  $sql[] = "CREATE TABLE Paper ( id INT NOT NULL AUTO_INCREMENT, conference_id INT NOT NULL, author_id INT NOT NULL, title VARCHAR(127) NOT NULL, abstract TEXT, last_edited DATETIME, version INT, filename VARCHAR(127), state INT NOT NULL, mime_type VARCHAR(127), PRIMARY KEY (id), INDEX (conference_id), INDEX (author_id), FOREIGN KEY (conference_id) REFERENCES Conference (id) ON DELETE CASCADE, FOREIGN KEY (author_id) REFERENCES Person (id) ON DELETE CASCADE ) TYPE = INNODB";
  $sql[] = "DROP TABLE IF EXISTS IsCoAuthorOf";
  $sql[] = "CREATE TABLE IsCoAuthorOf ( person_id INT, paper_id INT NOT NULL, name VARCHAR(127), INDEX (paper_id), FOREIGN KEY (paper_id) REFERENCES Paper (id) ON DELETE CASCADE ) TYPE = INNODB";
  $sql[] = "DROP TABLE IF EXISTS Topic";
  $sql[] = "CREATE TABLE Topic ( id INT NOT NULL AUTO_INCREMENT, conference_id INT NOT NULL, name VARCHAR(127) NOT NULL, PRIMARY KEY (id), INDEX (conference_id), FOREIGN KEY (conference_id) REFERENCES Conference (id) ON DELETE CASCADE ) TYPE = INNODB";
  $sql[] = "DROP TABLE IF EXISTS IsAboutTopic";
  $sql[] = "CREATE TABLE IsAboutTopic ( paper_id INT NOT NULL, topic_id INT NOT NULL, PRIMARY KEY (paper_id, topic_id), INDEX (paper_id), INDEX (topic_id), FOREIGN KEY (paper_id) REFERENCES Paper (id) ON DELETE CASCADE, FOREIGN KEY (topic_id) REFERENCES Topic (id) ON DELETE CASCADE ) TYPE = INNODB";
  $sql[] = "DROP TABLE IF EXISTS PrefersTopic";
  $sql[] = "CREATE TABLE PrefersTopic ( person_id INT NOT NULL, topic_id INT NOT NULL, PRIMARY KEY (person_id, topic_id), INDEX (person_id), INDEX (topic_id), FOREIGN KEY (person_id) REFERENCES Person (id) ON DELETE CASCADE, FOREIGN KEY (topic_id) REFERENCES Topic (id) ON DELETE CASCADE ) TYPE = INNODB";
  $sql[] = "DROP TABLE IF EXISTS PrefersPaper";
  $sql[] = "CREATE TABLE PrefersPaper ( person_id INT NOT NULL, paper_id INT NOT NULL, PRIMARY KEY (person_id, paper_id), INDEX (person_id), INDEX (paper_id), FOREIGN KEY (person_id) REFERENCES Person (id) ON DELETE CASCADE, FOREIGN KEY (paper_id) REFERENCES Paper (id) ON DELETE CASCADE ) TYPE = INNODB";
  $sql[] = "DROP TABLE IF EXISTS DeniesPaper";
  $sql[] = "CREATE TABLE DeniesPaper ( person_id INT NOT NULL, paper_id INT NOT NULL, PRIMARY KEY (person_id, paper_id), INDEX (person_id), INDEX (paper_id), FOREIGN KEY (person_id) REFERENCES Person (id) ON DELETE CASCADE, FOREIGN KEY (paper_id) REFERENCES Paper (id) ON DELETE CASCADE ) TYPE = INNODB";
  $sql[] = "DROP TABLE IF EXISTS ExcludesPaper";
  $sql[] = "CREATE TABLE ExcludesPaper ( person_id INT NOT NULL, paper_id INT NOT NULL, PRIMARY KEY (person_id, paper_id), INDEX (person_id), INDEX (paper_id), FOREIGN KEY (person_id) REFERENCES Person (id) ON DELETE CASCADE, FOREIGN KEY (paper_id) REFERENCES Paper (id) ON DELETE CASCADE ) TYPE = INNODB";
  $sql[] = "DROP TABLE IF EXISTS ReviewReport";
  $sql[] = "CREATE TABLE ReviewReport ( id INT NOT NULL AUTO_INCREMENT, paper_id INT NOT NULL, reviewer_id INT NOT NULL, summary TEXT, remarks TEXT, confidential TEXT, PRIMARY KEY (id), INDEX (paper_id), INDEX (reviewer_id), FOREIGN KEY (paper_id) REFERENCES Paper (id) ON DELETE CASCADE, FOREIGN KEY (reviewer_id) REFERENCES Person (id) ON DELETE CASCADE ) TYPE = INNODB";
  $sql[] = "DROP TABLE IF EXISTS Criterion";
  $sql[] = "CREATE TABLE Criterion ( id INT NOT NULL AUTO_INCREMENT, conference_id INT NOT NULL, name VARCHAR(127) NOT NULL, description TEXT, max_value INT, quality_rating INT, PRIMARY KEY (id), INDEX (conference_id), FOREIGN KEY (conference_id) REFERENCES Conference (id) ON DELETE CASCADE ) TYPE = INNODB";
  $sql[] = "DROP TABLE IF EXISTS Rating";
  $sql[] = "CREATE TABLE Rating ( review_id INT NOT NULL, criterion_id INT NOT NULL, grade INT NOT NULL, comment TEXT, PRIMARY KEY (review_id, criterion_id), INDEX (review_id), INDEX (criterion_id), FOREIGN KEY (review_id) REFERENCES ReviewReport (id) ON DELETE CASCADE, FOREIGN KEY (criterion_id) REFERENCES Criterion (id) ON DELETE CASCADE ) TYPE = INNODB";
  $sql[] = "DROP TABLE IF EXISTS Forum";
  $sql[] = "CREATE TABLE Forum ( id INT NOT NULL AUTO_INCREMENT, conference_id INT NOT NULL, title VARCHAR(127) NOT NULL, forum_type INT NOT NULL, paper_id INT, PRIMARY KEY (id), INDEX (conference_id), INDEX (forum_type), FOREIGN KEY (conference_id) REFERENCES Conference (id) ON DELETE CASCADE ) TYPE = INNODB";
  $sql[] = "DROP TABLE IF EXISTS Message";
  $sql[] = "CREATE TABLE Message ( id INT NOT NULL AUTO_INCREMENT, forum_id INT, reply_to INT, sender_id INT NOT NULL, send_time DATETIME, subject VARCHAR(127), text TEXT, PRIMARY KEY (id), INDEX (sender_id), FOREIGN KEY (sender_id) REFERENCES Person (id) ON DELETE CASCADE ) TYPE = INNODB";
  $sql[] = "DROP TABLE IF EXISTS Session";
  $sql[] = "CREATE TABLE Session ( sid VARCHAR(255) NOT NULL DEFAULT '', sname VARCHAR(25) NOT NULL DEFAULT '', sdata TEXT, stime TIMESTAMP(14) NOT NULL, PRIMARY KEY (sid, sname), KEY stime (stime) ) TYPE=MyISAM COMMENT='Session-Verwaltung'";
  $sql[] = "DROP TABLE IF EXISTS ConferenceConfig";
  $sql[] = "CREATE TABLE ConferenceConfig ( id INT NOT NULL, default_reviews_per_paper INT NOT NULL, min_number_of_papers INT NOT NULL, max_number_of_papers INT NOT NULL, critical_variance FLOAT NOT NULL DEFAULT '.5', auto_activate_account INT NOT NULL DEFAULT '1', auto_open_paper_forum INT NOT NULL DEFAULT '1', auto_add_reviewers INT NOT NULL DEFAULT '1', number_of_auto_add_reviewers INT NOT NULL, PRIMARY KEY (id) ) TYPE = INNODB";
  $sql[] = "DROP TABLE IF EXISTS Distribution";
  $sql[] = "CREATE TABLE Distribution ( paper_id INT NOT NULL, reviewer_id INT NOT NULL, PRIMARY KEY (paper_id, reviewer_id), INDEX (paper_id), INDEX (reviewer_id), FOREIGN KEY (paper_id) REFERENCES Paper (id) ON DELETE CASCADE, FOREIGN KEY (reviewer_id) REFERENCES Person (id) ON DELETE CASCADE ) TYPE = INNODB";
  $sql[] = "DROP TABLE IF EXISTS PaperData";
  $sql[] = "CREATE TABLE PaperData ( paper_id INT NOT NULL, filesize INT NOT NULL, file MEDIUMBLOB NOT NULL, PRIMARY KEY (paper_id), FOREIGN KEY (paper_id) REFERENCES Paper (id) ON DELETE CASCADE) TYPE = INNODB";
  
  if (empty($errors)) {
    foreach ($sql as $s) {
      $result = mysql_query($s, $conn);
      if (empty($result)) {
        $errors[] = mysql_error($conn);
      }
    }
  }
  if (empty($errors)) {
    echo "    <p class=\"message-ok\">Database successfully created!</p>";
  }
  else {
    echo "    <p class=\"message-failed\">\n    Database creation failed!...<br><br>\n";
    foreach ($errors as $error) {
      echo "      ".$error."<br>\n";
    }
    echo "      <br><br>\n";
    echo "      Please check 'include/config.inc.php' for correct values!\n";
    echo "    </p>\n";
  }
}
elseif ($STEP == 3) {
  echo "    <p class=\"message-failed\">\n";
  echo "      Not yet implemented!";
  echo "    </p>\n";
}
elseif ($STEP == 4) {
  // Ollis Skript aufrufen... Spaeter vielleicht hier drinne was eigenes machen ;)
  echo "    <p class=\"message-failed\">\n";
  include('test_basedata.php');
  echo "    </p>\n";
  echo "    <p class=\"message-ok\">Hopefully Data added! Check above for errors!</p>\n";
}
?>
  </div>
</body>
</html>
