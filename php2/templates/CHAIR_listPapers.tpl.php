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
			<b>rank</b>
		</td>
		<td align="center">
			<b>total&nbsp;grade<br>
(in&nbsp;%)</b>
		</td>
		<td align="left">
			<b>title</b>
		</td>
		<td align="center">
			<b>accept&nbsp;/<br>
reject</b>
		</td>
		<td align="center">
			<b>review<br>
status</b>
		</td>
		<td align="center">
			<b>review<br>
count</b>
		</td>
		<td align="center">
			<b>gradelist<br>
(in&nbsp;%)</b>
		</td>
	</tr>
	<tr>
		<td colspan="7">
 <font size="+2"><b>ACCEPTED:</b></font>			
		</td>
	</tr>

<?php

$i=0;
foreach($TPL['paperlist'] as $paper) {

$i++;
?>
	<tr>
		<td align="right" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $i; ?>&nbsp;&nbsp;&nbsp;
		</td>
		<td align="right" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['total_grade']; ?>&nbsp;&nbsp;&nbsp;
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['title']; ?>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<a href="index.php?m=chair&a=papers&s=accept&paperID=<?PHP echo $paper['id']; ?>"><img src="templates/images/thumb_up.gif" alt="accept" border="0"></a>&nbsp;&nbsp;<a href="index.php?m=chair&a=papers&s=reject&paperID=<?PHP echo $paper['id']; ?>"><img src="templates/images/thumb_down.gif" alt="reject" border="0"></a>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['review_status']; ?>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['review_count']; ?>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['totalgradelist']; ?>
		</td>
	</tr>
<?php
}
?>
	<tr>
		<td colspan="7">
 <font size="+2"><b>OPEN</b></font>			
		</td>
	</tr>

<?php

$i=0;
foreach($TPL['paperlist'] as $paper) {

$i++;
?>
	<tr>
		<td align="right" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $i; ?>&nbsp;&nbsp;&nbsp;
		</td>
		<td align="right" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['total_grade']; ?>&nbsp;&nbsp;&nbsp;
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['title']; ?>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<a href="index.php?m=chair&a=papers&s=accept&paperID=<?PHP echo $paper['id']; ?>"><img src="templates/images/thumb_up.gif" alt="accept" border="0"></a>&nbsp;&nbsp;<a href="index.php?m=chair&a=papers&s=reject&paperID=<?PHP echo $paper['id']; ?>"><img src="templates/images/thumb_down.gif" alt="reject" border="0"></a>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['review_status']; ?>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['review_count']; ?>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['totalgradelist']; ?>
		</td>
	</tr>
<?php
}
?>
	<tr>
		<td colspan="7">
 <font size="+2"><b>REJECTED</b></font>			
		</td>
	</tr>

<?php

$i=0;
foreach($TPL['paperlist'] as $paper) {

$i++;
?>
	<tr>
		<td align="right" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $i; ?>&nbsp;&nbsp;&nbsp;
		</td>
		<td align="right" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['total_grade']; ?>&nbsp;&nbsp;&nbsp;
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['title']; ?>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<a href="index.php?m=chair&a=papers&s=accept&paperID=<?PHP echo $paper['id']; ?>"><img src="templates/images/thumb_up.gif" alt="accept" border="0"></a>&nbsp;&nbsp;<a href="index.php?m=chair&a=papers&s=reject&paperID=<?PHP echo $paper['id']; ?>"><img src="templates/images/thumb_down.gif" alt="reject" border="0"></a>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['review_status']; ?>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['review_count']; ?>
		</td>
		<td align="center" class="<?php echo $paper['review_status']; ?>">
			<?PHP echo $paper['totalgradelist']; ?>
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
