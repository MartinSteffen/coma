<?

include('templates/header.tpl.php');


?>
<fieldset>
<legend><b>paper managment</b></legend>
<?

if (array_key_exists('msg', $TPL)) {
	?>
	<fieldset>
	<legend class=textBold color=red>MESSAGE</legend>
		<table>
			<tr>
				<td class=textBold>
				<? echo d('msg'); ?>
				</td>
			</tr>
		</table>
	</fieldset>
	<?
}

foreach($TPL as $row)
	{
	?>
	<fieldset>
	<legend class=textBold>Conference: <? echo $row['conference_name']; ?> -- last edited: <? echo $row['last_edited'] ?></legend>
		<table>
			<tr>
				<td class=textBold>
				<a href="index.php?m=author&a=view&s=getfile&pid=<? echo $row['id']; ?>"><? echo $row['title']; ?></a>
				</td>
				<td class=text>
				<? echo $row['abstract']; ?>
				</td>
			</td>
		</table>
	</fieldset>
	<?
	}
?>
</fieldset>

<?

include('templates/footer.tpl.php');
?>
