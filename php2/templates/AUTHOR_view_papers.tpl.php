<?

include('templates/header.tpl.php');


?>
<fieldset>
<legend><b>paper managment</b></legend>
<?
foreach($TPL as $row)
	{
	?>
	<fieldset>
	<legend class=textBold>Conference: <? echo $row['conference_name']; ?> -- last edited: <? echo $row['last_edited'] ?></legend>
		<table>
			<tr>
				<td class=textBold>
				<? echo $row['title']; ?>
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
