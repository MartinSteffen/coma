<?
  include('templates/header.tpl.php');
  $input = d("ADMIN_conferenceCreate");

?>


<font size="+2"><b>create a new conference</b></font>
<form action="index.php" method="post">
<input type="hidden" name="m" value="admin">
<input type="hidden" name="a" value="conferences">
<input type="hidden" name="s" value="create">
  <table border="1" cellspacing="0" cellpadding="5" bordercolor="#000000">
<?php 

if ($input) {
	echo "<TR><TD colspan='2'><br><UL>";
	foreach($input as $errortext) {
		echo "<LI style='color:red;'>".$errortext."</LI>";
	}
	echo "</UL></td></tr>";
}
?>
	<tr>
		<td>
			Name:
		</td>
		<td>
			<input type="text" name="confname" size="40" maxlength="80" value="<?php echo ($_POST['confname']?$_POST['confname']:"") ?>">
		</td>
	</tr>
	<tr>
		<td>
			Description:
		</td>
		<td>
			<textarea name="confdescription" rows="3" cols="40"><?php echo ($_POST['confdescription']?$_POST['confdescription']:"") ?></textarea>
		</td>
	</tr>
	<tr>
		<td>
			Homepage:
		</td>
		<td>
			<input type="text" name="confhomepage" size="40" maxlength="80" value="<?php echo ($_POST['confhomepage']?$_POST['confhomepage'] : "http://www.yoursite.org/anywhere") ?>">
		</td>
	</tr>
	<tr>
		<td>
			Abstract submission deadline:
		</td>
		<td>
			<input type="text" name="confabstract_dl" size="40" maxlength="80" value="<?php echo ($_POST['confabstract_dl']?$_POST['confabstract_dl']:"YYYY-MM-DD") ?>">
		</td>
	</tr>
	<tr>
		<td>
			Paper submission deadline:
		</td>
		<td>
			<input type="text" name="confpaper_dl" size="40" maxlength="80" value="<?php echo ($_POST['confpaper_dl']?$_POST['confpaper_dl']:"YYYY-MM-DD") ?>">
		</td>
	</tr>
	<tr>
		<td>
			Reviewing deadline:
		</td>
		<td>
			<input type="text" name="confreview_dl" size="40" maxlength="80" value="<?php echo ($_POST['confreview_dl']?$_POST['confreview_dl']:"YYYY-MM-DD") ?>">
		</td>
	</tr>
	<tr>
		<td>
			Final version deadline:
		</td>
		<td>
			<input type="text" name="conffinal_dl" size="40" maxlength="80" value="<?php echo ($_POST['conffinal_dl']?$_POST['conffinal_dl']:"YYYY-MM-DD") ?>">
		</td>
	</tr>
	<tr>
		<td>
			Notification:
		</td>
		<td>
			<input type="text" name="confnotification" size="40" maxlength="80" value="<?php echo ($_POST['confnotification']?$_POST['confnotification']:"YYYY-MM-DD") ?>">
		</td>
	</tr>
	<tr>
		<td>
			Conference start date:
		</td>
		<td>
			<input type="text" name="confstart" size="40" maxlength="80" value="<?php echo ($_POST['confstart']?$_POST['confstart']:"YYYY-MM-DD") ?>">
		</td>
	</tr>
	<tr>
		<td>
			Conference end date:
		</td>
		<td>
			<input type="text" name="confend" size="40" maxlength="80" value="<?php echo ($_POST['confend']?$_POST['confend']:"YYYY-MM-DD") ?>">
		</td>
	</tr>
	<tr>
		<td>
			Minimum number of reviews per paper:
		</td>
		<td>
			<input type="text" name="confmin_reviews" size="40" maxlength="80" value="<?php echo ($_POST['confmin_reviews']?$_POST['confmin_reviews']:"3") ?>">
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			Chair-account lastname:
		</td>
		<td>
			<input type="text" name="confchair_lastname" size="40" maxlength="80" value="<?php echo ($_POST['confchair_lastname']?$_POST['confchair_lastname']:"") ?>">
		</td>
	</tr>
	<tr>
		<td>
			Chair-account email:
		</td>
		<td>
			<input type="text" name="confchair_email" size="40" maxlength="80" value="<?php echo ($_POST['confchair_email']?$_POST['confchair_email']:"") ?>">
		</td>
	</tr>
	<tr>
		<td>
			Chair-account initial password:
		</td>
		<td>
			<input type="password" name="confchair_passwd" size="40" maxlength="80" value="">
		</td>
	</tr>
	<tr>
		<td>
			Chair-account password confirm:
		</td>
		<td>
			<input type="password" name="confchair_passwd_confirm" size="40" maxlength="80" value="">
		</td>
	</tr>

	<tr>
		<td align=center colspan="2">
			<input type="submit" value="submit" name="submit">&nbsp;&nbsp;&nbsp;<input type="reset" value="reset">
		</td>
	</tr>
  </table>
</form>
<?  
  include('templates/footer.tpl.php');
  ?>
