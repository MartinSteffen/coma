<?php
/**
 * Install Skript f?a1
 *
 * Stand der Tabellen 03.02.2005
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
if (isset($_POST['downloadconfig'])) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: public");
  header("Content-type: application/x-httpd-php");
  header("Content-Disposition: attachment; filename=config.inc.php");
  header("Content-Description: Download Configuration File");
  header("Content-Transfer-Encoding: binary");
  echo base64_decode($_POST['base64encoded']);
  die;
}
header('Content-type: text/html; charset=utf-8');
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
      Tutorial: <a href="INSTALL.PHP?4">Add Tutorial Data</a><br>
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
  if (!isset($_POST['submit'])) {
    /**@ignore */
    define('IN_COMA1', true);
    require_once('./include/config.inc.php');
  }
  else {
    $sqlServer = addslashes($_POST['sqlServer']);
    $sqlUser = addslashes($_POST['sqlUser']);
    $sqlPassword = addslashes($_POST['sqlPassword']);
    $sqlDatabase = addslashes($_POST['sqlDatabase']);
  }
  // Create ConfigFile
  $confFile = "<?php\n/**\n * Configuration of the Database Server\n *\n * @package coma1\n * @subpackage Configuration\n */\n/***/\nif ( !defined('IN_COMA1') )\n{\n  die('Hacking attempt');\n}\n\n// Change these Values\n\$sqlServer = \"$sqlServer\";\n\$sqlUser = \"$sqlUser\";\n\$sqlPassword = \"$sqlPassword\";\n\$sqlDatabase = \"$sqlDatabase\";\n\n?>";

  // Output
  $sqlServer = htmlentities(stripslashes($sqlServer), ENT_QUOTES, 'UTF-8');
  $sqlUser = htmlentities(stripslashes($sqlUser), ENT_QUOTES, 'UTF-8');
  $sqlPassword = htmlentities(stripslashes($sqlPassword), ENT_QUOTES, 'UTF-8');
  $sqlDatabase = htmlentities(stripslashes($sqlDatabase), ENT_QUOTES, 'UTF-8');
  echo "      <form action=\"INSTALL.PHP?1\" method=\"post\" accept-charset=\"UTF-8\">\n";
  echo "        <table border=\"0\" cellpadding=\"0\" cellspacing=\"4\">\n";
  echo "          <tr><td>SQL-Server: </td><td><input type=\"text\" name=\"sqlServer\" size=\"48\" maxlength=\"127\" value=\"$sqlServer\"></td></tr>\n";
  echo "          <tr><td>SQL-User: </td><td><input type=\"text\" name=\"sqlUser\" size=\"48\" maxlength=\"127\" value=\"$sqlUser\"></td></tr>\n";
  echo "          <tr><td>SQL-Password: </td><td><input type=\"text\" name=\"sqlPassword\" size=\"48\" maxlength=\"127\" value=\"$sqlPassword\"></td></tr>\n";
  echo "          <tr><td>SQL-Database: </td><td><input type=\"text\" name=\"sqlDatabase\" size=\"48\" maxlength=\"127\" value=\"$sqlDatabase\"></td></tr>\n";
  echo "          <tr><td colspan=\"2\"><input type=\"submit\" name=\"submit\" value=\"generate Config\"> <input type=\"submit\" name=\"downloadconfig\" value=\"Download Config File generated below\"></td></tr>\n";
  echo "        </table>\n";
  echo "        <input type=\"hidden\" name=\"base64encoded\" value=\"". base64_encode($confFile)."\">\n";
  echo "      </form>\n";

  echo "    <p class=\"menu\">\n";
  highlight_string($confFile);
  echo "    </p>\n";

  // Write Config File
  if ((!defined('DEBUG')) && (is_writable('./include/config.inc.php')) && ($file = fopen('./include/config.inc.php', 'w'))) {
    fwrite($file, $confFile);
    fclose($file);
    echo "    <p class=\"message-pk\">\n";
    echo "      Successfully written file './include/config.inc.php'!<br>\n";
    echo "    </p>\n";
  }
  else {
    echo "    <p class=\"message-failed\">\n";
    echo "      Can't write to file './include/config.inc.php'!<br>\n";
    echo "      Please replace it manually with the Code above!";
    echo "    </p>\n";
  }
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
  //$sql[] = "CREATE TABLE Message ( id INT NOT NULL AUTO_INCREMENT, forum_id INT, reply_to INT, sender_id INT NOT NULL, send_time DATETIME, subject VARCHAR(127), text TEXT, PRIMARY KEY (id), INDEX (sender_id), FOREIGN KEY (sender_id) REFERENCES Person (id) ON DELETE CASCADE ) TYPE = INNODB";
  // Version with better Constraints but differs to original!!
  $sql[] = "CREATE TABLE Message ( id INT NOT NULL AUTO_INCREMENT, forum_id INT NOT NULL, reply_to INT, sender_id INT NOT NULL, send_time DATETIME, subject VARCHAR(127), text TEXT, PRIMARY KEY (id), INDEX (forum_id), INDEX (sender_id), FOREIGN KEY (sender_id) REFERENCES Person (id) ON DELETE CASCADE, FOREIGN KEY (forum_id) REFERENCES Forum (id) ON DELETE CASCADE  ) TYPE = INNODB";
  $sql[] = "DROP TABLE IF EXISTS Session";
  $sql[] = "CREATE TABLE Session ( sid VARCHAR(255) NOT NULL DEFAULT '', sname VARCHAR(25) NOT NULL DEFAULT '', sdata TEXT, stime TIMESTAMP(14) NOT NULL, PRIMARY KEY (sid, sname), KEY stime (stime) ) TYPE=MyISAM COMMENT='Session-Verwaltung'";
  $sql[] = "DROP TABLE IF EXISTS ConferenceConfig";
  $sql[] = "CREATE TABLE ConferenceConfig ( id INT NOT NULL, default_reviews_per_paper INT NOT NULL, min_number_of_papers INT NOT NULL, max_number_of_papers INT NOT NULL, critical_variance FLOAT NOT NULL DEFAULT '.5', auto_activate_account INT NOT NULL DEFAULT '1', auto_open_paper_forum INT NOT NULL DEFAULT '1', auto_add_reviewers INT NOT NULL DEFAULT '1', number_of_auto_add_reviewers INT NOT NULL, PRIMARY KEY (id), FOREIGN KEY (id) REFERENCES Conference (id) ON DELETE CASCADE ) TYPE = INNODB";
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
  @unlink('INSTALL.PHP');
  if (file_exists('INSTALL.PHP')) {
    echo "    <p class=\"message-failed\">\n";
    echo "      Failed to delete file 'INSTALL.PHP'!<br>\n";
    echo "      You have to remove the file manually before you can continue using CoMa!\n";
    echo "    </p>\n";
  }
  else {
    echo "    <p class=\"message-ok\">\n";
    echo "      Successfully deleted file 'INSTALL.PHP'!\n";
    echo "    </p>\n";
  }
}
elseif ($STEP == 4) {
  // Block fuellen der Datenbank
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
  $sql[] = "INSERT INTO Conference (id, name, homepage, description, abstract_submission_deadline, paper_submission_deadline, review_deadline, final_version_deadline, notification, conference_start, conference_end, min_reviews_per_paper) VALUES (1, 'Klavierspielen++ für Anfänger', 'http://coma.notme.de', 'Diese Konferenz richtet sich an musikalisch Interessierte, die bislang keine oder nur wenig Erfahrung in puncto Klavierspiel und Notenlesen besitzen.\r\n\r\nEs werden Vortäge aus den Bereichen\r\n- Fingerübungen\r\n- Blindspiel\r\n- Vierhändiges Musizieren\r\n- Update auf Orgelspiel\r\nangeboten.', '2005-02-08', '2005-02-08', '2005-02-08', '2005-02-08', '2005-03-07', '2005-05-07', '2005-05-08', 1)";
  $sql[] = "INSERT INTO ConferenceConfig (id, default_reviews_per_paper, min_number_of_papers, max_number_of_papers, critical_variance, auto_activate_account, auto_open_paper_forum, auto_add_reviewers, number_of_auto_add_reviewers) VALUES (1, 2, 3, 10, 0.33, 0, 1, 1, 2)";
  $sql[] = "INSERT INTO Criterion (id, conference_id, name, description, max_value, quality_rating) VALUES (1, 1, 'Inhalt', 'Bewertung des Papers nach rein inhaltlichen Gesichtspunkten.\r\nDamit sind <b>NUR</b> inhaltliche Gesichtspunkte gemeint!', 10, 70),(2, 1, 'Künstlerischer Wert', 'Neue künstlerische Erkenntnisse oder schlechte Modern-Talking-Cover-Mucke?', 5, 20),(3, 1, 'Notenschönschnörkelei', 'Hässlich = 0;\r\nquasi hässlich = 1;\r\nvon ausgewählter Bach''scher Qualität (vgl. Sinf. #3 Brand. Konz. Orat., 4. Satz, Arr. nach LeGuién) = 2', 2, 10)";
  $sql[] = "INSERT INTO DeniesPaper (person_id, paper_id) VALUES (4, 6),(5, 1),(5, 2),(5, 3),(5, 4),(5, 5),(5, 7),(6, 4),(6, 7)";
  $sql[] = "INSERT INTO Distribution (paper_id, reviewer_id) VALUES (1, 3),(2, 6),(2, 7),(3, 4),(3, 6),(5, 4)";
  $sql[] = "INSERT INTO ExcludesPaper (person_id, paper_id) VALUES (2, 1),(2, 2),(3, 6),(4, 7),(5, 6),(6, 5),(7, 3),(7, 4)";
  $sql[] = "INSERT INTO Forum (id, conference_id, title, forum_type, paper_id) VALUES (1, 1, 'Global conference forum for all CoMa users', 4, 0),(2, 1, 'Public forum for conference members', 1, 0),(3, 1, 'Internal forum for committee members', 2, 0),(4, 1, 'Discussion of paper ''Wiegelieder''', 3, 2)";
  $sql[] = "INSERT INTO IsAboutTopic (paper_id, topic_id) VALUES (1, 2),(1, 3),(2, 1),(4, 1),(4, 2),(5, 4),(6, 2),(6, 4),(7, 1),(7, 3),(7, 4)";
  $sql[] = "INSERT INTO IsCoAuthorOf (person_id, paper_id, name) VALUES (NULL, 1, 'Dieter Bohlen'),(NULL, 1, 'Dr. Mäuserich'),(NULL, 2, 'Jack Daniels'),(NULL, 2, 'Wodka Gorbatschow'),(NULL, 4, 'Dr. Mäuserich'),(NULL, 5, 'Sandro Esquivel'),(NULL, 7, 'Thomas Anders')";
  $sql[] = "INSERT INTO Message (id, forum_id, reply_to, sender_id, send_time, subject, text) VALUES (1, 2, NULL, 1, '2005-02-07 20:03:43', 'Willkommen', 'Im Namen des gesamten Konferenzorganisationsteams heiße ich Sie auf unserem Konferenzmanager auf das Herzlichste willkommen.\r\n\r\nIhr JSB'),(2, 2, NULL, 2, '2005-02-07 20:12:38', 'Freue mich, Autor hier zu sein', '.'),(3, 4, NULL, 7, '2005-02-08 00:08:22', 'Wieso zum Teufel so schlecht bewertet?', '.')";
  $sql[] = "INSERT INTO Paper (id, conference_id, author_id, title, abstract, last_edited, version, filename, state, mime_type) VALUES (1, 1, 2, 'Mäusesonate', 'Die Mäusesonate wurde ursprünglich komponiert von Dr. Mauserich,  jedoch erlangte sie erst einige Jahre später weltweite Bekanntheit, als Dieter Bohlen sie in DSDS durch Daniel Küblböck interpretieren ließ.\r\n\r\nHier findet sich eine konsequente künstlerische Erweiterung in meditöse Sphären, die dem ganzen ein fernöstlich-asiatisches Klangtum verleiht.', '2005-02-07 20:18:19', 2, 0),(2, 1, 2, 'Wiegelieder', 'Wiegelieder aus einer anderen Welt.\r\n\r\nIhr Baby wird *entzückt* sein!!!', '2005-02-07 20:20:43', 2, 2),(3, 1, 7, 'Katzenjammer', 'Begleitmusik zur Beisetzung von Daisy, dem Mosikätzchen.', '2005-02-07 20:29:59', 2, 0),(4, 1, 7, 'Katzenjammer v2.0', 'The Revenge.', '2005-02-07 20:32:08', 2, 0),(5, 1, 6, 'Die fetten Jahre sind vorbei', 'In Anlehnung an die Zeit des Katzenjammers folgt hier ein Aufruf an alle Katzen, sich der menschlichen Ausbeutung (Futterneid!) zu widersetzen.', '2005-02-07 20:34:30', 2, 0),(6, 1, 5, 'Straßenmusik', 'Gar lustige Töne aus dem Tom''schen Hause.', '2005-02-07 20:35:52', 2, 3),(7, 1, 4, 'Hochzeits-"Blas"-Musik', 'Dieses Stück reiht sich ein in fetzige Hochzeitssounds des 20. Jahrhunderts wie "Als der Neandertaler steppte", "Bin ich Anwalt, oder was?" und "Hermann der Fleischerich".', '2005-02-07 20:39:30', 2, 0)";
  $sql[] = "INSERT INTO Person (id, first_name, last_name, title, affiliation, email, phone_number, fax_number, street, postal_code, city, state, country, password) VALUES (1, 'Johann Sebastian', 'Bach', 'Prof. Dr. Dr.', 'Institut für Musikalische Genüsse', 'jsb@spambob.com', '+49-(0)987/654-321', 'Durchwahl -99', 'Köntrabassstraße 17-B', 'D-00001', 'Bächingen', 'Germany', 'Bächinger Land', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073'),(2, '', 'Robby', '', '', 'robby@spambob.com', '', '', '', '', '', '', '', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073'),(3, 'Jan', 'Waller', '', '', 'jwa@spambob.com', '', '', '', '', '', '', '', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073'),(4, 'Falk', 'Starke', '', '', 'fast@spambob.com', '', '', '', '', '', '', '', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073'),(5, 'Tom', 'Scherzer', '', '', 'tos@spambob.com', '', '', '', '', '', '', '', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073'),(6, 'Daniel', 'Miesling', '', '', 'dmi@spambob.com', '', '', '', '', '', '', '', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073'),(7, 'Sandro', 'Esquivel', '', '', 'sae@spambob.com', '', '', '', '', '', '', '', '1a91d62f7ca67399625a4368a6ab5d4a3baa6073')";
  $sql[] = "INSERT INTO PrefersPaper (person_id, paper_id) VALUES (3, 1),(4, 3),(4, 4),(6, 3)";
  $sql[] = "INSERT INTO PrefersTopic (person_id, topic_id) VALUES (3, 2),(4, 1),(4, 3),(4, 4),(6, 1),(6, 3),(7, 1),(7, 2),(7, 3),(7, 4)";
  $sql[] = "INSERT INTO Rating (review_id, criterion_id, grade, comment) VALUES (1, 1, 2, 'Jar net jut..'),(1, 2, 1, 'Naja, hab schon wahrhaft Bessere jeseh''n.'),(1, 3, 2, 'Na immerhin!'),(2, 1, 7, 'Insgesamt überdurchschnittlicht. Eine deutliche Steigerung gegenüber den vorigen Papers des Herrn Esquivel.'),(2, 2, 4, 'Auch hier wesentlich besser als die Werke aus den jungen Jahren.'),(2, 3, 2, 'Schönheit in reinster Form.'),(3, 1, 9, 'Genau meine Einschlafmucke.'),(3, 2, 5, '*schmacht*\r\nHerrmann, ich könnte dahinschlafen...'),(3, 3, 2, 'Schnörkelesk.')";
  $sql[] = "INSERT INTO ReviewReport (id, paper_id, reviewer_id, summary, remarks, confidential) VALUES (1, 2, 6, 'Insgesamt enttäuschend.', '', 'Nicht empfehlenswert.'),(2, 3, 6, 'Gut. Etwas zu kurz vielleicht, aber lesenswert.', 'Im Großen und Ganzen gut.', 'Wenn es keine besseren gibt, würde ich es empfehlen.'),(3, 2, 7, 'Nicht in Worte zu fassen.\r\nIch fasse es nicht.', '#$%&-? Mein Hund wurde entführt...', 'Unbedingt nehmen, nehmen, nehmen!!!\r\nJa, nehmen, bitte!')";
  $sql[] = "INSERT INTO Role (conference_id, person_id, role_type, state) VALUES (1, 1, 2, NULL),(1, 2, 4, 0),(1, 3, 4, 0),(1, 3, 3, 0),(1, 3, 5, NULL),(1, 7, 3, 0),(1, 7, 4, NULL),(1, 7, 5, NULL),(1, 6, 3, 0),(1, 6, 4, 0),(1, 6, 5, NULL),(1, 4, 3, 0),(1, 4, 4, 0),(1, 4, 5, NULL),(1, 5, 3, 0),(1, 5, 4, 0),(1, 5, 5, NULL),(1, 7, 2, 1)";
  $sql[] = "INSERT INTO Topic (id, conference_id, name) VALUES (1, 1, 'Fingerübungen'),(2, 1, 'Blindspielen'),(3, 1, 'Vierhändiges Musizieren'),(4, 1, 'Noten lesen lernen')";
  
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
?>
  </div>
</body>
</html>
