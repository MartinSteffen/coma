<?
$userPaperConf = array();
$userPaperConf['userID'] = $_GET['userID'];
$userPaperConf['paperID'] = $_GET['paperID']; 
$userPaperConf['confID'] = $_GET['confID']; 

$TPL['chair'] = $userPaperConf;
template("CHAIR_paperOfUser");
?>