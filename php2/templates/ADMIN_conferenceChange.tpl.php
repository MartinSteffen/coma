<?
  include('templates/header.tpl.php');

?>
<script language="JavaScript" src="templates/calendar.js"></script>
<fieldset>
<legend>
<font class="textBold" size="+2"><b>edit a conference</b></font>
</legend>
<form action="index.php" method="post">
<input type="hidden" name="m" value="admin">
<input type="hidden" name="a" value="conferences">
<input type="hidden" name="s" value="change_form">
<input type="hidden" name="cid" value="<?php echo $TPL['cid']; ?>">
  <table border="0" cellspacing="0" cellpadding="5" bordercolor="#000000">
<?php 

if ($TPL['errors']) {
	echo "<TR class='textBold' ><TD colspan='2'><br><UL>";
	foreach($TPL['errors'] as $errortext) {
		echo "<LI style='color:red;'>".$errortext."</LI>";
	}
	echo "</UL></td></tr>";
}
?>
	<tr>
		<td class="textBold" >
			Name:
		</td>
		<td>
			<input class="text" type="text" name="confname" size="40" maxlength="80" value="<?php echo ($TPL['name']?$TPL['name']:"") ?>">
		</td>
	</tr>
	<tr>
		<td class="textBold" >
			Description:
		</td>
		<td class="text">
			<textarea name="confdescription" rows="4" cols="29"><?php echo ($TPL['description']?$TPL['description']:"") ?></textarea>
		</td>
	</tr>
	<tr>
		<td class="textBold" >
			Homepage:
		</td>
		<td>
			<input class="text" type="text" name="confhomepage" size="40" maxlength="80" value="<?php echo (isset($TPL['homepage'])?$TPL['homepage'] : "http://www.yoursite.org/anywhere") ?>">
		</td>
	</tr>
	<tr>
		<td class="text" colspan="2">&nbsp;</td>
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
