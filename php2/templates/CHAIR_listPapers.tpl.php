<?
  include('templates/header.tpl.php');

?>

<fieldset>
<legend>
<font class="textBold" size="+2"><b>Paper ranking</b></font>
</legend>
<br>
<br>

<table border="1" bordercolor="#000000" cellspacing="0" cellpadding="3" width="90%">
	<tr>
		<td align="center">
<font size="+1">			<b>rank</b>
</font>		</td>
		<td align="center">
<font size="+1">			<b>total&nbsp;grade</b>
</font>		</td>
		<td align="center">
<font size="+1">			<b>title</b>
</font>		</td>
		<td align="center" width="20%" >
<font size="+1">			<b>accept&nbsp;/&nbsp;reject /&nbsp;re-open</b>
</font>		</td>
		<td align="center" >
<font size="+1">			<b>review<br>
status</b>
</font>		</td>
		<td align="center" >
<font size="+1">			<b>review<br>
count</b>
</font>		</td>
		<td align="center" >
<font size="+1">			<b>gradelist</b>
</font>		</td>
	</tr>
<?php
if (count($TPL['paperlist_accepted']) >0) {
?>
	<tr>
		<td colspan="7" bgcolor="AAFFAA">
 <div align="center"><font size="+2"><b>ACCEPTED</b></font>			
</div>		</td>
	</tr>

<?php
}

foreach($TPL['paperlist_accepted'] as $paper) {

?>
	<tr>
		<td align="right"  bgcolor="AAFFAA" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['rank']; ?>&nbsp;&nbsp;&nbsp;
		</td>
		<td align="right" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['total_grade']; ?>&nbsp;&nbsp;&nbsp;
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
<font size="+1"><b>			<?PHP echo $paper['title']; ?>
</b></font>		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<a href="index.php?m=chair&a=program&s=paperlist&confID=<?PHP echo $_REQUEST['confID']; ?>&accept=<?PHP echo $paper['id']; ?>"><img src="templates/images/thumb_up.gif" alt="accept" border="0"></a>&nbsp;&nbsp;<a href="index.php?m=chair&a=program&s=paperlist&confID=<?PHP echo $_REQUEST['confID']; ?>&reject=<?PHP echo $paper['id']; ?>"><img src="templates/images/thumb_down.gif" alt="reject" border="0"></a>&nbsp;&nbsp;<a href="index.php?m=chair&a=program&s=paperlist&confID=<?PHP echo $_REQUEST['confID']; ?>&reopen=<?PHP echo $paper['id']; ?>"><img src="templates/images/thumb_mark.gif" alt="re-open" border="0"></a>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['review_status']; ?>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['review_count']; ?>&nbsp;/&nbsp;<?PHP echo $paper['reviewer_count']; ?>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['totalgradelist']; ?>&nbsp;
		</td>
	</tr>
<?php
}

if (count($TPL['paperlist_open']) >0) {
?>

	<tr>
		<td colspan="7">
<div align="center"> <font size="+2"><b>OPEN</b></font>			
</div>		</td>
	</tr>

<?php
}
foreach($TPL['paperlist_open'] as $paper) {

?>
	<tr>
		<td align="right" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['rank']; ?>&nbsp;&nbsp;&nbsp;
		</td>
		<td align="right" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['total_grade']; ?>&nbsp;&nbsp;&nbsp;
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<font size="+1"><b><?PHP echo $paper['title']; ?></b></font>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<a href="index.php?m=chair&a=program&s=paperlist&confID=<?PHP echo $_REQUEST['confID']; ?>&accept=<?PHP echo $paper['id']; ?>"><img src="templates/images/thumb_up.gif" alt="accept" border="0"></a>&nbsp;&nbsp;<a href="index.php?m=chair&a=program&s=paperlist&confID=<?PHP echo $_REQUEST['confID']; ?>&reject=<?PHP echo $paper['id']; ?>"><img src="templates/images/thumb_down.gif" alt="reject" border="0"></a>&nbsp;&nbsp;<a href="index.php?m=chair&a=program&s=paperlist&confID=<?PHP echo $_REQUEST['confID']; ?>&reopen=<?PHP echo $paper['id']; ?>"><img src="templates/images/thumb_mark.gif" alt="re-open" border="0"></a>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['review_status']; ?>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['review_count']; ?>&nbsp;/&nbsp;<?PHP echo $paper['reviewer_count']; ?>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['totalgradelist']; ?>&nbsp;
		</td>
	</tr>
<?php
}
if (count($TPL['paperlist_rejected']) >0) {

?>
	<tr>
		<td colspan="7" bgcolor="FFAAAA">
<div align="center"> <font size="+2"><b>REJECTED</b></font>			
</div>		</td>
	</tr>

<?php
}
foreach($TPL['paperlist_rejected'] as $paper) {

?>
	<tr>
		<td align="right" class="<?php echo $paper['review_status']; ?>" bgcolor="FFAAAA">
			<?PHP echo $paper['rank']; ?>&nbsp;&nbsp;&nbsp;
		</td>
		<td align="right" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['total_grade']; ?>&nbsp;&nbsp;&nbsp;
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
<font size="+1"><b>			<?PHP echo $paper['title']; ?>
</b></font>		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<a href="index.php?m=chair&a=program&s=paperlist&confID=<?PHP echo $_REQUEST['confID']; ?>&accept=<?PHP echo $paper['id']; ?>"><img src="templates/images/thumb_up.gif" alt="accept" border="0"></a>&nbsp;&nbsp;<a href="index.php?m=chair&a=program&s=paperlist&confID=<?PHP echo $_REQUEST['confID']; ?>&reject=<?PHP echo $paper['id']; ?>"><img src="templates/images/thumb_down.gif" alt="reject" border="0"></a>&nbsp;&nbsp;<a href="index.php?m=chair&a=program&s=paperlist&confID=<?PHP echo $_REQUEST['confID']; ?>&reopen=<?PHP echo $paper['id']; ?>"><img src="templates/images/thumb_mark.gif" alt="re-open" border="0"></a>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['review_status']; ?>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['review_count']; ?>&nbsp;/&nbsp;<?PHP echo $paper['reviewer_count']; ?>

		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['totalgradelist']; ?>&nbsp;
		</td>
	</tr>
<?php
}
if (count($TPL['paperlist_nothing']) >0) {
?>

	<tr>
		<td colspan="7" bgcolor="FF0000">
<div align="center"> <font size="+2"><b>NOT YET REVIEWED</b></font>			
</div>		</td>
	</tr>

<?php
}
foreach($TPL['paperlist_nothing'] as $paper) {

?>
	<tr>
		<td align="right" class="<?php echo $paper['review_status']; ?>" bgcolor="FF0000">
			<?PHP echo $paper['rank']; ?>&nbsp;&nbsp;&nbsp;
		</td>
		<td align="right" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['total_grade']; ?>&nbsp;&nbsp;&nbsp;
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
<font size="+1"><b>			<?PHP echo $paper['title']; ?>
</b></font>		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<a href="index.php?m=chair&a=program&s=paperlist&confID=<?PHP echo $_REQUEST['confID']; ?>&accept=<?PHP echo $paper['id']; ?>"><img src="templates/images/thumb_up.gif" alt="accept" border="0"></a>&nbsp;&nbsp;<a href="index.php?m=chair&a=program&s=paperlist&confID=<?PHP echo $_REQUEST['confID']; ?>&reject=<?PHP echo $paper['id']; ?>"><img src="templates/images/thumb_down.gif" alt="reject" border="0"></a>&nbsp;&nbsp;<a href="index.php?m=chair&a=program&s=paperlist&confID=<?PHP echo $_REQUEST['confID']; ?>&reopen=<?PHP echo $paper['id']; ?>"><img src="templates/images/thumb_mark.gif" alt="re-open" border="0"></a>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['review_status']; ?>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['review_count']; ?>&nbsp;/&nbsp;<?PHP echo $paper['reviewer_count']; ?>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['totalgradelist']; ?>&nbsp;
		</td>
	</tr>
<?php
}
?>

</table><br>
<br>


</fieldset>
<?  
  include('templates/footer.tpl.php');
  ?>
