<?
  include('templates/header.tpl.php');
  $input = d("ADMIN_conferenceList");

?>

<fieldset>
<legend>
<font class="textBold" size="+2"><b>conference management</b></font>
</legend>
<form action="index.php" method="post">
<input type="hidden" name="m" value="admin">
<input type="hidden" name="a" value="conferences">
<input type="hidden" name="s" value="viewlist">
<input type="hidden" name="orderby" value="name">
  <table border="0" cellspacing="0" cellpadding="5" bordercolor="#000000">
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				<fieldset>
					<legend>
						<font class="textBold">Legend</font>
					</legend>
					<table>
						<tr>
							<td class="textBold">To <font style="color:red">delete</font> Conference enable Checkbox and press <img height="14" width="14" align="middle" border="0" src="templates/images/delete.gif">-Button</td>
						</tr>
						<tr>
							<td class="textBold">To <font style="color:green">edit</font> Conference click the<img border="0" align="middle" src="templates/images/edit.gif" alt='edit'>-Button</td>
						</tr>
					</table>
				</fieldset>
				<br>
				<?php
					foreach($input as $row) {
					?>
				<fieldset>
				<legend>
					<a class="text" href="index.php?m=admin&a=conferences&s=delete&cid=<?php echo $row['id']; ?>" onClick="javascript: return confirm('Do you really want to delete the conference \n<? echo($row['name']); ?>?\nRemember: all data will be lost forever!');"><img align="middle" height="14" width="14" border="0" src="templates/images/delete.gif"></a>
					<a class="text" href="index.php?m=admin&a=conferences&s=change_form&cid=<?php echo $row['id']; ?>"><img align="middle" border="0" src="templates/images/edit.gif" alt='edit'></a>
					<font class="textBold" size="+2"><b><?php echo "&nbsp;".substr($row['name'],0,50); ?></b></font>
				</legend>
				<table>
					<tr>
						<td class="textBold">Description</td>
						<td class="text">
							<?php echo "&nbsp;".substr($row['description'],0,50); ?>
						</td>
					</tr>
					<tr>
						<td class="textBold">Chair of Conference</td>
						<td class="text">
							<a href="mailto:<? echo($row['chairemail']); ?>"><? echo($row['chairname']); ?></a>
						</td>
					</tr>
					<tr>
						<td class="textBold">Minimum Amount of Reviews per Paper</td>
						<td class="text">
							<?php echo "&nbsp;".substr($row['min_reviews_per_paper'],0,50); ?>
						</td>
					</tr>
					<tr>
						<td class="textBold">Date of Conference Start</td>
						<td class="text">
							<?php echo "&nbsp;".substr($row['conference_start'],0,50); ?>
						</td>
					</tr>
					<tr>
						<td class="textBold">Date of Conference End</td>
						<td class="text">
							<?php echo "&nbsp;".substr($row['conference_end'],0,50); ?>
						</td>
					</tr>
				</table>
				</fieldset>
				<br>
			<?php } ?>
			</td>
		</tr>
  </table>
</form>
</fieldset>
<?  
  include('templates/footer.tpl.php');
  ?>
