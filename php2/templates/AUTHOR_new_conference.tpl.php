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
						<td class="textBold">
						<p>Please select the conference, you want to participate.<br>
						Below all conferences are listed, their submission deadline for papers is not exceeded.<br>
						So, if you are missing a conference, check, if you are still on time.
						</p>
						</td>
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
							<td class="textBold">Description</td>
						</tr>
						<tr>
							<td class="text">
								<p>
								<? echo $value['description']; ?>
								</p>
							</td> 
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
