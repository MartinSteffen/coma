<?
$userPaperConf = array();
$userPaperConf['userID'] = $_GET['userID'];
$userPaperConf['paperID'] = $_GET['paperID']; 
$userPaperConf['confID'] = $_GET['confID']; 

/* DEBUG START */
$userPaperConf['userName'] = "John Smith";
$userPaperConf['paperName'] = "Philosophy: what is testing?";
$userPaperConf['confName'] = "Testing Conference";
/* DEBUG END */

$TPL['chair'] = $userPaperConf;
template("CHAIR_paperOfUser");
?>