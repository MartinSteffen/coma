<?
include("includes/config.inc.php");
  include('templates/header.tpl.php');
include("includes/sql.class.php");
  require("includes/rtp.lib.php");

$sql = new SQL();
?>

<table width="90%">
	<tr>
		<td align="center">
			<fieldset>
<legend>
<font class="textBold" size="+2"><b>Just 4 testing the RTPA...</b></font>
</legend>
<form action="rtptest.php" method="post">

function on ( <input type="text" name="conferenceId" size="40" maxlength="80" value="<?=($_POST['conferenceId'] ? $_POST['conferenceId']:1) ?>"> ) <input type="submit" value="testen">
</form>
</fieldset>

<?php

if (isset($_REQUEST['conferenceId'])) {
?>
<fieldset>
<legend>
<font class="textBold" size="+2"><b>Result:</b></font>
</legend>
<table width="75%">
	<tr>
		<td>
			<PRE>
<?php

var_dump( getAllPapersForConferenceSortByTotalScore( $_REQUEST['conferenceId']));
?>
</PRE>
		</td>
	</tr>
</table>
</fieldset>
		</td>
	</tr>
</table>

<?  

}
  include('templates/footer.tpl.php');
  ?>
