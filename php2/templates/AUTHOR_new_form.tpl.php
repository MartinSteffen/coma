<?
  include('header.tpl.php');
  $cid = d('cid');
?>
<fieldset>
<legend class="textBold"><? echo d('name'); ?></legend>
<form action="index.php?m=author&a=new&s=save&cid=<? echo $cid ?>" method="post" enctype="multipart/form-data">
  <table>
	  <tr>
		  <td>
			  <p>Title<br>
			  <input name="title" type="text" size="60" maxlength="80">
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p>Description<br>
				<textarea name="summary" cols="60" rows="10">Please enter a short summary of your paper.
				</textarea>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p>Coauthors (optional)<br>
			<textarea name="coauthors" cols="60" rows="8"><? echo $cid ?></textarea>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p>Please select your paper<br>
				<input name="file" type="file" size="60">
				</p>
			</td>
		</tr>
		<tr>
			<td>
			  <input type="submit" name="save" value="Next">
			</td>
		</tr>
	</table>
</form>
<?
  include('footer.tpl.php');
?>
