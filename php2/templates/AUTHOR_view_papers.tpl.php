<?

include('templates/header.tpl.php');


?>
<fieldset>
<legend class="textBold">paper managment</legend>
<?

if (array_key_exists('msg', $TPL)) {
	?>
	<fieldset>
	<legend class="textBold" color="red">MESSAGE</legend>
		<table>
			<tr>
				<td class="textBold">
				<? echo d('msg'); ?>
				</td>
			</tr>
		</table>
	</fieldset>
	<?
	unset($TPL['msg']);
}

?>

<fieldset>
<legend class="textBold">Legend</legend>
	<table>
		<tr>
			<td class="text">
			<p>To <font style="color:red">reject</font> a paper from conference, </p>
			</td>
			<td class="text">
			<p>click on <font class="textBold" style="color:red">DELETE PAPER</font> below the abstract.</p>
			</td>
		</tr>
		<tr>
			<td class="text">
			<p>To <font style="color:green">edit</font> a paper, </p>
			</td>
			<td class="text">
			<p>click on <font class="textBold" style="color:green">EDIT PAPER</font> below the abstract.</p>
			</td>
		</tr>
		<tr>
			<td class="text">
			<p>To <font class="textBold">download</font> a paper, </p>
			</td>
			<td class="text">
			<p>click on the papers title.</p>
			</td>
		</tr>
	</table>
</fieldset>

<?

foreach($TPL as $key => $value) {
	if ($key == 'alive') {
		foreach ($value as $row) {
			?>
<fieldset>
<legend class="text"><font class="textBold">Conference: </font><? echo $row['conference_name']; ?></legend>
			<?
			foreach ($row as $v) {
				if (is_array ($v)) {
					?>	
	<fieldset>
	<legend class="text"><font class="textBold">Paper: </font><? echo $v['title']; ?><font class="textBold"> -- last edited: </font><? echo $v['last_edited'] ?></legend>
		<table align="left">
			<tr>
				<td class="textBold">
					<a href="index.php?m=author&a=view&s=getfile&pid=<? echo $v['id']; ?>"><? echo $v['title']; ?></a>
				</td>
				<td class="text">
					<? echo $v['abstract']; ?>
				</td>
			</tr>
			<tr>
				<td class="textBold" style="color:red">
			<a href="index.php?m=author&a=edit&s=delete&pid=<? echo $v['id']; ?>" onClick="javascript: return confirm('Click \'OK\' for deleting the paper \n<? echo($row['title']); ?>\nThe paper will be completly rejected from the conference,\na recover of this file is not possible!');"><font style="color:red">DELETE PAPER</font></a>
				</td>
				<td class="textBold" style="color:green">
			<a href="index.php?m=author&a=edit&s=form&pid=<? echo $v['id']; ?>"><font style="color:green">EDIT PAPER</font></a>
				</td>
			</tr>
				
		</table>
	</fieldset>
					<?
				}
			}
			?>
</fieldset>
			<?
		}
	}
	elseif ($key == 'dead') {
		foreach ($value as $row) {
			?>
<fieldset>
<legend class="text"><font class="textBold">Conference: </font><? echo $row['conference_name']; ?> -- <font class="textBold" style=color:red>Paper submission deadline is exceeded for this conference.</font></legend>
			<?
			foreach ($row as $v) {
				if (is_array ($v)) {
					?>	
	<fieldset>
	<legend class="text"><font class="textBold">Paper: </font><? echo $v['title']; ?><font class="textBold"> -- last edited: </font><? echo $v['last_edited'] ?></legend>
		<table align="left">
			<tr>
				<td class="textBold">
					<a href="index.php?m=author&a=view&s=getfile&pid=<? echo $v['id']; ?>"><? echo $v['title']; ?></a>
				</td>
				<td class="text">
					<? echo $v['abstract']; ?>
				</td>
			</tr>
			<tr>
				<td class="textBold" style="color:red">
			<a href="index.php?m=author&a=edit&s=delete&pid=<? echo $v['id']; ?>" onClick="javascript: return confirm('Click \'OK\' for deleting the paper \n<? echo($row['title']); ?>\nThe paper will be completly rejected from the conference,\na recover of this file is not possible!\nATTENTION!\nAs the deadline for paper submission is exceeded,\nyou will not be able, to resubmit your paper!');"><font style="color:red">DELETE PAPER</font></a>
				</td>
				<?
				/*
				<td class="textBold" style="color:green">
			<a href="index.php?m=author&a=edit&s=form&pid=<? echo $v['id']; ?>"><font style="color:green">EDIT PAPER</font></a>
				</td>
				*/
				?>
			</tr>
				
		</table>
	</fieldset>
					<?
				}
			}
			?>
</fieldset>
			<?
		}
	}
}

?>

</fieldset>

<?

include('templates/footer.tpl.php');
?>
