<?
  include('templates/header.tpl.php');
  $input = d("ADMIN_conferenceList");

?>


<font size="+2"><b>conference management</b></font>
<form action="index.php" method="post">
<input type="hidden" name="m" value="admin">
<input type="hidden" name="a" value="conferences">
<input type="hidden" name="s" value="viewlist">
<input type="hidden" name="orderby" value="name">
  <table border="1" cellspacing="0" cellpadding="5" bordercolor="#000000">
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<?php
	foreach(d("ADMIN_colnames") as $colname) {
	?>
	<td><b>
		<?php echo substr($colname,0 ,40); ?>
      </b></td>
	<? } ?>
	</tr>
		
<?php

	foreach($input as $row) {
		?>
		<tr>
		<td><input type="checkbox" name="marked[]" value="<?php echo $row['id']; ?>">
		</td>
		<td>
			<a href="index.php?m=admin&a=conferences&s=change&cid=<?php echo $row['id']; ?>">edit</a>
		</td>
				<td>
					<?php echo "&nbsp;".substr($row['name'],0,50); ?>
				</td>
				<td>
					<?php echo "&nbsp;".substr($row['description'],0,50); ?>
				</td>
				<td>
					<?php echo "&nbsp;".substr($row['min_reviews_per_paper'],0,50); ?>
				</td>
				<td>
					<?php echo "&nbsp;".substr($row['conference_start'],0,50); ?>
				</td>
				<td>
					<?php echo "&nbsp;".substr($row['conference_end'],0,50); ?>
				</td>
		</tr>
	<?php } ?>
	<tr>
		<td colspan="2">
			<input type="submit" value="delete" name="delete" onClick="javascript: return confirm('Do you really want to delete the selected conferences?\nRemember: all data will be lost forever!');">
		</td>
	<td colspan="5">&nbsp;</td>
	</tr>
  </table>
</form>
<?  
  include('templates/footer.tpl.php');
  ?>
