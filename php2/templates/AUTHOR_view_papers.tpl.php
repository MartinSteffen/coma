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
			<p>To <font style="color:red">delete</font> a paper, </p>
			</td>
			<td class="text">
			<p>click on <font class="textBold" style="color:red">DELETE PAPER</font> below the summary.</p>
			</td>
		</tr>
		<tr>
			<td class="text">
			<p>To <font style="color:green">edit</font> a paper, </p>
			</td>
			<td class="text">
			<p>click on <font class="textBold" style="color:green">EDIT PAPER</font> below the summary.</p>
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

foreach($TPL as $row)
	{
	?>
	<fieldset>
<legend class="text"><font class="textBold">Conference: </font><? echo $row['conference_name']; ?><font class="textBold"> -- last edited: </font><? echo $row['last_edited'] ?></legend>
		<table align="left">
			<tr>
				<td class="textBold">
				<a href="index.php?m=author&a=view&s=getfile&pid=<? echo $row['id']; ?>"><? echo $row['title']; ?></a>
				</td>
				<td class="text">
				<? echo $row['abstract']; ?>
				</td>
			</tr>
			<tr>
				<td class="textBold" style="color:red">
			<a href="index.php?m=author&a=edit&s=delete&pid=<? echo $row['id']; ?>"><font style="color:red">DELETE PAPER</font></a>
				</td>
				<td class="textBold" style="color:green">
			<a href="index.php?m=author&a=edit&s=edit&pid=<? echo $row['id']; ?>"><font style="color:green">EDIT PAPER</font></a>
				</td>
			</tr>
				
		</table>
	</fieldset>
	<?
	}
?>
</fieldset>

<?

include('templates/footer.tpl.php');
?>
