<?
  include('templates/header.tpl.php');
	$input = $TPL['message'];
	?>

<fieldset>
<legend>
<font class="textBold" size="+2"><b>Chair already exists...</b></font>
</legend>
<form action="index.php" method="post">
<input type="hidden" name="m" value="admin">
<input type="hidden" name="a" value="conferences">
<input type="hidden" name="s" value="createStep2">
<input type="hidden" name="cid" value="<? echo($TPL['cid']); ?>">
<input type="hidden" name="TPLdata" value="<?php echo urlencode(serialize($TPL)); ?>">
 <table border="0" cellspacing="0" cellpadding="5" bordercolor="#000000">
	<tr>
		<td class="textBold">

			The email you specified for chair account is already in use by another user account.<br>
Do you want to:
		</td>
	</tr>
	<tr>
		<td class="textBold">
			<input type="radio" name="createNewChair" value="no" CHECKED>&nbsp;make existing user account Chair of this conference or
		</td>
	</tr>
	<tr>
		<td class="textBold">
			<input type="radio" name="createNewChair" value="yes">&nbsp;go back and change chair email?
		</td>
	</tr>
	<tr>
		<td align=center colspan="2" class="text">
			<input class="text" type="submit" value="submit" name="submit">&nbsp;&nbsp;&nbsp;<input type="reset" value="reset">
		</td>
	</tr>
  </table>
</form>
</fieldset>
<?  
  include('templates/footer.tpl.php');
  ?>

