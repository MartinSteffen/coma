<?
  include('header.tpl.php');
  $cid = d('cid');
  $topic = d('topic');
	
if (array_key_exists('msg', $TPL)) {
	?>
	<fieldset>
	<legend class="textBold" style="color:red">MESSAGE</legend>
		<table>
			<tr>
				<td class="textBold">
				<? echo d('msg'); ?>
				</td>
			</tr>
		</table>
	</fieldset>
	<?
}

	
?>
<fieldset>
<legend class="text"><font class="textBold">Conference: </font><? echo d('name'); ?></legend>
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
				<p>Please give an abstract of your paper here<br>
				<textarea name="summary" cols="60" rows="10"></textarea>
				</p>
			</td>
		<!--</tr>
		<tr>--!>
			<td valign="top">
				<p>Please mark the topics your paper matches.</p>
<?
foreach ($topic as $value) {
	?>
				<input name="<? echo $value['id'] ?>" type="checkbox" value="<? echo $value['id'] ?>" id="<? echo $value['id'] ?>"><label for="<? echo $value['id'] ?>"><? echo $value['name'] ?></label><br>
	<?
}
?>
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
			  <input type="submit" name="save" value="Submit">
			</td>
		</tr>
	</table>
</form>
</fieldset>
<?
  include('footer.tpl.php');
?>
