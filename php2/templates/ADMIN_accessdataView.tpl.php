<?
  include('templates/header.tpl.php');
  $input = d("errors");

?>

<fieldset>
<legend>
<font class="textBold" size="+2"><b>view and change access data</b></font>
</legend>
<form action="index.php" method="post">
<input type="hidden" name="m" value="admin">
<input type="hidden" name="a" value="accessdata">
<input type="hidden" name="s" value="view">
<table border="0" cellspacing="0" cellpadding="5" bordercolor="#000000">
<?php 

if ($input) {
	echo "<TR><TD class='textBold' colspan='2'><br><UL>";
	foreach($input as $errortext) {
		echo "<LI style='color:red;'>".$errortext."</LI>";
	}
	echo "</UL></td></tr>";
}
?>
<tr>
<td>
<fieldset>
<legend>
<font class="textBold" size="+2"><b>Database Access Data</b></font>
</legend>
<table border="0" width="100%">
<tr>
<td class="textBold" >
Database host:
</td>
<td align="right">
<input class="text" type="text" name="dbdata_host" size="40" maxlength="80" value="<?php echo ($_POST['dbdata_host']?$_POST['dbdata_host'] : $dbhost) ?>">
</td>
</tr>
<tr>
<td class="textBold" >
Database user:
</td>
<td align="right">
<input class="text" type="text" name="dbdata_user" size="40" maxlength="80" value="<?php echo ($_POST['dbdata_user']?$_POST['dbdata_user'] : $dbuser) ?>">
</td>
</tr>
<tr>
<td class="textBold" >
Database password:
</td>
<td align="right">
<input class="text" type="password" name="dbdata_pass" size="40" maxlength="80" value="<?php echo ($_POST['dbdata_pass']?$_POST['dbdata_pass'] : $dbpass) ?>">
</td>
</tr>
<tr>
<td class="textBold" >
Database instance name:
</td>
<td align="right">
<input class="text" type="text" name="dbdata_name" size="40" maxlength="80" value="<?php echo ($_POST['dbdata_name']?$_POST['dbdata_name'] : $dbname) ?>">
</td>
</tr>
</table>
</fieldset>
<br>
<fieldset>
<legend>
<font class="textBold" size="+2"><b>FTP/Webspace Access Data</b></font>
</legend>
<table>
<tr>
<td class="textBold" >
FTP-host:
</td>
<td>
<input class="text" type="text" name="ftp_host" size="40" maxlength="80" value="<?php echo ($_POST['ftp_host']?$_POST['ftp_host']:$ftphost) ?>">
</td>
</tr>
<tr>
<td class="textBold" >
FTP-user:
</td>
<td>
<input class="text" type="text" name="ftp_user" size="40" maxlength="80" value="<?php echo ($_POST['ftp_user']?$_POST['ftp_user']:$ftpuser) ?>">
</td>
</tr>
<tr>
<td class="textBold" >
FTP-password:
</td>
<td>
<input class="text" type="password" name="ftp_pass" size="40" maxlength="80" value="<?php echo ($_POST['ftp_pass']?$_POST['ftp_pass']:$ftppass) ?>">
</td>
</tr>
<tr>
<td class="textBold" >
FTP-directory:
</td>
<td>
<input class="text" type="text" name="ftp_dir" size="40" maxlength="80" value="<?php echo ($_POST['ftp_dir']?$_POST['ftp_dir']:$ftpdir) ?>">
</td>
</tr>
<tr>
<td class="textBold" >
HTTP base URL to FTP-webspace (including trailing "/"):
</td>
<td>
<input class="text" type="text" name="ftp_baseurl" size="40" maxlength="80" value="<?php echo ($_POST['ftp_baseurl']?$_POST['ftp_baseurl']:$httpftpurl) ?>">
</td>
</tr>
</table>
</fieldset>
</td>
</tr>
<tr>
<td align=center colspan="2">
<input class="text" type="submit" value="submit" name="submit">&nbsp;&nbsp;&nbsp;<input type="reset" value="reset">
</td>
</tr>
</table>
</form>
</fieldset>
<?  
  include('templates/footer.tpl.php');
  ?>
