<?
include 'security.php';
if(!isset($_SESSION['conf_id']))
{
	header("Location: choose_conference.php");
	exit;
}
include 'templates/header.php';
include 'templates/upper-links.php';
include 'templates/main-header.php';

include 'templates/footer.php';
?>