<table border="0" width="90%">
	<tr>
		<td>
			<fieldset>
<legend>
<font size="+2" class="textBold"><b>A little problem occured</b></font>
</legend>
<table border="0" cellspacing="0" cellpadding="5" bordercolor="#000000">
	<tr>
		<td class="text">
			The configuration script for saving your access data was not allowed to change the file "includes/config.inc.php".<br>
			Actually, this is no big problem, as you are abled to replace this file manually.<br>
<br>
Please click on the link below. A download will start (if not, you will see strange kind of code. Then choose "file - save as..." at your browser),
save the file "config.inc.php" to any local directory, and copy it to your webspace running CoMa to the directory named "includes". If there already exists a file named config.inc.php, you may replace it with the new one.<br>
<br>

<form action="templates/config.inc.php" method="post">
<input type="hidden" name="text" value="<?php echo urlencode($configAsString) ?>">
<input type="submit" value="Please click here to proceed...">


</form>
<br>
After that your new configuration will be saved and installation will be finished.
		</td>
	</tr>
</table>
</fieldset>

		</td>
	</tr>
</table><?  
  include('templates/footer.tpl.php');
  ?>
