<?
  include('templates/header.tpl.php');
	$input = $TPL['message'];
	?>

<fieldset>
<legend>
<font class="textBold" size="+2"><b>email already exists...</b></font>
</legend>
<form action="index.php" method="post">
<input type="hidden" name="m" value="cfp">
<input type="hidden" name="a" value="register">
<input type="hidden" name="s" value="register">
<input type="hidden" name="cid" value="<? echo($TPL['cid']); ?>">
<input type="hidden" name="pid" value="<? echo d('pid'); ?>">
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
			<input type="radio" name="createNewAccount" value="no" CHECKED>&nbsp;make existing user account Chair of this conference or
		</td>
	</tr>
	<tr>
		<td class="textBold">
			<input type="radio" name="createNewAccount" value="yes">&nbsp;go back and change chair email?
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

