<?
  include('templates/header.tpl.php');
  $input = d("ADMIN_conferenceCreate");

?>
<script language="JavaScript" src="templates/calendar.js"></script>
<fieldset>
<legend>
<font class="textBold" size="+2"><b>create a new conference</b></font>
</legend>
<form action="index.php" method="post">
<input type="hidden" name="m" value="admin">
<input type="hidden" name="a" value="conferences">
<input type="hidden" name="s" value="create">
  <table border="0" cellspacing="0" cellpadding="5" bordercolor="#000000">
<?php 

if ($input) {
	echo "<TR class='textBold' ><TD colspan='2'><br><UL>";
	foreach($input as $errortext) {
		echo "<LI style='color:red;'>".$errortext."</LI>";
	}
	echo "</UL></td></tr>";
}
?>
	<tr>
		<td class="textBold" >
			Name:<font style="color:red">*</font>
		</td>
		<td>
			<input class="text" type="text" name="confname" size="40" maxlength="80" value="<?php echo ($TPL['confname']?$TPL['confname']:"") ?>">
		</td>
	</tr>
	<tr>
		<td class="textBold" >
			Description:<font style="color:red">*</font>
		</td>
		<td class="text">
			<textarea name="confdescription" rows="4" cols="29"><?php echo ($TPL['confdescription']?$TPL['confdescription']:"") ?></textarea>
		</td>
	</tr>
	<tr>
		<td class="textBold" >
			Homepage:<font style="color:red">*</font>
		</td>
		<td>
			<input class="text" type="text" name="confhomepage" size="40" maxlength="80" value="<?php echo (isset($TPL['confhomepage'])?$TPL['confhomepage'] : "http://www.yoursite.org/anywhere") ?>">
		</td>
	</tr>
	<tr>
		<td class="text" colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class="textBold" >
			Chair-Account lastname:<font style="color:red">*</font><font style="color:red">*</font>
			</td>
		<td>
			<input class="text" type="text" name="confchair_lastname" size="40" maxlength="80" value="<?php echo ($TPL['confchair_lastname']?$TPL['confchair_lastname']:"") ?>">
		</td>
	</tr>
	<tr>
		<td class="textBold" >
			Chair-Account email:<font style="color:red">*</font>
		</td>
		<td>
			<input class="text" type="text" name="confchair_email" size="40" maxlength="80" value="<?php echo ($TPL['confchair_email']?$TPL['confchair_email']:"") ?>">
		</td>
	</tr>
	<tr>
		<td class="textBold" >
			Chair-Account initial password:<font style="color:red">*</font><font style="color:red">*</font>
		</td>
		<td>
			<input class="text" type="password" name="confchair_passwd" size="40" maxlength="80" value="">
		</td>
	</tr>
	<tr>
		<td class="textBold" >
			Chair-Account password confirm:<font style="color:red">*</font><font style="color:red">*</font>
		</td>
		<td>
			<input class="text" type="password" name="confchair_passwd_confirm" size="40" maxlength="80" value="">
		</td>
	</tr>

	<tr>
		<td align=center colspan="2">
			<input class="text" type="submit" value="submit" name="submit">&nbsp;&nbsp;&nbsp;<input type="reset" value="reset">
		</td>
	</tr>
	<tr>
		<td colspan ="2">
			<fieldset>
			<legend>
				<font class="textBold">Legend</font>
			</legend>
				<font style="color:red">*</font><font class="text">&nbsp;&nbsp;Requiered Information to create a new Conference</font><br>
				<font style="color:red">*</font><font style="color:red">*</font><font class="text">Not required if you point &quot;Chair-Account Email&quot; to an existing Account<br>&nbsp;&nbsp;&nbsp;&nbsp;Otherwise a new Account will be created using the filled in information</font>
			</fieldset>
		</td>
	</tr>
 </table>
</form>
</fieldset>
<?  
  include('templates/footer.tpl.php');
  ?>
