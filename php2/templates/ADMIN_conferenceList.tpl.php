<?
  include('templates/header.tpl.php');
  $input = d("ADMIN_conferenceList");
?>
  <table border="1">
	<tr>
	<td>&nbsp;</td>
	<?php
	foreach(d("ADMIN_colnames") as $colname) {
	?>
	<td><b>
		<?php echo substr($colname['Field'] ,0 ,20); ?>
      </b></td>
	<? } ?>
	</tr>
		
<?php

	foreach($input as $row) {
		?>
		<tr>
		<td>
			<a href="index.php?m=admin&a=conferences&s=changedetails&cid=<?php echo $row['id']; ?>">edit</a>
		</td>
		<?php

			foreach($row as $col) {
				?>
				<td>
					<?php echo "&nbsp;".substr($col,0,50); ?>
				</td>
			<?php } ?>
		</tr>
	<?php } ?>
  </table>
<?  
  include('templates/footer.tpl.php');
  ?>
