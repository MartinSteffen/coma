<? 
include("header.tpl.php");
?>
<form action="index.php?m=roles&a=change&s=save" method="post">
<table>
	<tr>
		<th>Please select the role you want to change to</th>
	</tr>
	<tr>
		<td>
			<select name=role>
				<? foreach(d("roles") as $roles){
					?>
					<option value="<?= $roles['id'] ?>"><?= $roles['role'] ?></option>
					<?
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<input type="submit" value="Change">
		</td>
	</tr>
</table>
</form>

<?
include("footer.tpl.php");
?>
