<?
include("templates/header.tpl.php");
?>

<fieldset>
<legend class="textBold">CALL FOR PAPERS</legend>
	<table>
		<tr>
			<td>
				<h1><? echo $TPL['conf']['name']; ?></h1>
			</td>
		</tr>
		<tr>
			<td>
				<p>
				<? echo $TPL['conf']['description']; ?>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<a href="index.php?m=cfp&a=register&s=register&cid=<? echo d('cid'); ?>" class="menus">Click here to become an author on this conference</a>
			</td>
		</tr>
	<table>
</fieldset>

<?
include("templates/footer.tpl.php");
?>
