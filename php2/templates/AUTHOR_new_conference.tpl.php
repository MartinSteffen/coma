<?
include("templates/header.tpl.php");
?>
<fieldset>
<legend class="textBold">Submit a new paper</legend>
<table>
	<tr>
		<td>
			<fieldset>
				<table>
					<tr>
						<td class="textBold">Please select the conference, you want to participate.</td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>

<?

$conf = d('conf');
foreach ($conf as $key => $value)
  {
		?>
		<tr>
			<td>
				<fieldset>
					<legend class="textBold"><a href="index.php?m=author&a=new&s=create&cid=<? echo $value['id']; ?>"><? echo $value['name']; ?></a></legend>
					<table>	
						<tr>
							<td class="textBold">Description	</td>
							<td class="text"><? echo $value['description']; ?></td> 
						</tr>
					</table>
				</fieldset>
			</td>
		</tr>
		<?
	}
?>
</table>
</fieldset>
<?
include("templates/footer.tpl.php");
?>
