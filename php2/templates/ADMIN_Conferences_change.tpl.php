<?
  include('templates/header.tpl.php');
	$input = $TPL['message'];
	?>

<fieldset>
<legend>
<font class="textBold" size="+2"><b>Edit conference &quot;<? echo($TPL['name']); ?>&quot;</b></font>
</legend>
<form action="index.php" method="post">
<input type="hidden" name="m" value="admin">
<input type="hidden" name="a" value="conferences">
<input type="hidden" name="s" value="change">
<input type="hidden" name="cid" value="<? echo($TPL['cid']); ?>">
  <table border="0" cellspacing="0" cellpadding="5" bordercolor="#000000">
	<tr>
		<td class="textBold">
			Name:
		</td>
		<td class="text">
			<input class="text" type="text" name="confname" size="40" maxlength="80" value="<? echo($TPL['name']); ?>">
		</td>
	</tr>
	<tr>
		<td class="textBold">
			Description:
		</td>
		<td class="text">
			<textarea name="confdescription" rows="4" cols="29"><? echo($TPL['description']); ?></textarea>
		</td>
	</tr>
	<tr>
		<td class="textBold">
			Homepage:
		</td>
		<td class="text">
			<input class="text" type="text" name="confhomepage" size="40" maxlength="80" value="<? echo($TPL['homepage']); ?>">
		</td>
	</tr>
	<tr>
		<td class="textBold">
			Minimum number of reviews per paper:
		</td>
		<td class="text">
			<input class="text" type="text" name="confmin_reviews" size="40" maxlength="80" value="<?php echo ($TPL['min_reviews_per_paper']?$TPL['min_reviews_per_paper']:"3") ?>">
		</td>
	</tr>
<?if ($input) {
		echo "<TR class='boldText'><TD colspan='2'><UL>";
		echo "<DIV align='center'><LI style='color:green;'>".$input."</LI></DIV>";
		echo "</UL></td></tr>";
}
?>
	<tr>
		<td align=center colspan="2" class="text">
			<input class="text" type="submit" value="submit" name="submit">&nbsp;&nbsp;&nbsp;
			<input type="reset" value="reset">&nbsp;&nbsp;&nbsp;
			<input type="submit" value="back to overview" name="back">
		</td>
	</tr>
  </table>
</form>
</fieldset>
<?  
  include('templates/footer.tpl.php');
  ?>

