<?
include("templates/header.tpl.php");
?>

<fieldset>
<legend class="textBold">Edit Paper: <? echo d('title'); ?>.</legend>
	<fieldset>
	<legend class="textBold">Legend</legend>
		<table>
			<tr>
				<td>
					<p>You may change one or more of the following values.<br>
					But remember: You will overwrite the current values with the entries of this formular.<br>
					To leave an entry, do not change it.<br>
					To leave the paper, do not select a new file.
					</p>
				</td>
			</tr>
		</table>
	</fieldset>
<form action="index.php?m=author&a=edit&s=edit&pid=<? echo d('pid'); ?>&filename=<? echo d(filename); ?>" method="post" enctype="multipart/form-data">
		<table>
	  		<tr>
		  		<td>
					<p>Change Title<br>
		  			<input name="title" type="text" size="60" maxlength="80" value="<? echo d('title'); ?>">
					</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>Change abstract<br>
					<textarea name="summary" cols="60" rows="10"><? echo d('abstract'); ?></textarea>
					</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>Submit a different paper<br>
					<input name="file" type="file" size="60">
					</p>
				</td>
			</tr>
			<tr>
				<td>
				<input type="submit" name="save" value="Submit">
				</td>
			</tr>
		</table>
	</form>
</fieldset>

<?
include("templates/footer.tpl.php");
?>
